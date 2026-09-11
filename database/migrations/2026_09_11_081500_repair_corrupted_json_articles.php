<?php

declare(strict_types=1);

use Application\Ai\Services\LlmJsonSanitizer;
use Domain\Article\Entities\Article;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Scans and auto-heals any articles where LLM JSON response was accidentally stored as raw string or ```json title.
     */
    public function up(): void
    {
        try {
            $corruptedArticles = Article::query()
                ->where('title', 'like', '%```%')
                ->orWhere('title', 'like', '%json%')
                ->orWhere('slug', 'like', '%json%')
                ->orWhere('content_markdown', 'like', '%"content_markdown":%')
                ->orWhere('content_markdown', 'like', '%```json%')
                ->get();

            foreach ($corruptedArticles as $article) {
                LlmJsonSanitizer::cleanCorruptedArticle($article);
            }
        } catch (\Throwable $e) {
            Log::error('Migration repair_corrupted_json_articles warning: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed for data sanitization
    }
};
