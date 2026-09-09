<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Entities;

use Domain\WebsiteBuilder\Enums\DomainStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $website_id
 * @property string $domain
 * @property string $verification_token
 * @property DomainStatus $status
 * @property string $ssl_status
 * @property array|null $dns_records
 * @property \Carbon\Carbon|null $verified_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Website $website
 */
class WebsiteDomain extends Model
{
    protected $table = 'website_domains';

    protected $fillable = [
        'website_id',
        'domain',
        'verification_token',
        'status',
        'ssl_status',
        'dns_records',
        'verified_at',
    ];

    protected $casts = [
        'status' => DomainStatus::class,
        'dns_records' => 'array',
        'verified_at' => 'datetime',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
