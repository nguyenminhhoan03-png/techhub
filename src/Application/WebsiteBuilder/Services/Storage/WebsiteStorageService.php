<?php

declare(strict_types=1);

namespace Application\WebsiteBuilder\Services\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class WebsiteStorageService
{
    private Filesystem $disk;
    private string $diskName;

    public function __construct()
    {
        // Kiểm tra nếu đã cấu hình AWS S3 credentials, ngược lại tự động fallback về disk 'public'
        $hasS3Config = ! empty(config('filesystems.disks.s3.key')) && ! empty(config('filesystems.disks.s3.bucket'));
        $this->diskName = $hasS3Config ? 's3' : 'public';
        $this->disk = Storage::disk($this->diskName);
    }

    /**
     * Lưu trữ một file tĩnh của đợt phát hành website (Release bundle).
     */
    public function putReleaseFile(string $releasePrefix, string $fileName, string $content, string $mimeType = 'text/html'): string
    {
        $path = rtrim($releasePrefix, '/') . '/' . ltrim($fileName, '/');

        $this->disk->put($path, $content, [
            'visibility' => 'public',
            'ContentType' => $mimeType,
            // Cache shield cho CDN: File tĩnh release là bất biến (immutable)
            'CacheControl' => 'public, max-age=31536000, s-maxage=31536000, immutable',
        ]);

        return $this->disk->url($path);
    }

    /**
     * Tải lên một asset media của người dùng.
     */
    public function uploadAsset(int $userId, UploadFile|UploadedFile $file, ?string $customFileName = null): array
    {
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $fileName = $customFileName ?: bin2hex(random_bytes(10)) . '.' . $extension;
        $targetDirectory = "tenants/{$userId}/assets";
        $fullPath = "{$targetDirectory}/{$fileName}";

        $this->disk->putFileAs($targetDirectory, $file, $fileName, [
            'visibility' => 'public',
            'CacheControl' => 'public, max-age=31536000, immutable',
        ]);

        return [
            'storage_path' => $fullPath,
            'file_name' => $fileName,
            'url' => $this->disk->url($fullPath),
            'disk' => $this->diskName,
        ];
    }

    /**
     * Lấy URL công khai của một file trên storage.
     */
    public function getUrl(string $path): string
    {
        return $this->disk->url($path);
    }

    /**
     * Xóa một file trên storage.
     */
    public function delete(string $path): bool
    {
        return $this->disk->delete($path);
    }

    /**
     * Lấy tên disk đang kích hoạt (s3 hoặc public).
     */
    public function getActiveDiskName(): string
    {
        return $this->diskName;
    }
}
