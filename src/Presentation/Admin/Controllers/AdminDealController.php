<?php

declare(strict_types=1);

namespace Presentation\Admin\Controllers;

use Domain\Deal\Entities\DigitalDeal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Presentation\Controller;

class AdminDealController extends Controller
{
    /**
     * Predefined digital account categories.
     */
    public const CATEGORIES = [
        'Google AI'          => 'Google AI (Gemini Pro, 5TB Storage)',
        'ChatGPT & OpenAI'   => 'ChatGPT & OpenAI (Plus, Team, API)',
        'Claude AI'          => 'Claude AI (Anthropic Pro, Sonnet)',
        'Developer Tools'    => 'Developer Tools (GitHub, Cursor, JetBrains)',
        'Design & Creative'  => 'Design & Media (Canva, Midjourney, Adobe)',
        'Cloud & VPS'        => 'Cloud, VPS & Proxy',
        'Other'              => 'Khác',
    ];

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $query = DigitalDeal::query()->orderBy('sort_order')->orderByDesc('id');

        if ($search && is_string($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($category && is_string($category)) {
            $query->where('category', $category);
        }

        $deals = $query->paginate(15)->withQueryString();

        return view('admin.deals.index', [
            'deals'      => $deals,
            'categories' => self::CATEGORIES,
        ]);
    }

    public function create(): View
    {
        return view('admin.deals.create', [
            'categories' => self::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDeal($request);

        // Process Slug
        $rawSlug = ! empty($validated['slug']) ? (string) $validated['slug'] : (string) $validated['name'];
        $slug = Str::slug($rawSlug);
        $originalSlug = $slug;
        $count = 1;
        while (DigitalDeal::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-" . $count++;
        }
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Process Variants
        $validated['variants'] = $this->processVariants($request->input('variants', []));

        // Process Tags
        if ( ! empty($validated['tags_raw'])) {
            $validated['tags'] = array_values(array_filter(array_map('trim', explode(',', (string) $validated['tags_raw']))));
        }

        // Process Commitments
        $validated['commitments'] = $this->processCommitments($request);

        unset($validated['tags_raw']);

        $deal = DigitalDeal::create($validated);

        return redirect()->route('admin.deals.index')->with('success', "Đã thêm sản phẩm [{$deal->name}] thành công.");
    }

    public function edit(int $id): View
    {
        /** @var DigitalDeal $deal */
        $deal = DigitalDeal::findOrFail($id);

        return view('admin.deals.edit', [
            'deal'       => $deal,
            'categories' => self::CATEGORIES,
        ]);
    }

    public function update(int $id, Request $request): RedirectResponse
    {
        /** @var DigitalDeal $deal */
        $deal = DigitalDeal::findOrFail($id);

        $validated = $this->validateDeal($request, $deal->id);

        // Process Slug
        $rawSlug = ! empty($validated['slug']) ? (string) $validated['slug'] : (string) $validated['name'];
        $slug = Str::slug($rawSlug);
        $originalSlug = $slug;
        $count = 1;
        while (DigitalDeal::where('slug', $slug)->where('id', '!=', $deal->id)->exists()) {
            $slug = "{$originalSlug}-" . $count++;
        }
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Process Variants
        $validated['variants'] = $this->processVariants($request->input('variants', []));

        // Process Tags
        if ( ! empty($validated['tags_raw'])) {
            $validated['tags'] = array_values(array_filter(array_map('trim', explode(',', (string) $validated['tags_raw']))));
        }

        // Process Commitments
        $validated['commitments'] = $this->processCommitments($request);

        unset($validated['tags_raw']);

        $deal->update($validated);

        return redirect()->route('admin.deals.index')->with('success', "Đã cập nhật sản phẩm [{$deal->name}].");
    }

    public function toggle(int $id): RedirectResponse
    {
        /** @var DigitalDeal $deal */
        $deal = DigitalDeal::findOrFail($id);
        $deal->is_active = ! $deal->is_active;
        $deal->save();

        $status = $deal->is_active ? 'bật hiển thị' : 'tắt ẩn';

        return back()->with('success', "Đã {$status} sản phẩm [{$deal->name}].");
    }

    public function destroy(int $id): RedirectResponse
    {
        /** @var DigitalDeal $deal */
        $deal = DigitalDeal::findOrFail($id);
        $deal->delete();

        return redirect()->route('admin.deals.index')->with('success', "Đã xóa sản phẩm [{$deal->name}].");
    }

    private function validateDeal(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'slug'                 => ['nullable', 'string', 'max:150'],
            'category'             => ['required', 'string', 'max:80'],
            'badge_text'           => ['nullable', 'string', 'max:50'],
            'sub_badge'            => ['nullable', 'string', 'max:50'],
            'tags_raw'             => ['nullable', 'string', 'max:500'],
            'price'                => ['required', 'numeric', 'min:0'],
            'original_price'       => ['nullable', 'numeric', 'min:0'],
            'discount_percentage'  => ['nullable', 'integer', 'min:0', 'max:100'],
            'rating'               => ['nullable', 'numeric', 'min:1', 'max:5'],
            'rating_count'         => ['nullable', 'integer', 'min:0'],
            'sold_count'           => ['nullable', 'integer', 'min:0'],
            'stock_status'         => ['required', 'string', 'in:in_stock,out_of_stock,pre_order'],
            'thumbnail_url'        => ['nullable', 'string', 'max:600'],
            'summary'              => ['nullable', 'string', 'max:500'],
            'description_markdown' => ['nullable', 'string'],
            'zalo_contact'         => ['nullable', 'string', 'max:50'],
            'telegram_contact'     => ['nullable', 'string', 'max:100'],
            'is_featured'          => ['boolean'],
            'is_active'            => ['boolean'],
            'sort_order'           => ['integer'],
            'meta_title'           => ['nullable', 'string', 'max:255'],
            'meta_description'     => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function processVariants(mixed $rawVariants): array
    {
        if ( ! is_array($rawVariants)) {
            return [];
        }

        $clean = [];
        foreach ($rawVariants as $index => $item) {
            $name = trim((string) ($item['name'] ?? ''));
            $price = (int) ($item['price'] ?? 0);
            if ('' !== $name && $price >= 0) {
                $clean[] = [
                    'name'       => $name,
                    'price'      => $price,
                    'is_default' => ! empty($item['is_default']) || 0 === $index,
                ];
            }
        }

        return $clean;
    }

    private function processCommitments(Request $request): array
    {
        return [
            [
                'icon'  => '🛡️',
                'title' => 'Bảo vệ bởi Escrow',
                'desc'  => 'Giao dịch an toàn & uy tín tuyệt đối',
            ],
            [
                'icon'  => '⏱️',
                'title' => 'Giữ tiền 72h',
                'desc'  => 'Bảo hành 1-đổi-1 hoặc hoàn tiền nếu lỗi',
            ],
            [
                'icon'  => '⚡',
                'title' => 'Giao hàng tức thì',
                'desc'  => 'Kích hoạt ngay trong 5 - 15 phút',
            ],
        ];
    }
}
