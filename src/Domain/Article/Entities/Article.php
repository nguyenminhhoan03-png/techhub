<?php

declare(strict_types=1);

namespace Domain\Article\Entities;

use Domain\Article\Enums\ArticleType;
use Domain\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $ulid
 * @property int $author_id
 * @property int $category_id
 * @property ArticleType $type
 * @property string $slug
 * @property string $title
 * @property string $excerpt
 * @property string $content_markdown
 * @property string $content_html
 * @property string|null $featured_image_url
 * @property int $read_time_minutes
 * @property int $view_count
 * @property string $status
 * @property \Carbon\Carbon|null $published_at
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $canonical_url
 * @property array|null $schema_markup
 */
class Article extends Model
{
    use SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'ulid',
        'author_id',
        'category_id',
        'type',
        'slug',
        'title',
        'excerpt',
        'content_markdown',
        'content_html',
        'featured_image_url',
        'read_time_minutes',
        'view_count',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'canonical_url',
        'schema_markup',
    ];

    protected $casts = [
        'type' => ArticleType::class,
        'read_time_minutes' => 'integer',
        'view_count' => 'integer',
        'published_at' => 'datetime',
        'schema_markup' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Article $article): void {
            if (empty($article->ulid)) {
                $article->ulid = (string) Str::ulid();
            }
            if (empty($article->read_time_minutes) && !empty($article->content_markdown)) {
                $words = str_word_count(strip_tags($article->content_markdown));
                $article->read_time_minutes = max(1, (int) ceil($words / 200));
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
    }

    public function isPublished(): bool
    {
        return 'published' === $this->status;
    }

    public function getSummaryAttribute(): ?string
    {
        return $this->excerpt;
    }
}
