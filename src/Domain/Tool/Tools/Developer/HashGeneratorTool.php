<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use Illuminate\Support\Facades\Hash;

class HashGeneratorTool implements ToolContract
{
    public function slug(): string
    {
        return 'hash-generator';
    }

    public function name(): string
    {
        return 'Hash & Checksum Generator';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Generate cryptographic hashes (MD5, SHA-1, SHA-256, SHA-512, Bcrypt) with HMAC secret key support.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'text' => ['required', 'string'],
            'algorithm' => ['sometimes', 'string', 'in:all,md5,sha1,sha256,sha512,bcrypt'],
            'secret_key' => ['nullable', 'string'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $text = (string) ($input['text'] ?? '');
        $algorithm = (string) ($input['algorithm'] ?? 'all');
        $secretKey = ! empty($input['secret_key']) ? (string) $input['secret_key'] : null;

        $results = [];

        if (null !== $secretKey) {
            // HMAC hashing
            if ('all' === $algorithm || 'md5' === $algorithm) {
                $results['hmac_md5'] = hash_hmac('md5', $text, $secretKey);
            }
            if ('all' === $algorithm || 'sha1' === $algorithm) {
                $results['hmac_sha1'] = hash_hmac('sha1', $text, $secretKey);
            }
            if ('all' === $algorithm || 'sha256' === $algorithm) {
                $results['hmac_sha256'] = hash_hmac('sha256', $text, $secretKey);
            }
            if ('all' === $algorithm || 'sha512' === $algorithm) {
                $results['hmac_sha512'] = hash_hmac('sha512', $text, $secretKey);
            }
        } else {
            // Standard hashing
            if ('all' === $algorithm || 'md5' === $algorithm) {
                $results['md5'] = md5($text);
            }
            if ('all' === $algorithm || 'sha1' === $algorithm) {
                $results['sha1'] = sha1($text);
            }
            if ('all' === $algorithm || 'sha256' === $algorithm) {
                $results['sha256'] = hash('sha256', $text);
            }
            if ('all' === $algorithm || 'sha512' === $algorithm) {
                $results['sha512'] = hash('sha512', $text);
            }
            if ('all' === $algorithm || 'bcrypt' === $algorithm) {
                $results['bcrypt'] = Hash::make($text);
            }
        }

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'hashes' => $results,
            'input_length' => mb_strlen($text),
        ], executionTimeMs: $executionTimeMs);
    }
}
