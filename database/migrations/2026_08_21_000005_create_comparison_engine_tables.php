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
        Schema::create('comparisons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories');
            $table->string('slug', 255)->unique(); // intel-core-i9-14900k-vs-amd-ryzen-9-7950x3d
            $table->string('title', 300);
            $table->text('summary_markdown')->nullable();
            $table->foreignId('winner_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();

            $table->index(['category_id', 'view_count']);
        });

        Schema::create('comparison_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('comparison_id')->constrained('comparisons')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->boolean('is_winner')->default(false);
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();
            $table->timestamps();

            $table->unique(['comparison_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparison_items');
        Schema::dropIfExists('comparisons');
    }
};
