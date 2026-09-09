<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $website_id
 * @property string|null $favicon_url
 * @property string|null $custom_css
 * @property string|null $custom_js_head
 * @property string|null $custom_js_body
 * @property string|null $google_analytics_id
 * @property string|null $facebook_pixel_id
 * @property bool $remove_branding
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Website $website
 */
class WebsiteSetting extends Model
{
    public $incrementing = false;
    protected $table = 'website_settings';

    protected $primaryKey = 'website_id';

    protected $fillable = [
        'website_id',
        'favicon_url',
        'custom_css',
        'custom_js_head',
        'custom_js_body',
        'google_analytics_id',
        'facebook_pixel_id',
        'remove_branding',
    ];

    protected $casts = [
        'remove_branding' => 'boolean',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
