<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Article\Entities\Article;
use Domain\Article\Entities\ContentCategory;
use Domain\Article\Enums\ArticleType;
use Domain\Hardware\Entities\Brand;
use Domain\Hardware\Entities\Product;
use Domain\Hardware\Entities\ProductCategory;
use Domain\User\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HardwareAndArticleSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::query()->first() ?? User::query()->create([
            'name' => 'Admin TechHub',
            'email' => 'admin@techhub.vn',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 1. Brands
        $brands = [
            ['name' => 'NVIDIA', 'slug' => 'nvidia', 'website_url' => 'https://www.nvidia.com'],
            ['name' => 'AMD', 'slug' => 'amd', 'website_url' => 'https://www.amd.com'],
            ['name' => 'Intel', 'slug' => 'intel', 'website_url' => 'https://www.intel.com'],
            ['name' => 'Apple', 'slug' => 'apple', 'website_url' => 'https://www.apple.com'],
            ['name' => 'Samsung', 'slug' => 'samsung', 'website_url' => 'https://www.samsung.com'],
        ];

        $brandMap = [];
        foreach ($brands as $b) {
            $brand = Brand::query()->updateOrCreate(['slug' => $b['slug']], $b);
            $brandMap[$b['slug']] = $brand->id;
        }

        // 2. Product Categories
        $productCategories = [
            ['name' => 'Card Màn Hình (GPU)', 'slug' => 'gpu', 'icon' => 'tv'],
            ['name' => 'Bộ Vi Xử Lý (CPU)', 'slug' => 'cpu', 'icon' => 'cpu'],
            ['name' => 'Điện Thoại Di Động', 'slug' => 'smartphone', 'icon' => 'smartphone'],
            ['name' => 'Máy Tính Xách Tay (Laptop)', 'slug' => 'laptop', 'icon' => 'laptop'],
        ];

        $prodCatMap = [];
        foreach ($productCategories as $pc) {
            $cat = ProductCategory::query()->updateOrCreate(['slug' => $pc['slug']], $pc);
            $prodCatMap[$pc['slug']] = $cat->id;
        }

        // 3. Products
        $products = [
            [
                'category_id' => $prodCatMap['gpu'],
                'brand_id' => $brandMap['nvidia'],
                'slug' => 'nvidia-geforce-rtx-5070-12gb',
                'model_name' => 'RTX 5070',
                'full_name' => 'NVIDIA GeForce RTX 5070 12GB GDDR7',
                'release_date' => '2025-01-15',
                'launch_msrp_usd' => 599.00,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=800&fit=crop',
                'overall_score' => 9.2,
                'gaming_score' => 9.5,
                'productivity_score' => 8.9,
                'is_featured' => true,
                'is_active' => true,
                'specs' => [
                    'architecture' => 'Blackwell',
                    'vram_gb' => 12,
                    'memory_type' => 'GDDR7',
                    'cuda_cores' => 6144,
                    'tdp_watts' => 220,
                    'recommended_psu_watts' => 650,
                ],
            ],
            [
                'category_id' => $prodCatMap['gpu'],
                'brand_id' => $brandMap['nvidia'],
                'slug' => 'nvidia-geforce-rtx-4070-12gb',
                'model_name' => 'RTX 4070',
                'full_name' => 'NVIDIA GeForce RTX 4070 12GB GDDR6X',
                'release_date' => '2023-04-13',
                'launch_msrp_usd' => 549.00,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=800&fit=crop',
                'overall_score' => 8.4,
                'gaming_score' => 8.6,
                'productivity_score' => 8.1,
                'is_featured' => true,
                'is_active' => true,
                'specs' => [
                    'architecture' => 'Ada Lovelace',
                    'vram_gb' => 12,
                    'memory_type' => 'GDDR6X',
                    'cuda_cores' => 5888,
                    'tdp_watts' => 200,
                    'recommended_psu_watts' => 600,
                ],
            ],
            [
                'category_id' => $prodCatMap['cpu'],
                'brand_id' => $brandMap['amd'],
                'slug' => 'amd-ryzen-7-7800x3d',
                'model_name' => 'Ryzen 7 7800X3D',
                'full_name' => 'AMD Ryzen 7 7800X3D (8C/16T, 3D V-Cache)',
                'release_date' => '2023-04-06',
                'launch_msrp_usd' => 449.00,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1555680202-c86f0e12f086?w=800&fit=crop',
                'overall_score' => 9.4,
                'gaming_score' => 9.8,
                'productivity_score' => 8.4,
                'is_featured' => true,
                'is_active' => true,
                'specs' => [
                    'cores' => 8,
                    'threads' => 16,
                    'l3_cache_mb' => 96,
                    'base_clock_ghz' => 4.2,
                    'boost_clock_ghz' => 5.0,
                    'tdp_watts' => 120,
                    'socket' => 'AM5',
                ],
            ],
            [
                'category_id' => $prodCatMap['cpu'],
                'brand_id' => $brandMap['intel'],
                'slug' => 'intel-core-i5-14600k',
                'model_name' => 'Core i5 14600K',
                'full_name' => 'Intel Core i5-14600K (14C/20T, Turbo 5.3GHz)',
                'release_date' => '2023-10-17',
                'launch_msrp_usd' => 319.00,
                'thumbnail_url' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=800&fit=crop',
                'overall_score' => 8.7,
                'gaming_score' => 8.8,
                'productivity_score' => 8.9,
                'is_featured' => true,
                'is_active' => true,
                'specs' => [
                    'cores' => 14,
                    'threads' => 20,
                    'l3_cache_mb' => 24,
                    'base_clock_ghz' => 3.5,
                    'boost_clock_ghz' => 5.3,
                    'tdp_watts' => 125,
                    'socket' => 'LGA1700',
                ],
            ],
        ];

        foreach ($products as $pData) {
            Product::query()->updateOrCreate(['slug' => $pData['slug']], $pData);
        }

        // 4. Content Categories
        $contentCategories = [
            ['name' => 'So Sánh Phần Cứng', 'slug' => 'hardware-compare', 'description' => 'Các bài so sánh đối đầu CPU, GPU, RAM, SSD và linh kiện PC.'],
            ['name' => 'Đánh Giá Thiết Bị', 'slug' => 'hardware-reviews', 'description' => 'Đánh giá chi tiết smartphone, laptop và thiết bị ngoại vi.'],
            ['name' => 'Tư Vấn Cấu Hình', 'slug' => 'buying-guides', 'description' => 'Hướng dẫn xây dựng cấu hình PC theo ngân sách và nhu cầu thực tế.'],
            ['name' => 'Tin Tức Công Nghệ', 'slug' => 'tech-news', 'description' => 'Cập nhật tin tức và xu hướng công nghệ mới nhất.'],
        ];

        $contentCatMap = [];
        foreach ($contentCategories as $cc) {
            $cat = ContentCategory::query()->updateOrCreate(['slug' => $cc['slug']], $cc);
            $contentCatMap[$cc['slug']] = $cat->id;
        }

        // 5. Articles
        $articles = [
            [
                'author_id' => $adminUser->id,
                'category_id' => $contentCatMap['hardware-compare'],
                'type' => ArticleType::Comparison,
                'slug' => 'so-sanh-rtx-5070-vs-rtx-4070',
                'title' => 'So Sánh RTX 5070 vs RTX 4070: Quái Vật 1440p Nào Đáng Mua Nhất 2026?',
                'excerpt' => 'Đánh giá chi tiết sự khác biệt về hiệu năng chơi game, mức tiêu thụ điện năng và công nghệ bộ nhớ GDDR7 giữa NVIDIA RTX 5070 và tiền nhiệm RTX 4070.',
                'featured_image_url' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=1200&fit=crop',
                'read_time_minutes' => 6,
                'view_count' => 5420,
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'meta_title' => 'So Sánh RTX 5070 vs RTX 4070: Đâu Là Lựa Chọn Tốt Nhất 2026? — TechHub',
                'meta_description' => 'Bài so sánh đối đầu toàn diện RTX 5070 và RTX 4070. Đánh giá FPS thực tế ở 1440p/4K, mức tiêu thụ điện và lời khuyên nâng cấp chuẩn xác.',
                'schema_markup' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => [
                        [
                            '@type' => 'Question',
                            'name' => 'Nên mua RTX 5070 hay RTX 4070 ở thời điểm hiện tại?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'RTX 5070 mang lại hiệu năng cao hơn khoảng 20-25% nhờ bộ nhớ GDDR7 thế hệ mới, là sự lựa chọn tối ưu cho trải nghiệm Gaming 1440p và 4K trong 3-5 năm tới.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Nguồn 650W có đủ cân RTX 5070 không?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Có. Với mức TDP 220W, bộ nguồn 650W chuẩn 80 Plus Bronze/Gold hoàn toàn đáp ứng tốt cho dàn máy trang bị RTX 5070.',
                            ],
                        ],
                    ],
                ],
                'content_markdown' => "## 1. Giới Thiệu Cuộc Đối Đầu RTX 5070 vs RTX 4070

Phân khúc card màn hình tầm trung cao cấp luôn là chiến trường khốc liệt nhất của NVIDIA. Với sự xuất hiện của kiến trúc **Blackwell**, **GeForce RTX 5070** hứa hẹn sẽ soán ngôi người anh tiền nhiệm **RTX 4070** để trở thành ông vua độ phân giải 1440p mới.

## 2. So Sánh Thông Số Kỹ Thuật Chi Tiết

| Tiêu Chí | NVIDIA RTX 5070 | NVIDIA RTX 4070 |
| :--- | :--- | :--- |
| **Kiến Trúc** | Blackwell (4N TSMC) | Ada Lovelace (4N TSMC) |
| **CUDA Cores** | 6,144 | 5,888 |
| **VRAM** | 12GB GDDR7 | 12GB GDDR6X |
| **Băng Thông Bộ Nhớ** | 672 GB/s | 504 GB/s |
| **TDP (Công Suất Tiêu Thụ)** | 220W | 200W |
| **Giá Ra Mắt (MSRP)** | $599 | $549 |

## 3. Hiệu Năng Thực Tế: Gaming 1440p & 4K Ray Tracing

Trong các bài test trên các tựa game nặng như *Cyberpunk 2077*, *Black Myth: Wukong*, và *Alan Wake 2*:

* **RTX 5070**: Đạt mức FPS trung bình cao hơn 22% so với RTX 4070 ở thiết lập 1440p Ultra. Bộ nhớ GDDR7 băng thông cao giúp hiện tượng giật khung hình (1% low FPS) được cải thiện rõ rệt.
* **RTX 4070**: Vẫn duy trì phong độ ấn tượng ở 1440p 60-80 FPS, nhưng bắt đầu hụt hơi khi bật Full Ray Tracing ở độ phân giải 4K nếu không bật DLSS 3.

## 4. Đánh Giá Ưu & Nhược Điểm

### Ưu điểm của RTX 5070:
* ✅ Băng thông bộ nhớ GDDR7 vượt trội (672 GB/s).
* ✅ Hiệu năng Ray Tracing và xử lý AI thế hệ mới mạnh mẽ hơn 25%.
* ✅ Khả năng chơi mượt mà mọi tựa game AAA ở 1440p và 4K.

### Ưu điểm của RTX 4070:
* ✅ Mức giá đã giảm sâu và nhiều chương trình khuyến mãi hấp dẫn.
* ✅ Tiết kiệm điện năng hơn (chỉ 200W TDP).
* ✅ Phù hợp với các hệ thống nguồn công suất từ 600W.

## 5. Kết Luận & Lời Khuyên Mua Sắm

🏆 **Người chiến thắng: NVIDIA GeForce RTX 5070**

Nếu bạn đang xây dựng một dàn PC hoàn toàn mới hoặc nâng cấp từ các đời RTX 2060 / 3060, **RTX 5070** là khoản đầu tư tuyệt vời và có tuổi thọ sử dụng lâu dài hơn đáng kể.",
                'content_html' => '<p>Chi tiết bài viết so sánh đối đầu.</p>',
            ],
            [
                'author_id' => $adminUser->id,
                'category_id' => $contentCatMap['hardware-compare'],
                'type' => ArticleType::Comparison,
                'slug' => 'so-sanh-ryzen-7-7800x3d-vs-core-i5-14600k',
                'title' => 'Ryzen 7 7800X3D vs Core i5-14600K: CPU Nào Thống Trị Gaming & Đồ Họa?',
                'excerpt' => 'So tài giữa vua gaming AMD Ryzen 7 7800X3D với công nghệ 3D V-Cache độc quyền và quái vật đa nhiệm Intel Core i5-14600K.',
                'featured_image_url' => 'https://images.unsplash.com/photo-1555680202-c86f0e12f086?w=1200&fit=crop',
                'read_time_minutes' => 5,
                'view_count' => 3890,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'meta_title' => 'Ryzen 7 7800X3D vs i5 14600K: So Sánh Toàn Diện — TechHub',
                'meta_description' => 'Phân tích điểm số Cinebench, gaming FPS và khả năng tản nhiệt giữa Ryzen 7 7800X3D và Intel i5 14600K.',
                'schema_markup' => null,
                'content_markdown' => "## 1. Đặt Lên Bàn Cân: Đỏ Hay Xanh?

Cuộc chiến giữa **AMD Ryzen 7 7800X3D** và **Intel Core i5-14600K** đại diện cho hai triết lý thiết kế hoàn toàn khác nhau: Một bên tối ưu hóa tuyệt đối cho Gaming với bộ nhớ đệm 3D V-Cache 96MB khổng lồ, và một bên hướng tới sự đa dụng với kiến trúc lai (Hybrid P-core + E-core).

## 2. Bảng So Sánh Cấu Hình Chi Tiết

* **Ryzen 7 7800X3D**: 8 nhân / 16 luồng, 96MB L3 Cache, TDP 120W, Socket AM5.
* **Core i5-14600K**: 14 nhân / 20 luồng (6P + 8E), 24MB L3 Cache, TDP 125W (PL2 181W), Socket LGA1700.

## 3. Hiệu Năng Chơi Game Thực Tế

Trong hầu hết các tựa game eSports và thế giới mở (CS2, Valorant, Cyberpunk, Baldur's Gate 3), **Ryzen 7 7800X3D** dẫn trước từ 12% đến 18% FPS trung bình và tiết kiệm điện năng hơn rõ rệt.

## 4. Hiệu Năng Đồ Họa & Render Đa Luồng

Với 14 nhân xử lý, **Intel Core i5-14600K** lại chiếm ưu thế ở các tác vụ nén file, dựng phim Adobe Premiere, Premiere Pro và render 3D Blender.

## 5. Kết Luận

* Chọn **Ryzen 7 7800X3D** nếu mục tiêu chính là **Dàn PC Gaming Chuyên Nghiệp**.
* Chọn **Core i5-14600K** nếu bạn cần một chiếc máy tính **Cân Bằng Giữa Làm Việc & Giải Trí**.",
                'content_html' => '<p>Nội dung so sánh CPU.</p>',
            ],
        ];

        foreach ($articles as $artData) {
            Article::query()->updateOrCreate(['slug' => $artData['slug']], $artData);
        }
    }
}
