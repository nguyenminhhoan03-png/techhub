<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Enums;

enum DomainStatus: string
{
    case PENDING_DNS = 'pending_dns';
    case VERIFIED = 'verified';
    case ACTIVE = 'active';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_DNS => 'Chờ cấu hình DNS',
            self::VERIFIED => 'Đã xác minh DNS',
            self::ACTIVE => 'Đang hoạt động (SSL OK)',
            self::FAILED => 'Xác minh thất bại',
        };
    }
}
