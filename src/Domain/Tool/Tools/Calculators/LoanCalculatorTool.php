<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Calculators;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class LoanCalculatorTool implements ToolContract
{
    public function slug(): string
    {
        return 'loan-calculator';
    }

    public function name(): string
    {
        return 'Loan & Mortgage Amortization Calculator';
    }

    public function categorySlug(): string
    {
        return 'calculators';
    }

    public function summary(): string
    {
        return 'Calculate monthly EMI, total interest, overall loan cost, and full amortization payment schedule.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'principal' => ['required', 'numeric', 'min:100', 'max:100000000000'],
            'annual_interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'term_months' => ['required', 'integer', 'min:1', 'max:600'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $principal = (float) ($input['principal'] ?? 0);
        $annualRate = (float) ($input['annual_interest_rate'] ?? 0);
        $months = (int) ($input['term_months'] ?? 12);

        $monthlyRate = ($annualRate / 100) / 12;

        if (0.0 === $monthlyRate) {
            $monthlyPayment = $principal / $months;
            $totalPayment = $principal;
            $totalInterest = 0.0;
        } else {
            // Standard Equated Monthly Installment (EMI) formula: P * r * (1 + r)^n / ((1 + r)^n - 1)
            $factor = pow(1 + $monthlyRate, $months);
            $monthlyPayment = $principal * ($monthlyRate * $factor) / ($factor - 1);
            $totalPayment = $monthlyPayment * $months;
            $totalInterest = $totalPayment - $principal;
        }

        // Generate Amortization schedule (first 12 months preview + yearly breakdown)
        $schedule = [];
        $balance = $principal;

        for ($m = 1; $m <= $months; $m++) {
            $interestForMonth = $balance * $monthlyRate;
            $principalForMonth = $monthlyPayment - $interestForMonth;
            $balance = max(0.0, $balance - $principalForMonth);

            if ($m <= 12 || $m === $months || 0 === $m % 12) {
                $schedule[] = [
                    'month' => $m,
                    'payment' => round($monthlyPayment, 2),
                    'principal_paid' => round($principalForMonth, 2),
                    'interest_paid' => round($interestForMonth, 2),
                    'remaining_balance' => round($balance, 2),
                ];
            }
        }

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'principal' => round($principal, 2),
            'annual_interest_rate' => $annualRate,
            'term_months' => $months,
            'term_years' => round($months / 12, 1),
            'monthly_payment' => round($monthlyPayment, 2),
            'total_payment' => round($totalPayment, 2),
            'total_interest' => round($totalInterest, 2),
            'interest_to_principal_ratio' => $principal > 0 ? round(($totalInterest / $principal) * 100, 2) : 0,
            'amortization_preview' => $schedule,
        ], executionTimeMs: $executionTimeMs);
    }
}
