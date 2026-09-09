<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Entities;

use Domain\User\Entities\User;
use Domain\WebsiteBuilder\Enums\PublishedSiteStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $ulid
 * @property int $website_id
 * @property string $version_tag
 * @property string $storage_directory
 * @property array $manifest_json
 * @property PublishedSiteStatus $status
 * @property int $deployed_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Website $website
 * @property-read User $deployer
 */
class PublishedSite extends Model
{
    protected $table = 'published_sites';

    protected $fillable = [
        'ulid',
        'website_id',
        'version_tag',
        'storage_directory',
        'manifest_json',
        'status',
        'deployed_by',
    ];

    protected $casts = [
        'status' => PublishedSiteStatus::class,
        'manifest_json' => 'array',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }

    public function deployer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deployed_by');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (PublishedSite $site): void {
            if (empty($site->ulid)) {
                $site->ulid = (string) Str::ulid();
            }
        });
    }
}
