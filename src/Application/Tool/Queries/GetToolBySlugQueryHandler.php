<?php

declare(strict_types=1);

namespace Application\Tool\Queries;

use Application\Bus\QueryHandler;
use Application\Tool\Data\ToolCategoryData;
use Application\Tool\Data\ToolData;
use Domain\Tool\Repositories\ToolRepositoryContract;

final class GetToolBySlugQueryHandler extends QueryHandler
{
    public function __construct(private readonly ToolRepositoryContract $toolRepository) {}

    public function handle(GetToolBySlugQuery $query): ?ToolData
    {
        $tool = $this->toolRepository->getToolBySlug($query->slug);
        if ( ! $tool) {
            return null;
        }

        $categoryData = $tool->category ? new ToolCategoryData(
            id: $tool->category->id,
            slug: $tool->category->slug,
            name: $tool->category->name,
            description: $tool->category->description,
            icon: $tool->category->icon,
            sort_order: $tool->category->sort_order,
            is_active: $tool->category->is_active,
            meta_title: $tool->category->meta_title,
            meta_description: $tool->category->meta_description,
        ) : null;

        return new ToolData(
            id: $tool->id,
            category_id: $tool->category_id,
            slug: $tool->slug,
            name: $tool->name,
            summary: $tool->summary,
            description_markdown: $tool->description_markdown,
            icon: $tool->icon,
            engine_type: $tool->engine_type->value,
            is_premium_only: $tool->is_premium_only,
            is_active: $tool->is_active,
            execution_count: $tool->execution_count,
            view_count: $tool->view_count,
            rating_avg: $tool->rating_avg,
            rating_count: $tool->rating_count,
            config_schema: $tool->config_schema,
            meta_title: $tool->meta_title,
            meta_description: $tool->meta_description,
            category: $categoryData,
        );
    }
}
