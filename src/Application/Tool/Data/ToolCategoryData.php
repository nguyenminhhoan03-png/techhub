<?php

declare(strict_types=1);

namespace Application\Tool\Data;

use Spatie\LaravelData\Data;

class ToolCategoryData extends Data
{
    public function __construct(
        public int $id,
        public string $slug,
        public string $name,
        public ?string $description,
        public ?string $icon,
        public int $sort_order,
        public bool $is_active,
        public ?string $meta_title = null,
        public ?string $meta_description = null,
    ) {}
}
