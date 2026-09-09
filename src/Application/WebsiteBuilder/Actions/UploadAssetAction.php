<?php

declare(strict_types=1);

namespace Application\WebsiteBuilder\Actions;

use Application\WebsiteBuilder\Services\Storage\WebsiteStorageService;
use Domain\WebsiteBuilder\Entities\Asset;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class UploadAssetAction
{
    public function __construct(
        protected WebsiteStorageService $storage,
    ) {}

    /**
     * Xác thực, khử độc (SVG Sanitization) và tải asset lên S3.
     */
    public function execute(int $userId, ?int $websiteId, UploadedFile $file): Asset
    {
        $mimeType = $file->getMimeType() ?: 'application/octet-stream';
        $originalName = $file->getClientOriginalName();
        $fileSize = $file->getSize();

        // 1. Kiểm tra định dạng cho phép
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/webp', 'image/gif',
            'image/svg+xml', 'image/avif', 'video/mp4', 'application/pdf',
        ];

        if ( ! in_array($mimeType, $allowedMimes, true)) {
            throw ValidationException::withMessages([
                'file' => 'Định dạng file không được hỗ trợ. Chỉ chấp nhận ảnh (JPG, PNG, WebP, SVG, AVIF) hoặc video MP4.',
            ]);
        }

        // 2. Bảo mật chống Stored XSS đối với file SVG
        if ('image/svg+xml' === $mimeType || str_ends_with(mb_strtolower($originalName), '.svg')) {
            $this->sanitizeSvg($file->getRealPath());
        }

        // 3. Trích xuất kích thước ảnh nếu là hình ảnh bitmap
        $dimensions = null;
        if (str_starts_with($mimeType, 'image/') && 'image/svg+xml' !== $mimeType) {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo) {
                $dimensions = [
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1],
                ];
            }
        }

        // 4. Upload lên S3 Storage
        $uploadResult = $this->storage->uploadAsset($userId, $file);

        // 5. Lưu thông tin vào Database
        return Asset::create([
            'user_id' => $userId,
            'website_id' => $websiteId,
            'file_name' => $uploadResult['file_name'],
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'storage_path' => $uploadResult['storage_path'],
            'variants' => [
                'original' => $uploadResult['url'],
            ],
            'dimensions' => $dimensions,
        ]);
    }

    /**
     * Làm sạch mã độc nhúng trong SVG (loại bỏ script, event handlers, onload).
     */
    private function sanitizeSvg(string $filePath): void
    {
        $content = file_get_contents($filePath);
        if (false === $content) {
            return;
        }

        // Kiểm tra payload XXE
        if (false !== mb_stripos($content, '<!ENTITY') || false !== mb_stripos($content, '<!DOCTYPE')) {
            $content = preg_replace('/<!DOCTYPE[^>]*>/i', '', $content) ?? $content;
            $content = preg_replace('/<!ENTITY[^>]*>/i', '', $content) ?? $content;
        }

        // Loại bỏ <script> và các thuộc tính javascript on* (onload, onclick...)
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content) ?? $content;
        $content = preg_replace('/(?:\s+)(on\w+)\s*=\s*(["\'][^"\']*["\']|[^\s>]+)/i', '', $content) ?? $content;
        $content = preg_replace('/href\s*=\s*["\']\s*javascript:[^"\']*["\']/i', 'href="#"', $content) ?? $content;

        file_put_contents($filePath, $content);
    }
}
