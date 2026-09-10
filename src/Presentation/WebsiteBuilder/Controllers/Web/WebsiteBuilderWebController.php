<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Controllers\Web;

use Application\WebsiteBuilder\Services\Compiler\StaticSiteCompilerService;
use Domain\WebsiteBuilder\Entities\Page;
use Domain\WebsiteBuilder\Entities\Website;
use Domain\WebsiteBuilder\Enums\WebsiteStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Presentation\Controller;

class WebsiteBuilderWebController extends Controller
{
    /**
     * Dashboard quản lý danh sách website của người dùng hiện tại.
     */
    public function index(): View
    {
        $websites = Website::with(['pages', 'publishedSites', 'settings', 'seo', 'domains'])
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->get();

        return view('pages.builder.index', compact('websites'));
    }

    /**
     * Mở Visual Editor để chỉnh sửa trang trực quan (chỉ chủ sở hữu).
     */
    public function editor(int $pageId): View
    {
        $page = Page::with('website.pages')->findOrFail($pageId);

        if ($page->website->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập hoặc chỉnh sửa website này.');
        }

        return view('pages.builder.editor', compact('page'));
    }

    /**
     * Xem trước (Live Preview) trang web đã được xuất bản tĩnh hoặc bản nháp (hỗ trợ chuyển trang theo slug).
     */
    public function preview(int $websiteId, StaticSiteCompilerService $compiler, Request $request, ?string $slug = null): Response
    {
        $website = Website::with(['pages', 'publishedSites', 'settings', 'seo'])->findOrFail($websiteId);

        $statusValue = $website->status instanceof WebsiteStatus ? $website->status->value : (string) $website->status;

        // Nếu website ở trạng thái draft, chỉ chủ sở hữu mới xem trước được
        if ('published' !== $statusValue && (! Auth::check() || $website->user_id !== Auth::id())) {
            abort(403, 'Website này chưa được xuất bản hoặc bạn không có quyền xem trước.');
        }

        $targetPage = null;
        if ($slug) {
            $cleanSlug = ltrim($slug, '/');
            $targetPage = $website->pages->first(fn ($p) => ltrim((string) $p->slug, '/') === $cleanSlug);
        } elseif ($request->has('page')) {
            $cleanSlug = ltrim((string) $request->query('page'), '/');
            $targetPage = $website->pages->first(fn ($p) => ltrim((string) $p->slug, '/') === $cleanSlug);
        }

        if (! $targetPage) {
            $targetPage = $website->pages->firstWhere('is_home', true) ?: $website->pages->first();
        }

        if (! $targetPage) {
            abort(404, 'Website chưa có trang nào.');
        }

        // Biên dịch HTML từ phiên bản mới nhất
        $compiled = $compiler->compilePage($website, $targetPage, $targetPage->draft_content);
        $html = $compiled['html'];

        // Trong chế độ xem trước (Preview mode), tự động chuyển các relative link /slug thành /builder/preview/{websiteId}/slug
        $html = preg_replace_callback('/href="\/([a-zA-Z0-9_\-]+)"/', function ($m) use ($websiteId) {
            return 'href="/builder/preview/' . $websiteId . '/' . $m[1] . '"';
        }, $html);

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }
}
