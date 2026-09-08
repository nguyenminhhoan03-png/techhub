<?php

declare(strict_types=1);

namespace Domain\Deal\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $category
 * @property string|null $badge_text
 * @property string|null $sub_badge
 * @property array|null $tags
 * @property int $price
 * @property int|null $original_price
 * @property int $discount_percentage
 * @property float $rating
 * @property int $rating_count
 * @property int $sold_count
 * @property string $stock_status
 * @property string|null $thumbnail_url
 * @property array|null $variants
 * @property array|null $commitments
 * @property string|null $summary
 * @property string|null $description_markdown
 * @property string|null $zalo_contact
 * @property string|null $telegram_contact
 * @property bool $is_featured
 * @property bool $is_active
 * @property int $sort_order
 * @property string|null $meta_title
 * @property string|null $meta_description
 */
class DigitalDeal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'digital_deals';

    protected $fillable = [
        'slug',
        'name',
        'category',
        'badge_text',
        'sub_badge',
        'tags',
        'price',
        'original_price',
        'discount_percentage',
        'rating',
        'rating_count',
        'sold_count',
        'stock_status',
        'thumbnail_url',
        'variants',
        'commitments',
        'summary',
        'description_markdown',
        'zalo_contact',
        'telegram_contact',
        'is_featured',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'tags'                => 'array',
        'variants'            => 'array',
        'commitments'         => 'array',
        'price'               => 'integer',
        'original_price'      => 'integer',
        'discount_percentage' => 'integer',
        'rating'              => 'float',
        'rating_count'        => 'integer',
        'sold_count'          => 'integer',
        'is_featured'         => 'boolean',
        'is_active'           => 'boolean',
        'sort_order'          => 'integer',
    ];

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . '₫';
    }

    public function getFormattedOriginalPriceAttribute(): ?string
    {
        if ( ! $this->original_price) {
            return null;
        }

        return number_format($this->original_price, 0, ',', '.') . '₫';
    }

    public function getZaloUrlAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', (string) ($this->zalo_contact ?: '0866655803'));

        return 'https://zalo.me/' . $phone;
    }

    public function getTelegramUrlAttribute(): string
    {
        $tele = trim((string) ($this->telegram_contact ?: 'https://t.me/hoannm'));
        if ( ! str_starts_with($tele, 'http')) {
            $tele = 'https://t.me/' . ltrim($tele, '@');
        }

        return $tele;
    }

    public function getDefaultVariantAttribute(): ?array
    {
        if (empty($this->variants) || ! is_array($this->variants)) {
            return null;
        }

        foreach ($this->variants as $variant) {
            if ( ! empty($variant['is_default'])) {
                return $variant;
            }
        }

        return $this->variants[0] ?? null;
    }
}
