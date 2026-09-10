<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Controllers\Api;

use Application\WebsiteBuilder\Actions\AutosavePageAction;
use Application\WebsiteBuilder\Exceptions\OptimisticLockConflictException;
use Domain\WebsiteBuilder\Entities\Page;
use Domain\WebsiteBuilder\Entities\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Presentation\Controller;
use Presentation\WebsiteBuilder\Requests\AutosavePageRequest;

class PageApiController extends Controller
{
    /**
     * Tải dữ liệu trang để khởi tạo Canvas trong Visual Editor (chỉ chủ sở hữu).
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $page = Page::with('website:id,user_id,name,subdomain,status')->findOrFail($id);

        if ($page->website->user_id !== (int) $request->user()->id) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

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
     * Autosave nội dung canvas kèm kiểm tra xung đột Optimistic Lock (chỉ chủ sở hữu).
     */
    public function autosave(int $id, AutosavePageRequest $request, AutosavePageAction $action): JsonResponse
    {
        $page = Page::with('website:id,user_id')->findOrFail($id);

        if ($page->website->user_id !== (int) $request->user()->id) {
            abort(403, 'Bạn không có quyền chỉnh sửa trang này.');
        }

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
     * Tạo trang mới cho website từ Visual Editor (chỉ chủ sở hữu website).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'title' => 'required|string|max:100',
            'slug' => 'required|string|max:100',
        ]);

        // Đảm bảo website thuộc về người dùng đang đăng nhập
        $website = Website::where('user_id', (int) $request->user()->id)->findOrFail((int) $validated['website_id']);

        $slug = \Illuminate\Support\Str::slug($validated['slug']);

        $page = Page::create([
            'ulid' => (string) \Illuminate\Support\Str::ulid(),
            'website_id' => $website->id,
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
