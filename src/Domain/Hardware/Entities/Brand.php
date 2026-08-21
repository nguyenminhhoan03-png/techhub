<?php

declare(strict_types=1);

namespace Domain\Hardware\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string|null $logo_url
 * @property string|null $website_url
 */
class Brand extends Model
{
    protected $table = 'brands';

    protected $fillable = [
        'slug',
        'name',
        'logo_url',
        'website_url',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
