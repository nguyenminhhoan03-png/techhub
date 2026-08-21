<?php

declare(strict_types=1);

namespace Application\Tool\Queries;

use Application\Bus\Query;

final class GetToolBySlugQuery extends Query
{
    public function __construct(public string $slug) {}
}
