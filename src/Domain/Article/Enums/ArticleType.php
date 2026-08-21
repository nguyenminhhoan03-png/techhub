<?php

declare(strict_types=1);

namespace Domain\Article\Enums;

enum ArticleType: string
{
    case Article = 'article';
    case Comparison = 'comparison';
    case Review = 'review';
    case BuyingGuide = 'buying_guide';
    case News = 'news';

    public function label(): string
    {
        return match ($this) {
            self::Article => 'Bài Viết',
            self::Comparison => 'So Sánh Đối Đầu',
            self::Review => 'Đánh Giá Chi Tiết',
            self::BuyingGuide => 'Tư Vấn Mua Sắm',
            self::News => 'Tin Công Nghệ',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Comparison => 'badge-cyan',
            self::Review => 'badge-emerald',
            self::BuyingGuide => 'badge-amber',
            self::News => 'badge-rose',
            self::Article => 'badge-indigo',
        };
    }
}
