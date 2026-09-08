<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class ApiTesterTool implements ToolContract
{
    public function slug(): string
    {
        return 'api-tester';
    }

    public function name(): string
    {
        return 'REST API Tester & HTTP Client';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Send HTTP requests (GET, POST, PUT, PATCH, DELETE) to REST endpoints, inspect response headers, latency, status codes, and JSON bodies with SSRF protection.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'url' => ['required', 'url', 'max:1000'],
            'method' => ['sometimes', 'string', 'in:GET,POST,PUT,PATCH,DELETE,HEAD'],
            'headers' => ['sometimes', 'string', 'max:3000'],
            'body' => ['sometimes', 'string', 'max:50000'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $url = trim((string) ($input['url'] ?? ''));
        $method = mb_strtoupper(trim((string) ($input['method'] ?? 'GET')));
        $rawHeaders = trim((string) ($input['headers'] ?? ''));
        $body = (string) ($input['body'] ?? '');

        // 1. SSRF Safeguard Check
        if ($this->isPrivateOrReservedHost($url)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Security Alert: Requests to private, loopback, internal network addresses (localhost, 127.0.0.1, 10.x, 192.168.x) are strictly prohibited.', $executionTimeMs);
        }

        // 2. Parse Headers
        $headersList = ['User-Agent: TechHub-Api-Tester/2.0'];
        if ( ! empty($rawHeaders)) {
            // Check if JSON
            $jsonHeaders = json_decode($rawHeaders, true);
            if (is_array($jsonHeaders)) {
                foreach ($jsonHeaders as $k => $v) {
                    $headersList[] = "{$k}: {$v}";
                }
            } else {
                $headerLines = explode("\n", $rawHeaders);
                foreach ($headerLines as $hLine) {
                    $hLine = trim($hLine);
                    if ( ! empty($hLine) && str_contains($hLine, ':')) {
                        $headersList[] = $hLine;
                    }
                }
            }
        }

        // 3. cURL Execution
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headersList);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        if (in_array($method, ['POST', 'PUT', 'PATCH'], true) && ! empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $rawResponse = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $totalTime = (float) curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        $contentType = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        if (false === $rawResponse || ! empty($curlError)) {
            return ToolResult::failure('HTTP Request failed: ' . ($curlError ?: 'Connection timeout or host unreachable.'), $executionTimeMs);
        }

        $responseHeaders = trim(mb_substr((string) $rawResponse, 0, $headerSize));
        $responseBody = mb_substr((string) $rawResponse, $headerSize);

        // Format body if JSON
        $isJson = str_contains(mb_strtolower($contentType), 'json');
        $formattedBody = $responseBody;
        if ($isJson) {
            $decoded = json_decode($responseBody, true);
            if (null !== $decoded) {
                $formattedBody = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        return ToolResult::success([
            'result' => $formattedBody,
            'status_code' => $httpCode,
            'status_text' => $this->getStatusText($httpCode),
            'latency_ms' => (int) round($totalTime * 1000),
            'content_type' => $contentType,
            'is_json' => $isJson,
            'response_headers' => $responseHeaders,
            'body_size_bytes' => mb_strlen($responseBody),
        ], executionTimeMs: $executionTimeMs);
    }

    private function isPrivateOrReservedHost(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (empty($host)) {
            return true;
        }

        if (in_array(mb_strtolower($host), ['localhost', '127.0.0.1', '::1', '0.0.0.0'], true)) {
            return true;
        }

        $ip = gethostbyname($host);
        if ($ip === $host && ! filter_var($ip, FILTER_VALIDATE_IP)) {
            return false; // could not resolve
        }

        return ! filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE,
        );
    }

    private function getStatusText(int $code): string
    {
        return match ($code) {
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Unprocessable Content',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            default => 'HTTP ' . $code,
        };
    }
}
