<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Entities;

use Domain\WebsiteBuilder\Enums\PageStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $ulid
 * @property int $website_id
 * @property string $title
 * @property string $slug
 * @property bool $is_home
 * @property array $draft_content
 * @property int $version_number
 * @property PageStatus $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read Website $website
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PageVersion> $versions
 */
class Page extends Model
{
    use SoftDeletes;

    protected $table = 'pages';

    protected $fillable = [
        'ulid',
        'website_id',
        'title',
        'slug',
        'is_home',
        'draft_content',
        'version_number',
        'status',
    ];

    protected $casts = [
        'is_home' => 'boolean',
        'draft_content' => 'array',
        'version_number' => 'integer',
        'status' => PageStatus::class,
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PageVersion::class, 'page_id')->orderByDesc('version_number');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Page $page): void {
            if (empty($page->ulid)) {
                $page->ulid = (string) Str::ulid();
            }
            if (empty($page->version_number)) {
                $page->version_number = 1;
            }
        });
    }
}
