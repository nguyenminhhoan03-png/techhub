<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Calculators;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class PercentageCalculatorTool implements ToolContract
{
    public function slug(): string
    {
        return 'percentage-calculator';
    }

    public function name(): string
    {
        return 'Percentage Calculator';
    }

    public function categorySlug(): string
    {
        return 'calculators';
    }

    public function summary(): string
    {
        return 'Calculate percentage value, percentage increase/decrease, discount rates, and proportion ratios.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'mode' => ['required', 'string', 'in:percent_of,is_what_percent,increase_decrease'],
            'value_a' => ['required', 'numeric'],
            'value_b' => ['required', 'numeric'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $mode = (string) ($input['mode'] ?? 'percent_of');
        $valA = (float) ($input['value_a'] ?? 0);
        $valB = (float) ($input['value_b'] ?? 0);

        $result = 0.0;
        $description = '';

        $isVi = (class_exists(\Illuminate\Support\Facades\Facade::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication())
            ? \Illuminate\Support\Facades\App::getLocale() === 'vi'
            : true;

        if ('percent_of' === $mode) {
            // What is X% of Y? (e.g. 20% of 150 = 30)
            $result = ($valA / 100) * $valB;
            $description = $isVi
                ? "{$valA}% của {$valB} là " . round($result, 4)
                : "{$valA}% of {$valB} is " . round($result, 4);
        } elseif ('is_what_percent' === $mode) {
            // X is what percent of Y? (e.g. 30 is what % of 150 = 20%)
            if (0.0 === $valB) {
                return ToolResult::failure($isVi ? 'Không thể chia cho số 0 khi tính tỷ lệ phần trăm.' : 'Cannot divide by zero for percentage calculation.', 0);
            }
            $result = ($valA / $valB) * 100;
            $description = $isVi
                ? "{$valA} bằng " . round($result, 4) . "% của {$valB}"
                : "{$valA} is " . round($result, 4) . "% of {$valB}";
        } elseif ('increase_decrease' === $mode) {
            // Percentage increase/decrease from A to B
            if (0.0 === $valA) {
                return ToolResult::failure($isVi ? 'Giá trị ban đầu không được bằng 0 khi tính tỷ lệ tăng/giảm.' : 'Initial value cannot be zero for change calculation.', 0);
            }
            $diff = $valB - $valA;
            $result = ($diff / $valA) * 100;
            $changeType = $diff >= 0 ? ($isVi ? 'tăng' : 'increase') : ($isVi ? 'giảm' : 'decrease');
            $description = $isVi
                ? "Mức thay đổi từ {$valA} sang {$valB} là {$changeType} " . abs(round($result, 4)) . '%'
                : "Change from {$valA} to {$valB} is a {$changeType} of " . abs(round($result, 4)) . '%';
        }

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'mode' => $mode,
            'value_a' => $valA,
            'value_b' => $valB,
            'result' => round($result, 4),
            'formatted_description' => $description,
        ], executionTimeMs: $executionTimeMs);
    }
}
