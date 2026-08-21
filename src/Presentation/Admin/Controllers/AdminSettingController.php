<?php

declare(strict_types=1);

namespace Presentation\Admin\Controllers;

use Application\Setting\Services\SettingService;
use Domain\Setting\Entities\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Presentation\Controller;

class AdminSettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::query()->orderBy('group')->orderBy('id')->get()->groupBy('group');

        return view('admin.settings.index', [
            'settingsGrouped' => $settings,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            Setting::query()->where('key', $key)->update([
                'value' => is_array($value) ? json_encode($value) : (string) $value,
            ]);
        }

        // Clear settings cache immediately
        SettingService::clearCache();

        return redirect()->route('admin.settings.index')->with('success', 'Đã lưu toàn bộ cấu hình hệ thống thành công.');
    }
}
