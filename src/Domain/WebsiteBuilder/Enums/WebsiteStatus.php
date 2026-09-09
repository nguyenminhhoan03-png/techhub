<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Enums;

enum WebsiteStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Bản nháp',
            self::PUBLISHED => 'Đã xuất bản',
            self::SUSPENDED => 'Tạm khóa',
        };
    }

    public function isPublished(): bool
    {
        return self::PUBLISHED === $this;
    }
}
