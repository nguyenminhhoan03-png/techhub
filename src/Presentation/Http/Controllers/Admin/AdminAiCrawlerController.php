<?php

declare(strict_types=1);

namespace Presentation\Http\Controllers\Admin;

use Application\Setting\Services\SettingService;
use Domain\Ai\Entities\AiContentJob;
use Domain\Article\Entities\Article;
use Domain\Article\Entities\ContentCategory;
use Domain\Hardware\Entities\Brand;
use Domain\Hardware\Entities\Comparison;
use Domain\Hardware\Entities\ComparisonItem;
use Domain\Hardware\Entities\Product;
use Domain\Hardware\Entities\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Infrastructure\Ai\Services\GeminiContentGenerator;
use Infrastructure\Crawler\Services\WebArticleCrawler;

class AdminAiCrawlerController
{
    public function index(): View
    {
        $products = Product::query()->with('brand', 'category')->where('is_active', true)->orderBy('model_name')->get();
        $categories = ContentCategory::query()->where('is_active', true)->get();
        $recentJobs = AiContentJob::query()->with('article')->latest('id')->take(10)->get();

        $geminiKey = (string) SettingService::get('gemini_api_key', env('GEMINI_API_KEY', ''));
        $openaiKey = (string) SettingService::get('openai_api_key', env('OPENAI_API_KEY', ''));
        $hasLiveAiKey = ! empty($geminiKey) || ! empty($openaiKey);
        $activeProvider = (string) SettingService::get('ai_default_provider', 'gemini');
        $activeModel = (string) SettingService::get('ai_model_name', 'gemini-1.5-flash');

        return view('admin.ai_studio.index', [
            'products' => $products,
            'categories' => $categories,
            'recentJobs' => $recentJobs,
            'hasLiveAiKey' => $hasLiveAiKey,
            'activeProvider' => $activeProvider,
            'activeModel' => $activeModel,
        ]);
    }

    /**
     * Senior Real-Time Action: Generate comparison review from ANY 2 device names (Live LLM + Specs AI).
     */
    public function generateFromSpecs(Request $request, GeminiContentGenerator $aiService): JsonResponse
    {
        $startTime = hrtime(true);
        $validated = $request->validate([
            'name_a' => ['nullable', 'string', 'max:255'],
            'name_b' => ['nullable', 'string', 'max:255'],
            'product_a_id' => ['nullable', 'exists:products,id'],
            'product_b_id' => ['nullable', 'exists:products,id'],
            'category_hint' => ['nullable', 'string', 'max:100'],
        ]);

        $nameA = trim((string) ($validated['name_a'] ?? ''));
        $nameB = trim((string) ($validated['name_b'] ?? ''));

        // If user picked from dropdown instead of free text
        if (empty($nameA) && ! empty($validated['product_a_id'])) {
            $pA = Product::query()->find($validated['product_a_id']);
            $nameA = $pA?->full_name ?? '';
        }
        if (empty($nameB) && ! empty($validated['product_b_id'])) {
            $pB = Product::query()->find($validated['product_b_id']);
            $nameB = $pB?->full_name ?? '';
        }

        if (empty($nameA) || empty($nameB)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ tên 2 thiết bị cần so sánh đối đầu.',
            ], 422);
        }

        if (mb_strtolower($nameA) === mb_strtolower($nameB)) {
            return response()->json([
                'success' => false,
                'message' => 'Tên 2 thiết bị cần phải khác nhau để so sánh.',
            ], 422);
        }

        $generated = $aiService->generateComparisonFromNames($nameA, $nameB, $validated['category_hint'] ?? null);
        $execMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        // Upsert Product A in Hardware DB
        $prodAData = $generated['product_a_data'] ?? [];
        $prodBData = $generated['product_b_data'] ?? [];

        $productA = $this->upsertHardwareProduct($prodAData, $nameA);
        $productB = $this->upsertHardwareProduct($prodBData, $nameB);

        // Record Comparison in Hardware DB
        $comparison = Comparison::query()->create([
            'slug' => Str::slug("{$nameA}-vs-{$nameB}"),
            'title' => $generated['title'],
            'summary' => $generated['excerpt'],
            'pros_a' => $generated['pros_a'],
            'cons_a' => $generated['cons_a'],
            'pros_b' => $generated['pros_b'],
            'cons_b' => $generated['cons_b'],
            'verdict' => "{$generated['winner_name']} là người chiến thắng với điểm số hiệu năng vượt trội.",
            'winner_product_id' => ($generated['winner_name'] === $nameA) ? $productA->id : $productB->id,
            'is_published' => true,
        ]);

        ComparisonItem::query()->create([
            'comparison_id' => $comparison->id,
            'product_id' => $productA->id,
            'position' => 1,
            'score_overall' => $productA->overall_score,
            'score_gaming' => $productA->gaming_score,
            'score_productivity' => $productA->productivity_score,
            'key_highlight' => $generated['pros_a'][0] ?? 'Hiệu năng cao cấp',
        ]);

        ComparisonItem::query()->create([
            'comparison_id' => $comparison->id,
            'product_id' => $productB->id,
            'position' => 2,
            'score_overall' => $productB->overall_score,
            'score_gaming' => $productB->gaming_score,
            'score_productivity' => $productB->productivity_score,
            'key_highlight' => $generated['pros_b'][0] ?? 'P/P cạnh tranh',
        ]);

        // Record job in history
        $job = AiContentJob::query()->create([
            'job_type' => 'vs_specs_generator',
            'target_topic' => "{$nameA} vs {$nameB}",
            'generated_markdown' => $generated['content_markdown'],
            'generated_metadata' => [
                'title' => $generated['title'],
                'slug' => $generated['slug'],
                'excerpt' => $generated['excerpt'],
                'faqs' => $generated['faqs'],
                'pros_a' => $generated['pros_a'],
                'pros_b' => $generated['pros_b'],
                'winner_name' => $generated['winner_name'],
                'is_live_ai' => $generated['is_live_ai'] ?? false,
                'comparison_id' => $comparison->id,
            ],
            'status' => 'completed',
            'execution_time_ms' => $execMs,
        ]);

        return response()->json([
            'success' => true,
            'job_id' => $job->id,
            'execution_time_ms' => $execMs,
            'data' => $generated,
        ]);
    }

    /**
     * AJAX action: Scrape raw article from URL and synthesize using AI.
     */
    public function crawlAndRewrite(
        Request $request,
        WebArticleCrawler $crawler,
        GeminiContentGenerator $aiService,
    ): JsonResponse {
        $startTime = hrtime(true);
        $validated = $request->validate([
            'source_url' => ['required', 'url', 'max:500'],
        ]);

        $url = $validated['source_url'];
        $crawlResult = $crawler->crawlUrl($url);

        if (! $crawlResult['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cào dữ liệu từ URL: ' . ($crawlResult['error'] ?? 'Lỗi không xác định'),
            ], 422);
        }

        $generated = $aiService->rewriteScrapedArticle(
            $crawlResult['text_content'],
            $crawlResult['title'],
            $url
        );

        $execMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        $job = AiContentJob::query()->create([
            'job_type' => 'url_crawl_rewrite',
            'source_url' => $url,
            'target_topic' => $crawlResult['title'],
            'raw_scraped_text' => $crawlResult['text_content'],
            'generated_markdown' => $generated['content_markdown'],
            'generated_metadata' => [
                'title' => $generated['title'],
                'slug' => $generated['slug'],
                'excerpt' => $generated['excerpt'],
                'faqs' => $generated['faqs'],
                'featured_image_url' => $crawlResult['featured_image'],
                'is_live_ai' => $generated['is_live_ai'] ?? false,
            ],
            'status' => 'completed',
            'execution_time_ms' => $execMs,
        ]);

        return response()->json([
            'success' => true,
            'job_id' => $job->id,
            'execution_time_ms' => $execMs,
            'data' => array_merge($generated, [
                'featured_image_url' => $crawlResult['featured_image'],
                'source_domain' => $crawlResult['domain'],
                'source_words' => $crawlResult['word_count'],
            ]),
        ]);
    }

    /**
     * Save generated AI article directly to database as published or draft.
     */
    public function saveArticle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:content_categories,id'],
            'type' => ['required', 'string', 'in:comparison,review,buying_guide,news,article'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content_markdown' => ['required', 'string'],
            'featured_image_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:draft,published'],
            'job_id' => ['nullable', 'exists:ai_content_jobs,id'],
            'faqs_json' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($validated['title']);
        $count = Article::query()->where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $schemaMarkup = null;
        if (! empty($validated['faqs_json'])) {
            $decodedFaqs = json_decode($validated['faqs_json'], true);
            if (is_array($decodedFaqs)) {
                $schemaMarkup = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => array_map(fn ($f): array => [
                        '@type' => 'Question',
                        'name' => $f['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $f['answer'],
                        ],
                    ], $decodedFaqs),
                ];
            }
        }

        $article = Article::query()->create([
            'author_id' => Auth::id() ?? 1,
            'category_id' => $validated['category_id'],
            'type' => $validated['type'],
            'slug' => $slug,
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content_markdown' => $validated['content_markdown'],
            'content_html' => nl2br(htmlspecialchars($validated['content_markdown'], ENT_QUOTES, 'UTF-8')),
            'featured_image_url' => $validated['featured_image_url'],
            'meta_title' => $validated['title'] . ' — TechHub Review',
            'meta_description' => $validated['excerpt'],
            'schema_markup' => $schemaMarkup,
            'status' => $validated['status'],
            'published_at' => ('published' === $validated['status']) ? now() : null,
        ]);

        if (! empty($validated['job_id'])) {
            AiContentJob::query()->where('id', $validated['job_id'])->update(['post_id' => $article->id]);
        }

        return redirect()->route('admin.articles.index')->with('success', '🎉 Bài viết AI đã được xuất bản lên hệ thống thành công!');
    }

    /**
     * Helper to upsert a Product entity in Hardware Database.
     *
     * @param array<string, mixed> $data
     */
    protected function upsertHardwareProduct(array $data, string $fallbackName): Product
    {
        $fullName = (string) ($data['name'] ?? $fallbackName);
        $brandName = (string) ($data['brand'] ?? 'Công Nghệ');
        $catSlug = (string) ($data['category'] ?? 'cpu');

        $brand = Brand::query()->firstOrCreate(
            ['slug' => Str::slug($brandName)],
            ['name' => $brandName, 'is_active' => true]
        );

        $category = ProductCategory::query()->firstOrCreate(
            ['slug' => $catSlug],
            ['name' => strtoupper($catSlug), 'is_active' => true]
        );

        $slug = Str::slug($fullName);

        /** @var Product $product */
        $product = Product::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'model_name' => $fullName,
                'full_name' => $fullName,
                'release_date' => now(),
                'launch_msrp_usd' => (float) ($data['msrp_usd'] ?? 499),
                'specs_json' => (array) ($data['specs'] ?? []),
                'overall_score' => (float) ($data['overall_score'] ?? 9.0),
                'gaming_score' => (float) ($data['gaming_score'] ?? 9.0),
                'productivity_score' => (float) ($data['productivity_score'] ?? 8.8),
                'is_active' => true,
            ]
        );

        return $product;
    }
}
