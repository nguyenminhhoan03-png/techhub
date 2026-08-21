<?php

declare(strict_types=1);

namespace Domain\Hardware\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property string $benchmark_type
 * @property float $score_value
 * @property string $test_unit
 * @property string|null $test_conditions
 * @property \Carbon\Carbon|null $tested_at
 */
class ProductBenchmark extends Model
{
    protected $table = 'product_benchmarks';

    protected $fillable = [
        'product_id',
        'benchmark_type',
        'score_value',
        'test_unit',
        'test_conditions',
        'tested_at',
    ];

    protected $casts = [
        'score_value' => 'float',
        'tested_at' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
