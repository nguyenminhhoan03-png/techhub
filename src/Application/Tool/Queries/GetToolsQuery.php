<?php

declare(strict_types=1);

namespace Application\Tool\Queries;

use Application\Bus\Query;

final class GetToolsQuery extends Query
{
    public function __construct(
        public ?string $categorySlug = null,
        public ?string $search = null,
    ) {}
}
