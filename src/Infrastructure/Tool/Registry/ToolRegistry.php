<?php

declare(strict_types=1);

namespace Infrastructure\Tool\Registry;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Contracts\ToolRegistryContract;

class ToolRegistry implements ToolRegistryContract
{
    /**
     * @var array<string, ToolContract>
     */
    private array $tools = [];

    public function register(ToolContract $tool): void
    {
        $this->tools[$tool->slug()] = $tool;
    }

    public function get(string $slug): ?ToolContract
    {
        return $this->tools[$slug] ?? null;
    }

    public function has(string $slug): bool
    {
        return isset($this->tools[$slug]);
    }

    /**
     * @return array<string, ToolContract>
     */
    public function all(): array
    {
        return $this->tools;
    }

    /**
     * @return array<string, ToolContract>
     */
    public function getByCategory(string $categorySlug): array
    {
        return array_filter($this->tools, fn(ToolContract $tool): bool => $tool->categorySlug() === $categorySlug);
    }
}
