<?php

declare(strict_types=1);

namespace Presentation\Admin\Controllers;

use Application\Setting\Services\SettingService;
use Domain\Setting\Entities\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Presentation\Controller;

class AdminSettingController extends Controller
{
    public function index(): View
    {
        $defaultAiSettings = [
            'ai_default_provider' => [
                'value' => env('AI_DEFAULT_PROVIDER', 'openai'),
                'group' => 'ai',
                'type' => 'select',
                'label' => 'Nhà Cung Cấp AI Mặc Định',
                'description' => 'Chọn OpenAI / Cổng Proxy trung gian (như vilao.ai) hoặc Google Gemini AI Studio',
            ],
            'openai_api_url' => [
                'value' => env('OPENAI_API_URL', 'https://api.vilao.ai/v1'),
                'group' => 'ai',
                'type' => 'text',
                'label' => 'OpenAI Base URL / Cổng Proxy API (vilao.ai)',
                'description' => 'Mặc định là https://api.openai.com/v1. Nếu dùng dịch vụ trung gian (như vilao.ai), hãy điền Base URL: https://api.vilao.ai/v1',
            ],
            'openai_api_key' => [
                'value' => env('OPENAI_API_KEY', ''),
                'group' => 'ai',
                'type' => 'text',
                'label' => 'OpenAI / Proxy API Key (sk-...)',
                'description' => 'Khóa API mua trên vilao.ai (dạng sk-...) hoặc OpenAI chính hãng',
            ],
            'ai_model_name' => [
                'value' => env('AI_MODEL_NAME', 'ram/gemini-3.6-flash-high'),
                'group' => 'ai',
                'type' => 'text',
                'label' => 'Tên Model AI',
                'description' => 'Tên model đã mua/đăng ký (ví dụ: ram/gemini-3.6-flash-high, gpt-4o-mini, gemini-1.5-flash)',
            ],
            'gemini_api_key' => [
                'value' => env('GEMINI_API_KEY', ''),
                'group' => 'ai',
                'type' => 'text',
                'label' => 'Google Gemini API Key (Chính hãng)',
                'description' => 'Khóa API Google Gemini chính hãng (bắt đầu bằng AIza...) nếu không dùng Proxy',
            ],
            'ai_auto_publish' => [
                'value' => '1',
                'group' => 'ai',
                'type' => 'boolean',
                'label' => 'Tự Động Xuất Bản Bài Viết Sau Khi Sinh',
                'description' => 'Bật để bài viết xuất bản (Published) ngay lập tức, tắt để lưu ở dạng Bản nháp (Draft)',
            ],
        ];

        foreach ($defaultAiSettings as $key => $data) {
            Setting::query()->firstOrCreate(['key' => $key], $data);
        }

        $settings = Setting::query()->orderBy('group')->orderBy('id')->get()->groupBy('group');

        return view('admin.settings.index', [
            'settingsGrouped' => $settings,
        ]);
    }

    /**
     * Test AI connection dynamically directly from Admin UI without running any commands.
     */
    public function testAiConnection(Request $request): \Illuminate\Http\JsonResponse
    {
        $provider = (string) ($request->has('ai_default_provider') ? $request->input('ai_default_provider') : SettingService::get('ai_default_provider', 'openai'));
        $openaiKey = trim((string) ($request->has('openai_api_key') ? $request->input('openai_api_key') : SettingService::get('openai_api_key', '')));
        $geminiKey = trim((string) ($request->has('gemini_api_key') ? $request->input('gemini_api_key') : SettingService::get('gemini_api_key', '')));
        $baseUrl = trim((string) ($request->has('openai_api_url') ? $request->input('openai_api_url') : SettingService::get('openai_api_url', 'https://api.vilao.ai/v1')));
        $model = trim((string) ($request->has('ai_model_name') ? $request->input('ai_model_name') : SettingService::get('ai_model_name', 'ram/gemini-3.6-flash-high')));

        // Smart route: If key in gemini_api_key starts with sk-
        if (empty($openaiKey) && ! empty($geminiKey) && str_starts_with($geminiKey, 'sk-')) {
            $openaiKey = $geminiKey;
            $provider = 'openai';
        }

        $startTime = hrtime(true);

        if ($provider === 'openai' || ! empty($openaiKey)) {
            if (empty($openaiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng nhập API Key (sk-...) trước khi kiểm tra kết nối.',
                ], 422);
            }

            $baseUrl = rtrim($baseUrl, '/');
            if (empty($baseUrl)) {
                $baseUrl = 'https://api.vilao.ai/v1';
            }
            if (! str_starts_with($baseUrl, 'http://') && ! str_starts_with($baseUrl, 'https://')) {
                $baseUrl = 'https://' . $baseUrl;
            }

            $url = str_ends_with($baseUrl, '/chat/completions') ? $baseUrl : "{$baseUrl}/chat/completions";

            try {
                $response = \Illuminate\Support\Facades\Http::timeout(25)
                    ->withToken($openaiKey)
                    ->post($url, [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => 'Xin chao! Hay tra loi dung 4 chu ngan gon: Ket noi TechHub OK!'],
                        ],
                        'max_tokens' => 50,
                        'temperature' => 0.3,
                    ]);

                $latencyMs = round((hrtime(true) - $startTime) / 1e6);

                if ($response->successful()) {
                    $data = $response->json();
                    $reply = $data['choices'][0]['message']['content'] ?? 'Phản hồi thành công!';

                    return response()->json([
                        'success' => true,
                        'message' => "Kết nối thành công tới {$baseUrl}! Model '{$model}' phản hồi ({$latencyMs}ms): \"{$reply}\"",
                        'latency_ms' => $latencyMs,
                    ]);
                }

                $errorMsg = $response->json('error.message') ?? $response->json('message') ?? $response->body();

                return response()->json([
                    'success' => false,
                    'message' => "Lỗi HTTP {$response->status()}: {$errorMsg}",
                ], 400);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi kết nối tới Gateway: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Test Google Gemini
        if (empty($geminiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập Google Gemini API Key trước khi kiểm tra.',
            ], 422);
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$geminiKey}";

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(25)->post($url, [
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => 'Xin chao! Hay tra loi dung 4 chu ngan gon: Ket noi TechHub OK!']]],
                ],
            ]);

            $latencyMs = round((hrtime(true) - $startTime) / 1e6);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Phản hồi thành công!';

                return response()->json([
                    'success' => true,
                    'message' => "Kết nối Google Gemini thành công! Model '{$model}' phản hồi ({$latencyMs}ms): \"{$reply}\"",
                    'latency_ms' => $latencyMs,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "Lỗi HTTP {$response->status()}: " . ($response->json('error.message') ?? $response->body()),
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối Gemini: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request): RedirectResponse
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            Setting::query()->where('key', $key)->update([
                'value' => is_array($value) ? json_encode($value) : (string) $value,
            ]);
        }

        // Clear settings cache immediately
        SettingService::clearCache();

        return redirect()->route('admin.settings.index')->with('success', 'Đã lưu toàn bộ cấu hình hệ thống thành công.');
    }
}
