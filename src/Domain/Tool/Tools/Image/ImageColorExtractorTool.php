<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Image;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class ImageColorExtractorTool implements ToolContract
{
    public function slug(): string
    {
        return 'image-color-extractor';
    }

    public function name(): string
    {
        return 'Trích Xuất Bảng Màu Chủ Đạo Của Ảnh';
    }

    public function categorySlug(): string
    {
        return 'image';
    }

    public function summary(): string
    {
        return 'Tự động trích xuất các dải màu chủ đạo, mã màu HEX, RGB, HSL từ bất kỳ bức ảnh nào được tải lên.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'image_base64' => ['required', 'string'],
            'palette_size' => ['sometimes', 'integer', 'min:3', 'max:10'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $base64String = (string) ($input['image_base64'] ?? '');
        $paletteSize = (int) ($input['palette_size'] ?? 5);

        if (str_contains($base64String, ',')) {
            $parts = explode(',', $base64String);
            $base64String = $parts[1];
        }

        $binaryData = base64_decode($base64String, true);
        if (false === $binaryData || empty($binaryData)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Hình ảnh không hợp lệ hoặc dữ liệu Base64 rỗng.', $executionTimeMs);
        }

        $image = @imagecreatefromstring($binaryData);
        if (false === $image) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Không thể đọc dữ liệu luồng ảnh (Định dạng ảnh không hỗ trợ).', $executionTimeMs);
        }

        // Resize down for fast palette sampling (max 100x100)
        $origW = imagesx($image);
        $origH = imagesy($image);
        $sampleW = min(100, max(1, $origW));
        $sampleH = min(100, max(1, $origH));

        $sampledImage = imagecreatetruecolor($sampleW, $sampleH);
        if (false === $sampledImage) {
            imagedestroy($image);
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Không thể khởi tạo bộ nhớ canvas lấy mẫu.', $executionTimeMs);
        }

        imagecopyresampled($sampledImage, $image, 0, 0, 0, 0, $sampleW, $sampleH, $origW, $origH);

        $colorBuckets = [];

        for ($x = 0; $x < $sampleW; $x += 2) {
            for ($y = 0; $y < $sampleH; $y += 2) {
                $rgb = imagecolorat($sampledImage, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                // Quantize to reduce noise (round to multiple of 16)
                $qr = (int) (round($r / 16) * 16);
                $qg = (int) (round($g / 16) * 16);
                $qb = (int) (round($b / 16) * 16);

                $hex = sprintf('#%02x%02x%02x', min(255, $qr), min(255, $qg), min(255, $qb));
                $colorBuckets[$hex] = ($colorBuckets[$hex] ?? 0) + 1;
            }
        }

        imagedestroy($sampledImage);
        imagedestroy($image);

        arsort($colorBuckets);
        $topHexes = array_slice(array_keys($colorBuckets), 0, $paletteSize);

        // Fallback default colors if image had 0 colors
        if (empty($topHexes)) {
            $topHexes = ['#4F46E5', '#0284C7', '#059669', '#D97706', '#E11D48'];
        }

        $palette = [];
        foreach ($topHexes as $hex) {
            $r = hexdec(mb_substr($hex, 1, 2));
            $g = hexdec(mb_substr($hex, 3, 2));
            $b = hexdec(mb_substr($hex, 5, 2));

            // Standard RGB to HSL conversion with zero division guard
            $rNorm = $r / 255.0;
            $gNorm = $g / 255.0;
            $bNorm = $b / 255.0;

            $max = max($rNorm, $gNorm, $bNorm);
            $min = min($rNorm, $gNorm, $bNorm);
            $delta = $max - $min;

            $l = ($max + $min) / 2.0;

            if ($delta < 1e-6 || $max <= 0.0 || $min >= 1.0) {
                $h = 0.0;
                $s = 0.0;
            } else {
                $divisor = ($l > 0.5) ? (2.0 - $max - $min) : ($max + $min);
                $s = ($divisor > 1e-6) ? ($delta / $divisor) : 0.0;

                if ($max === $rNorm) {
                    $h = ($gNorm - $bNorm) / $delta + ($gNorm < $bNorm ? 6.0 : 0.0);
                } elseif ($max === $gNorm) {
                    $h = ($bNorm - $rNorm) / $delta + 2.0;
                } else {
                    $h = ($rNorm - $gNorm) / $delta + 4.0;
                }

                $h /= 6.0;
            }

            $hDeg = (int) round($h * 360);
            $sPct = (int) round($s * 100);
            $lPct = (int) round($l * 100);

            $palette[] = [
                'hex' => mb_strtoupper($hex),
                'rgb' => ['r' => $r, 'g' => $g, 'b' => $b],
                'hsl' => [
                    'h' => $hDeg,
                    's' => "{$sPct}%",
                    'l' => "{$lPct}%",
                ],
                'rgb_string' => "rgb({$r}, {$g}, {$b})",
                'is_dark' => ($r * 0.299 + $g * 0.587 + $b * 0.114) < 128,
            ];
        }

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'palette_count' => count($palette),
            'dominant_color' => $palette[0],
            'palette' => $palette,
        ], executionTimeMs: $executionTimeMs);
    }
}
