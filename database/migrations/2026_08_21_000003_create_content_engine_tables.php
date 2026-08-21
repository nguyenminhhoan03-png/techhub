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
        Schema::create('content_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('content_categories')->nullOnDelete();
            $table->string('slug', 150)->unique();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('category_id')->constrained('content_categories');
            $table->string('type', 30)->default('article'); // article, review, benchmark_guide, news, comparison_guide
            $table->string('slug', 255)->unique();
            $table->string('title', 300);
            $table->text('excerpt');
            $table->longText('content_markdown');
            $table->longText('content_html');
            $table->string('featured_image_url', 500)->nullable();
            $table->unsignedInteger('read_time_minutes')->default(3);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->string('status', 30)->default('draft'); // draft, published, scheduled, archived
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->json('schema_markup')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->index(['category_id', 'published_at']);
        });

        Schema::create('post_tool', function (Blueprint $table): void {
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();

            $table->primary(['post_id', 'tool_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tool');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('content_categories');
    }
};
