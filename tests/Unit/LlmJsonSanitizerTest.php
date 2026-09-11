<?php

declare(strict_types=1);

namespace Tests\Unit;

use Application\Ai\Services\LlmJsonSanitizer;
use Domain\Article\Entities\Article;
use Domain\Article\Entities\ContentCategory;
use Domain\User\Entities\User;
use Domain\User\Enums\UserStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LlmJsonSanitizerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_parses_json_with_unescaped_newlines_in_content(): void
    {
        $payload = <<<'JSON'
```json
{
  "title": "Thoát Khỏi Tutorial Hell 2026: Phương Pháp Học Công Nghệ Mới Bằng AI",
  "excerpt": "Khám phá framework 6 bước học công nghệ mới đỉnh cao năm 2026. Làm chủ Next.js 15, Rust, AI Tools mà không rơi vào bẫy Tutorial Hell.",
  "content_markdown": "# Thoát Khỏi Tutorial Hell 2026: Phương Pháp Học Công Nghệ Mới Bằng AI

Năm 2018, kịch bản phổ biến của một lập trình viên là dành hàng tuần xem video.

## 1. Bẫy Tâm Lý Tutorial Hell
Nội dung chi tiết ở đây...",
  "faqs": [
    {
      "question": "Tutorial Hell là gì?",
      "answer": "Bẫy học vẹt khi chỉ xem mà không tự code."
    }
  ]
}
```
JSON;

        $result = LlmJsonSanitizer::parseArticleJson($payload, 'Fallback Title');

        $this->assertSame('Thoát Khỏi Tutorial Hell 2026: Phương Pháp Học Công Nghệ Mới Bằng AI', $result['title']);
        $this->assertStringStartsWith('# Thoát Khỏi Tutorial Hell 2026', $result['content_markdown']);
        $this->assertStringNotContainsString('```json', $result['title']);
        $this->assertCount(1, $result['faqs']);
        $this->assertSame('Tutorial Hell là gì?', $result['faqs'][0]['question']);
    }

    public function test_it_never_allows_markdown_fence_as_title(): void
    {
        $raw = "```json\nNội dung bài viết không phải JSON nhưng có mở đầu bằng fence";
        $result = LlmJsonSanitizer::parseArticleJson($raw, 'Fallback Chuẩn');

        $this->assertNotSame('```json', $result['title']);
        $this->assertStringStartsNotWith('```', $result['title']);
    }

    public function test_it_auto_heals_corrupted_article_in_database(): void
    {
        $user = User::create([
            'ulid' => (string) Str::ulid(),
            'name' => 'Admin User',
            'email' => 'admin-' . time() . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => UserStatus::Active,
        ]);

        $category = ContentCategory::create([
            'name' => 'Công Nghệ',
            'slug' => 'cong-nghe-' . time(),
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $corruptedJson = <<<'JSON'
```json
{
  "title": "Thoát Khỏi Tutorial Hell 2026: Phương Pháp Học Công Nghệ Mới Bằng AI",
  "excerpt": "Khám phá framework 6 bước học công nghệ mới.",
  "content_markdown": "# Thoát Khỏi Tutorial Hell 2026\n\nNội dung bài viết...",
  "faqs": [
    {
      "question": "Câu hỏi 1?",
      "answer": "Trả lời 1"
    }
  ]
}
```
JSON;

        $article = Article::create([
            'author_id' => $user->id,
            'category_id' => $category->id,
            'type' => 'news',
            'slug' => 'json-2',
            'title' => '```json',
            'excerpt' => '```json { "title": "Thoát Khỏi Tutorial Hell"...',
            'content_markdown' => $corruptedJson,
            'content_html' => '<pre><code>' . htmlspecialchars($corruptedJson) . '</code></pre>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->assertSame('```json', $article->title);
        $this->assertSame('json-2', $article->slug);

        // Run auto-heal
        $healed = LlmJsonSanitizer::cleanCorruptedArticle($article);

        $this->assertTrue($healed);
        $this->assertSame('Thoát Khỏi Tutorial Hell 2026: Phương Pháp Học Công Nghệ Mới Bằng AI', $article->title);
        $this->assertNotSame('json-2', $article->slug);
        $this->assertStringStartsWith('# Thoát Khỏi Tutorial Hell 2026', $article->content_markdown);
        $this->assertIsArray($article->schema_markup);
        $this->assertSame('FAQPage', $article->schema_markup['@type']);

        // Verify re-reading from DB
        $reloaded = Article::find($article->id);
        $this->assertSame('Thoát Khỏi Tutorial Hell 2026: Phương Pháp Học Công Nghệ Mới Bằng AI', $reloaded->title);
        $this->assertNotSame('```json', $reloaded->title);
    }
}
