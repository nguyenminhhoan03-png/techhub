<?php

declare(strict_types=1);

namespace Application\WebsiteBuilder\Actions;

use Application\WebsiteBuilder\Exceptions\OptimisticLockConflictException;
use Domain\WebsiteBuilder\Entities\Page;
use Illuminate\Support\Facades\DB;

class AutosavePageAction
{
    /**
     * Lưu bản thảo trang với khóa lạc quan (Optimistic Locking) chống ghi đè dữ liệu.
     *
     * @param Page $page
     * @param array $newContent JSON AST mới từ editor
     * @param int $clientBaseVersion Version number mà client đang giữ khi bắt đầu sửa
     * @return Page
     * @throws OptimisticLockConflictException
     */
    public function execute(Page $page, array $newContent, int $clientBaseVersion): Page
    {
        $affected = DB::table('pages')
            ->where('id', $page->id)
            ->where('version_number', $clientBaseVersion)
            ->update([
                'draft_content' => json_encode($newContent, JSON_UNESCAPED_UNICODE),
                'version_number' => $clientBaseVersion + 1,
                'updated_at' => now(),
            ]);

        if (0 === $affected) {
            // Đã bị một tab/session khác lưu trước đó
            $latest = Page::findOrFail($page->id);
            throw new OptimisticLockConflictException(
                currentVersionNumber: $latest->version_number,
                latestContent: $latest->draft_content,
            );
        }

        $page->refresh();
        return $page;
    }
}
