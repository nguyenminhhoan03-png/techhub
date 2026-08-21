<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Presentation\Tool\Controllers\ToolWebController;

Route::controller(ToolWebController::class)->group(function (): void {
    Route::get('/lang/{locale}', 'setLocale')->name('lang.switch');
    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
    Route::get('/', 'home')->name('home');
    Route::get('/tools', 'index')->name('tools.index');
    Route::get('/tools/{slug}', 'show')->name('tools.show');
});

// Articles, Reviews & Hardware Comparisons
Route::controller(\Presentation\Http\Controllers\Web\ArticleController::class)->group(function (): void {
    Route::get('/articles', 'index')->name('articles.index');
    Route::get('/articles/{slug}', 'show')->name('articles.show');
    Route::get('/compare', function () {
        return redirect()->route('articles.index', ['type' => 'comparison']);
    })->name('compare.index');
    Route::get('/reviews', function () {
        return redirect()->route('articles.index', ['type' => 'review']);
    })->name('reviews.index');
});
