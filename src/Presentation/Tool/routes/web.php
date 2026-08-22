<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Presentation\Tool\Controllers\ToolWebController;

// IndexNow Key Verification Route (Fail-safe for all web servers)
Route::get('/{key}.txt', function (string $key) {
    if (preg_match('/^[a-f0-9]{32}$/i', $key)) {
        return response($key, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
    abort(404);
})->where('key', '^[a-f0-9]{32}$');

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

// Web Games
Route::controller(\Presentation\Http\Controllers\Web\GameController::class)->group(function (): void {
    Route::get('/games', 'index')->name('games.index');
    Route::get('/games/{slug}', 'show')->name('games.show');
    Route::post('/games/{slug}/play', 'play')->name('games.play');
});
