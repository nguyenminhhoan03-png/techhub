<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $website_id
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $og_image_url
 * @property bool $sitemap_enabled
 * @property string|null $robots_txt
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Website $website
 */
class WebsiteSeo extends Model
{
    public $incrementing = false;
    protected $table = 'website_seo';

    protected $primaryKey = 'website_id';

    protected $fillable = [
        'website_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image_url',
        'sitemap_enabled',
        'robots_txt',
    ];

    protected $casts = [
        'sitemap_enabled' => 'boolean',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
