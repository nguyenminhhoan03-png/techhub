<?php

declare(strict_types=1);

namespace Presentation\Admin\Controllers;

use Domain\Ad\Entities\Advertisement;
use Domain\Tool\Entities\Tool;
use Domain\Tool\Entities\ToolExecution;
use Domain\User\Entities\User;
use Illuminate\Contracts\View\View;
use Presentation\Controller;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::query()->count();
        $totalTools = Tool::query()->count();
        $activeTools = Tool::query()->where('is_active', true)->count();
        $totalExecutions = Tool::query()->sum('execution_count');
        $activeAds = Advertisement::query()->where('is_active', true)->count();
        $totalAdClicks = Advertisement::query()->sum('clicks_count');

        $recentExecutions = ToolExecution::query()
            ->with('tool')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $topTools = Tool::query()
            ->with('category')
            ->orderByDesc('execution_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', [
            'totalUsers' => $totalUsers,
            'totalTools' => $totalTools,
            'activeTools' => $activeTools,
            'totalExecutions' => $totalExecutions,
            'activeAds' => $activeAds,
            'totalAdClicks' => $totalAdClicks,
            'recentExecutions' => $recentExecutions,
            'topTools' => $topTools,
        ]);
    }
}
