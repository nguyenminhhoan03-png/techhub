<?php

declare(strict_types=1);

namespace Application\Tool\Data;

use Spatie\LaravelData\Data;

class ToolExecutionData extends Data
{
    /**
     * @param array<string, mixed> $result_data
     * @param array<string, mixed>|null $input_meta
     */
    public function __construct(
        public bool $success,
        public string $tool_slug,
        public array $result_data,
        public int $execution_time_ms,
        public ?string $execution_id = null,
        public ?string $error_message = null,
        public ?string $download_url = null,
        public ?array $input_meta = null,
    ) {}
}
