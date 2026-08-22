<?php

declare(strict_types=1);

use Application\Game\Commands\ImportGamesCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::registerCommand(app(ImportGamesCommand::class));
