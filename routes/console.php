<?php

declare(strict_types=1);

use Application\Game\Commands\ImportGamesCommand;
use Application\Seo\Commands\SubmitIndexNowCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// ── Register Application Commands (Senior Clean Architecture) ───────────────
Artisan::registerCommand(app(ImportGamesCommand::class));
Artisan::registerCommand(app(SubmitIndexNowCommand::class));
Artisan::registerCommand(app(\Application\Seo\Commands\SeoIndexUrlsCommand::class));

// ── Daily Automated SEO Indexing Schedule ──────────────────────────────────
Schedule::command('seo:indexnow')->dailyAt('03:00')->withoutOverlapping();
