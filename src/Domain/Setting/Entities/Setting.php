<?php

declare(strict_types=1);

namespace Domain\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string $group
 * @property string $type
 * @property string $label
 * @property string|null $description
 */
class Setting extends Model
{
    /**
     * @var string
     */
    protected $table = 'system_settings';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
    ];
}
