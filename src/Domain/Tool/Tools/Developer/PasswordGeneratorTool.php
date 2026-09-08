<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class PasswordGeneratorTool implements ToolContract
{
    public function slug(): string
    {
        return 'password-generator';
    }

    public function name(): string
    {
        return 'Strong Password Generator & Entropy Analyzer';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Generate cryptographically strong, random passwords with custom lengths, character pools, ambiguous character filters, and Shannon entropy analysis.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'length' => ['sometimes', 'integer', 'min:6', 'max:64'],
            'include_uppercase' => ['sometimes', 'boolean'],
            'include_lowercase' => ['sometimes', 'boolean'],
            'include_numbers' => ['sometimes', 'boolean'],
            'include_symbols' => ['sometimes', 'boolean'],
            'exclude_ambiguous' => ['sometimes', 'boolean'],
            'count' => ['sometimes', 'integer', 'min:1', 'max:10'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $length = (int) ($input['length'] ?? 16);
        $length = max(6, min(64, $length));

        $incUpper = (bool) ($input['include_uppercase'] ?? true);
        $incLower = (bool) ($input['include_lowercase'] ?? true);
        $incNum = (bool) ($input['include_numbers'] ?? true);
        $incSym = (bool) ($input['include_symbols'] ?? true);
        $excludeAmbiguous = (bool) ($input['exclude_ambiguous'] ?? false);
        $count = (int) ($input['count'] ?? 1);
        $count = max(1, min(10, $count));

        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $num = '0123456789';
        $sym = '!@#$%^&*()-_=+[]{}|;:,.<>?';

        if ($excludeAmbiguous) {
            $ambiguous = ['0', 'O', 'o', 'l', '1', 'I', '|', '`', "'", '"'];
            $upper = str_replace($ambiguous, '', $upper);
            $lower = str_replace($ambiguous, '', $lower);
            $num = str_replace($ambiguous, '', $num);
            $sym = str_replace($ambiguous, '', $sym);
        }

        $charPool = '';
        $guaranteed = [];

        if ($incUpper && ! empty($upper)) {
            $charPool .= $upper;
            $guaranteed[] = $upper[random_int(0, mb_strlen($upper) - 1)];
        }
        if ($incLower && ! empty($lower)) {
            $charPool .= $lower;
            $guaranteed[] = $lower[random_int(0, mb_strlen($lower) - 1)];
        }
        if ($incNum && ! empty($num)) {
            $charPool .= $num;
            $guaranteed[] = $num[random_int(0, mb_strlen($num) - 1)];
        }
        if ($incSym && ! empty($sym)) {
            $charPool .= $sym;
            $guaranteed[] = $sym[random_int(0, mb_strlen($sym) - 1)];
        }

        if (empty($charPool)) {
            $charPool = $lower . $num;
            $guaranteed[] = $lower[0];
        }

        $poolSize = mb_strlen($charPool);
        // Shannon entropy = Length * log2(poolSize)
        $entropyBits = round($length * (log($poolSize) / log(2)), 1);

        $strengthLabel = match (true) {
            $entropyBits >= 80 => 'Very Strong (Quá mạnh mẽ)',
            $entropyBits >= 60 => 'Strong (Mạnh)',
            $entropyBits >= 40 => 'Fair (Khá)',
            default => 'Weak (Yếu)',
        };

        $passwords = [];
        for ($c = 0; $c < $count; $c++) {
            $pwChars = $guaranteed;
            while (count($pwChars) < $length) {
                $pwChars[] = $charPool[random_int(0, $poolSize - 1)];
            }
            // Shuffle securely
            for ($i = count($pwChars) - 1; $i > 0; $i--) {
                $j = random_int(0, $i);
                $temp = $pwChars[$i];
                $pwChars[$i] = $pwChars[$j];
                $pwChars[$j] = $temp;
            }
            $passwords[] = implode('', $pwChars);
        }

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => 1 === count($passwords) ? $passwords[0] : implode("\n", $passwords),
            'passwords' => $passwords,
            'primary_password' => $passwords[0],
            'length' => $length,
            'entropy_bits' => $entropyBits,
            'strength_label' => $strengthLabel,
            'pool_size' => $poolSize,
        ], executionTimeMs: $executionTimeMs);
    }
}
