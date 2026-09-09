<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Entities;

use Domain\User\Entities\User;
use Domain\WebsiteBuilder\Enums\WebsiteStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $ulid
 * @property int $user_id
 * @property string $name
 * @property string $subdomain
 * @property WebsiteStatus $status
 * @property int|null $active_version_id
 * @property \Carbon\Carbon|null $published_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Page> $pages
 * @property-read WebsiteSetting|null $settings
 * @property-read WebsiteSeo|null $seo
 */
class Website extends Model
{
    use SoftDeletes;

    protected $table = 'websites';

    protected $fillable = [
        'ulid',
        'user_id',
        'name',
        'subdomain',
        'status',
        'active_version_id',
        'published_at',
    ];

    protected $casts = [
        'status' => WebsiteStatus::class,
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'website_id');
    }

    public function domains(): HasMany
    {
        return $this->hasMany(WebsiteDomain::class, 'website_id');
    }

    public function publishedSites(): HasMany
    {
        return $this->hasMany(PublishedSite::class, 'website_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'website_id');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(WebsiteSetting::class, 'website_id');
    }

    public function seo(): HasOne
    {
        return $this->hasOne(WebsiteSeo::class, 'website_id');
    }

    public function homePage(): HasOne
    {
        return $this->hasOne(Page::class, 'website_id')->where('is_home', true);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Website $website): void {
            if (empty($website->ulid)) {
                $website->ulid = (string) Str::ulid();
            }
        });
    }
}
