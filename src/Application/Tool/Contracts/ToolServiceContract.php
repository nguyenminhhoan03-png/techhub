<?php

declare(strict_types=1);

namespace Application\Tool\Contracts;

use Application\Tool\Data\ToolCategoryData;
use Application\Tool\Data\ToolData;
use Application\Tool\Data\ToolExecutionData;
use Illuminate\Support\Collection;

interface ToolServiceContract
{
    /**
     * @return Collection<int, ToolCategoryData>
     */
    public function getCategories(): Collection;

    /**
     * @return Collection<int, ToolData>
     */
    public function getTools(?string $categorySlug = null, ?string $search = null): Collection;

    public function getToolBySlug(string $slug): ?ToolData;

    /**
     * @param array<string, mixed> $input
     */
    public function executeTool(string $slug, array $input, ?int $userId = null, string $ipAddress = '127.0.0.1'): ToolExecutionData;
}
