<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Entities;

use Domain\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $page_id
 * @property int $version_number
 * @property array $content_json
 * @property array|null $styles_json
 * @property string|null $commit_message
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property-read Page $page
 * @property-read User $creator
 */
class PageVersion extends Model
{
    public $timestamps = false;

    protected $table = 'page_versions';

    protected $fillable = [
        'page_id',
        'version_number',
        'content_json',
        'styles_json',
        'commit_message',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'version_number' => 'integer',
        'content_json' => 'array',
        'styles_json' => 'array',
        'created_at' => 'datetime',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (PageVersion $version): void {
            if (empty($version->created_at)) {
                $version->created_at = now();
            }
        });
    }
}
