<?php

declare(strict_types=1);

namespace Domain\Tool\Entities;

use Domain\Tool\Enums\ToolEngineType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $category_id
 * @property string $slug
 * @property string $name
 * @property string $summary
 * @property string|null $description_markdown
 * @property string|null $icon
 * @property ToolEngineType $engine_type
 * @property bool $is_premium_only
 * @property bool $is_active
 * @property int $execution_count
 * @property int $view_count
 * @property float $rating_avg
 * @property int $rating_count
 * @property array<string, mixed>|null $config_schema
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property ToolCategory|null $category
 */
class Tool extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'tools';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'slug',
        'name',
        'summary',
        'description_markdown',
        'icon',
        'engine_type',
        'is_premium_only',
        'is_active',
        'execution_count',
        'view_count',
        'rating_avg',
        'rating_count',
        'config_schema',
        'meta_title',
        'meta_description',
    ];

    /**
     * @return BelongsTo<ToolCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ToolCategory::class, 'category_id');
    }

    /**
     * @return HasMany<ToolExecution, $this>
     */
    public function executions(): HasMany
    {
        return $this->hasMany(ToolExecution::class, 'tool_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'engine_type' => ToolEngineType::class,
            'is_premium_only' => 'boolean',
            'is_active' => 'boolean',
            'execution_count' => 'integer',
            'view_count' => 'integer',
            'rating_avg' => 'float',
            'rating_count' => 'integer',
            'config_schema' => 'array',
        ];
    }
}
