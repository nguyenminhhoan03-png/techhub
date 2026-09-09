<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Presentation\WebsiteBuilder\Controllers\Api\AssetApiController;
use Presentation\WebsiteBuilder\Controllers\Api\PageApiController;
use Presentation\WebsiteBuilder\Controllers\Api\PublishApiController;
use Presentation\WebsiteBuilder\Controllers\Api\WebsiteApiController;

Route::prefix('builder')->group(function (): void {
    // 1. Quản lý Website
    Route::get('websites', [WebsiteApiController::class, 'index']);
    Route::post('websites', [WebsiteApiController::class, 'store']);
    Route::get('websites/{id}', [WebsiteApiController::class, 'show']);
    Route::post('websites/{id}/publish', [PublishApiController::class, 'publish']);

    // 2. Quản lý Trang & Visual Editor Autosave
    Route::get('pages/{id}', [PageApiController::class, 'show']);
    Route::post('pages', [PageApiController::class, 'store']);
    Route::post('pages/{id}/autosave', [PageApiController::class, 'autosave']);

    // 3. Quản lý Media Assets
    Route::get('assets', [AssetApiController::class, 'index']);
    Route::post('assets/upload', [AssetApiController::class, 'upload']);
});
