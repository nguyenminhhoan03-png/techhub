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
        Schema::create('system_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general')->index();
            $table->string('type')->default('text');
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('advertisements', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slot')->index(); // header_top, sidebar_right, tool_workspace_bottom, footer_banner
            $table->string('type')->default('custom_banner'); // custom_banner, adsense_html, affiliate
            $table->string('image_url')->nullable();
            $table->string('target_url')->nullable();
            $table->longText('raw_html')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('clicks_count')->default(0);
            $table->unsignedInteger('impressions_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
        Schema::dropIfExists('system_settings');
    }
};
