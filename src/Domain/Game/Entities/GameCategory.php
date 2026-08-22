<?php

declare(strict_types=1);

namespace Domain\Game\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property string|null $icon
 * @property string $color
 * @property int $sort_order
 * @property bool $is_active
 */
class GameCategory extends Model
{
    use HasFactory;

    protected $table = 'game_categories';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'category_id');
    }

    public function activeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'category_id')->where('is_active', true);
    }
}
