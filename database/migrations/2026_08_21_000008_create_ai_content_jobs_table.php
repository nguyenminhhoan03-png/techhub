<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_content_jobs', function (Blueprint $table): void {
            $table->id();
            $table->string('job_type', 50)->default('url_crawl_rewrite'); // url_crawl_rewrite, vs_specs_generator, faq_generator
            $table->string('source_url', 500)->nullable();
            $table->string('target_topic', 255)->nullable();
            $table->longText('raw_scraped_text')->nullable();
            $table->longText('prompt_used')->nullable();
            $table->longText('generated_markdown')->nullable();
            $table->json('generated_metadata')->nullable(); // title, excerpt, seo_keywords, faqs, toc
            $table->foreignId('post_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->string('status', 30)->default('pending'); // pending, processing, completed, failed
            $table->text('error_message')->nullable();
            $table->unsignedInteger('execution_time_ms')->default(0);
            $table->timestamps();

            $table->index(['job_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_content_jobs');
    }
};
