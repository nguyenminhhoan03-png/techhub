<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Enums;

enum PublishedSiteStatus: string
{
    case DEPLOYING = 'deploying';
    case LIVE = 'live';
    case ROLLED_BACK = 'rolled_back';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::DEPLOYING => 'Đang biên dịch & triển khai',
            self::LIVE => 'Đang phát trực tiếp (Live)',
            self::ROLLED_BACK => 'Đã thu hồi',
            self::FAILED => 'Triển khai thất bại',
        };
    }
}
