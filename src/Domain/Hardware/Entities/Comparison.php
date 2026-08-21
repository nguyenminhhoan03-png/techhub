<?php

declare(strict_types=1);

namespace Domain\Hardware\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $category_id
 * @property string $slug
 * @property string $title
 * @property string|null $summary_markdown
 * @property int|null $winner_product_id
 * @property int $view_count
 * @property string|null $meta_title
 * @property string|null $meta_description
 */
class Comparison extends Model
{
    protected $table = 'comparisons';

    protected $fillable = [
        'category_id',
        'slug',
        'title',
        'summary_markdown',
        'winner_product_id',
        'view_count',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'view_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'winner_product_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ComparisonItem::class, 'comparison_id');
    }
}
