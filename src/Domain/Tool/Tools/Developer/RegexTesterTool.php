<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use ErrorException;

class RegexTesterTool implements ToolContract
{
    public function slug(): string
    {
        return 'regex-tester';
    }

    public function name(): string
    {
        return 'Regex Tester & Match Extractor';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Test and debug Regular Expressions with live match extraction, group capture, and error explanations.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'pattern' => ['required', 'string'],
            'test_text' => ['required', 'string'],
            'flags' => ['sometimes', 'string'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $pattern = (string) ($input['pattern'] ?? '');
        $testText = (string) ($input['test_text'] ?? '');
        $flags = (string) ($input['flags'] ?? 'gmi');

        // Normalize delimiters if user didn't provide them
        $firstChar = mb_substr($pattern, 0, 1);
        $lastChar = mb_substr($pattern, -1);

        if ('/' === $firstChar && 0 !== mb_strrpos($pattern, '/')) {
            $regex = $pattern;
        } else {
            // Filter valid PHP regex flags (i, m, s, x, u, A, D, U)
            $cleanFlags = preg_replace('/[^imsxuADU]/', '', $flags);
            $regex = '/' . addcslashes($pattern, '/') . '/' . $cleanFlags;
        }

        try {
            // Capture any regex warning/error
            set_error_handler(function (int $severity, string $message): void {
                throw new ErrorException($message);
            });

            $matches = [];
            $matchCount = preg_match_all($regex, $testText, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

            restore_error_handler();

            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            $formattedMatches = [];
            if ($matchCount > 0) {
                foreach ($matches as $index => $matchGroup) {
                    $groupList = [];
                    foreach ($matchGroup as $groupIndex => $captured) {
                        $groupList[] = [
                            'group' => $groupIndex,
                            'match' => $captured[0],
                            'offset' => $captured[1],
                            'length' => mb_strlen($captured[0]),
                        ];
                    }
                    $formattedMatches[] = [
                        'match_number' => $index + 1,
                        'full_match' => $matchGroup[0][0],
                        'offset' => $matchGroup[0][1],
                        'groups' => $groupList,
                    ];
                }
            }

            return ToolResult::success([
                'pattern_used' => $regex,
                'is_match' => $matchCount > 0,
                'total_matches' => $matchCount,
                'matches' => $formattedMatches,
            ], executionTimeMs: $executionTimeMs);

        } catch (ErrorException $e) {
            restore_error_handler();
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Regex Compilation Error: ' . $e->getMessage(), $executionTimeMs);
        }
    }
}
