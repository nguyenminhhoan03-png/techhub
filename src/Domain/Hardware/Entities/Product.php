<?php

declare(strict_types=1);

namespace Domain\Hardware\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $ulid
 * @property int $category_id
 * @property int $brand_id
 * @property string $slug
 * @property string $model_name
 * @property string $full_name
 * @property \Carbon\Carbon|null $release_date
 * @property float|null $launch_msrp_usd
 * @property string|null $thumbnail_url
 * @property array|null $gallery_images
 * @property float $overall_score
 * @property float $gaming_score
 * @property float $productivity_score
 * @property int $view_count
 * @property bool $is_featured
 * @property bool $is_active
 * @property array $specs
 * @property string|null $meta_title
 * @property string|null $meta_description
 */
class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'ulid',
        'category_id',
        'brand_id',
        'slug',
        'model_name',
        'full_name',
        'release_date',
        'launch_msrp_usd',
        'thumbnail_url',
        'gallery_images',
        'overall_score',
        'gaming_score',
        'productivity_score',
        'view_count',
        'is_featured',
        'is_active',
        'specs',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'release_date' => 'date',
        'launch_msrp_usd' => 'decimal:2',
        'overall_score' => 'float',
        'gaming_score' => 'float',
        'productivity_score' => 'float',
        'view_count' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'gallery_images' => 'array',
        'specs' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Product $product): void {
            if (empty($product->ulid)) {
                $product->ulid = (string) Str::ulid();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function benchmarks(): HasMany
    {
        return $this->hasMany(ProductBenchmark::class, 'product_id');
    }
}
