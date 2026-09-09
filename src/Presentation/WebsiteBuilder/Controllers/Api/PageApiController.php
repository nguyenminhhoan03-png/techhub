<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Controllers\Api;

use Application\WebsiteBuilder\Actions\AutosavePageAction;
use Application\WebsiteBuilder\Exceptions\OptimisticLockConflictException;
use Domain\WebsiteBuilder\Entities\Page;
use Illuminate\Http\JsonResponse;
use Presentation\Controller;
use Presentation\WebsiteBuilder\Requests\AutosavePageRequest;

class PageApiController extends Controller
{
    /**
     * Tải dữ liệu trang để khởi tạo Canvas trong Visual Editor.
     */
    public function show(int $id): JsonResponse
    {
        $page = Page::with('website:id,name,subdomain,status')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $page->id,
                'ulid' => $page->ulid,
                'title' => $page->title,
                'slug' => $page->slug,
                'is_home' => $page->is_home,
                'draft_content' => $page->draft_content,
                'version_number' => $page->version_number,
                'status' => $page->status->value,
                'website' => $page->website,
                'updated_at' => $page->updated_at?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Autosave nội dung canvas kèm kiểm tra xung đột Optimistic Lock.
     */
    public function autosave(int $id, AutosavePageRequest $request, AutosavePageAction $action): JsonResponse
    {
        $page = Page::findOrFail($id);
        $newContent = (array) $request->validated('content_json');
        $baseVersion = (int) $request->validated('base_version');

        try {
            $updatedPage = $action->execute($page, $newContent, $baseVersion);

            return response()->json([
                'success' => true,
                'message' => 'Lưu bản thảo tự động thành công!',
                'data' => [
                    'page_id' => $updatedPage->id,
                    'new_version_number' => $updatedPage->version_number,
                    'updated_at' => $updatedPage->updated_at?->toIso8601String(),
                ],
            ]);
        } catch (OptimisticLockConflictException $e) {
            return response()->json([
                'success' => false,
                'error' => 'VERSION_CONFLICT',
                'message' => $e->getMessage(),
                'data' => [
                    'server_version_number' => $e->currentVersionNumber,
                    'latest_content' => $e->latestContent,
                ],
            ], 409);
        }
    }

    /**
     * Tạo trang mới cho website từ Visual Editor.
     */
    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'title' => 'required|string|max:100',
            'slug' => 'required|string|max:100',
        ]);

        $slug = \Illuminate\Support\Str::slug($validated['slug']);

        $page = Page::create([
            'ulid' => (string) \Illuminate\Support\Str::ulid(),
            'website_id' => (int) $validated['website_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'is_home' => false,
            'draft_content' => [
                'gjs_html' => '<section style="padding: 100px 20px; text-align: center;"><h1 style="font-size: 38px; font-weight: 800; color: #0f172a; margin-bottom: 16px;">' . e($validated['title']) . '</h1><p style="color: #64748b; font-size: 18px;">Bắt đầu thiết kế trang của bạn bằng cách kéo thả các khối bên trái vào đây.</p></section>',
                'gjs_css' => '',
            ],
            'version_number' => 1,
            'status' => \Domain\WebsiteBuilder\Enums\PageStatus::DRAFT,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo trang mới thành công!',
            'data' => $page,
        ], 201);
    }
}
