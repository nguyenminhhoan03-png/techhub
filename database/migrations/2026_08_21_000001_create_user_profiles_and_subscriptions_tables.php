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
        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('theme', 20)->default('dark');
            $table->string('locale', 10)->default('vi');
            $table->string('timezone', 50)->default('Asia/Ho_Chi_Minh');
            $table->json('preferences')->nullable();
            $table->text('bio')->nullable();
            $table->string('github_url', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('subscription_plans', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0.00);
            $table->decimal('price_yearly', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('USD');
            $table->unsignedInteger('daily_tool_limit')->default(50);
            $table->unsignedInteger('max_file_upload_mb')->default(10);
            $table->unsignedInteger('ai_credits_monthly')->default(100);
            $table->boolean('has_api_access')->default(false);
            $table->json('features');
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('user_subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans');
            $table->string('payment_provider', 50);
            $table->string('provider_subscription_id', 191)->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('api_keys', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('key_hash', 64)->unique();
            $table->string('key_prefix', 10);
            $table->unsignedInteger('rate_limit_per_minute')->default(60);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['key_hash', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('user_profiles');
    }
};
