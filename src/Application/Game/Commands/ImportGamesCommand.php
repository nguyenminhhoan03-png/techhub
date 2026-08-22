<?php

declare(strict_types=1);

namespace Application\Game\Commands;

use Application\Game\Services\GameFeedImportService;
use Illuminate\Console\Command;

final class ImportGamesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:import 
                            {--amount=30 : Số lượng game muốn import (5 - 100)} 
                            {--category= : Lọc theo danh mục cụ thể (Action, Puzzle, Racing...)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động import và đồng bộ game HTML5 từ Game Feed API vào TechHub Portal';

    public function handle(GameFeedImportService $importService): int
    {
        $amount   = (int) $this->option('amount');
        $category = $this->option('category') ? (string) $this->option('category') : null;

        $this->info("🚀 Đang kết nối tới Game Feed API để import {$amount} games...");

        $result = $importService->importGames($amount, $category);

        if (! $result['success']) {
            $this->error('❌ ' . $result['message']);
            return Command::FAILURE;
        }

        $this->info('✅ ' . $result['message']);
        $this->table(
            ['Trạng Thái', 'Số Lượng'],
            [
                ['Game Mới Thêm Vào', $result['imported_count']],
                ['Game Đã Cập Nhật', $result['updated_count']],
            ]
        );

        return Command::SUCCESS;
    }
}
