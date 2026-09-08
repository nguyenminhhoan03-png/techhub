<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Carbon\CarbonImmutable;
use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use Exception;

class TimestampConverterTool implements ToolContract
{
    public function slug(): string
    {
        return 'timestamp-converter';
    }

    public function name(): string
    {
        return 'Unix Epoch Timestamp Converter';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Convert between Unix timestamps (seconds & milliseconds) and human-readable dates across multiple timezones with relative time calculations.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'mode' => ['sometimes', 'string', 'in:epoch_to_date,date_to_epoch'],
            'timestamp' => ['sometimes', 'nullable'],
            'datetime_string' => ['sometimes', 'string', 'max:100'],
            'timezone' => ['sometimes', 'string', 'max:50'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $mode = (string) ($input['mode'] ?? 'epoch_to_date');
        $tz = (string) ($input['timezone'] ?? 'Asia/Ho_Chi_Minh');

        try {
            if ('date_to_epoch' === $mode) {
                $rawDate = trim((string) ($input['datetime_string'] ?? ''));
                if (empty($rawDate)) {
                    $date = CarbonImmutable::now($tz);
                } else {
                    $date = CarbonImmutable::parse($rawDate, $tz);
                }

                $epochSec = $date->getTimestamp();
                $epochMs = (int) round($date->valueOf());

                $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

                return ToolResult::success([
                    'result' => (string) $epochSec,
                    'mode' => 'date_to_epoch',
                    'epoch_seconds' => $epochSec,
                    'epoch_milliseconds' => $epochMs,
                    'input_date' => $date->toIso8601String(),
                    'timezone' => $tz,
                    'utc' => $date->setTimezone('UTC')->toDateTimeString(),
                    'relative' => $date->diffForHumans(),
                ], executionTimeMs: $executionTimeMs);
            }

            // Mode: epoch_to_date
            $tsVal = $input['timestamp'] ?? null;
            if (null === $tsVal || '' === trim((string) $tsVal)) {
                $ts = CarbonImmutable::now()->getTimestamp();
            } else {
                $ts = (int) $tsVal;
            }

            // Detect milliseconds (greater than year 3000 in seconds: > 32503680000)
            $isMs = $ts > 32503680000;
            $seconds = $isMs ? (int) round($ts / 1000) : $ts;

            $dateLocal = CarbonImmutable::createFromTimestamp($seconds, $tz);
            $dateUtc = CarbonImmutable::createFromTimestamp($seconds, 'UTC');

            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::success([
                'result' => $dateLocal->toDateTimeString(),
                'mode' => 'epoch_to_date',
                'epoch_seconds' => $seconds,
                'epoch_milliseconds' => $seconds * 1000,
                'local_time' => $dateLocal->toDateTimeString() . " ({$tz})",
                'utc_time' => $dateUtc->toDateTimeString() . ' (UTC)',
                'iso_8601' => $dateLocal->toIso8601String(),
                'rfc_2822' => $dateLocal->toRfc2822String(),
                'relative_time' => $dateLocal->diffForHumans(),
                'day_of_week' => $dateLocal->format('l'),
                'day_of_year' => $dateLocal->dayOfYear,
                'week_number' => $dateLocal->weekOfYear,
                'is_leap_year' => $dateLocal->isLeapYear(),
            ], executionTimeMs: $executionTimeMs);

        } catch (Exception $e) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Invalid timestamp or date format: ' . $e->getMessage(), $executionTimeMs);
        }
    }
}
