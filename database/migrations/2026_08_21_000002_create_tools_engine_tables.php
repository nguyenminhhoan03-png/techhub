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
        Schema::create('tool_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('tools', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained('tool_categories')->cascadeOnDelete();
            $table->string('slug', 150)->unique();
            $table->string('name', 200);
            $table->string('summary', 300);
            $table->longText('description_markdown')->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('engine_type', 50); // client_browser, server_sync, server_async_queue, ai_api
            $table->boolean('is_premium_only')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('execution_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(5.00);
            $table->unsignedInteger('rating_count')->default(0);
            $table->json('config_schema')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_active']);
            $table->index('execution_count');
        });

        Schema::create('tool_executions', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tool_id')->constrained('tools')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45);
            $table->string('status', 30)->default('pending'); // pending, processing, completed, failed
            $table->unsignedInteger('execution_time_ms')->nullable();
            $table->unsignedInteger('input_size_bytes')->nullable();
            $table->unsignedInteger('output_size_bytes')->nullable();
            $table->string('storage_disk', 50)->nullable();
            $table->string('result_file_path', 500)->nullable();
            $table->text('error_message')->nullable();
            $table->json('input_meta')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_executions');
        Schema::dropIfExists('tools');
        Schema::dropIfExists('tool_categories');
    }
};
