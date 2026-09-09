<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Controllers\Api;

use Application\WebsiteBuilder\Actions\UploadAssetAction;
use Domain\WebsiteBuilder\Entities\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Presentation\Controller;
use Presentation\WebsiteBuilder\Requests\UploadAssetRequest;

class AssetApiController extends Controller
{
    /**
     * Lấy danh sách thư viện media assets của người dùng hoặc theo website.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        $websiteId = $request->query('website_id');

        $query = Asset::where('user_id', $userId)->orderByDesc('id');

        if ($websiteId) {
            $query->where('website_id', (int) $websiteId);
        }

        $assets = $query->paginate(24);

        return response()->json([
            'success' => true,
            'data' => $assets->items(),
            'meta' => [
                'current_page' => $assets->currentPage(),
                'total' => $assets->total(),
            ],
        ]);
    }

    /**
     * Tải media asset lên S3 kèm SVG Sanitization chống XSS.
     */
    public function upload(UploadAssetRequest $request, UploadAssetAction $action): JsonResponse
    {
        $userId = $request->user()?->id ?? 1;
        $websiteId = $request->validated('website_id') ? (int) $request->validated('website_id') : null;
        $file = $request->file('file');

        $asset = $action->execute(
            userId: $userId,
            websiteId: $websiteId,
            file: $file,
        );

        return response()->json([
            'success' => true,
            'message' => 'Tải lên asset thành công!',
            'data' => $asset,
        ], 201);
    }
}
