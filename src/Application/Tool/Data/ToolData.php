<?php

declare(strict_types=1);

namespace Application\Tool\Data;

use Spatie\LaravelData\Data;

class ToolData extends Data
{
    /**
     * @param array<string, mixed>|null $config_schema
     */
    public function __construct(
        public int $id,
        public int $category_id,
        public string $slug,
        public string $name,
        public string $summary,
        public ?string $description_markdown,
        public ?string $icon,
        public string $engine_type,
        public bool $is_premium_only,
        public bool $is_active,
        public int $execution_count,
        public int $view_count,
        public float $rating_avg,
        public int $rating_count,
        public ?array $config_schema = null,
        public ?string $meta_title = null,
        public ?string $meta_description = null,
        public ?ToolCategoryData $category = null,
    ) {}
}
