<?php

declare(strict_types=1);

namespace Domain\Ai\Entities;

use Domain\Article\Entities\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $job_type
 * @property string|null $source_url
 * @property string|null $target_topic
 * @property string|null $raw_scraped_text
 * @property string|null $prompt_used
 * @property string|null $generated_markdown
 * @property array|null $generated_metadata
 * @property int|null $post_id
 * @property string $status
 * @property string|null $error_message
 * @property int $execution_time_ms
 */
class AiContentJob extends Model
{
    protected $table = 'ai_content_jobs';

    protected $fillable = [
        'job_type',
        'source_url',
        'target_topic',
        'raw_scraped_text',
        'prompt_used',
        'generated_markdown',
        'generated_metadata',
        'post_id',
        'status',
        'error_message',
        'execution_time_ms',
    ];

    protected $casts = [
        'generated_metadata' => 'array',
        'execution_time_ms' => 'integer',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'post_id');
    }
}
