<?php

declare(strict_types=1);

namespace Domain\Hardware\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $comparison_id
 * @property int $product_id
 * @property bool $is_winner
 * @property array|null $pros
 * @property array|null $cons
 */
class ComparisonItem extends Model
{
    protected $table = 'comparison_items';

    protected $fillable = [
        'comparison_id',
        'product_id',
        'is_winner',
        'pros',
        'cons',
    ];

    protected $casts = [
        'is_winner' => 'boolean',
        'pros' => 'array',
        'cons' => 'array',
    ];

    public function comparison(): BelongsTo
    {
        return $this->belongsTo(Comparison::class, 'comparison_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
