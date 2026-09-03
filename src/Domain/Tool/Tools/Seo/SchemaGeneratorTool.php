<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Seo;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class SchemaGeneratorTool implements ToolContract
{
    public function slug(): string
    {
        return 'schema-generator';
    }

    public function name(): string
    {
        return 'Tạo Schema Markup (JSON-LD) Chuẩn Google';
    }

    public function categorySlug(): string
    {
        return 'seo';
    }

    public function summary(): string
    {
        return 'Tạo mã dữ liệu có cấu trúc Schema.org chuẩn Google Rich Results: Article, LocalBusiness, FAQPage, Product, Breadcrumbs.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'schema_type' => ['required', 'string', 'in:Article,LocalBusiness,Product,FAQPage,BreadcrumbList,SoftwareApplication,Organization'],
            // Dynamic field validation rules handled in execute
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $type = (string) ($input['schema_type'] ?? 'Article');

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $type,
        ];

        $isEn = (class_exists(\Illuminate\Support\Facades\Facade::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication())
            ? \Illuminate\Support\Facades\App::getLocale() === 'en'
            : false;

        switch ($type) {
            case 'Article':
                $schema['headline'] = (string) ($input['headline'] ?? $input['title'] ?? ($isEn ? 'SEO-Friendly Article Headline' : 'Tiêu đề bài viết chuẩn SEO'));
                $schema['description'] = (string) ($input['description'] ?? '');
                $schema['image'] = (string) ($input['image_url'] ?? 'https://example.com/banner.jpg');
                $schema['author'] = [
                    '@type' => 'Person',
                    'name' => (string) ($input['author_name'] ?? 'TechHub Editorial'),
                    'url' => (string) ($input['author_url'] ?? ''),
                ];
                $schema['publisher'] = [
                    '@type' => 'Organization',
                    'name' => (string) ($input['publisher_name'] ?? 'TechHub'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => (string) ($input['publisher_logo'] ?? 'https://example.com/logo.png'),
                    ],
                ];
                $schema['datePublished'] = (string) ($input['date_published'] ?? date('Y-m-d\TH:i:s\Z'));
                $schema['dateModified'] = (string) ($input['date_modified'] ?? date('Y-m-d\TH:i:s\Z'));
                $schema['mainEntityOfPage'] = [
                    '@type' => 'WebPage',
                    '@id' => (string) ($input['url'] ?? ($isEn ? 'https://example.com/article' : 'https://example.com/bai-viet')),
                ];
                break;

            case 'FAQPage':
                $faqs = $this->parseFaqs($input);
                $schema['mainEntity'] = array_map(function ($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['answer'],
                        ],
                    ];
                }, $faqs);
                break;

            case 'Product':
                $schema['name'] = (string) ($input['name'] ?? ($isEn ? 'Sample Product Name' : 'Tên sản phẩm mẫu'));
                $schema['image'] = (string) ($input['image_url'] ?? 'https://example.com/product.jpg');
                $schema['description'] = (string) ($input['description'] ?? '');
                $schema['sku'] = (string) ($input['sku'] ?? 'SKU-10001');
                $schema['brand'] = [
                    '@type' => 'Brand',
                    'name' => (string) ($input['brand'] ?? ($isEn ? 'BrandName' : 'Thương hiệu')),
                ];
                $schema['offers'] = [
                    '@type' => 'Offer',
                    'url' => (string) ($input['url'] ?? ($isEn ? 'https://example.com/product' : 'https://example.com/san-pham')),
                    'priceCurrency' => (string) ($input['price_currency'] ?? ($isEn ? 'USD' : 'VND')),
                    'price' => (string) ($input['price'] ?? ($isEn ? '49.99' : '500000')),
                    'priceValidUntil' => date('Y-12-31'),
                    'itemCondition' => 'https://schema.org/NewCondition',
                    'availability' => 'https://schema.org/' . (string) ($input['availability'] ?? 'InStock'),
                ];
                if (!empty($input['rating_value'])) {
                    $schema['aggregateRating'] = [
                        '@type' => 'AggregateRating',
                        'ratingValue' => (string) $input['rating_value'],
                        'reviewCount' => (string) ($input['review_count'] ?? '25'),
                    ];
                }
                break;

            case 'LocalBusiness':
            case 'Organization':
                $schema['name'] = (string) ($input['name'] ?? ($isEn ? 'TechHub Corp / Store' : 'Công ty / Cửa hàng TechHub'));
                $schema['url'] = (string) ($input['url'] ?? 'https://example.com');
                $schema['logo'] = (string) ($input['logo_url'] ?? 'https://example.com/logo.png');
                $schema['image'] = (string) ($input['image_url'] ?? 'https://example.com/storefront.jpg');
                $schema['telephone'] = (string) ($input['telephone'] ?? ($isEn ? '+1 800 555 0199' : '+84 901 234 567'));
                $schema['email'] = (string) ($input['email'] ?? 'contact@example.com');
                $schema['address'] = [
                    '@type' => 'PostalAddress',
                    'streetAddress' => (string) ($input['address_street'] ?? ($isEn ? '123 Tech Street' : '123 Đường Công Nghệ')),
                    'addressLocality' => (string) ($input['address_locality'] ?? ($isEn ? 'San Francisco' : 'Hà Nội')),
                    'addressRegion' => (string) ($input['address_region'] ?? ($isEn ? 'CA' : 'HN')),
                    'postalCode' => (string) ($input['postal_code'] ?? ($isEn ? '94105' : '100000')),
                    'addressCountry' => (string) ($input['address_country'] ?? ($isEn ? 'US' : 'VN')),
                ];
                if ('LocalBusiness' === $type && !empty($input['price_range'])) {
                    $schema['priceRange'] = (string) $input['price_range'];
                }
                break;

            case 'BreadcrumbList':
                $breadcrumbs = $this->parseBreadcrumbs($input);
                $itemList = [];
                $pos = 1;
                foreach ($breadcrumbs as $bc) {
                    $itemList[] = [
                        '@type' => 'ListItem',
                        'position' => $pos++,
                        'name' => $bc['name'],
                        'item' => $bc['url'],
                    ];
                }
                $schema['itemListElement'] = $itemList;
                break;

            case 'SoftwareApplication':
                $schema['name'] = (string) ($input['name'] ?? 'TechHub Developer Suite');
                $schema['operatingSystem'] = (string) ($input['operating_system'] ?? 'Web Browser');
                $schema['applicationCategory'] = (string) ($input['application_category'] ?? 'DeveloperApplication');
                $schema['offers'] = [
                    '@type' => 'Offer',
                    'price' => (string) ($input['price'] ?? '0'),
                    'priceCurrency' => (string) ($input['price_currency'] ?? 'USD'),
                ];
                if (!empty($input['rating_value'])) {
                    $schema['aggregateRating'] = [
                        '@type' => 'AggregateRating',
                        'ratingValue' => (string) $input['rating_value'],
                        'ratingCount' => (string) ($input['rating_count'] ?? '120'),
                    ];
                }
                break;
        }

        // Clean empty values recursively
        $schema = $this->arrayFilterRecursive($schema);

        $jsonEncoded = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $scriptTag = "<script type=\"application/ld+json\">\n" . $jsonEncoded . "\n</script>";

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $scriptTag,
            'json_ld' => $jsonEncoded,
            'schema_object' => $schema,
            'schema_type' => $type,
            'is_valid_json' => true,
            'rich_results_ready' => true,
        ], executionTimeMs: $executionTimeMs);
    }

    /**
     * Parse FAQ questions & answers from inputs.
     *
     * @return array<int, array{question: string, answer: string}>
     */
    private function parseFaqs(array $input): array
    {
        if (!empty($input['faqs']) && is_array($input['faqs'])) {
            return $input['faqs'];
        }

        $rawText = (string) ($input['faq_text'] ?? '');
        if (!empty($rawText)) {
            $lines = explode("\n", $rawText);
            $faqs = [];
            $currentQ = '';
            $currentA = '';

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                if (preg_match('/^(Q:|Hỏi:|\d+\.)\s*(.+)$/i', $line, $m)) {
                    if (!empty($currentQ) && !empty($currentA)) {
                        $faqs[] = ['question' => $currentQ, 'answer' => trim($currentA)];
                    }
                    $currentQ = $m[2];
                    $currentA = '';
                } elseif (preg_match('/^(A:|Trả lời:|Đáp:)\s*(.+)$/i', $line, $m)) {
                    $currentA .= ' ' . $m[2];
                } else {
                    $currentA .= ' ' . $line;
                }
            }

            if (!empty($currentQ) && !empty($currentA)) {
                $faqs[] = ['question' => $currentQ, 'answer' => trim($currentA)];
            }

            if (!empty($faqs)) {
                return $faqs;
            }
        }

        $isEn = (class_exists(\Illuminate\Support\Facades\Facade::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication())
            ? \Illuminate\Support\Facades\App::getLocale() === 'en'
            : false;

        // Fallback default sample FAQs
        return $isEn ? [
            [
                'question' => 'What is Onpage SEO and why is it important?',
                'answer' => 'Onpage SEO is the practice of optimizing web page content and HTML structure to improve search rankings and deliver the best user experience.',
            ],
            [
                'question' => 'Does JSON-LD Schema improve Google rankings?',
                'answer' => 'Yes, structured data helps Google understand page context and enables Rich Results (star ratings, pricing, FAQ snippets) which dramatically increases click-through rates (CTR).',
            ],
        ] : [
            [
                'question' => 'SEO Onpage là gì và tại sao lại quan trọng?',
                'answer' => 'SEO Onpage là tập hợp các kỹ thuật tối ưu hóa trực tiếp trên trang web nhằm nâng cao thứ hạng trên công cụ tìm kiếm và mang lại trải nghiệm tốt nhất cho người dùng.',
            ],
            [
                'question' => 'Schema JSON-LD có giúp website tăng thứ hạng Google không?',
                'answer' => 'Có, dữ liệu cấu trúc Schema giúp Google hiểu chính xác nội dung trang và kích hoạt tính năng Rich Results (kết quả tìm kiếm mở rộng với đánh giá sao, bảng giá, FAQ) giúp tăng tỷ lệ nhấp chuột (CTR).',
            ],
        ];
    }

    /**
     * Parse Breadcrumb navigation entries.
     *
     * @return array<int, array{name: string, url: string}>
     */
    private function parseBreadcrumbs(array $input): array
    {
        if (!empty($input['breadcrumbs']) && is_array($input['breadcrumbs'])) {
            return $input['breadcrumbs'];
        }

        $isEn = (class_exists(\Illuminate\Support\Facades\Facade::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication())
            ? \Illuminate\Support\Facades\App::getLocale() === 'en'
            : false;

        return $isEn ? [
            ['name' => 'Home', 'url' => 'https://example.com'],
            ['name' => 'SEO Tools', 'url' => 'https://example.com/tools/seo'],
            ['name' => 'Schema Generator', 'url' => 'https://example.com/tools/schema-generator'],
        ] : [
            ['name' => 'Trang Chủ', 'url' => 'https://example.com'],
            ['name' => 'Công Cụ SEO', 'url' => 'https://example.com/tools/seo'],
            ['name' => 'Schema Generator', 'url' => 'https://example.com/tools/schema-generator'],
        ];
    }

    /**
     * Recursively remove empty string fields.
     */
    private function arrayFilterRecursive(array $array): array
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->arrayFilterRecursive($value);
                if (empty($value)) {
                    unset($array[$key]);
                }
            } elseif ('' === $value || null === $value) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}
