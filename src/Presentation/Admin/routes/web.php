<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Presentation\Admin\Controllers\AdminAdController;
use Presentation\Admin\Controllers\AdminAuthController;
use Presentation\Admin\Controllers\AdminDashboardController;
use Presentation\Admin\Controllers\AdminSettingController;
use Presentation\Admin\Controllers\AdminToolController;
use Presentation\Admin\Controllers\AdminUserController;
use Presentation\Admin\Middleware\AdminMiddleware;

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Panel Routes
    Route::middleware([AdminMiddleware::class])->group(function (): void {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Users Management
        Route::prefix('users')->name('users.')->group(function (): void {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::post('/', [AdminUserController::class, 'store'])->name('store');
            Route::put('/{id}', [AdminUserController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('destroy');
        });

        // Tools Management
        Route::prefix('tools')->name('tools.')->group(function (): void {
            Route::get('/', [AdminToolController::class, 'index'])->name('index');
            Route::post('/{id}/toggle', [AdminToolController::class, 'toggle'])->name('toggle');
            Route::put('/{id}', [AdminToolController::class, 'update'])->name('update');
        });

        // Advertisements Management
        Route::prefix('ads')->name('ads.')->group(function (): void {
            Route::get('/', [AdminAdController::class, 'index'])->name('index');
            Route::post('/', [AdminAdController::class, 'store'])->name('store');
            Route::put('/{id}', [AdminAdController::class, 'update'])->name('update');
            Route::post('/{id}/toggle', [AdminAdController::class, 'toggle'])->name('toggle');
            Route::delete('/{id}', [AdminAdController::class, 'destroy'])->name('destroy');
        });

        // Dynamic System Settings
        Route::prefix('settings')->name('settings.')->group(function (): void {
            Route::get('/', [AdminSettingController::class, 'index'])->name('index');
            Route::post('/', [AdminSettingController::class, 'update'])->name('update');
        });

        // Articles & Content Management
        Route::prefix('articles')->name('articles.')->group(function (): void {
            Route::get('/', [\Presentation\Http\Controllers\Admin\AdminArticleController::class, 'index'])->name('index');
            Route::get('/create', [\Presentation\Http\Controllers\Admin\AdminArticleController::class, 'create'])->name('create');
            Route::post('/', [\Presentation\Http\Controllers\Admin\AdminArticleController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\Presentation\Http\Controllers\Admin\AdminArticleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\Presentation\Http\Controllers\Admin\AdminArticleController::class, 'update'])->name('update');
            Route::post('/{id}/toggle', [\Presentation\Http\Controllers\Admin\AdminArticleController::class, 'toggle'])->name('toggle');
            Route::delete('/{id}', [\Presentation\Http\Controllers\Admin\AdminArticleController::class, 'destroy'])->name('destroy');
        });

        // Hardware Specs Database Management
        Route::prefix('hardware')->name('hardware.')->group(function (): void {
            Route::get('/', [\Presentation\Http\Controllers\Admin\AdminHardwareController::class, 'index'])->name('index');
            Route::get('/create', [\Presentation\Http\Controllers\Admin\AdminHardwareController::class, 'create'])->name('create');
            Route::post('/', [\Presentation\Http\Controllers\Admin\AdminHardwareController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\Presentation\Http\Controllers\Admin\AdminHardwareController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\Presentation\Http\Controllers\Admin\AdminHardwareController::class, 'update'])->name('update');
            Route::delete('/{id}', [\Presentation\Http\Controllers\Admin\AdminHardwareController::class, 'destroy'])->name('destroy');
        });

        // AI Auto-Writer & Crawler Studio
        Route::prefix('ai-studio')->name('ai_studio.')->group(function (): void {
            Route::get('/', [\Presentation\Http\Controllers\Admin\AdminAiCrawlerController::class, 'index'])->name('index');
            Route::post('/generate-specs', [\Presentation\Http\Controllers\Admin\AdminAiCrawlerController::class, 'generateFromSpecs'])->name('generate_specs');
            Route::post('/crawl-rewrite', [\Presentation\Http\Controllers\Admin\AdminAiCrawlerController::class, 'crawlAndRewrite'])->name('crawl_rewrite');
            Route::post('/save-article', [\Presentation\Http\Controllers\Admin\AdminAiCrawlerController::class, 'saveArticle'])->name('save_article');
        });
    });
});
