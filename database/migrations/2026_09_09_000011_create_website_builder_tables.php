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
        // 1. Websites
        Schema::create('websites', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('subdomain', 63)->unique();
            $table->string('status', 30)->default('draft'); // draft, published, suspended
            $table->unsignedBigInteger('active_version_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index('subdomain');
        });

        // 2. Website Domains (Custom Domain)
        Schema::create('website_domains', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->cascadeOnDelete();
            $table->string('domain', 255)->unique();
            $table->string('verification_token', 64);
            $table->string('status', 30)->default('pending_dns'); // pending_dns, verified, active, failed
            $table->string('ssl_status', 30)->default('pending'); // pending, issued, failed
            $table->json('dns_records')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['domain', 'status']);
        });

        // 3. Pages
        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('website_id')->constrained('websites')->cascadeOnDelete();
            $table->string('title', 200);
            $table->string('slug', 150);
            $table->boolean('is_home')->default(false);
            $table->longText('draft_content'); // JSON AST tree
            $table->unsignedBigInteger('version_number')->default(1); // Optimistic locking
            $table->string('status', 30)->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['website_id', 'slug', 'deleted_at']);
            $table->index(['website_id', 'is_home']);
        });

        // 4. Page Versions (Immutable history)
        Schema::create('page_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->unsignedBigInteger('version_number');
            $table->longText('content_json');
            $table->longText('styles_json')->nullable();
            $table->string('commit_message', 255)->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->unique(['page_id', 'version_number']);
            $table->index(['page_id', 'created_at']);
        });

        // 5. Published Sites (Release deployments on S3/CDN)
        Schema::create('published_sites', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('website_id')->constrained('websites')->cascadeOnDelete();
            $table->string('version_tag', 50); // e.g. v1.0.1
            $table->string('storage_directory', 255); // S3 prefix path
            $table->json('manifest_json'); // File list and hashes
            $table->string('status', 30)->default('deploying'); // deploying, live, rolled_back, failed
            $table->foreignId('deployed_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['website_id', 'status']);
        });

        // 6. Website Assets
        Schema::create('website_assets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('website_id')->nullable()->constrained('websites')->nullOnDelete();
            $table->string('file_name', 255);
            $table->string('original_name', 255);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->string('storage_path', 500);
            $table->json('variants')->nullable(); // webp, thumbnail, avif URLs
            $table->json('dimensions')->nullable(); // { width, height }
            $table->timestamps();
            $table->softDeletes();

            $table->index(['website_id', 'created_at']);
            $table->index('user_id');
        });

        // 7. Website Settings
        Schema::create('website_settings', function (Blueprint $table): void {
            $table->foreignId('website_id')->primary()->constrained('websites')->cascadeOnDelete();
            $table->string('favicon_url', 500)->nullable();
            $table->mediumText('custom_css')->nullable();
            $table->mediumText('custom_js_head')->nullable();
            $table->mediumText('custom_js_body')->nullable();
            $table->string('google_analytics_id', 50)->nullable();
            $table->string('facebook_pixel_id', 50)->nullable();
            $table->boolean('remove_branding')->default(false);
            $table->timestamps();
        });

        // 8. Website SEO
        Schema::create('website_seo', function (Blueprint $table): void {
            $table->foreignId('website_id')->primary()->constrained('websites')->cascadeOnDelete();
            $table->string('meta_title', 150)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('og_image_url', 500)->nullable();
            $table->boolean('sitemap_enabled')->default(true);
            $table->text('robots_txt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_seo');
        Schema::dropIfExists('website_settings');
        Schema::dropIfExists('website_assets');
        Schema::dropIfExists('published_sites');
        Schema::dropIfExists('page_versions');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('website_domains');
        Schema::dropIfExists('websites');
    }
};
