<?php

declare(strict_types=1);

namespace Domain\WebsiteBuilder\Entities;

use Domain\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $website_id
 * @property string $file_name
 * @property string $original_name
 * @property string $mime_type
 * @property int $file_size
 * @property string $storage_path
 * @property array|null $variants
 * @property array|null $dimensions
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read User $user
 * @property-read Website|null $website
 */
class Asset extends Model
{
    use SoftDeletes;

    protected $table = 'website_assets';

    protected $fillable = [
        'user_id',
        'website_id',
        'file_name',
        'original_name',
        'mime_type',
        'file_size',
        'storage_path',
        'variants',
        'dimensions',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'variants' => 'array',
        'dimensions' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
