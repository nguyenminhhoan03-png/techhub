<?php

declare(strict_types=1);

namespace Infrastructure\Ai\Services;

use Application\Setting\Services\SettingService;
use Domain\Hardware\Entities\Brand;
use Domain\Hardware\Entities\Product;
use Domain\Hardware\Entities\ProductCategory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiContentGenerator
{
    /**
     * Call LLM (Google Gemini or OpenAI) to generate structured output.
     */
    public function callLlm(string $prompt, string $systemInstruction = '', bool $requireJson = true): ?string
    {
        $provider = (string) SettingService::get('ai_default_provider', env('AI_DEFAULT_PROVIDER', 'gemini'));
        $geminiKey = (string) SettingService::get('gemini_api_key', env('GEMINI_API_KEY', ''));
        $openaiKey = (string) SettingService::get('openai_api_key', env('OPENAI_API_KEY', ''));

        // 1. Try Google Gemini if configured or selected
        if (($provider === 'gemini' || empty($openaiKey)) && ! empty($geminiKey)) {
            $result = $this->callGeminiApi($prompt, $systemInstruction, $geminiKey, $requireJson);
            if ($result !== null) {
                return $result;
            }
        }

        // 2. Try OpenAI if configured
        if (! empty($openaiKey)) {
            $result = $this->callOpenAiApi($prompt, $systemInstruction, $openaiKey, $requireJson);
            if ($result !== null) {
                return $result;
            }
        }

        return null;
    }

    /**
     * Call Google Gemini 1.5 Flash / Pro REST API.
     */
    protected function callGeminiApi(string $prompt, string $systemInstruction, string $apiKey, bool $requireJson): ?string
    {
        $model = (string) SettingService::get('ai_model_name', env('AI_MODEL_NAME', 'gemini-1.5-flash'));
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'maxOutputTokens' => 8192,
            ],
        ];

        if (! empty($systemInstruction)) {
            $payload['systemInstruction'] = [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ];
        }

        if ($requireJson) {
            $payload['generationConfig']['responseMimeType'] = 'application/json';
        }

        try {
            $response = Http::timeout(45)->post($url, $payload);
            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($text) {
                    return trim($text);
                }
            } else {
                Log::warning('Gemini API call failed', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Throwable $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Call OpenAI Chat Completions API.
     */
    protected function callOpenAiApi(string $prompt, string $systemInstruction, string $apiKey, bool $requireJson): ?string
    {
        $model = (string) SettingService::get('ai_model_name', env('AI_MODEL_NAME', 'gpt-4o-mini'));
        if (str_starts_with($model, 'gemini')) {
            $model = 'gpt-4o-mini';
        }

        $url = 'https://api.openai.com/v1/chat/completions';
        $messages = [];
        if (! empty($systemInstruction)) {
            $messages[] = ['role' => 'system', 'content' => $systemInstruction];
        }
        $messages[] = ['role' => 'user', 'content' => $prompt];

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.4,
        ];

        if ($requireJson) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        try {
            $response = Http::timeout(45)->withToken($apiKey)->post($url, $payload);
            if ($response->successful()) {
                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? null;
                if ($text) {
                    return trim($text);
                }
            } else {
                Log::warning('OpenAI API call failed', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Throwable $e) {
            Log::error('OpenAI API exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Senior Dynamic Comparison Generator: Free text inputs for any 2 devices in the world.
     * Uses Live LLM with strict JSON schema, or intelligent deterministic fallback.
     *
     * @return array{
     *     product_a_data: array<string, mixed>,
     *     product_b_data: array<string, mixed>,
     *     title: string,
     *     slug: string,
     *     excerpt: string,
     *     content_markdown: string,
     *     content_html: string,
     *     pros_a: list<string>,
     *     cons_a: list<string>,
     *     pros_b: list<string>,
     *     cons_b: list<string>,
     *     winner_name: string,
     *     faqs: list<array{question: string, answer: string}>,
     *     seo_title: string,
     *     seo_description: string,
     *     is_live_ai: bool
     * }
     */
    public function generateComparisonFromNames(string $nameA, string $nameB, ?string $categoryHint = null): array
    {
        $nameA = trim($nameA);
        $nameB = trim($nameB);

        $systemPrompt = 'Bạn là Chuyên gia Đánh giá Phần cứng Công nghệ Cao cấp của TechHub. '
            . 'Nhiệm vụ của bạn là nghiên cứu thông số kỹ thuật thực tế của 2 thiết bị và tạo một bài phân tích so sánh đối đầu toàn diện 1.500 từ chuẩn SEO bằng Tiếng Việt. '
            . 'Bạn PHẢI trả về duy nhất một chuỗi JSON hợp lệ theo đúng cấu trúc yêu cầu.';

        $prompt = <<<PROMPT
Hãy so sánh chi tiết giữa 2 thiết bị/linh kiện công nghệ: "{$nameA}" và "{$nameB}".
Gợi ý danh mục: {$categoryHint}

Trả về JSON với cấu trúc CHÍNH XÁC như sau:
{
  "product_a": {
    "name": "Tên đầy đủ chuẩn xác của {$nameA}",
    "brand": "Tên thương hiệu (VD: NVIDIA, AMD, Apple, Intel, Samsung, Qualcomm)",
    "category": "cpu/gpu/smartphone/laptop/other",
    "release_year": 2024,
    "msrp_usd": 599,
    "overall_score": 9.2,
    "gaming_score": 9.4,
    "productivity_score": 8.9,
    "specs": {
      "architecture": "...",
      "process_node": "...",
      "cores_or_cus": "...",
      "clock_speed": "...",
      "memory_or_vram": "...",
      "tdp_power": "..."
    }
  },
  "product_b": {
    "name": "Tên đầy đủ chuẩn xác của {$nameB}",
    "brand": "Tên thương hiệu",
    "category": "cpu/gpu/smartphone/laptop/other",
    "release_year": 2024,
    "msrp_usd": 549,
    "overall_score": 8.9,
    "gaming_score": 9.0,
    "productivity_score": 8.7,
    "specs": {
      "architecture": "...",
      "process_node": "...",
      "cores_or_cus": "...",
      "clock_speed": "...",
      "memory_or_vram": "...",
      "tdp_power": "..."
    }
  },
  "title": "So Sánh {$nameA} vs {$nameB}: Đâu Là Lựa Chọn Tối Ưu Nhất Năm 2026?",
  "excerpt": "Đánh giá chi tiết điểm hiệu năng, kiến trúc phần cứng, khả năng gaming và tỷ lệ p/p giữa {$nameA} và {$nameB}.",
  "content_markdown": "## 1. Giới Thiệu & Bối Cảnh Thị Trường\\n\\n...\\n\\n## 2. Bảng So Sánh Thông Số Kỹ Thuật (Specs Breakdown)\\n\\n...\\n\\n## 3. Đánh Giá Hiệu Năng Thực Tế & Benchmark\\n\\n...\\n\\n## 4. Ưu & Nhược Điểm Từng Thiết Bị\\n\\n...\\n\\n## 5. Lời Khuyên Mua Sắm & Kết Luận\\n\\n...",
  "pros_a": ["Ưu điểm 1 của A", "Ưu điểm 2 của A", "Ưu điểm 3 của A"],
  "cons_a": ["Nhược điểm 1 của A"],
  "pros_b": ["Ưu điểm 1 của B", "Ưu điểm 2 của B", "Ưu điểm 3 của B"],
  "cons_b": ["Nhược điểm 1 của B"],
  "winner_name": "Tên sản phẩm chiến thắng",
  "faqs": [
    {"question": "Nên chọn mua {$nameA} hay {$nameB} ở thời điểm hiện tại?", "answer": "..."},
    {"question": "Mức tiêu thụ điện và yêu cầu tản nhiệt như thế nào?", "answer": "..."},
    {"question": "Chênh lệch hiệu năng thực tế có đáng để nâng cấp không?", "answer": "..."}
  ]
}
PROMPT;

        $llmResponse = $this->callLlm($prompt, $systemPrompt, true);
        $isLiveAi = false;

        if ($llmResponse) {
            // Strip markdown code fences if LLM wrapped in ```json ... ```
            $cleaned = preg_replace('/^```(?:json)?\s*/i', '', trim($llmResponse));
            $cleaned = preg_replace('/\s*```$/', '', (string) $cleaned);
            $parsed = json_decode((string) $cleaned, true);

            if (is_array($parsed) && isset($parsed['title'], $parsed['content_markdown'], $parsed['product_a'], $parsed['product_b'])) {
                $isLiveAi = true;
                $slug = Str::slug("{$nameA}-vs-{$nameB}");
                $html = nl2br(htmlspecialchars((string) $parsed['content_markdown'], ENT_QUOTES, 'UTF-8'));

                return [
                    'product_a_data' => (array) $parsed['product_a'],
                    'product_b_data' => (array) $parsed['product_b'],
                    'title' => (string) $parsed['title'],
                    'slug' => $slug,
                    'excerpt' => (string) ($parsed['excerpt'] ?? ''),
                    'content_markdown' => (string) $parsed['content_markdown'],
                    'content_html' => $html,
                    'pros_a' => array_values((array) ($parsed['pros_a'] ?? [])),
                    'cons_a' => array_values((array) ($parsed['cons_a'] ?? [])),
                    'pros_b' => array_values((array) ($parsed['pros_b'] ?? [])),
                    'cons_b' => array_values((array) ($parsed['cons_b'] ?? [])),
                    'winner_name' => (string) ($parsed['winner_name'] ?? $nameA),
                    'faqs' => array_values((array) ($parsed['faqs'] ?? [])),
                    'seo_title' => "So Sánh {$nameA} vs {$nameB}: Phân Tích Hiệu Năng & Giá",
                    'seo_description' => (string) ($parsed['excerpt'] ?? "Đánh giá chi tiết {$nameA} và {$nameB}."),
                    'is_live_ai' => true,
                ];
            }
        }

        // Fallback: Deterministic Synthesis
        return $this->generateFallbackComparison($nameA, $nameB, $categoryHint);
    }

    /**
     * Senior Universal Web Synthesizer: Crawl raw HTML/Text -> LLM creates 100% unique Vietnamese tech article.
     *
     * @return array{
     *     title: string,
     *     slug: string,
     *     excerpt: string,
     *     content_markdown: string,
     *     content_html: string,
     *     faqs: list<array{question: string, answer: string}>,
     *     seo_title: string,
     *     seo_description: string,
     *     is_live_ai: bool
     * }
     */
    public function rewriteScrapedArticle(string $rawText, string $originalTitle, string $sourceUrl): array
    {
        $systemPrompt = 'Bạn là Trưởng ban Biên tập Công nghệ của TechHub. '
            . 'Nhiệm vụ của bạn là đọc nội dung bài viết gốc từ nguồn báo nước ngoài, bóc tách các luận điểm cốt lõi và viết lại thành một bài phân tích chuyên sâu 1.200 - 1.800 từ bằng Tiếng Việt chuẩn SEO, văn phong sắc bén, có chiều sâu kỹ thuật. '
            . 'Bạn PHẢI trả về định dạng JSON theo đúng schema yêu cầu.';

        $cleanTitle = trim($originalTitle) ?: 'Phân Tích Xu Hướng Công Nghệ Mới Nhất';
        $prompt = <<<PROMPT
Tiêu đề nguồn: {$cleanTitle}
Link nguồn: {$sourceUrl}
Nội dung thô bài báo:
"""
{$rawText}
"""

Hãy phân tích và viết lại thành một bài viết hoàn chỉnh trên TechHub. Trả về JSON:
{
  "title": "Tiêu đề tiếng Việt hấp dẫn, chuẩn SEO (khoảng 60-70 ký tự)",
  "excerpt": "Đoạn mô tả tóm tắt khoảng 150 ký tự",
  "content_markdown": "## 1. Tóm Tắt Diễn Biến & Khái Quát Vấn Đề\\n\\n...\\n\\n## 2. Phân Tích Kỹ Thuật & Chi Tiết Nâng Cấp\\n\\n...\\n\\n## 3. Tác Động Tới Thị Trường & Người Dùng\\n\\n...\\n\\n## 4. Đánh Giá Từ Chuyên Gia & Lời Khuyên\\n\\n...",
  "faqs": [
    {"question": "Câu hỏi thường gặp 1 liên quan đến chủ đề?", "answer": "Câu trả lời chi tiết 1"},
    {"question": "Câu hỏi thường gặp 2?", "answer": "Câu trả lời chi tiết 2"}
  ]
}
PROMPT;

        $llmResponse = $this->callLlm($prompt, $systemPrompt, true);

        if ($llmResponse) {
            $cleaned = preg_replace('/^```(?:json)?\s*/i', '', trim($llmResponse));
            $cleaned = preg_replace('/\s*```$/', '', (string) $cleaned);
            $parsed = json_decode((string) $cleaned, true);

            if (is_array($parsed) && isset($parsed['title'], $parsed['content_markdown'])) {
                $title = (string) $parsed['title'];
                $slug = Str::slug($title . '-' . date('Y'));
                $markdown = (string) $parsed['content_markdown'] . "\n\n*Nguồn tham khảo: " . parse_url($sourceUrl, PHP_URL_HOST) . "*\n";
                $html = nl2br(htmlspecialchars($markdown, ENT_QUOTES, 'UTF-8'));
                $excerpt = (string) ($parsed['excerpt'] ?? 'Tổng hợp và phân tích toàn diện các diễn biến công nghệ mới nhất.');

                return [
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => $excerpt,
                    'content_markdown' => $markdown,
                    'content_html' => $html,
                    'faqs' => array_values((array) ($parsed['faqs'] ?? [])),
                    'seo_title' => $title . ' — TechHub',
                    'seo_description' => $excerpt,
                    'is_live_ai' => true,
                ];
            }
        }

        // Fallback: Deterministic Synthesizer
        $title = 'Đánh Giá & Phân Tích Chuyên Sâu: ' . $cleanTitle;
        $slug = Str::slug($cleanTitle . '-' . date('Y'));
        $excerpt = 'Tổng hợp và phân tích toàn diện các thông tin công nghệ nóng hổi nhất, đánh giá tác động thực tế và hướng dẫn chi tiết dành cho người dùng.';

        $markdown = "## 1. Tóm Tắt Điểm Tin & Khái Quát Vấn Đề\n\n";
        $markdown .= "Thông tin mới nhất vừa được công bố đang thu hút sự quan tâm lớn từ cộng đồng công nghệ thế giới. Dưới đây là phân tích chi tiết từ đội ngũ chuyên gia TechHub.\n\n";

        $markdown .= "## 2. Phân Tích Chi Tiết & Tác Động Thực Tế\n\n";
        $paragraphs = array_filter(explode('. ', mb_substr($rawText, 0, 2500)));
        $chunk1 = implode('. ', array_slice($paragraphs, 0, 4)) . '.';
        $chunk2 = implode('. ', array_slice($paragraphs, 4, 5)) . '.';

        $markdown .= $chunk1 . "\n\n";
        $markdown .= "### Những Nâng Cấp Đáng Chú Ý Nhất:\n\n";
        $markdown .= "* ⚡ **Tối ưu hóa hiệu năng**: Cải thiện đáng kể khả năng phản hồi và xử lý tác vụ phức tạp.\n";
        $markdown .= "* 🛡️ **Bảo mật & Ổn định**: Nâng cấp thuật toán kiểm soát dữ liệu an toàn theo tiêu chuẩn quốc tế.\n";
        $markdown .= "* 🌐 **Khả năng tương thích**: Hỗ trợ mở rộng trên nhiều nền tảng phần cứng và hệ sinh thái khác nhau.\n\n";

        if (! empty($chunk2)) {
            $markdown .= "## 3. Góc Nhìn Đánh Giá Từ Chuyên Gia\n\n";
            $markdown .= $chunk2 . "\n\n";
        }

        $markdown .= "## 4. Lời Khuyên Cho Người Dùng & Kết Luận\n\n";
        $markdown .= "Đây là một bước tiến quan trọng giúp nâng cao trải nghiệm người dùng. Bạn nên theo dõi thêm các bản cập nhật tiếp theo để tận dụng tối đa các tính năng mới.\n\n";
        $markdown .= "*Nguồn tham khảo: " . parse_url($sourceUrl, PHP_URL_HOST) . "*\n";

        $faqs = [
            [
                'question' => "Thông tin này có ảnh hưởng trực tiếp đến người dùng tại Việt Nam không?",
                'answer' => "Có, các bản nâng cấp và sản phẩm mới sẽ sớm được áp dụng rộng rãi trên toàn cầu bao gồm thị trường Việt Nam.",
            ],
            [
                'question' => "Làm thế nào để cập nhật thêm các tin tức so sánh liên quan?",
                'answer' => "Bạn có thể theo dõi chuyên mục Tin tức & So sánh công nghệ trên TechHub để nhận được các bài phân tích độc quyền mới nhất.",
            ],
        ];

        $html = nl2br(htmlspecialchars($markdown, ENT_QUOTES, 'UTF-8'));

        return [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content_markdown' => $markdown,
            'content_html' => $html,
            'faqs' => $faqs,
            'seo_title' => $title . ' — TechHub',
            'seo_description' => $excerpt,
            'is_live_ai' => false,
        ];
    }

    /**
     * Fallback comparison generator when API key is not configured or offline.
     *
     * @return array<string, mixed>
     */
    protected function generateFallbackComparison(string $nameA, string $nameB, ?string $categoryHint): array
    {
        $title = "So Sánh {$nameA} vs {$nameB}: Đâu Là Lựa Chọn Đáng Tiền Nhất Năm " . date('Y') . '?';
        $slug = Str::slug("{$nameA}-vs-{$nameB}");
        $excerpt = "Đánh giá chi tiết và so sánh đối đầu giữa {$nameA} và {$nameB}. Phân tích điểm hiệu năng, điện năng tiêu thụ, khả năng gaming và tỷ lệ p/p.";

        $prosA = [
            "Hiệu năng tổng thể vượt trội trong các bài kiểm tra thực tế",
            "Mức tiêu thụ điện năng tối ưu với tiến trình sản xuất hiện đại",
            "Tối ưu tốt cho các tựa game và ứng dụng đồ họa mới nhất",
        ];
        $consA = [
            "Mức giá khởi điểm có thể cao hơn thế hệ trước",
        ];

        $prosB = [
            "Tỷ lệ hiệu năng trên giá thành (P/P) cực kỳ cạnh tranh",
            "Khả năng tương thích rộng rãi với các linh kiện phổ biến",
            "Hỗ trợ các chuẩn kết nối và tính năng thông minh mới",
        ];
        $consB = [
            "Cần hệ thống tản nhiệt và nguồn điện ổn định khi tải nặng",
        ];

        $faqs = [
            [
                'question' => "Nên chọn mua {$nameA} hay {$nameB} ở thời điểm hiện tại?",
                'answer' => "Nếu bạn ưu tiên hiệu năng tối đa cho công việc và trải nghiệm đỉnh cao, {$nameA} là lựa chọn hàng đầu. Tuy nhiên nếu bạn cân nhắc ngân sách, {$nameB} mang lại giá trị p/p vô cùng ấn tượng.",
            ],
            [
                'question' => "{$nameA} và {$nameB} có cần nâng cấp nguồn hay bo mạch chủ không?",
                'answer' => "Bạn nên kiểm tra chuẩn socket hoặc khe cắm PCIe tương thích, đồng thời chuẩn bị bộ nguồn công suất thực từ 650W trở lên.",
            ],
            [
                'question' => "Chênh lệch hiệu năng giữa hai thiết bị có đáng để nâng cấp?",
                'answer' => "Chênh lệch hiệu năng thực tế khoảng 15-25% tùy tác vụ, là bước nhảy vọt đáng giá nếu bạn đang dùng thế hệ cũ hơn.",
            ],
        ];

        $markdown = "## 1. Giới Thiệu & Bối Cảnh Thị Trường\n\n";
        $markdown .= "Cuộc đối đầu giữa **{$nameA}** và **{$nameB}** đang là tâm điểm chú ý của cộng đồng công nghệ. Cả hai đại diện cho những công nghệ đột phá, nhưng đâu mới là giải pháp tối ưu cho nhu cầu của bạn?\n\n";

        $markdown .= "## 2. Bảng So Sánh Thông Số & Điểm Đánh Giá\n\n";
        $markdown .= "| Tiêu Chí So Sánh | {$nameA} | {$nameB} |\n";
        $markdown .= "| :--- | :--- | :--- |\n";
        $markdown .= "| **Phân Khúc** | Cao cấp / Flagship | Cận cao cấp / Mainstream |\n";
        $markdown .= "| **Năm Ra Mắt** | " . date('Y') . " | " . date('Y') . " |\n";
        $markdown .= "| **Điểm Hiệu Năng Tổng Thể** | **9.3 / 10** | **8.9 / 10** |\n";
        $markdown .= "| **Điểm Gaming Chuyên Dụng** | **9.5 / 10** | **9.1 / 10** |\n";
        $markdown .= "| **Điểm Năng Suất Làm Việc** | **9.1 / 10** | **8.7 / 10** |\n\n";

        $markdown .= "## 3. Đánh Giá Hiệu Năng Thực Tế & Khả Năng Xử Lý\n\n";
        $markdown .= "* **{$nameA}**: Mang lại tốc độ phản hồi tức thì, khả năng render mượt mà và khung hình ổn định ở độ phân giải cao.\n";
        $markdown .= "* **{$nameB}**: Cho hiệu năng gaming xuất sắc, tối ưu hóa thuật toán nâng cao chất lượng hình ảnh và nhiệt độ vận hành mát mẻ.\n\n";

        $markdown .= "## 4. Ưu & Nhược Điểm Từng Sản Phẩm\n\n";
        $markdown .= "### Ưu điểm của {$nameA}:\n";
        foreach ($prosA as $p) {
            $markdown .= "* ✅ {$p}\n";
        }
        $markdown .= "\n### Ưu điểm của {$nameB}:\n";
        foreach ($prosB as $p) {
            $markdown .= "* ✅ {$p}\n";
        }
        $markdown .= "\n";

        $markdown .= "## 5. Lời Khuyên Mua Hàng & Kết Luận\n\n";
        $markdown .= "🏆 **Người chiến thắng chung cuộc: {$nameA}**\n\n";
        $markdown .= "Với hiệu năng vượt trội và kiến trúc tân tiến, **{$nameA}** xứng đáng là khoản đầu tư dài hạn cho dàn máy của bạn.\n\n";

        $html = nl2br(htmlspecialchars($markdown, ENT_QUOTES, 'UTF-8'));

        return [
            'product_a_data' => [
                'name' => $nameA,
                'brand' => 'Công Nghệ',
                'category' => $categoryHint ?: 'cpu',
                'release_year' => (int) date('Y'),
                'msrp_usd' => 599,
                'overall_score' => 9.3,
                'gaming_score' => 9.5,
                'productivity_score' => 9.1,
                'specs' => ['segment' => 'Flagship', 'performance' => 'Top-Tier'],
            ],
            'product_b_data' => [
                'name' => $nameB,
                'brand' => 'Công Nghệ',
                'category' => $categoryHint ?: 'cpu',
                'release_year' => (int) date('Y'),
                'msrp_usd' => 529,
                'overall_score' => 8.9,
                'gaming_score' => 9.1,
                'productivity_score' => 8.7,
                'specs' => ['segment' => 'High-End', 'performance' => 'Great P/P'],
            ],
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content_markdown' => $markdown,
            'content_html' => $html,
            'pros_a' => $prosA,
            'cons_a' => $consA,
            'pros_b' => $prosB,
            'cons_b' => $consB,
            'winner_name' => $nameA,
            'faqs' => $faqs,
            'seo_title' => "So Sánh {$nameA} vs {$nameB}: Chi Tiết & Đánh Giá",
            'seo_description' => $excerpt,
            'is_live_ai' => false,
        ];
    }
}
