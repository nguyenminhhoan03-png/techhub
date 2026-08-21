<?php

declare(strict_types=1);

namespace Domain\Tool\Contracts;

use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

interface ToolContract
{
    /**
     * Unique URL slug for the tool (e.g. 'json-formatter', 'loan-calculator').
     */
    public function slug(): string;

    /**
     * Human-readable display name.
     */
    public function name(): string;

    /**
     * Category slug (e.g. 'developer', 'calculators', 'image', 'pdf').
     */
    public function categorySlug(): string;

    /**
     * Summary description of what the tool does.
     */
    public function summary(): string;

    /**
     * Execution engine type.
     */
    public function engineType(): ToolEngineType;

    /**
     * Validation rules for the tool input.
     *
     * @return array<string, mixed>
     */
    public function validationRules(): array;

    /**
     * Execute the tool with validated input parameters.
     *
     * @param array<string, mixed> $input
     */
    public function execute(array $input): ToolResult;
}
