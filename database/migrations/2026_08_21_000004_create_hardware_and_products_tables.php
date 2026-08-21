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
        Schema::create('brands', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 150);
            $table->string('logo_url', 500)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('product_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('slug', 100)->unique(); // cpu, gpu, laptop, motherboard, ram, ssd
            $table->string('name', 150);
            $table->string('icon', 100)->nullable();
            $table->json('spec_schema')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('category_id')->constrained('product_categories');
            $table->foreignId('brand_id')->constrained('brands');
            $table->string('slug', 255)->unique();
            $table->string('model_name', 200);
            $table->string('full_name', 300);
            $table->date('release_date')->nullable();
            $table->decimal('launch_msrp_usd', 10, 2)->nullable();
            $table->string('thumbnail_url', 500)->nullable();
            $table->json('gallery_images')->nullable();
            $table->decimal('overall_score', 4, 1)->default(0.0);
            $table->decimal('gaming_score', 4, 1)->default(0.0);
            $table->decimal('productivity_score', 4, 1)->default(0.0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('specs');
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'brand_id']);
            $table->index(['overall_score', 'gaming_score']);
            $table->index('release_date');
        });

        Schema::create('product_benchmarks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('benchmark_type', 100); // cinebench_r23_multi, 3dmark_timespy, geekbench_6, etc.
            $table->decimal('score_value', 12, 2);
            $table->string('test_unit', 30)->default('pts');
            $table->string('test_conditions', 255)->nullable();
            $table->date('tested_at')->nullable();
            $table->timestamps();

            $table->index(['benchmark_type', 'score_value']);
        });

        Schema::create('post_product', function (Blueprint $table): void {
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            $table->primary(['post_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_product');
        Schema::dropIfExists('product_benchmarks');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('brands');
    }
};
