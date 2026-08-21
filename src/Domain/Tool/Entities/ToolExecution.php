<?php

declare(strict_types=1);

namespace Domain\Tool\Entities;

use Domain\Tool\Enums\ToolExecutionStatus;
use Domain\User\Entities\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolExecution extends Model
{
    use HasUlids;

    /**
     * @var string
     */
    protected $table = 'tool_executions';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'ulid',
        'tool_id',
        'user_id',
        'ip_address',
        'status',
        'execution_time_ms',
        'input_size_bytes',
        'output_size_bytes',
        'storage_disk',
        'result_file_path',
        'error_message',
        'input_meta',
        'expires_at',
    ];

    /**
     * @return list<string>
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    /**
     * @return BelongsTo<Tool, $this>
     */
    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class, 'tool_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ToolExecutionStatus::class,
            'execution_time_ms' => 'integer',
            'input_size_bytes' => 'integer',
            'output_size_bytes' => 'integer',
            'input_meta' => 'array',
            'expires_at' => 'datetime',
        ];
    }
}
