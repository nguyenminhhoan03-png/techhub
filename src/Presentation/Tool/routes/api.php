<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Presentation\Tool\Controllers\ToolController;

Route::prefix('tools')->controller(ToolController::class)->group(function (): void {
    Route::get('categories', 'indexCategories');
    Route::get('/', 'index');
    Route::get('{slug}', 'show');
    Route::post('{slug}/execute', 'execute')->middleware('throttle:api');
});
