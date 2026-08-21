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
                            <label for="setting_{{ $setting->key }}" class="form-label" style="margin-bottom: 0;">
                                {{ $setting->label }}
                            </label>
                            <code style="font-size: 0.75rem; color: var(--text-muted);">{{ $setting->key }}</code>
                        </div>

                        @if($setting->type === 'textarea')
                            <textarea id="setting_{{ $setting->key }}" 
                                      name="{{ $setting->key }}" 
                                      class="form-control" 
                                      style="min-height: 90px;">{{ $setting->value }}</textarea>
                        @elseif($setting->type === 'boolean')
                            <select id="setting_{{ $setting->key }}" name="{{ $setting->key }}" class="form-control" style="max-width: 200px;">
                                <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>● Bật / Kích Hoạt</option>
                                <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>○ Tắt / Vô Hiệu Hóa</option>
                            </select>
                        @else
                            <input type="text" 
                                   id="setting_{{ $setting->key }}" 
                                   name="{{ $setting->key }}" 
                                   class="form-control" 
                                   value="{{ $setting->value }}">
                        @endif

                        @if($setting->description)
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.3rem;">
                                {{ $setting->description }}
                            </small>
                        @endif
                    </div>
                @endforeach
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

@endsection
