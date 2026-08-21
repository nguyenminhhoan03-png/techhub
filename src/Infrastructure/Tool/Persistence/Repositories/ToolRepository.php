<?php

declare(strict_types=1);

namespace Infrastructure\Tool\Persistence\Repositories;

use Domain\Tool\Entities\Tool;
use Domain\Tool\Entities\ToolCategory;
use Domain\Tool\Entities\ToolExecution;
use Domain\Tool\Repositories\ToolRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class ToolRepository implements ToolRepositoryContract
{
    /**
     * @return Collection<int, ToolCategory>
     */
    public function getCategories(): Collection
    {
        return ToolCategory::query()
            ->where('is_active', true)
            ->whereHas('tools', function ($q): void {
                $q->where('is_active', true);
            })
            ->withCount(['tools' => function ($q): void {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function getCategoryBySlug(string $slug): ?ToolCategory
    {
        return ToolCategory::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    /**
     * @return Collection<int, Tool>
     */
    public function getTools(?string $categorySlug = null, ?string $search = null): Collection
    {
        $query = Tool::query()
            ->with('category')
            ->where('is_active', true);

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug): void {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('execution_count')->get();
    }

    public function getToolBySlug(string $slug): ?Tool
    {
        return Tool::query()
            ->with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function incrementExecutionCount(int $toolId): void
    {
        Tool::query()->where('id', $toolId)->increment('execution_count');
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function createExecution(array $attributes): ToolExecution
    {
        /** @var ToolExecution $execution */
        $execution = ToolExecution::query()->create($attributes);

        return $execution;
    }
}
