<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class HttpStatusCheckerTool implements ToolContract
{
    public function slug(): string
    {
        return 'http-status-checker';
    }

    public function name(): string
    {
        return 'HTTP Status Code & Redirect Chain Checker';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Inspect server HTTP status codes, trace 301/302 redirect loops and hop chains, and verify SSL certificate validity.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'url' => ['required', 'url', 'max:1000'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $url = trim((string) ($input['url'] ?? ''));

        // SSRF Check
        if ($this->isPrivateOrReservedHost($url)) {
            $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

            return ToolResult::failure('Cannot inspect internal or loopback network addresses.', $executionTimeMs);
        }

        $currentUrl = $url;
        $hops = [];
        $maxHops = 10;
        $totalTime = 0.0;

        for ($i = 0; $i < $maxHops; $i++) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $currentUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) TechHub-HTTP-Checker/2.0');

            $response = curl_exec($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $redirectUrl = (string) curl_getinfo($ch, CURLINFO_REDIRECT_URL);
            $hopTime = (float) curl_getinfo($ch, CURLINFO_TOTAL_TIME);
            $primaryIp = (string) curl_getinfo($ch, CURLINFO_PRIMARY_IP);
            $curlError = curl_error($ch);
            curl_close($ch);

            $totalTime += $hopTime;

            if (false === $response && ! empty($curlError)) {
                $hops[] = [
                    'hop' => $i + 1,
                    'url' => $currentUrl,
                    'status_code' => 0,
                    'status_text' => 'Failed: ' . $curlError,
                    'ip' => $primaryIp,
                    'time_ms' => (int) round($hopTime * 1000),
                ];
                break;
            }

            $hops[] = [
                'hop' => $i + 1,
                'url' => $currentUrl,
                'status_code' => $httpCode,
                'status_text' => $this->getStatusText($httpCode),
                'ip' => $primaryIp,
                'time_ms' => (int) round($hopTime * 1000),
            ];

            // If redirect (301, 302, 303, 307, 308) and has next URL
            if ($httpCode >= 300 && $httpCode < 400 && ! empty($redirectUrl)) {
                // SSRF check on next hop
                if ($this->isPrivateOrReservedHost($redirectUrl)) {
                    break;
                }
                $currentUrl = $redirectUrl;
            } else {
                break;
            }
        }

        $lastHop = end($hops);
        $finalStatus = $lastHop['status_code'];

        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'result' => "Checked {$url}: Final HTTP {$finalStatus} ({$this->getStatusText($finalStatus)}) across " . count($hops) . ' hop(s).',
            'target_url' => $url,
            'final_status' => $finalStatus,
            'final_status_text' => $this->getStatusText($finalStatus),
            'hops_count' => count($hops),
            'total_latency_ms' => (int) round($totalTime * 1000),
            'hops' => $hops,
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
            return false;
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
            200 => '200 OK',
            201 => '201 Created',
            204 => '204 No Content',
            301 => '301 Moved Permanently',
            302 => '302 Found (Temporary Redirect)',
            307 => '307 Temporary Redirect',
            308 => '308 Permanent Redirect',
            400 => '400 Bad Request',
            401 => '401 Unauthorized',
            403 => '403 Forbidden',
            404 => '404 Not Found',
            405 => '405 Method Not Allowed',
            429 => '429 Too Many Requests',
            500 => '500 Internal Server Error',
            502 => '502 Bad Gateway',
            503 => '503 Service Unavailable',
            504 => '504 Gateway Timeout',
            default => 'HTTP ' . $code,
        };
    }
}
