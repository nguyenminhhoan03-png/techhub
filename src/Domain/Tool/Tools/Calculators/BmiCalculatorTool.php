<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Calculators;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class BmiCalculatorTool implements ToolContract
{
    public function slug(): string
    {
        return 'bmi-calculator';
    }

    public function name(): string
    {
        return 'BMI & Ideal Body Weight Calculator';
    }

    public function categorySlug(): string
    {
        return 'calculators';
    }

    public function summary(): string
    {
        return 'Calculate Body Mass Index (BMI), WHO health classification, and healthy target weight range.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'unit_system' => ['sometimes', 'string', 'in:metric,imperial'],
            'height' => ['required', 'numeric', 'min:30', 'max:300'], // cm (metric) or inches (imperial)
            'weight' => ['required', 'numeric', 'min:10', 'max:600'], // kg (metric) or lbs (imperial)
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $unit = (string) ($input['unit_system'] ?? 'metric');
        $height = (float) ($input['height'] ?? 170);
        $weight = (float) ($input['weight'] ?? 65);

        if ('imperial' === $unit) {
            // BMI = 703 * weight (lbs) / (height in inches)^2
            $bmi = ($height > 0) ? (703 * $weight) / ($height * $height) : 0;
            $heightMeters = $height * 0.0254;
        } else {
            // BMI = weight (kg) / (height in meters)^2
            $heightMeters = $height / 100;
            $bmi = ($heightMeters > 0) ? $weight / ($heightMeters * $heightMeters) : 0;
        }

        $bmi = round($bmi, 1);

        // WHO Classification
        $category = 'Normal weight';
        $healthRisk = 'Minimal';

        if ($bmi < 18.5) {
            $category = 'Underweight';
            $healthRisk = 'Increased risk for nutritional deficiency and osteoporosis';
        } elseif ($bmi <= 24.9) {
            $category = 'Normal weight (Healthy)';
            $healthRisk = 'Lowest risk of health complications';
        } elseif ($bmi <= 29.9) {
            $category = 'Overweight';
            $healthRisk = 'Increased risk for cardiovascular conditions';
        } elseif ($bmi <= 34.9) {
            $category = 'Obesity Class I';
            $healthRisk = 'Moderate risk for type 2 diabetes & hypertension';
        } elseif ($bmi <= 39.9) {
            $category = 'Obesity Class II';
            $healthRisk = 'Severe health risk';
        } else {
            $category = 'Obesity Class III (Severe)';
            $healthRisk = 'Very high health risk';
        }

        // Calculate healthy weight range (BMI 18.5 - 24.9)
        $minHealthyKg = round(18.5 * ($heightMeters * $heightMeters), 1);
        $maxHealthyKg = round(24.9 * ($heightMeters * $heightMeters), 1);

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'bmi_score' => $bmi,
            'category' => $category,
            'health_risk' => $healthRisk,
            'healthy_weight_range' => [
                'min_kg' => $minHealthyKg,
                'max_kg' => $maxHealthyKg,
                'min_lbs' => round($minHealthyKg * 2.20462, 1),
                'max_lbs' => round($maxHealthyKg * 2.20462, 1),
            ],
            'input' => [
                'unit_system' => $unit,
                'height' => $height,
                'weight' => $weight,
            ],
        ], executionTimeMs: $executionTimeMs);
    }
}
