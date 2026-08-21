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
        Schema::create('stores', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 150);
            $table->string('domain', 150);
            $table->string('logo_url', 500)->nullable();
            $table->string('affiliate_network', 50)->nullable();
            $table->string('affiliate_tag_param', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('deals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->decimal('current_price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('discount_percentage')->default(0);
            $table->string('currency', 10)->default('USD');
            $table->string('coupon_code', 50)->nullable();
            $table->string('product_store_url', 1000);
            $table->string('affiliate_url', 1000);
            $table->boolean('in_stock')->default(true);
            $table->boolean('is_hot_deal')->default(false);
            $table->timestamp('last_scraped_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'current_price']);
            $table->index(['is_hot_deal', 'discount_percentage']);
        });

        Schema::create('price_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->timestamp('recorded_at');

            $table->index(['product_id', 'recorded_at']);
        });

        Schema::create('price_alerts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('target_price', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->boolean('is_triggered')->default(false);
            $table->timestamp('triggered_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'is_triggered', 'target_price']);
        });

        Schema::create('affiliate_clicks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('deal_id')->constrained('deals')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->string('referrer_url', 1000)->nullable();
            $table->timestamp('clicked_at');

            $table->index(['store_id', 'clicked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_clicks');
        Schema::dropIfExists('price_alerts');
        Schema::dropIfExists('price_histories');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('stores');
    }
};
