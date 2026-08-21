<?php

declare(strict_types=1);

namespace Domain\Tool\Repositories;

use Domain\Tool\Entities\Tool;
use Domain\Tool\Entities\ToolCategory;
use Domain\Tool\Entities\ToolExecution;
use Illuminate\Database\Eloquent\Collection;

interface ToolRepositoryContract
{
    /**
     * Get all active tool categories.
     *
     * @return Collection<int, ToolCategory>
     */
    public function getCategories(): Collection;

    /**
     * Get category by slug.
     */
    public function getCategoryBySlug(string $slug): ?ToolCategory;

    /**
     * Get all active tools, optionally filtered by category or search term.
     *
     * @return Collection<int, Tool>
     */
    public function getTools(?string $categorySlug = null, ?string $search = null): Collection;

    /**
     * Get tool by slug.
     */
    public function getToolBySlug(string $slug): ?Tool;

    /**
     * Increment tool execution count.
     */
    public function incrementExecutionCount(int $toolId): void;

    /**
     * Record a tool execution log.
     *
     * @param array<string, mixed> $attributes
     */
    public function createExecution(array $attributes): ToolExecution;
}
