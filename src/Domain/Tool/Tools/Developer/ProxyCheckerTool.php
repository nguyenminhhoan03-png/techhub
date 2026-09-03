<?php

declare(strict_types=1);

namespace Domain\Tool\Tools\Developer;

use Domain\Tool\Contracts\ToolContract;
use Domain\Tool\Enums\ToolEngineType;
use Domain\Tool\ValueObjects\ToolResult;

class ProxyCheckerTool implements ToolContract
{
    public function slug(): string
    {
        return 'proxy-checker';
    }

    public function name(): string
    {
        return 'Kiểm Tra Proxy (HTTP, HTTPS, SOCKS4, SOCKS5)';
    }

    public function categorySlug(): string
    {
        return 'developer';
    }

    public function summary(): string
    {
        return 'Kiểm tra trạng thái sống/chết (Live/Dead), tốc độ phản hồi (Latency), địa chỉ IP đầu ra, quốc gia và mức độ ẩn danh của Proxy đơn lẻ hoặc danh sách hàng loạt.';
    }

    public function engineType(): ToolEngineType
    {
        return ToolEngineType::ServerSync;
    }

    public function validationRules(): array
    {
        return [
            'proxies' => ['required', 'string', 'max:10000'],
            'protocol' => ['sometimes', 'string', 'in:auto,http,https,socks4,socks5'],
            'timeout' => ['sometimes', 'integer', 'min:1', 'max:15'],
        ];
    }

    public function execute(array $input): ToolResult
    {
        $startTime = hrtime(true);
        $rawProxies = (string) ($input['proxies'] ?? '');
        $defaultProtocol = (string) ($input['protocol'] ?? 'auto');
        $timeout = (int) ($input['timeout'] ?? 5);
        $timeout = max(1, min(15, $timeout));

        $lines = preg_split('/\r\n|\r|\n/', $rawProxies);
        if (false === $lines) {
            $lines = [];
        }

        // Limit to max 20 proxies per batch check to keep execution fast and prevent timeouts
        $proxyList = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ('' === $trimmed || str_starts_with($trimmed, '#') || str_starts_with($trimmed, '//')) {
                continue;
            }
            $proxyList[] = $trimmed;
            if (count($proxyList) >= 20) {
                break;
            }
        }

        $isVi = (class_exists(\Illuminate\Support\Facades\Facade::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication())
            ? \Illuminate\Support\Facades\App::getLocale() === 'vi'
            : true;

        if (empty($proxyList)) {
            return ToolResult::failure($isVi
                ? 'Vui lòng nhập ít nhất một địa chỉ Proxy hợp lệ (Định dạng: IP:Port hoặc IP:Port:User:Pass).'
                : 'Please enter at least one valid Proxy address (Format: IP:Port or IP:Port:User:Pass).');
        }

        $results = [];
        $liveCount = 0;
        $deadCount = 0;
        $totalLatency = 0;
        $liveProxiesText = [];

        foreach ($proxyList as $proxyStr) {
            $parsed = $this->parseProxyString($proxyStr, $defaultProtocol);
            if ( ! $parsed) {
                $results[] = [
                    'raw' => $proxyStr,
                    'proxy' => $proxyStr,
                    'status' => 'dead',
                    'protocol' => 'UNKNOWN',
                    'latency_ms' => 0,
                    'exit_ip' => null,
                    'country' => 'Unknown',
                    'country_code' => '',
                    'city' => '',
                    'isp' => '',
                    'anonymity' => 'Unknown',
                    'has_auth' => false,
                    'error' => $isVi ? 'Định dạng Proxy không hợp lệ (Cần IP:Port hoặc protocol://...)' : 'Invalid Proxy format (Required IP:Port or protocol://...)',
                ];
                $deadCount++;

                continue;
            }

            $checkResult = $this->checkSingleProxy($parsed, $timeout);
            $results[] = $checkResult;

            if ('live' === $checkResult['status']) {
                $liveCount++;
                $totalLatency += $checkResult['latency_ms'];
                $liveProxiesText[] = $proxyStr;
            } else {
                $deadCount++;
            }
        }

        $avgLatency = $liveCount > 0 ? (int) round($totalLatency / $liveCount) : 0;
        $executionTimeMs = (int) round((hrtime(true) - $startTime) / 1e+6);

        return ToolResult::success([
            'total' => count($results),
            'live_count' => $liveCount,
            'dead_count' => $deadCount,
            'avg_latency_ms' => $avgLatency,
            'live_proxies_text' => implode("\n", $liveProxiesText),
            'results' => $results,
        ], executionTimeMs: $executionTimeMs);
    }

    /**
     * Parse various proxy string formats.
     * Supported:
     * - protocol://user:pass@host:port
     * - protocol://host:port
     * - host:port:user:pass
     * - host:port
     *
     * @return array{protocol: string, host: string, port: int, user: ?string, pass: ?string}|null
     */
    private function parseProxyString(string $proxyStr, string $fallbackProtocol): ?array
    {
        $proxyStr = trim($proxyStr);

        // 1. Format: protocol://[user:pass@]host:port
        if (preg_match('/^(https?|socks4|socks5):\/\//i', $proxyStr)) {
            $parsedUrl = parse_url($proxyStr);
            if (false === $parsedUrl || empty($parsedUrl['host']) || empty($parsedUrl['port'])) {
                return null;
            }

            return [
                'protocol' => mb_strtolower((string) ($parsedUrl['scheme'] ?? 'http')),
                'host' => (string) $parsedUrl['host'],
                'port' => (int) $parsedUrl['port'],
                'user' => isset($parsedUrl['user']) ? (string) $parsedUrl['user'] : null,
                'pass' => isset($parsedUrl['pass']) ? (string) $parsedUrl['pass'] : null,
            ];
        }

        // 2. Format: host:port:user:pass or host:port
        $parts = explode(':', $proxyStr);
        $count = count($parts);

        if ($count < 2) {
            return null;
        }

        $host = trim($parts[0]);
        $port = (int) trim($parts[1]);

        if ('' === $host || $port <= 0 || $port > 65535) {
            return null;
        }

        $user = null;
        $pass = null;

        if ($count >= 4) {
            $user = trim($parts[2]);
            $pass = trim($parts[3]);
        }

        $protocol = $fallbackProtocol;
        if ('auto' === $protocol) {
            // Heuristic port check
            if (1080 === $port || 1081 === $port || 9050 === $port || 9150 === $port) {
                $protocol = 'socks5';
            } else {
                $protocol = 'http';
            }
        }

        return [
            'protocol' => mb_strtolower($protocol),
            'host' => $host,
            'port' => $port,
            'user' => $user ?: null,
            'pass' => $pass ?: null,
        ];
    }

    /**
     * Test single proxy connectivity and measure latency via cURL.
     *
     * @param array{protocol: string, host: string, port: int, user: ?string, pass: ?string} $parsed
     * @return array<string, mixed>
     */
    private function checkSingleProxy(array $parsed, int $timeout): array
    {
        $proxyAddress = $parsed['host'] . ':' . $parsed['port'];
        $protocol = mb_strtolower($parsed['protocol']);
        $hasAuth = ! empty($parsed['user']);

        $ch = curl_init();

        // Target lightweight endpoint
        curl_setopt($ch, CURLOPT_URL, 'http://api.ipify.org?format=json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

        // Proxy destination
        curl_setopt($ch, CURLOPT_PROXY, $proxyAddress);

        // Proxy Protocol Type
        $curlProxyType = match ($protocol) {
            'socks5' => CURLPROXY_SOCKS5,
            'socks4' => CURLPROXY_SOCKS4,
            'https' => CURLPROXY_HTTPS,
            default => CURLPROXY_HTTP,
        };
        curl_setopt($ch, CURLOPT_PROXYTYPE, $curlProxyType);

        // Proxy Authentication
        if ($hasAuth) {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $parsed['user'] . ':' . ($parsed['pass'] ?? ''));
        }

        // Disable strict SSL verify on proxy tunnel
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $startReq = hrtime(true);
        $response = curl_exec($ch);
        $latencyMs = (int) round((hrtime(true) - $startReq) / 1e+6);

        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErrno = curl_errno($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if (0 === $curlErrno && $httpCode >= 200 && $httpCode < 400 && is_string($response) && '' !== $response) {
            $json = json_decode($response, true);
            $exitIp = is_array($json) && isset($json['ip']) ? (string) $json['ip'] : null;

            $geo = $this->getGeoLocation($exitIp ?: $parsed['host']);

            $anonymity = 'Transparent (L3)';
            if ($exitIp) {
                if ($exitIp === $parsed['host']) {
                    $anonymity = 'Elite / High Anonymity (L1)';
                } else {
                    $anonymity = 'Anonymous (L2)';
                }
            }

            return [
                'raw' => ($hasAuth ? $parsed['user'] . ':***@' : '') . $proxyAddress,
                'proxy' => $proxyAddress,
                'status' => 'live',
                'protocol' => mb_strtoupper($protocol),
                'latency_ms' => $latencyMs,
                'exit_ip' => $exitIp ?: $parsed['host'],
                'country' => $geo['country'],
                'country_code' => $geo['country_code'],
                'city' => $geo['city'],
                'isp' => $geo['isp'],
                'anonymity' => $anonymity,
                'has_auth' => $hasAuth,
                'error' => null,
            ];
        }

        // Connection failed
        $isVi = (class_exists(\Illuminate\Support\Facades\Facade::class) && \Illuminate\Support\Facades\Facade::getFacadeApplication())
            ? \Illuminate\Support\Facades\App::getLocale() === 'vi'
            : true;
        $errorMessage = match ($curlErrno) {
            CURLE_OPERATION_TIMEDOUT => $isVi ? "Hết thời gian chờ (Timeout > {$timeout}s)" : "Operation timed out (> {$timeout}s)",
            CURLE_COULDNT_CONNECT => $isVi ? 'Không thể kết nối đến Proxy (Connection Refused)' : 'Could not connect to proxy (Connection Refused)',
            CURLE_COULDNT_RESOLVE_PROXY => $isVi ? 'Không thể phân giải tên miền Proxy (DNS Failed)' : 'Could not resolve proxy host (DNS Failed)',
            default => 407 === $httpCode
                ? ($isVi ? 'Lỗi xác thực: Sai User/Pass hoặc Proxy yêu cầu đăng nhập (407 Proxy Auth Required)' : 'Authentication error: Invalid User/Pass or Auth required (407)')
                : ($curlError ?: ($isVi ? 'Lỗi kết nối HTTP ' : 'HTTP connection error ') . $httpCode),
        };

        return [
            'raw' => ($hasAuth ? $parsed['user'] . ':***@' : '') . $proxyAddress,
            'proxy' => $proxyAddress,
            'status' => 'dead',
            'protocol' => mb_strtoupper($protocol),
            'latency_ms' => $latencyMs,
            'exit_ip' => null,
            'country' => 'Unknown',
            'country_code' => '',
            'city' => '',
            'isp' => '',
            'anonymity' => 'Unknown',
            'has_auth' => $hasAuth,
            'error' => $errorMessage,
        ];
    }

    /**
     * Fast GeoIP query via ip-api.com.
     *
     * @return array{country: string, country_code: string, city: string, isp: string}
     */
    private function getGeoLocation(string $ip): array
    {
        $default = [
            'country' => 'Unknown',
            'country_code' => '',
            'city' => '',
            'isp' => '',
        ];

        // Validate IP to avoid injection
        if ( ! filter_var($ip, FILTER_VALIDATE_IP)) {
            return $default;
        }

        $ch = curl_init("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,city,isp");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        $res = curl_exec($ch);
        curl_close($ch);

        if (is_string($res) && '' !== $res) {
            $data = json_decode($res, true);
            if (is_array($data) && 'success' === ($data['status'] ?? '')) {
                return [
                    'country' => (string) ($data['country'] ?? 'Unknown'),
                    'country_code' => (string) ($data['countryCode'] ?? ''),
                    'city' => (string) ($data['city'] ?? ''),
                    'isp' => (string) ($data['isp'] ?? ''),
                ];
            }
        }

        return $default;
    }
}
