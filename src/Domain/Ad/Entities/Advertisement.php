<?php

declare(strict_types=1);

namespace Domain\Ad\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $slot
 * @property string $type
 * @property string|null $image_url
 * @property string|null $target_url
 * @property string|null $raw_html
 * @property bool $is_active
 * @property string|null $starts_at
 * @property string|null $expires_at
 * @property int $clicks_count
 * @property int $impressions_count
 */
class Advertisement extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'advertisements';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slot',
        'type',
        'image_url',
        'target_url',
        'raw_html',
        'is_active',
        'starts_at',
        'expires_at',
        'clicks_count',
        'impressions_count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'clicks_count' => 'integer',
            'impressions_count' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }
}
