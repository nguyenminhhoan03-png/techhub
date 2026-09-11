@extends('admin.layouts.app')

@section('title', 'Cấu Hình & Văn Bản Động')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1>Cấu Hình <span class="gradient-text">Hệ Thống &amp; Văn Bản Động</span></h1>
    <p style="margin-top: 0.25rem;">Chỉnh sửa trực tiếp tiêu đề, đoạn văn bản, thông báo khẩn và các thông số hiển thị ngoài website mà không cần sửa code.</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

    @foreach($settingsGrouped as $groupName => $settings)
        <div class="tool-panel" style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.85rem; margin-bottom: 1.5rem;">
                <h3 style="color: var(--text-main); text-transform: capitalize;">
                    @if($groupName === 'hero') <x-heroicon-o-sparkles style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Khối Hero Trang Chủ
                    @elseif($groupName === 'announcement') <x-heroicon-o-megaphone style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Thanh Thông Báo Khẩn (Top Banner)
                    @elseif($groupName === 'general') <x-heroicon-o-globe-alt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Cấu Hình Chung
                    @elseif($groupName === 'ai') <x-heroicon-o-cpu-chip style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle; color: var(--accent-indigo);" /> <span style="color: var(--accent-indigo); font-weight: 800;">Cấu Hình AI Content Engine &amp; LLM API</span>
                    @elseif($groupName === 'contact') <x-heroicon-o-envelope style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Liên Hệ &amp; Hỗ Trợ
                    @else <x-heroicon-o-cog-6-tooth style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nhóm: {{ $groupName }}
                    @endif
                </h3>
                <span class="badge">{{ count($settings) }} cài đặt</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach($settings as $setting)
                    <div class="form-group" style="margin-bottom: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <label for="setting_{{ $setting->key }}" class="form-label" style="margin-bottom: 0; font-weight: 600;">
                                {{ $setting->label }}
                            </label>
                            <code style="font-size: 0.75rem; color: var(--text-muted); background: var(--bg-surface); padding: 2px 6px; border-radius: 4px;">{{ $setting->key }}</code>
                        </div>

                        @if($setting->type === 'textarea')
                            <textarea id="setting_{{ $setting->key }}" 
                                      name="{{ $setting->key }}" 
                                      class="form-control" 
                                      style="min-height: 90px;">{{ $setting->value }}</textarea>
                        @elseif($setting->type === 'boolean')
                            <select id="setting_{{ $setting->key }}" name="{{ $setting->key }}" class="form-control" style="max-width: 220px;">
                                <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>● Bật / Kích Hoạt</option>
                                <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>○ Tắt / Vô Hiệu Hóa</option>
                            </select>
                        @elseif($setting->key === 'ai_default_provider')
                            <select id="setting_{{ $setting->key }}" name="{{ $setting->key }}" class="form-control" style="max-width: 450px;">
                                <option value="openai" {{ $setting->value === 'openai' ? 'selected' : '' }}>OpenAI / Cổng Proxy API (vilao.ai, rcloud, openrouter...)</option>
                                <option value="gemini" {{ $setting->value === 'gemini' ? 'selected' : '' }}>Google Gemini AI Studio (Chính hãng)</option>
                            </select>
                        @else
                            <input type="text" 
                                   id="setting_{{ $setting->key }}" 
                                   name="{{ $setting->key }}" 
                                   class="form-control" 
                                   value="{{ $setting->value }}"
                                   @if($setting->key === 'openai_api_url') placeholder="https://api.vilao.ai/v1"
                                   @elseif($setting->key === 'ai_model_name') placeholder="ram/gemini-3.6-flash-high"
                                   @elseif($setting->key === 'openai_api_key') placeholder="sk-f85a...b4ea"
                                   @elseif($setting->key === 'gemini_api_key') placeholder="AIzaSy..."
                                   @endif>
                        @endif

                        @if($setting->description)
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem; line-height: 1.4;">
                                {{ $setting->description }}
                            </small>
                        @endif
                    </div>
                @endforeach

                @if($groupName === 'ai')
                    <div style="margin-top: 1rem; padding: 1.25rem; background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: var(--radius-md);">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                            <div>
                                <h4 style="margin: 0; color: var(--accent-indigo); font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <x-heroicon-s-bolt style="width: 1.1em; height: 1.1em;" /> Kiểm Tra Trực Tiếp Kết Nối API AI
                                </h4>
                                <p style="margin: 0.25rem 0 0 0; font-size: 0.82rem; color: var(--text-muted);">
                                    Kiểm tra xem Key, Base URL và Model bạn vừa nhập trên giao diện có hoạt động ngay với vilao.ai hay không mà không cần chạy lệnh.
                                </p>
                            </div>
                            <button type="button" id="btn-test-ai" class="btn btn-secondary" style="border-color: var(--accent-indigo); color: var(--accent-indigo); white-space: nowrap;">
                                <x-heroicon-o-arrow-path id="test-ai-icon" style="width: 1.1em; height: 1.1em; display: inline-block; vertical-align: middle;" />
                                <span id="test-ai-text">⚡ Test Kết Nối Ngay</span>
                            </button>
                        </div>
                        <div id="test-ai-result" style="display: none; margin-top: 1rem; padding: 0.85rem 1rem; border-radius: var(--radius-sm); font-size: 0.88rem; line-height: 1.45;"></div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    <div style="position: sticky; bottom: 2rem; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px); padding: 1rem 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-medium); display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow-hover); z-index: 50;">
        <span style="font-size: 0.9rem; color: var(--accent-emerald);">
            <x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Mọi thay đổi sẽ xóa Cache và áp dụng ngay lập tức ra toàn bộ trang web.
        </span>
        <button type="submit" class="btn btn-primary">
            <x-heroicon-o-document-check style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Lưu Toàn Bộ Cấu Hình Hệ Thống
        </button>
    </div>

</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnTestAi = document.getElementById('btn-test-ai');
    const resultBox = document.getElementById('test-ai-result');
    const icon = document.getElementById('test-ai-icon');
    const text = document.getElementById('test-ai-text');

    if (!btnTestAi) return;

    btnTestAi.addEventListener('click', async function () {
        const providerEl = document.getElementById('setting_ai_default_provider');
        const urlEl = document.getElementById('setting_openai_api_url');
        const keyEl = document.getElementById('setting_openai_api_key');
        const modelEl = document.getElementById('setting_ai_model_name');
        const geminiKeyEl = document.getElementById('setting_gemini_api_key');

        const payload = {
            ai_default_provider: providerEl ? providerEl.value : 'openai',
            openai_api_url: urlEl ? urlEl.value : '',
            openai_api_key: keyEl ? keyEl.value : '',
            ai_model_name: modelEl ? modelEl.value : '',
            gemini_api_key: geminiKeyEl ? geminiKeyEl.value : '',
            _token: '{{ csrf_token() }}'
        };

        btnTestAi.disabled = true;
        text.textContent = 'Đang gửi ping kiểm tra tới Gateway...';
        resultBox.style.display = 'block';
        resultBox.style.background = 'rgba(59, 130, 246, 0.08)';
        resultBox.style.border = '1px solid rgba(59, 130, 246, 0.2)';
        resultBox.style.color = 'var(--text-main)';
        resultBox.innerHTML = '⏳ Đang kết nối tới mô hình AI... Vui lòng đợi trong giây lát.';

        try {
            const res = await fetch('{{ route('admin.settings.test_ai') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (data.success) {
                resultBox.style.background = 'rgba(16, 185, 129, 0.12)';
                resultBox.style.border = '1px solid rgba(16, 185, 129, 0.4)';
                resultBox.style.color = '#065f46';
                resultBox.innerHTML = '<strong>✅ KẾT NỐI THÀNH CÔNG!</strong><br>' + data.message;
            } else {
                resultBox.style.background = 'rgba(239, 68, 68, 0.12)';
                resultBox.style.border = '1px solid rgba(239, 68, 68, 0.4)';
                resultBox.style.color = '#991b1b';
                resultBox.innerHTML = '<strong>❌ KẾT NỐI THẤT BẠI:</strong><br>' + (data.message || 'Không thể kết nối tới mô hình.');
            }
        } catch (err) {
            resultBox.style.background = 'rgba(239, 68, 68, 0.12)';
            resultBox.style.border = '1px solid rgba(239, 68, 68, 0.4)';
            resultBox.style.color = '#991b1b';
            resultBox.innerHTML = '<strong>❌ LỖI TRÌNH DUYỆT:</strong><br>' + err.message;
        } finally {
            btnTestAi.disabled = false;
            text.textContent = '⚡ Test Kết Nối Ngay';
        }
    });
});
</script>
@endpush

@endsection
