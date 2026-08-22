<?php

declare(strict_types=1);

namespace Domain\Game\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $category_id
 * @property string $slug
 * @property string $name
 * @property string $summary
 * @property string|null $description_markdown
 * @property string|null $thumbnail_url
 * @property string $engine_path
 * @property string $difficulty
 * @property string|null $controls_hint
 * @property int $play_count
 * @property bool $is_active
 * @property bool $is_featured
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property GameCategory $category
 */
class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'games';

    protected $fillable = [
        'category_id',
        'slug',
        'name',
        'summary',
        'description_markdown',
        'thumbnail_url',
        'engine_path',
        'difficulty',
        'controls_hint',
        'play_count',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'play_count'  => 'integer',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(GameCategory::class, 'category_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getDifficultyLabelAttribute(): string
    {
        return match ($this->difficulty) {
            'easy'   => 'Dễ',
            'medium' => 'Trung Bình',
            'hard'   => 'Khó',
            default  => 'Dễ',
        };
    }

    public function getDifficultyColorAttribute(): string
    {
        return match ($this->difficulty) {
            'easy'   => '#059669',
            'medium' => '#d97706',
            'hard'   => '#e11d48',
            default  => '#059669',
        };
    }

    public function incrementPlayCount(): void
    {
        $this->increment('play_count');
    }
}
