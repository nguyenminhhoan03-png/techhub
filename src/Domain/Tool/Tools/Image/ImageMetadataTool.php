<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Image;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class ImageMetadataTool implements ToolContract
{
    public function slug(): string
    {
        return 'image-metadata-inspector';
    }

    public function name(): string
    {
        return 'Image Metadata & EXIF Inspector';
    }

    public function categorySlug(): string
    {
        return 'image';
    }

    public function summary(): string
    {
        return 'Analyze image dimensions, aspect ratio, color depth, MIME type, and EXIF camera metadata.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'image_base64' => ['required', 'string'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $base64String = (string) ($input['image_base64'] ?? '');

        // Remove base64 data URI header if present (e.g. data:image/png;base64,...)
        if (str_contains($base64String, ',')) {
            $parts = explode(',', $base64String);
            $base64String = $parts[1];
        }

        $binaryData = base64_decode($base64String, true);
        if (false === $binaryData || empty($binaryData)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Invalid image binary/base64 payload.', $executionTimeMs);
        }

        $imageSizeInfo = @getimagesizefromstring($binaryData);
        if (false === $imageSizeInfo) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Unsupported or corrupted image format.', $executionTimeMs);
        }

        $width = $imageSizeInfo[0];
        $height = $imageSizeInfo[1];
        $mime = $imageSizeInfo['mime'];
        $bits = $imageSizeInfo['bits'] ?? 8;
        $channels = $imageSizeInfo['channels'] ?? 3;

        // Calculate aspect ratio
        $gcd = function ($a, $b) use (&$gcd) {
            return ($a % $b) ? $gcd($b, $a % $b) : $b;
        };
        $divisor = $height > 0 ? $gcd($width, $height) : 1;
        $aspectRatio = ($divisor > 0) ? ($width / $divisor) . ':' . ($height / $divisor) : '1:1';

        $sizeBytes = mb_strlen($binaryData);

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'width_px' => $width,
            'height_px' => $height,
            'aspect_ratio' => $aspectRatio,
            'mime_type' => $mime,
            'size_bytes' => $sizeBytes,
            'size_kb' => round($sizeBytes / 1024, 2),
            'size_mb' => round($sizeBytes / (1024 * 1024), 2),
            'color_depth_bits' => $bits,
            'channels' => $channels,
        ], executionTimeMs: $executionTimeMs);
    }
}
