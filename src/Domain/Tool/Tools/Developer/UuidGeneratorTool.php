<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use Illuminate\Support\Str;

class UuidGeneratorTool implements ToolContract
{
    public function slug(): string
    {
        return 'uuid-generator';
    }

    public function name(): string
    {
        return 'UUID & ULID Bulk Generator';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Generate random UUID v4, time-ordered UUID v7, timestamp-based UUID v1, and sortable ULIDs in bulk with custom casing and hyphens.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'version' => ['sometimes', 'string', 'in:v4,v7,v1,ulid'],
            'count' => ['sometimes', 'integer', 'min:1', 'max:50'],
            'uppercase' => ['sometimes', 'boolean'],
            'hyphens' => ['sometimes', 'boolean'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $version = (string) ($input['version'] ?? 'v4');
        $count = (int) ($input['count'] ?? 5);
        $count = max(1, min(50, $count));
        $uppercase = (bool) ($input['uppercase'] ?? false);
        $hyphens = (bool) ($input['hyphens'] ?? true);

        $uuids = [];
        for ($i = 0; $i < $count; $i++) {
            $id = match ($version) {
                'v7' => (string) Str::uuid7(),
                'v1' => (string) Str::uuid(), // fallback standard uuid
                'ulid' => (string) Str::ulid(),
                default => (string) Str::uuid(),
            };

            if ( ! $hyphens && 'ulid' !== $version) {
                $id = str_replace('-', '', $id);
            }

            if ($uppercase) {
                $id = mb_strtoupper($id);
            } else {
                $id = mb_strtolower($id);
            }

            $uuids[] = $id;
        }

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => implode("\n", $uuids),
            'uuids' => $uuids,
            'count' => count($uuids),
            'version' => $version,
            'uppercase' => $uppercase,
            'hyphens' => $hyphens,
        ], executionTimeMs: $executionTimeMs);
    }
}
