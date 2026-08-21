<?php

declare(strict_types=1);

namespace Application\Tool\Services;

use Application\Bus\Contracts\CommandBusContract;
use Application\Bus\Contracts\QueryBusContract;
use Application\Tool\Commands\ExecuteToolCommand;
use Application\Tool\Contracts\ToolServiceContract;
use Application\Tool\Data\ToolCategoryData;
use Application\Tool\Data\ToolData;
use Application\Tool\Data\ToolExecutionData;
use Application\Tool\Queries\GetToolBySlugQuery;
use Application\Tool\Queries\GetToolCategoriesQuery;
use Application\Tool\Queries\GetToolsQuery;
use Illuminate\Support\Collection;

final class ToolService implements ToolServiceContract
{
    public function __construct(
        private readonly CommandBusContract $commandBus,
        private readonly QueryBusContract $queryBus,
    ) {}

    /**
     * @return Collection<int, ToolCategoryData>
     */
    public function getCategories(): Collection
    {
        return $this->queryBus->ask(new GetToolCategoriesQuery());
    }

    /**
     * @return Collection<int, ToolData>
     */
    public function getTools(?string $categorySlug = null, ?string $search = null): Collection
    {
        return $this->queryBus->ask(new GetToolsQuery($categorySlug, $search));
    }

    public function getToolBySlug(string $slug): ?ToolData
    {
        return $this->queryBus->ask(new GetToolBySlugQuery($slug));
    }

    /**
     * @param array<string, mixed> $input
     */
    public function executeTool(string $slug, array $input, ?int $userId = null, string $ipAddress = '127.0.0.1'): ToolExecutionData
    {
        return $this->commandBus->dispatch(
            new ExecuteToolCommand($slug, $input, $userId, $ipAddress),
        );
    }
}
