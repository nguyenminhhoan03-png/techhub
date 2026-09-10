<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Presentation\WebsiteBuilder\Controllers\Web\WebsiteBuilderWebController;

Route::prefix('builder')->name('builder.')->group(function (): void {
    // Authenticated user builder routes
    Route::middleware('auth')->group(function (): void {
        Route::get('/', [WebsiteBuilderWebController::class, 'index'])->name('index');
        Route::get('/editor/{pageId}', [WebsiteBuilderWebController::class, 'editor'])->name('editor');
    });

    // Preview route (handles draft/published access control inside controller)
    Route::get('/preview/{websiteId}/{slug?}', [WebsiteBuilderWebController::class, 'preview'])->name('preview');
});
