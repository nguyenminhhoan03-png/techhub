<?php

declare(strict_types=1);

namespace Domain\Tool\Contracts;

interface ToolRegistryContract
{
    /**
     * Register a tool instance.
     */
    public function register(ToolContract $tool): void;

    /**
     * Resolve a tool instance by its slug.
     */
    public function get(string $slug): ?ToolContract;

    /**
     * Check if a tool is registered by slug.
     */
    public function has(string $slug): bool;

    /**
     * Get all registered tools.
     *
     * @return array<string, ToolContract>
     */
    public function all(): array;

    /**
     * Get all registered tools in a specific category.
     *
     * @return array<string, ToolContract>
     */
    public function getByCategory(string $categorySlug): array;
}
