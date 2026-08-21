<?php

declare(strict_types=1);

namespace Presentation\Admin\Controllers;

use Domain\Ad\Entities\Advertisement;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Presentation\Controller;

class AdminAdController extends Controller
{
    public function index(): View
    {
        $ads = Advertisement::query()->orderBy('slot')->orderByDesc('id')->get();

        return view('admin.ads.index', [
            'ads' => $ads,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slot' => ['required', 'string', 'in:header_top,sidebar_right,tool_workspace_bottom,footer_banner,in_content'],
            'type' => ['required', 'string', 'in:custom_banner,adsense_html,affiliate'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'target_url' => ['nullable', 'url', 'max:500'],
            'raw_html' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        Advertisement::query()->create([
            'name' => $validated['name'],
            'slot' => $validated['slot'],
            'type' => $validated['type'],
            'image_url' => $validated['image_url'] ?? null,
            'target_url' => $validated['target_url'] ?? null,
            'raw_html' => $validated['raw_html'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'Đã tạo quảng cáo mới thành công.');
    }

    public function update(int $id, Request $request): RedirectResponse
    {
        /** @var Advertisement $ad */
        $ad = Advertisement::query()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slot' => ['required', 'string', 'in:header_top,sidebar_right,tool_workspace_bottom,footer_banner,in_content'],
            'type' => ['required', 'string', 'in:custom_banner,adsense_html,affiliate'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'target_url' => ['nullable', 'url', 'max:500'],
            'raw_html' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $ad->update($validated);

        return redirect()->route('admin.ads.index')->with('success', "Đã cập nhật quảng cáo [{$ad->name}].");
    }

    public function toggle(int $id): RedirectResponse
    {
        /** @var Advertisement $ad */
        $ad = Advertisement::query()->findOrFail($id);
        $ad->is_active = ! $ad->is_active;
        $ad->save();

        $status = $ad->is_active ? 'kích hoạt' : 'tạm dừng';

        return back()->with('success', "Đã {$status} quảng cáo [{$ad->name}].");
    }

    public function destroy(int $id): RedirectResponse
    {
        /** @var Advertisement $ad */
        $ad = Advertisement::query()->findOrFail($id);
        $ad->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Đã xóa quảng cáo thành công.');
    }
}
