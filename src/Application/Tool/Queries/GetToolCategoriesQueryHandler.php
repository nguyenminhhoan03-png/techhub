<?php

declare(strict_types=1);

namespace Application\Tool\Queries;

use Application\Bus\QueryHandler;
use Application\Tool\Data\ToolCategoryData;
use Domain\Tool\Repositories\ToolRepositoryContract;
use Illuminate\Support\Collection;

final class GetToolCategoriesQueryHandler extends QueryHandler
{
    public function __construct(private readonly ToolRepositoryContract $toolRepository) {}

    /**
     * @return Collection<int, ToolCategoryData>
     */
    public function handle(GetToolCategoriesQuery $query): Collection
    {
        $categories = $this->toolRepository->getCategories();

        return $categories->map(fn($cat): ToolCategoryData => new ToolCategoryData(
            id: $cat->id,
            slug: $cat->slug,
            name: $cat->name,
            description: $cat->description,
            icon: $cat->icon,
            sort_order: $cat->sort_order,
            is_active: $cat->is_active,
            meta_title: $cat->meta_title,
            meta_description: $cat->meta_description,
        ));
    }
}
