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
        Schema::create('digital_deals', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 150)->unique();
            $table->string('name', 255);
            $table->string('category', 80)->default('Google AI');
            $table->string('badge_text', 50)->nullable()->default('KHÔNG TRÙNG');
            $table->string('sub_badge', 50)->nullable()->default('GOOGLE');
            $table->json('tags')->nullable();
            $table->decimal('price', 14, 0)->default(50000);
            $table->decimal('original_price', 14, 0)->nullable()->default(500000);
            $table->integer('discount_percentage')->default(88);
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->integer('rating_count')->default(0);
            $table->integer('sold_count')->default(0);
            $table->string('stock_status', 40)->default('in_stock');
            $table->string('thumbnail_url', 600)->nullable();
            $table->json('variants')->nullable();
            $table->json('commitments')->nullable();
            $table->text('summary')->nullable();
            $table->longText('description_markdown')->nullable();
            $table->string('zalo_contact', 50)->nullable()->default('0866655803');
            $table->string('telegram_contact', 100)->nullable()->default('https://t.me/hoannm');
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'is_active', 'sort_order']);
            $table->index(['is_featured', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_deals');
    }
};
