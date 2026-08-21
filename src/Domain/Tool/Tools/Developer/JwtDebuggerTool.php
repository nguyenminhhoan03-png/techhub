<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;
use JsonException;

class JwtDebuggerTool implements ToolContract
{
    public function slug(): string
    {
        return 'jwt-debugger';
    }

    public function name(): string
    {
        return 'JWT Debugger & Decoder';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Decode, inspect, and verify JSON Web Token (JWT) header, payload claims, and expiration status.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'token' => ['required', 'string'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $token = trim((string) ($input['token'] ?? ''));

        $parts = explode('.', $token);
        if (3 !== count($parts)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Invalid JWT format: Token must have exactly 3 segments separated by dots.', $executionTimeMs);
        }

        try {
            $headerJson = $this->base64UrlDecode($parts[0]);
            $payloadJson = $this->base64UrlDecode($parts[1]);

            /** @var array<string, mixed> $header */
            $header = json_decode($headerJson, true, 512, JSON_THROW_ON_ERROR);
            /** @var array<string, mixed> $payload */
            $payload = json_decode($payloadJson, true, 512, JSON_THROW_ON_ERROR);

            $issuedAt = isset($payload['iat']) && is_numeric($payload['iat'])
                ? date('Y-m-d H:i:s T', (int) $payload['iat'])
                : null;

            $expiresAt = isset($payload['exp']) && is_numeric($payload['exp'])
                ? date('Y-m-d H:i:s T', (int) $payload['exp'])
                : null;

            $isExpired = isset($payload['exp']) && is_numeric($payload['exp'])
                ? time() > (int) $payload['exp']
                : null;

            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::success([
                'header' => $header,
                'payload' => $payload,
                'signature_hash' => $parts[2],
                'algorithm' => $header['alg'] ?? 'unknown',
                'issued_at' => $issuedAt,
                'expires_at' => $expiresAt,
                'is_expired' => $isExpired,
            ], executionTimeMs: $executionTimeMs);

        } catch (JsonException $e) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Failed to parse JWT JSON claims: ' . $e->getMessage(), $executionTimeMs);
        }
    }

    private function base64UrlDecode(string $input): string
    {
        $remainder = mb_strlen($input) % 4;
        if ($remainder) {
            $padLen = 4 - $remainder;
            $input .= str_repeat('=', $padLen);
        }

        $decoded = base64_decode(strtr($input, '-_', '+/'), true);

        return false !== $decoded ? $decoded : '';
    }
}
