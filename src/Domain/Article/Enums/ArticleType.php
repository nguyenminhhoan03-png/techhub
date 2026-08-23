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
        $isEn = app()->getLocale() === 'en';
        return match ($this) {
            self::Article => $isEn ? 'Article' : 'Bài Viết',
            self::Comparison => $isEn ? 'Head-to-Head Comparison' : 'So Sánh Đối Đầu',
            self::Review => $isEn ? 'In-Depth Review' : 'Đánh Giá Chi Tiết',
            self::BuyingGuide => $isEn ? 'Buying Guide' : 'Tư Vấn Mua Sắm',
            self::News => $isEn ? 'Tech News' : 'Tin Công Nghệ',
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
