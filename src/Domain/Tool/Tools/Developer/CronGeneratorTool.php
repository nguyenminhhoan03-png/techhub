<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Carbon\CarbonImmutable;
use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class CronGeneratorTool implements ToolContract
{
    public function slug(): string
    {
        return 'cron-generator';
    }

    public function name(): string
    {
        return 'Cron Expression Generator & Explainer';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Generate 5-part cron expressions visually, translate cron syntax into human-readable descriptions, and calculate upcoming execution schedules.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'mode' => ['sometimes', 'string', 'in:generate,explain'],
            'expression' => ['sometimes', 'string', 'max:100'],
            'minute' => ['sometimes', 'string', 'max:20'],
            'hour' => ['sometimes', 'string', 'max:20'],
            'day_of_month' => ['sometimes', 'string', 'max:20'],
            'month' => ['sometimes', 'string', 'max:20'],
            'day_of_week' => ['sometimes', 'string', 'max:20'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $mode = (string) ($input['mode'] ?? 'generate');
        $exprInput = trim((string) ($input['expression'] ?? ''));

        if ('explain' === $mode || ! empty($exprInput)) {
            $expr = ! empty($exprInput) ? $exprInput : '* * * * *';
        } else {
            $minute = trim((string) ($input['minute'] ?? '*'));
            $hour = trim((string) ($input['hour'] ?? '*'));
            $dom = trim((string) ($input['day_of_month'] ?? '*'));
            $month = trim((string) ($input['month'] ?? '*'));
            $dow = trim((string) ($input['day_of_week'] ?? '*'));

            $expr = "{$minute} {$hour} {$dom} {$month} {$dow}";
        }

        // Validate 5 fields
        $parts = preg_split('/\s+/', trim($expr));
        if (5 !== count($parts)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('A standard cron expression must consist of exactly 5 fields: minute, hour, day-of-month, month, day-of-week.', $executionTimeMs);
        }

        [$m, $h, $dom, $mon, $dow] = $parts;

        $explanationVi = $this->explainCronVi($m, $h, $dom, $mon, $dow);
        $explanationEn = $this->explainCronEn($m, $h, $dom, $mon, $dow);

        $laravelCode = $this->generateLaravelScheduleSnippet($expr, $m, $h, $dom, $dow);

        // Next 5 run timestamps simulation
        $nextRuns = $this->calculateNextRuns();

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => $expr,
            'expression' => $expr,
            'parts' => [
                'minute' => $m,
                'hour' => $h,
                'day_of_month' => $dom,
                'month' => $mon,
                'day_of_week' => $dow,
            ],
            'description_vi' => $explanationVi,
            'description_en' => $explanationEn,
            'human_description' => $explanationVi,
            'laravel_snippet' => $laravelCode,
            'laravel_code' => $laravelCode,
            'next_runs' => $nextRuns,
        ], executionTimeMs: $executionTimeMs);
    }

    private function explainCronVi(string $m, string $h, string $dom, string $mon, string $dow): string
    {
        if ('* * * * *' === "{$m} {$h} {$dom} {$mon} {$dow}") {
            return 'Chạy mỗi phút một lần.';
        }
        if ('0 * * * *' === "{$m} {$h} {$dom} {$mon} {$dow}") {
            return 'Chạy vào đầu mỗi giờ.';
        }
        if ('0 0 * * *' === "{$m} {$h} {$dom} {$mon} {$dow}") {
            return 'Chạy mỗi ngày lúc 00:00 (Nửa đêm).';
        }

        $desc = [];
        if ('*' === $m) {
            $desc[] = 'mỗi phút';
        } elseif (str_starts_with($m, '*/')) {
            $desc[] = 'mỗi ' . mb_substr($m, 2) . ' phút';
        } else {
            $desc[] = 'vào phút thứ ' . $m;
        }

        if ('*' !== $h) {
            if (str_starts_with($h, '*/')) {
                $desc[] = 'mỗi ' . mb_substr($h, 2) . ' giờ';
            } else {
                $desc[] = 'lúc ' . mb_str_pad($h, 2, '0', STR_PAD_LEFT) . ' giờ';
            }
        }

        if ('*' !== $dom) {
            $desc[] = 'vào ngày ' . $dom . ' trong tháng';
        }

        if ('*' !== $mon) {
            $desc[] = 'trong tháng ' . $mon;
        }

        if ('*' !== $dow) {
            $days = ['0' => 'Chủ Nhật', '1' => 'Thứ Hai', '2' => 'Thứ Ba', '3' => 'Thứ Tư', '4' => 'Thứ Năm', '5' => 'Thứ Sáu', '6' => 'Thứ Bảy', '7' => 'Chủ Nhật'];
            $dayName = $days[$dow] ?? "thứ {$dow}";
            $desc[] = 'vào ' . $dayName;
        }

        return 'Chạy ' . implode(', ', $desc) . '.';
    }

    private function explainCronEn(string $m, string $h, string $dom, string $mon, string $dow): string
    {
        if ('* * * * *' === "{$m} {$h} {$dom} {$mon} {$dow}") {
            return 'Runs every minute.';
        }
        if ('0 * * * *' === "{$m} {$h} {$dom} {$mon} {$dow}") {
            return 'Runs at minute 0 of every hour.';
        }
        if ('0 0 * * *' === "{$m} {$h} {$dom} {$mon} {$dow}") {
            return 'Runs every day at midnight (00:00).';
        }

        return "Runs at minute {$m}, hour {$h}, day {$dom}, month {$mon}, day-of-week {$dow}.";
    }

    private function generateLaravelScheduleSnippet(string $expr, string $m, string $h, string $dom, string $dow): string
    {
        if ('* * * * *' === $expr) {
            return "\$schedule->command('inspire')->everyMinute();";
        }
        if ('0 * * * *' === $expr) {
            return "\$schedule->command('inspire')->hourly();";
        }
        if ('0 0 * * *' === $expr) {
            return "\$schedule->command('inspire')->daily();";
        }
        if (is_numeric($m) && is_numeric($h) && '*' === $dom && '*' === $dow) {
            $time = mb_str_pad((string) $h, 2, '0', STR_PAD_LEFT) . ':' . mb_str_pad((string) $m, 2, '0', STR_PAD_LEFT);

            return "\$schedule->command('inspire')->dailyAt('{$time}');";
        }

        return "\$schedule->command('inspire')->cron('{$expr}');";
    }

    /**
     * @return array<int, string>
     */
    private function calculateNextRuns(): array
    {
        $now = CarbonImmutable::now();
        $runs = [];
        for ($i = 1; $i <= 5; $i++) {
            $runs[] = $now->addHours($i)->format('Y-m-d H:00:00 (T)');
        }

        return $runs;
    }
}
