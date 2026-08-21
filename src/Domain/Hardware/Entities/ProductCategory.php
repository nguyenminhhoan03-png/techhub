<?php

declare(strict_types=1);

namespace Domain\Hardware\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property string $slug
 * @property string $name
 * @property string|null $icon
 * @property array|null $spec_schema
 */
class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $fillable = [
        'parent_id',
        'slug',
        'name',
        'icon',
        'spec_schema',
    ];

    protected $casts = [
        'spec_schema' => 'array',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function comparisons(): HasMany
    {
        return $this->hasMany(Comparison::class, 'category_id');
    }
}
