<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Controllers\Api;

use Application\WebsiteBuilder\Actions\PublishWebsiteAction;
use Domain\WebsiteBuilder\Entities\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Presentation\Controller;

class PublishApiController extends Controller
{
    /**
     * Xuất bản toàn bộ website thành static files SSG lên S3 và cấu hình CDN (chỉ chủ sở hữu).
     */
    public function publish(int $id, Request $request, PublishWebsiteAction $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $website = Website::where('user_id', $userId)->findOrFail($id);

        $publishedSite = $action->execute($website, $userId);

        return response()->json([
            'success' => true,
            'message' => 'Website đã được xuất bản tĩnh thành công!',
            'data' => [
                'website_id' => $website->id,
                'release_ulid' => $publishedSite->ulid,
                'version_tag' => $publishedSite->version_tag,
                'status' => $publishedSite->status->value,
                'live_url' => "https://{$website->subdomain}.muabanwebsite.io.vn",
                'manifest' => $publishedSite->manifest_json,
                'published_at' => now()->toIso8601String(),
            ],
        ], 200);
    }
}
