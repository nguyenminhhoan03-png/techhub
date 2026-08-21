<?php

declare(strict_types=1);

namespace Presentation\Admin\Controllers;

use Domain\Tool\Entities\Tool;
use Domain\Tool\Entities\ToolCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Presentation\Controller;

class AdminToolController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $query = Tool::query()->with('category')->orderBy('category_id')->orderBy('id');

        if ($search && is_string($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $tools = $query->get();
        $categories = ToolCategory::query()->orderBy('sort_order')->get();

        return view('admin.tools.index', [
            'tools' => $tools,
            'categories' => $categories,
        ]);
    }

    public function toggle(int $id): RedirectResponse
    {
        /** @var Tool $tool */
        $tool = Tool::query()->findOrFail($id);
        $tool->is_active = ! $tool->is_active;
        $tool->save();

        $status = $tool->is_active ? 'kích hoạt' : 'tạm dừng';

        return back()->with('success', "Đã {$status} công cụ [{$tool->name}].");
    }

    public function update(int $id, Request $request): RedirectResponse
    {
        /** @var Tool $tool */
        $tool = Tool::query()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'category_id' => ['required', 'exists:tool_categories,id'],
            'is_premium_only' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $tool->update($validated);

        return back()->with('success', "Đã cập nhật công cụ [{$tool->name}].");
    }
}
