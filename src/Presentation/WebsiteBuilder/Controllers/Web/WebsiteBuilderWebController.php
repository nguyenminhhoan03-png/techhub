<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Controllers\Web;

use Application\WebsiteBuilder\Services\Compiler\StaticSiteCompilerService;
use Domain\WebsiteBuilder\Entities\Page;
use Domain\WebsiteBuilder\Entities\Website;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Presentation\Controller;

class WebsiteBuilderWebController extends Controller
{
    /**
     * Dashboard quản lý danh sách website của người dùng.
     */
    public function index(): View
    {
        $websites = Website::with(['pages', 'publishedSites', 'settings', 'seo', 'domains'])
            ->orderByDesc('id')
            ->get();

        return view('pages.builder.index', compact('websites'));
    }

    /**
     * Mở Visual Editor để chỉnh sửa trang trực quan.
     */
    public function editor(int $pageId): View
    {
        $page = Page::with('website.pages')->findOrFail($pageId);

        return view('pages.builder.editor', compact('page'));
    }

    /**
     * Xem trước (Live Preview) trang web đã được xuất bản tĩnh.
     */
    public function preview(int $websiteId, StaticSiteCompilerService $compiler): Response
    {
        $website = Website::with(['pages', 'publishedSites', 'settings', 'seo'])->findOrFail($websiteId);
        $homePage = $website->pages->firstWhere('is_home', true) ?: $website->pages->first();

        if (! $homePage) {
            abort(404, 'Website chưa có trang nào.');
        }

        // Biên dịch HTML từ phiên bản mới nhất
        $compiled = $compiler->compilePage($website, $homePage, $homePage->draft_content);

        return response($compiled['html'], 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }
}
