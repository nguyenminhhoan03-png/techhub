<?php

declare(strict_types=1);

namespace Domain\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property int $sort_order
 * @property bool $is_active
 */
class ContentCategory extends Model
{
    protected $table = 'content_categories';

    protected $fillable = [
        'parent_id',
        'slug',
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    public function getDisplayNameAttribute(): string
    {
        $key = 'categories.' . $this->slug . '.name';
        if (\Illuminate\Support\Facades\Lang::has($key)) {
            return __($key);
        }
        return $this->name;
    }
}
