<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Enums;

enum PageStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Bản nháp',
            self::PUBLISHED => 'Đã xuất bản',
        };
    }
}
