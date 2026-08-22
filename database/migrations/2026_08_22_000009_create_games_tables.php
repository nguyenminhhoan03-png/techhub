<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('game_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 150);
            $table->string('description', 400)->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('color', 30)->default('#4f46e5'); // hex or CSS var
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained('game_categories')->cascadeOnDelete();
            $table->string('slug', 150)->unique();
            $table->string('name', 200);
            $table->string('summary', 350);
            $table->text('description_markdown')->nullable();
            $table->string('thumbnail_url', 500)->nullable();
            $table->string('engine_path', 300); // e.g. /games/2048/index.html
            $table->string('difficulty', 20)->default('easy'); // easy, medium, hard
            $table->string('controls_hint', 300)->nullable(); // "Arrow Keys / WASD"
            $table->unsignedBigInteger('play_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_active']);
            $table->index('is_featured');
            $table->index('play_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
        Schema::dropIfExists('game_categories');
    }
};
