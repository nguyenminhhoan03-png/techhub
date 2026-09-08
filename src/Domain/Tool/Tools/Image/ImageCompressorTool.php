<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Image;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class ImageCompressorTool implements ToolContract
{
    public function slug(): string
    {
        return 'image-compressor';
    }

    public function name(): string
    {
        return 'Smart Image Compressor & WebP Converter';
    }

    public function categorySlug(): string
    {
        return 'image';
    }

    public function summary(): string
    {
        return 'Compress JPG, PNG, and WebP images, adjust quality percentage, optionally resize dimensions, and optimize asset file sizes.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'image_base64' => ['required', 'string'],
            'quality' => ['sometimes', 'integer', 'min:10', 'max:100'],
            'format' => ['sometimes', 'string', 'in:webp,jpeg,png'],
            'max_width' => ['sometimes', 'integer', 'min:50', 'max:4000'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $base64 = (string) ($input['image_base64'] ?? '');
        $quality = (int) ($input['quality'] ?? 80);
        $targetFormat = (string) ($input['format'] ?? 'webp');
        $maxWidth = isset($input['max_width']) && ! empty($input['max_width']) ? (int) $input['max_width'] : null;

        // Clean Base64 Data URL prefix if present
        if (preg_match('/^data:image\/(\w+);base64,/', $base64)) {
            $binary = base64_decode((string) preg_replace('/^data:image\/\w+;base64,/', '', $base64), true);
        } else {
            $binary = base64_decode($base64, true);
        }

        if (false === $binary || '' === $binary) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Invalid image binary or base64 data.', $executionTimeMs);
        }

        $originalSizeBytes = mb_strlen($binary);

        // Load image resource via GD
        $img = @imagecreatefromstring($binary);
        if (false === $img) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Failed to decode image. Supported formats are PNG, JPEG, WEBP, and GIF.', $executionTimeMs);
        }

        $origWidth = imagesx($img);
        $origHeight = imagesy($img);

        // Resize if maxWidth is specified and smaller than original width
        if (null !== $maxWidth && $maxWidth < $origWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round(($origHeight / $origWidth) * $newWidth);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($img);
            $img = $resized;
        } else {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        }

        // Export compressed image into memory buffer
        ob_start();
        $mimeType = 'image/webp';
        if ('png' === $targetFormat) {
            imagealphablending($img, false);
            imagesavealpha($img, true);
            // PNG quality in GD is 0 (uncompressed) to 9 (max compression)
            $pngCompression = (int) round((100 - $quality) / 11);
            imagepng($img, null, $pngCompression);
            $mimeType = 'image/png';
        } elseif ('jpeg' === $targetFormat) {
            imagejpeg($img, null, $quality);
            $mimeType = 'image/jpeg';
        } else {
            // WebP
            imagealphablending($img, false);
            imagesavealpha($img, true);
            imagewebp($img, null, $quality);
            $mimeType = 'image/webp';
        }
        $compressedBinary = (string) ob_get_clean();
        imagedestroy($img);

        $compressedSizeBytes = mb_strlen($compressedBinary);
        $savedPct = round((($originalSizeBytes - $compressedSizeBytes) / $originalSizeBytes) * 100, 2);

        $compressedDataUrl = "data:{$mimeType};base64," . base64_encode($compressedBinary);
        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $compressedDataUrl,
            'data_url' => $compressedDataUrl,
            'compressed_base64' => $compressedDataUrl,
            'format' => $targetFormat,
            'mime_type' => $mimeType,
            'original_size_bytes' => $originalSizeBytes,
            'compressed_size_bytes' => $compressedSizeBytes,
            'original_size_formatted' => round($originalSizeBytes / 1024, 1) . ' KB',
            'compressed_size_formatted' => round($compressedSizeBytes / 1024, 1) . ' KB',
            'saved_percentage' => $savedPct,
            'width' => $newWidth,
            'height' => $newHeight,
        ], executionTimeMs: $executionTimeMs);
    }
}
