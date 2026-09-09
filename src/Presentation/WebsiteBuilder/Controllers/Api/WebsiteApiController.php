<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Controllers\Api;

use Application\WebsiteBuilder\Actions\CreateWebsiteAction;
use Domain\WebsiteBuilder\Entities\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Presentation\Controller;
use Presentation\WebsiteBuilder\Requests\CreateWebsiteRequest;

class WebsiteApiController extends Controller
{
    /**
     * Lấy danh sách website của người dùng.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;

        $websites = Website::with(['settings', 'seo'])
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $websites->items(),
            'meta' => [
                'current_page' => $websites->currentPage(),
                'total' => $websites->total(),
                'per_page' => $websites->perPage(),
            ],
        ]);
    }

    /**
     * Tạo website mới với trang chủ và starter AST mặc định.
     */
    public function store(CreateWebsiteRequest $request, CreateWebsiteAction $action): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        $name = (string) $request->validated('name');
        $subdomain = $request->validated('subdomain');

        $website = $action->execute(
            userId: $userId,
            name: $name,
            subdomain: $subdomain ? (string) $subdomain : null,
        );

        return response()->json([
            'success' => true,
            'message' => 'Tạo website thành công!',
            'data' => $website,
        ], 201);
    }

    /**
     * Chi tiết website kèm các trang con, settings và SEO.
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;

        $website = Website::with(['pages', 'settings', 'seo', 'domains'])
            ->where('user_id', $userId)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $website,
        ]);
    }
}
