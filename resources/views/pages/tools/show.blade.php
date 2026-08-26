@php
    $displayTitle = $tool->display_meta_title;
    $displayDesc = $tool->display_meta_description;

    // Extract FAQs dynamically from Markdown for Google FAQPage Rich Schema
    $faqItems = [];
    if (!empty($tool->display_description_markdown)) {
        if (preg_match_all('/###\s*(?:\d+[\.\)]\s*)?([^\n\r\?]+\?)\s*\n+([^#\n\r][^\n\r]+(?:\n+[^#\n\r][^\n\r]+)*)/u', $tool->display_description_markdown, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $q = trim($match[1]);
                $a = trim(strip_tags(\Illuminate\Support\Str::markdown($match[2])));
                if (!empty($q) && !empty($a)) {
                    $faqItems[] = ['question' => $q, 'answer' => $a];
                }
            }
        }
    }
@endphp
@extends('layouts.app')

@section('meta_title', $displayTitle)
@section('meta_description', $displayDesc)
@section('canonical_url', url('/tools/' . $tool->slug))
@section('meta_keywords', $tool->display_name . ', ' . ($tool->category?->display_name ?? 'công cụ online') . ', ' . $tool->slug . ', công cụ trực tuyến miễn phí, online tools, TechHub, ' . ($tool->category?->name ?? ''))
@section('og_image', asset('images/techhub-og.png'))

@push('head')
{{-- SoftwareApplication Schema — enriched for Google rich results --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "{{ $tool->display_name }}",
  "url": "{{ url('/tools/' . $tool->slug) }}",
  "description": "{{ addslashes($displayDesc) }}",
  "operatingSystem": "Web Browser, Windows, macOS, Linux, iOS, Android",
  "browserRequirements": "Requires JavaScript. Works on Chrome, Firefox, Safari, Edge.",
  "applicationCategory": "{{ match($tool->category?->slug ?? '') {
    'seo' => 'WebApplication',
    'developer', 'dev' => 'DeveloperApplication',
    'finance', 'calculator', 'calculators' => 'FinanceApplication',
    'image', 'media' => 'MultimediaApplication',
    default => 'UtilitiesApplication'
  } }}",
  "inLanguage": ["vi", "en"],
  "isAccessibleForFree": true,
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "VND",
    "availability": "https://schema.org/InStock"
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url('/tools/' . $tool->slug) }}"
  }@if($tool->rating_count > 0),
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ $tool->rating_avg }}",
    "ratingCount": "{{ $tool->rating_count }}",
    "bestRating": "5",
    "worstRating": "1"
  }
  @endif
}
</script>

{{-- FAQPage Schema (Google Rich Snippets) --}}
@if(!empty($faqItems))
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @foreach($faqItems as $index => $faq)
    {
      "@type": "Question",
      "name": "{{ addslashes($faq['question']) }}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ addslashes($faq['answer']) }}"
      }
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endif

{{-- BreadcrumbList Schema --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "{{ __('home') }}",
      "item": "{{ url('/') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "{{ __('tools_hub') }}",
      "item": "{{ url('/tools') }}"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{ $tool->category?->display_name ?? 'Tools' }}",
      "item": "{{ url('/tools?category=' . ($tool->category?->slug ?? '')) }}"
    },
    {
      "@type": "ListItem",
      "position": 4,
      "name": "{{ $tool->display_name }}",
      "item": "{{ url('/tools/' . $tool->slug) }}"
    }
  ]
}
</script>
@endpush

@section('content')
<section style="padding: 3rem 0;">
    <div class="container">
        
        {{-- Breadcrumb Navigation --}}
        <div class="breadcrumb">
            <a href="{{ url('/') }}">{{ __('home') }}</a>
            <span>/</span>
            <a href="{{ url('/tools') }}">{{ __('tools_hub') }}</a>
            <span>/</span>
            @if($tool->category)
                <a href="{{ url('/tools?category=' . $tool->category->slug) }}">{{ $tool->category->display_name }}</a>
                <span>/</span>
            @endif
            <span style="color: var(--text-main);">{{ $tool->display_name }}</span>
        </div>

        {{-- Workspace Header --}}
        <div style="margin-bottom: 2.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
                <span class="badge">{{ $tool->category?->display_name ?? 'Utility' }}</span>
                <span class="badge badge-emerald">★ {{ number_format((float)$tool->rating_avg, 2) }}</span>
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    {{ __('executions_count', ['count' => number_format($tool->execution_count)]) }}
                </span>
            </div>
            <h1>{{ $tool->display_name }}</h1>
            <p style="font-size: 1.12rem; margin-top: 0.5rem; color: var(--text-sub);">{{ $tool->display_summary }}</p>
        </div>

        {{-- Main Workspace Grid --}}
        <div class="workspace-grid">
            
            {{-- Left: Interactive Tool Playground --}}
            <div class="tool-panel">
                <form id="tool-execution-form" data-tool-slug="{{ $tool->slug }}">
                    @csrf

                    {{-- 1. JSON FORMATTER --}}
                    @if($tool->slug === 'json-formatter')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="json-input" class="form-label" style="margin-bottom: 0;">Chuỗi JSON cần xử lý</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-json"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp JSON Mẫu</button>
                            </div>
                            <textarea id="json-input" name="json" class="form-control" style="min-height: 140px; font-family: var(--font-mono);" placeholder='{"hello": "world", "status": true, "version": 2.0}' required></textarea>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            <div>
                                <label for="json-action" class="form-label" style="margin-bottom: 0.25rem;">Hành động</label>
                                <select id="json-action" name="action" class="form-control" style="width: 180px;">
                                    <option value="beautify">Beautify (Làm đẹp)</option>
                                    <option value="minify">Nén gọn (Minify)</option>
                                    <option value="validate">Chỉ kiểm tra lỗi (Validate)</option>
                                </select>
                            </div>
                            <div>
                                <label for="json-indent" class="form-label" style="margin-bottom: 0.25rem;">Thụt dòng (Indent)</label>
                                <select id="json-indent" name="indent_size" class="form-control" style="width: 130px;">
                                    <option value="2">2 Spaces</option>
                                    <option value="4">4 Spaces</option>
                                </select>
                            </div>
                            <div style="margin-top: 1.5rem;">
                                <button type="submit" class="btn btn-primary">{{ __('run_tool') }}</button>
                            </div>
                        </div>

                    {{-- 2. BASE64 ENCODER & DECODER --}}
                    @elseif($tool->slug === 'base64-encode-decode')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="base64-text" class="form-label" style="margin-bottom: 0;">Chuỗi văn bản hoặc Base64 cần xử lý</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-base64"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp Văn Bản Mẫu</button>
                            </div>
                            <textarea id="base64-text" name="text" class="form-control" style="min-height: 130px; font-family: var(--font-mono);" placeholder="Nhập văn bản tiếng Việt hoặc chuỗi Base64..." required></textarea>
                        </div>

                        <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="action" value="encode" checked>
                                <span>Mã hóa sang Base64 (Encode)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="action" value="decode">
                                <span>Giải mã Base64 (Decode)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="url_safe" value="1">
                                <span>URL-Safe (RFC 4648)</span>
                            </label>
                            <button type="submit" class="btn btn-primary" style="margin-left: auto;">{{ __('run_tool') }}</button>
                        </div>

                    {{-- 3. HASH GENERATOR --}}
                    @elseif($tool->slug === 'hash-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="hash-text" class="form-label" style="margin-bottom: 0;">Chuỗi văn bản cần băm (Input string)</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-hash"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp Mẫu</button>
                            </div>
                            <textarea id="hash-text" name="text" class="form-control" style="min-height: 110px; font-family: var(--font-mono);" placeholder="Nhập chuỗi văn bản hoặc mật khẩu cần băm..." required></textarea>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="hash-alg" class="form-label">Thuật toán băm (Algorithm)</label>
                                <select id="hash-alg" name="algorithm" class="form-control">
                                    <option value="all">Tất cả thuật toán (MD5, SHA1, SHA256, SHA512, Bcrypt)</option>
                                    <option value="sha256">SHA-256 (Khuyên dùng cho bảo mật)</option>
                                    <option value="md5">MD5 Checksum</option>
                                    <option value="sha1">SHA-1</option>
                                    <option value="sha512">SHA-512</option>
                                    <option value="bcrypt">Bcrypt Password Hash</option>
                                </select>
                            </div>
                            <div>
                                <label for="hash-secret" class="form-label">Khóa bí mật HMAC (Tùy chọn)</label>
                                <input type="text" id="hash-secret" name="secret_key" class="form-control" placeholder="Để trống nếu không dùng HMAC...">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-lock-closed style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Tạo Mã Băm Ngay</button>

                    {{-- 4. JWT DEBUGGER --}}
                    @elseif($tool->slug === 'jwt-debugger')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="jwt-token" class="form-label" style="margin-bottom: 0;">Mã JSON Web Token (JWT)</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-jwt"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Tải Token JWT Mẫu</button>
                            </div>
                            <textarea id="jwt-token" name="token" class="form-control" style="min-height: 120px; font-family: var(--font-mono); font-size: 0.85rem;" placeholder="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlY2hIdWIiLCJpYXQiOjE1MTYyMzkwMjJ9..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-magnifying-glass style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Giải Mã &amp; Kiểm Tra Token</button>

                    {{-- 5. REGEX TESTER --}}
                    @elseif($tool->slug === 'regex-tester')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="regex-pattern" class="form-label" style="margin-bottom: 0;">Biểu thức chính quy (Regular Expression Pattern)</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-regex"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp Regex Email Mẫu</button>
                            </div>
                            <div style="display: flex; gap: 0.75rem; align-items: center;">
                                <span style="font-family: var(--font-mono); font-weight: 700; font-size: 1.2rem; color: var(--text-muted);">/</span>
                                <input type="text" id="regex-pattern" name="pattern" class="form-control" style="font-family: var(--font-mono);" placeholder="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" value="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required>
                                <span style="font-family: var(--font-mono); font-weight: 700; font-size: 1.2rem; color: var(--text-muted);">/</span>
                                <input type="text" id="regex-flags" name="flags" class="form-control" style="width: 80px; font-family: var(--font-mono);" placeholder="gmi" value="gmi">
                            </div>
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem;">Cờ thông dụng: <code>g</code> (global), <code>i</code> (case-insensitive), <code>m</code> (multiline), <code>s</code> (dotAll)</small>
                        </div>

                        <div class="form-group">
                            <label for="regex-test-text" class="form-label">Đoạn văn bản cần kiểm tra khớp (Test String)</label>
                            <textarea id="regex-test-text" name="test_text" class="form-control" style="min-height: 120px; font-family: var(--font-mono);" placeholder="Nhập văn bản cần tìm kiếm khớp regex..." required>Liên hệ chúng tôi tại contact@techhub.vn hoặc admin@techhub.local để được hỗ trợ.</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Kiểm Tra Khớp Regex</button>

                    {{-- 6. URL ENCODER & DECODER --}}
                    @elseif($tool->slug === 'url-encoder-decoder')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="url-input" class="form-label" style="margin-bottom: 0;">Đường dẫn URL hoặc chuỗi tham số</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-url"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp URL Mẫu</button>
                            </div>
                            <textarea id="url-input" name="url" class="form-control" style="min-height: 120px; font-family: var(--font-mono);" placeholder="https://techhub.vn/search?q=công cụ lập trình & ddd=true#tools" required></textarea>
                        </div>

                        <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="action" value="encode" checked>
                                <span>Mã hóa URL (Encode)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="action" value="decode">
                                <span>Giải mã URL (Decode)</span>
                            </label>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-left: auto;">
                                <label for="url-standard" class="form-label" style="margin-bottom: 0;">Chuẩn:</label>
                                <select id="url-standard" name="standard" class="form-control" style="width: 160px;">
                                    <option value="rfc3986">RFC 3986 (%20)</option>
                                    <option value="legacy">Legacy (+ space)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('run_tool') }}</button>
                        </div>

                    {{-- 7. LOAN CALCULATOR --}}
                    @elseif($tool->slug === 'loan-calculator')
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label for="loan-principal" class="form-label">Số tiền vay (VNĐ)</label>
                                <input type="number" id="loan-principal" name="principal" class="form-control" value="500000000" min="1000000" step="10000000" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">Ví dụ: 500,000,000 đ</small>
                            </div>
                            <div class="form-group">
                                <label for="loan-rate" class="form-label">Lãi suất năm (%/năm)</label>
                                <input type="number" id="loan-rate" name="annual_interest_rate" class="form-control" value="8.5" min="0.1" step="0.1" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">Ví dụ: 8.5%</small>
                            </div>
                            <div class="form-group">
                                <label for="loan-term" class="form-label">Thời hạn vay (Số tháng)</label>
                                <input type="number" id="loan-term" name="term_months" class="form-control" value="60" min="1" step="6" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">(60 tháng = 5 năm)</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Tính Số Tiền Trả Hàng Tháng (EMI)</button>

                    {{-- 8. PERCENTAGE CALCULATOR --}}
                    @elseif($tool->slug === 'percentage-calculator')
                        <div class="form-group">
                            <label for="pct-mode" class="form-label">Chế độ tính toán phần trăm</label>
                            <select id="pct-mode" name="mode" class="form-control">
                                <option value="percent_of">1. Tính X% của Y là bao nhiêu? (Ví dụ: 20% của 500,000đ)</option>
                                <option value="is_what_percent">2. X là bao nhiêu % của Y? (Ví dụ: 25 là mấy % của 200)</option>
                                <option value="increase_decrease">3. Tỷ lệ % tăng hoặc giảm từ A sang B</option>
                            </select>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="pct-val-a" class="form-label">Giá trị A (X hoặc Giá gốc)</label>
                                <input type="number" id="pct-val-a" name="value_a" class="form-control" value="20" step="any" required>
                            </div>
                            <div class="form-group">
                                <label for="pct-val-b" class="form-label">Giá trị B (Y hoặc Giá mới)</label>
                                <input type="number" id="pct-val-b" name="value_b" class="form-control" value="500000" step="any" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Tính Toán Ngay</button>

                    {{-- 9. BMI CALCULATOR --}}
                    @elseif($tool->slug === 'bmi-calculator')
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label for="bmi-unit" class="form-label">Hệ đo lường</label>
                                <select id="bmi-unit" name="unit_system" class="form-control">
                                    <option value="metric">Chuẩn Quốc Tế (cm, kg)</option>
                                    <option value="imperial">Imperial (inches, lbs)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bmi-height" class="form-label">Chiều cao</label>
                                <input type="number" id="bmi-height" name="height" class="form-control" value="172" step="any" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">cm</small>
                            </div>
                            <div class="form-group">
                                <label for="bmi-weight" class="form-label">Cân nặng</label>
                                <input type="number" id="bmi-weight" name="weight" class="form-control" value="65" step="any" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">kg</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Đánh Giá Chỉ Số BMI</button>

                    {{-- 10. IMAGE METADATA & EXIF --}}
                    @elseif($tool->slug === 'image-metadata-inspector')
                        <div class="form-group">
                            <label class="form-label">Tải lên hình ảnh cần phân tích thông số</label>
                            <div class="dropzone" id="file-dropzone">
                                <span style="font-size: 2.5rem; display: flex; justify-content: center; margin-bottom: 0.5rem;"><x-heroicon-o-photo style="width: 1em; height: 1em;" /></span>
                                <strong style="font-size: 1.05rem; color: var(--text-main);">Nhấn để chọn file ảnh</strong>
                                <span style="color: var(--text-muted); display: block; margin-top: 0.25rem;">hoặc kéo thả hình ảnh trực tiếp vào khung này (JPG, PNG, WEBP, GIF)</span>
                                <input type="file" id="file-input" accept="image/*" style="display: none;">
                                <input type="hidden" name="image_base64" id="image-base64-input" required>
                            </div>
                            <div id="file-preview-wrap" style="display: none; align-items: center; gap: 1rem; margin-top: 1rem; padding: 0.85rem 1.25rem; background: var(--bg-surface-elevated); border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                                <img id="file-preview-thumb" src="" alt="Preview" style="width: 54px; height: 54px; object-fit: cover; border-radius: var(--radius-sm);">
                                <div>
                                    <strong id="file-preview-name" style="color: var(--text-main); font-size: 0.95rem; display: block;"></strong>
                                    <span id="file-preview-size" style="font-size: 0.82rem; color: var(--text-muted);"></span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-magnifying-glass style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Phân Tích Thông Số Ảnh Ngay</button>

                    {{-- 11. IMAGE COLOR PALETTE EXTRACTOR --}}
                    @elseif($tool->slug === 'image-color-extractor')
                        <div class="form-group">
                            <label class="form-label">Tải lên bức ảnh cần trích xuất bảng mã màu</label>
                            <div class="dropzone" id="file-dropzone">
                                <span style="font-size: 2.5rem; display: flex; justify-content: center; margin-bottom: 0.5rem;"><x-heroicon-o-swatch style="width: 1em; height: 1em;" /></span>
                                <strong style="font-size: 1.05rem; color: var(--text-main);">Nhấn để chọn ảnh thiết kế</strong>
                                <span style="color: var(--text-muted); display: block; margin-top: 0.25rem;">hoặc kéo thả hình ảnh vào đây (Hệ thống xử lý an toàn 100% không lưu trữ)</span>
                                <input type="file" id="file-input" accept="image/*" style="display: none;">
                                <input type="hidden" name="image_base64" id="image-base64-input" required>
                            </div>
                            <div id="file-preview-wrap" style="display: none; align-items: center; gap: 1rem; margin-top: 1rem; padding: 0.85rem 1.25rem; background: var(--bg-surface-elevated); border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                                <img id="file-preview-thumb" src="" alt="Preview" style="width: 54px; height: 54px; object-fit: cover; border-radius: var(--radius-sm);">
                                <div>
                                    <strong id="file-preview-name" style="color: var(--text-main); font-size: 0.95rem; display: block;"></strong>
                                    <span id="file-preview-size" style="font-size: 0.82rem; color: var(--text-muted);"></span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
                            <div>
                                <label for="palette-size" class="form-label" style="margin-bottom: 0.25rem;">Số lượng màu trích xuất</label>
                                <select id="palette-size" name="palette_size" class="form-control" style="width: 160px;">
                                    <option value="5" selected>5 màu chủ đạo</option>
                                    <option value="3">3 màu chính</option>
                                    <option value="8">8 dải màu</option>
                                    <option value="10">10 dải màu</option>
                                </select>
                            </div>
                            <div style="margin-top: 1.5rem;">
                                <button type="submit" class="btn btn-primary"><x-heroicon-s-swatch style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Trích Xuất Bảng Màu</button>
                            </div>
                        </div>

                    {{-- 12. GOOGLE SERP PREVIEW --}}
                    @elseif($tool->slug === 'serp-preview')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="serp-title" class="form-label" style="margin-bottom: 0;">Tiêu đề trang (SEO Page Title)</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-serp"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp Mẫu SERP</button>
                            </div>
                            <input type="text" id="serp-title" name="title" class="form-control" placeholder="Ví dụ: Hướng Dẫn Tối Ưu SEO Onpage 2026 Toàn Diện — TechHub" value="Hướng Dẫn Tối Ưu SEO Onpage 2026 Toàn Diện — TechHub" required>
                            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-top: 0.35rem;">
                                <span>Khuyên dùng: 50 - 60 ký tự (~600 pixel)</span>
                                <span id="serp-title-counter" style="font-weight: 600; color: var(--accent-indigo);">0 ký tự</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="serp-desc" class="form-label">Thẻ mô tả (Meta Description)</label>
                            <textarea id="serp-desc" name="description" class="form-control" style="min-height: 90px;" placeholder="Nhập đoạn mô tả hấp dẫn chứa từ khóa chính giúp tăng tỷ lệ nhấp chuột CTR..." required>Khám phá trọn bộ kỹ thuật tối ưu SEO Onpage chuẩn Google: Tối ưu thẻ Meta, cấu trúc Schema JSON-LD, Sitemap XML và tối ưu tốc độ tải trang vượt trội.</textarea>
                            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-top: 0.35rem;">
                                <span>Khuyên dùng: 120 - 160 ký tự (~960 pixel)</span>
                                <span id="serp-desc-counter" style="font-weight: 600; color: var(--accent-indigo);">0 ký tự</span>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="serp-url" class="form-label">Đường dẫn trang web (URL)</label>
                                <input type="url" id="serp-url" name="url" class="form-control" value="https://techhub.vn/kien-thuc/toi-uu-seo-onpage" required>
                            </div>
                            <div>
                                <label for="serp-site-name" class="form-label">Tên website (Tùy chọn)</label>
                                <input type="text" id="serp-site-name" name="site_name" class="form-control" value="TechHub Việt Nam">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; background: var(--bg-surface-elevated); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                            <div>
                                <label for="serp-device" class="form-label">Thiết bị giả lập</label>
                                <select id="serp-device" name="device" class="form-control">
                                    <option value="desktop" selected>💻 Desktop (Máy tính)</option>
                                    <option value="mobile">📱 Mobile (Điện thoại)</option>
                                </select>
                            </div>
                            <div>
                                <label for="serp-date" class="form-label">Ngày xuất bản</label>
                                <input type="text" id="serp-date" name="date" class="form-control" placeholder="21 thg 8, 2026" value="21 thg 8, 2026">
                            </div>
                            <div>
                                <label for="serp-rating-val" class="form-label">Đánh giá sao (Rich Snippet)</label>
                                <input type="number" id="serp-rating-val" name="rating_value" class="form-control" value="4.9" step="0.1" min="1" max="5">
                            </div>
                            <div>
                                <label for="serp-rating-cnt" class="form-label">Số lượt đánh giá</label>
                                <input type="number" id="serp-rating-cnt" name="rating_count" class="form-control" value="128" min="1">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Mô Phỏng Hiển Thị SERP Ngay</button>

                    {{-- 13. META TAG GENERATOR --}}
                    @elseif($tool->slug === 'meta-tag-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="meta-title" class="form-label" style="margin-bottom: 0;">Tiêu đề trang (Title Tag)</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-meta"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp Mẫu Meta</button>
                            </div>
                            <input type="text" id="meta-title" name="title" class="form-control" placeholder="Tiêu đề trang web..." value="TechHub - Nền Tảng Công Cụ Lập Trình &amp; Tiện Ích Trực Tuyến" required>
                        </div>

                        <div class="form-group">
                            <label for="meta-desc" class="form-label">Mô tả trang (Meta Description)</label>
                            <textarea id="meta-desc" name="description" class="form-control" style="min-height: 80px;" placeholder="Đoạn mô tả ngắn gọn về nội dung trang..." required>TechHub cung cấp hơn 20+ công cụ trực tuyến miễn phí dành cho lập trình viên và chuyên gia SEO: Định dạng JSON, Regex, Base64, Schema Generator, SERP Preview.</textarea>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="meta-keywords" class="form-label">Từ khóa (Meta Keywords)</label>
                                <input type="text" id="meta-keywords" name="keywords" class="form-control" value="công cụ lập trình, seo tools, json formatter, schema generator, techhub">
                            </div>
                            <div>
                                <label for="meta-canonical" class="form-label">Canonical URL</label>
                                <input type="url" id="meta-canonical" name="canonical_url" class="form-control" value="https://techhub.vn">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="meta-author" class="form-label">Tác giả / Tổ chức (Author)</label>
                                <input type="text" id="meta-author" name="author" class="form-control" value="TechHub Engineering Team">
                            </div>
                            <div>
                                <label for="meta-lang" class="form-label">Ngôn ngữ trang (Language)</label>
                                <input type="text" id="meta-lang" name="language" class="form-control" value="vi">
                            </div>
                        </div>

                        <div style="background: var(--bg-surface-elevated); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 700;">Chỉ thị Robots (Robots Meta Directives)</label>
                            <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; margin-bottom: 0.75rem;">
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                    <input type="radio" name="robots_index" value="index" checked>
                                    <span>Index (Cho phép lập chỉ mục)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                    <input type="radio" name="robots_index" value="noindex">
                                    <span>Noindex (Chặn lập chỉ mục)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                    <input type="radio" name="robots_follow" value="follow" checked>
                                    <span>Follow (Thu thập liên kết)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                    <input type="radio" name="robots_follow" value="nofollow">
                                    <span>Nofollow (Không thu thập)</span>
                                </label>
                            </div>
                            <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.88rem;">
                                    <input type="checkbox" name="robots_noarchive" value="1">
                                    <span>Noarchive (Không lưu bản sao bộ nhớ đệm)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.88rem;">
                                    <input type="checkbox" name="robots_nosnippet" value="1">
                                    <span>Nosnippet (Không hiển thị trích đoạn)</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Tạo Bộ Thẻ Meta HTML5 Ngay</button>

                    {{-- 14. SCHEMA GENERATOR --}}
                    @elseif($tool->slug === 'schema-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="schema-type" class="form-label" style="margin-bottom: 0;">Loại Schema Dữ Liệu Cấu Trúc</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-schema"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp Schema Mẫu</button>
                            </div>
                            <select id="schema-type" name="schema_type" class="form-control" style="font-weight: 600;">
                                <option value="Article" selected>📰 Article / BlogPosting (Bài viết tin tức, blog)</option>
                                <option value="FAQPage">❓ FAQPage (Trang câu hỏi thường gặp)</option>
                                <option value="Product">🛍️ Product (Sản phẩm &amp; Báo giá, Review)</option>
                                <option value="LocalBusiness">🏢 LocalBusiness (Doanh nghiệp địa phương)</option>
                                <option value="BreadcrumbList">🧭 BreadcrumbList (Thanh điều hướng phân cấp)</option>
                                <option value="SoftwareApplication">💻 SoftwareApplication (Ứng dụng phần mềm)</option>
                                <option value="Organization">🌐 Organization (Tổ chức / Công ty)</option>
                            </select>
                        </div>

                        {{-- Schema Dynamic Subfields Container --}}
                        <div id="schema-subfields-wrap" style="margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label class="form-label">Tiêu đề bài viết / Tên thực thể (Headline / Name)</label>
                                <input type="text" name="headline" class="form-control" value="10 Cách Tối Ưu Tốc Độ Website Với Clean Architecture" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Mô tả tóm tắt (Description)</label>
                                <textarea name="description" class="form-control" style="min-height: 70px;">Hướng dẫn từng bước cách refactor mã nguồn và áp dụng bộ nhớ đệm giúp giảm độ trễ dưới 5ms.</textarea>
                            </div>
                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">URL bài viết / Trang</label>
                                    <input type="url" name="url" class="form-control" value="https://techhub.vn/blog/toi-uu-toc-do-website">
                                </div>
                                <div>
                                    <label class="form-label">Đường dẫn ảnh đại diện (Image URL)</label>
                                    <input type="url" name="image_url" class="form-control" value="https://techhub.vn/images/blog-banner.jpg">
                                </div>
                            </div>
                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">Tên tác giả (Author)</label>
                                    <input type="text" name="author_name" class="form-control" value="TechHub Senior Developer">
                                </div>
                                <div>
                                    <label class="form-label">Tên tổ chức xuất bản (Publisher)</label>
                                    <input type="text" name="publisher_name" class="form-control" value="TechHub">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nội dung câu hỏi FAQ (Dành riêng cho FAQPage: Mỗi dòng Hỏi: / Đáp:)</label>
                                <textarea name="faq_text" class="form-control" style="min-height: 90px; font-family: var(--font-mono); font-size: 0.85rem;" placeholder="Q: Câu hỏi 1?&#10;A: Trả lời câu hỏi 1...&#10;Q: Câu hỏi 2?&#10;A: Trả lời câu hỏi 2...">Hỏi: Schema JSON-LD có tác dụng gì trong SEO?
Đáp: Giúp Google hiểu rõ nội dung và hiển thị Rich Snippets nổi bật trên kết quả tìm kiếm.
Hỏi: TechHub có hỗ trợ tạo Schema miễn phí không?
Đáp: Toàn bộ công cụ tạo dữ liệu cấu trúc trên TechHub đều hoàn toàn miễn phí 100%.</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Tạo Schema JSON-LD Ngay</button>

                    {{-- 15. OPEN GRAPH & TWITTER CARD GENERATOR --}}
                    @elseif($tool->slug === 'open-graph-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="og-title" class="form-label" style="margin-bottom: 0;">Tiêu đề chia sẻ (OG Title)</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-og"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp Mẫu OG</button>
                            </div>
                            <input type="text" id="og-title" name="title" class="form-control" placeholder="Tiêu đề hiển thị khi chia sẻ link..." value="TechHub — Nền Tảng Công Cụ Lập Trình &amp; SEO Trực Tuyến Số 1" required>
                        </div>

                        <div class="form-group">
                            <label for="og-desc" class="form-label">Đoạn mô tả ngắn (OG Description)</label>
                            <textarea id="og-desc" name="description" class="form-control" style="min-height: 80px;" placeholder="Đoạn trích dẫn bài viết khi hiển thị trên newsfeed..." required>Trải nghiệm hơn 20+ tiện ích lập trình, máy tính và công cụ tối ưu SEO Onpage tốc độ cực nhanh, bảo mật tuyệt đối không lưu dữ liệu.</textarea>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="og-image" class="form-label">Đường dẫn ảnh Thumbnail (Khuyên dùng 1200x630px)</label>
                                <input type="url" id="og-image" name="image_url" class="form-control" placeholder="https://example.com/banner-1200x630.png" value="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200&h=630&fit=crop" required>
                            </div>
                            <div>
                                <label for="og-url" class="form-label">Đường dẫn đích (Canonical URL)</label>
                                <input type="url" id="og-url" name="url" class="form-control" value="https://techhub.vn" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <label for="og-site-name" class="form-label">Tên website (Site Name)</label>
                                <input type="text" id="og-site-name" name="site_name" class="form-control" value="TechHub">
                            </div>
                            <div>
                                <label for="og-type" class="form-label">Loại Open Graph (OG Type)</label>
                                <select id="og-type" name="og_type" class="form-control">
                                    <option value="website" selected>website (Trang chủ / Danh mục)</option>
                                    <option value="article">article (Bài viết blog / Tin tức)</option>
                                    <option value="product">product (Sản phẩm thương mại)</option>
                                </select>
                            </div>
                            <div>
                                <label for="twitter-card" class="form-label">Kiểu thẻ Twitter / X</label>
                                <select id="twitter-card" name="twitter_card" class="form-control">
                                    <option value="summary_large_image" selected>summary_large_image (Ảnh lớn)</option>
                                    <option value="summary">summary (Ảnh vuông nhỏ)</option>
                                </select>
                            </div>
                            <div>
                                <label for="twitter-site" class="form-label">Twitter / X Account</label>
                                <input type="text" id="twitter-site" name="twitter_site" class="form-control" placeholder="@techhub_vn" value="@techhub_vn">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Tạo Thẻ Chia Sẻ &amp; Xem Trước Social Card</button>

                    {{-- 16. ROBOTS.TXT GENERATOR --}}
                    @elseif($tool->slug === 'robots-txt-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="robots-preset" class="form-label" style="margin-bottom: 0;">Mẫu cấu hình sẵn (Presets)</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-robots"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp Mẫu Tiêu Chuẩn</button>
                            </div>
                            <select id="robots-preset" name="preset" class="form-control" style="font-weight: 600;">
                                <option value="default" selected>⚡ Cấu hình chuẩn SEO (Mặc định)</option>
                                <option value="allow_all">🟢 Cho phép tất cả Bot thu thập (Allow All)</option>
                                <option value="block_all">🔴 Chặn toàn bộ Bot (Disallow All - Dành cho web đang phát triển)</option>
                                <option value="block_ai_bots">🛡️ Chặn toàn bộ AI Crawlers (OpenAI, Anthropic, CCBot)</option>
                                <option value="wordpress">🌐 Tối ưu riêng cho WordPress</option>
                                <option value="laravel">🚀 Tối ưu riêng cho Laravel Web App</option>
                            </select>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="robots-disallow" class="form-label">Đường dẫn cần CHẶN (Mỗi dòng một mục Disallow)</label>
                                <textarea id="robots-disallow" name="disallow_paths" class="form-control" style="min-height: 110px; font-family: var(--font-mono); font-size: 0.88rem;">/admin/
/login
/api/private/
/telescope/
/tmp/</textarea>
                            </div>
                            <div class="form-group">
                                <label for="robots-allow" class="form-label">Đường dẫn CHO PHÉP (Mỗi dòng một mục Allow)</label>
                                <textarea id="robots-allow" name="allow_paths" class="form-control" style="min-height: 110px; font-family: var(--font-mono); font-size: 0.88rem;">/public/
/css/
/js/
/images/
/assets/</textarea>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="robots-sitemap" class="form-label">Đường dẫn tệp Sitemap XML</label>
                                <input type="url" id="robots-sitemap" name="sitemap_url" class="form-control" value="https://techhub.vn/sitemap.xml">
                            </div>
                            <div>
                                <label for="robots-delay" class="form-label">Crawl-delay (Giây - Tùy chọn)</label>
                                <input type="number" id="robots-delay" name="crawl_delay" class="form-control" placeholder="Để trống nếu không cần" min="0" max="60">
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="block_ai_crawlers" value="1" checked>
                                <span style="font-weight: 600; color: var(--text-main);">🛡️ Tự động bổ sung quy tắc chặn AI Bot &amp; Web Scraper (GPTBot, CCBot, Claude-Web, PerplexityBot)</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Tạo Tệp Robots.txt Ngay</button>

                    {{-- 17. SITEMAP XML GENERATOR (XML-Sitemaps Standard) --}}
                    @elseif($tool->slug === 'sitemap-generator')
                        <div style="text-align: center; margin-bottom: 2rem; padding: 1rem 0;">
                            <h2 style="font-size: 2.2rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.6rem; letter-spacing: -0.5px;">
                                Better Indexing Starts Here
                            </h2>
                            <p style="color: var(--text-sub); font-size: 1.05rem; max-width: 620px; margin: 0 auto; line-height: 1.6;">
                                Tự động quét (crawl) website và tạo tệp XML Sitemap chuẩn Google, Bing & Sitemaps.org. Nhanh chóng, miễn phí 100% và không cần đăng ký.
                            </p>
                        </div>

                        {{-- Main High-Impact Domain Crawler Input Bar --}}
                        <div style="background: var(--bg-card); border: 2px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 0.6rem; box-shadow: 0 12px 36px rgba(0,0,0,0.06); display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.25rem; transition: border-color 0.2s ease;">
                            <div style="display: flex; align-items: center; gap: 0.6rem; flex: 1; min-width: 260px; padding-left: 0.75rem;">
                                <span style="font-size: 1.35rem;">🌐</span>
                                <input 
                                    type="text" 
                                    id="sitemap-base-url" 
                                    name="base_url" 
                                    class="form-control" 
                                    style="border: none; background: transparent; font-size: 1.05rem; padding: 0.6rem 0; box-shadow: none; width: 100%; color: var(--text-main);"
                                    placeholder="Your Website Domain (e.g. https://muabanwebsite.io.vn)..." 
                                    value="{{ request()->root() }}"
                                    required
                                >
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 0.85rem 1.85rem; font-size: 1rem; font-weight: 700; border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
                                <span>🚀</span> <span>Generate Sitemap</span>
                            </button>
                        </div>

                        {{-- Collapsible Settings Toggle --}}
                        <div style="text-align: right; margin-bottom: 1.5rem;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('sitemap-advanced-settings').classList.toggle('hidden-settings');" style="font-size: 0.85rem; padding: 0.35rem 0.85rem; color: var(--text-muted);">
                                ⚙️ Cài Đặt Nâng Cao (Settings ▾)
                            </button>
                        </div>

                        {{-- Collapsible Advanced Settings Panel --}}
                        <div id="sitemap-advanced-settings" class="hidden-settings" style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 1.75rem; transition: all 0.3s ease;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                                <div>
                                    <label for="sitemap-freq" class="form-label" style="font-size: 0.85rem;">Tần suất cập nhật (Changefreq)</label>
                                    <select id="sitemap-freq" name="default_changefreq" class="form-control">
                                        <option value="daily">daily (Hàng ngày)</option>
                                        <option value="weekly" selected>weekly (Hàng tuần)</option>
                                        <option value="monthly">monthly (Hàng tháng)</option>
                                        <option value="always">always (Liên tục)</option>
                                        <option value="hourly">hourly (Mỗi giờ)</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="sitemap-priority" class="form-label" style="font-size: 0.85rem;">Độ ưu tiên mặc định (Priority)</label>
                                    <select id="sitemap-priority" name="default_priority" class="form-control">
                                        <option value="1.0">1.0 (Trang chủ / Cao nhất)</option>
                                        <option value="0.8" selected>0.8 (Trang bài viết / Chi tiết)</option>
                                        <option value="0.6">0.6 (Trang phụ / Tiện ích)</option>
                                        <option value="0.5">0.5 (Trang liên hệ / Giới thiệu)</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="sitemap-max" class="form-label" style="font-size: 0.85rem;">Số trang quét tối đa (Max URLs)</label>
                                    <select id="sitemap-max" name="max_urls" class="form-control">
                                        <option value="50">50 trang</option>
                                        <option value="100" selected>100 trang</option>
                                        <option value="250">250 trang</option>
                                        <option value="500">500 trang</option>
                                    </select>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle);">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.88rem; color: var(--text-main);">
                                    <input type="checkbox" name="include_lastmod" value="1" checked>
                                    <span>Tự động đính kèm ngày sửa đổi (Lastmod)</span>
                                </label>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('sitemap-manual-wrap').style.display = document.getElementById('sitemap-manual-wrap').style.display === 'none' ? 'block' : 'none';" style="font-size: 0.8rem;">
                                    ✍️ Chế độ nhập URL thủ công
                                </button>
                            </div>

                            <div id="sitemap-manual-wrap" style="display: none; margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px dashed var(--border-subtle);">
                                <label for="sitemap-urls" class="form-label" style="font-size: 0.85rem;">Danh sách URL tuỳ chỉnh (Nếu không muốn tự động quét)</label>
                                <textarea id="sitemap-urls" name="urls_list" class="form-control" style="min-height: 110px; font-family: var(--font-mono); font-size: 0.85rem;" placeholder="/\n/tools\n/articles\n/games"></textarea>
                            </div>
                        </div>

                        {{-- Trust Stats Bar (xml-sitemaps.com style) --}}
                        <div style="display: flex; justify-content: center; align-items: center; gap: 2rem; flex-wrap: wrap; margin-bottom: 2rem; color: var(--text-muted); font-size: 0.88rem; font-weight: 500;">
                            <span>⚡ 100% Miễn Phí</span>
                            <span>🕷️ Live Web Crawler</span>
                            <span>🔍 Chuẩn Google & Bing</span>
                            <span>📄 Xuất Tệp sitemap.xml Ngay</span>
                        </div>

                        <style>
                            .hidden-settings { display: none !important; }
                        </style>

                    {{-- 18. SEO SLUG GENERATOR --}}
                    @elseif($tool->slug === 'slug-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="slug-text" class="form-label" style="margin-bottom: 0;">Tiêu đề bài viết / Đoạn văn bản cần tạo Slug</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-slug"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Nạp Tiêu Đề Mẫu</button>
                            </div>
                            <textarea id="slug-text" name="text" class="form-control" style="min-height: 100px;" placeholder="Nhập tiêu đề bài viết tiếng Việt có dấu hoặc ký tự đặc biệt..." required>Hướng Dẫn Toàn Diện Về Cách Tối Ưu Hóa SEO Onpage Cho Website Năm 2026!</textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label for="slug-sep" class="form-label">Ký tự phân cách (Separator)</label>
                                <select id="slug-sep" name="separator" class="form-control">
                                    <option value="-" selected>- Dấu gạch ngang (Chuẩn Google)</option>
                                    <option value="_">_ Dấu gạch dưới (Snake case)</option>
                                </select>
                            </div>
                            <div>
                                <label for="slug-case" class="form-label">Định dạng chữ (Case Format)</label>
                                <select id="slug-case" name="case_format" class="form-control">
                                    <option value="lowercase" selected>lowercase (kebab-case)</option>
                                    <option value="uppercase">UPPERCASE</option>
                                    <option value="camel">camelCase</option>
                                    <option value="pascal">PascalCase</option>
                                    <option value="snake">snake_case</option>
                                </select>
                            </div>
                            <div>
                                <label for="slug-max-len" class="form-label">Độ dài tối đa (Ký tự)</label>
                                <input type="number" id="slug-max-len" name="max_length" class="form-control" value="80" min="20" max="200">
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="remove_stop_words" value="1" checked>
                                <span style="font-weight: 600; color: var(--text-main);">⚡ Tự động loại bỏ từ dừng (Stop Words: và, là, của, cho, ở, in, on, the...) giúp URL ngắn gọn và chuẩn SEO</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> Tạo URL Slug Chuẩn SEO</button>
                        {{-- Generic Fallback Form --}}
                        <div class="form-group">
                            <label class="form-label">Dữ liệu đầu vào</label>
                            <textarea name="input_text" class="form-control" style="min-height: 120px;" placeholder="Nhập dữ liệu..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;">{{ __('run_tool') }}</button>
                    @endif

                    {{-- RICH VISUAL OUTPUT CONTAINER (Rendered by JS) --}}
                    <div id="tool-rich-output" style="display: none; margin-bottom: 1.5rem;"></div>

                    {{-- CODE / RAW TEXTAREA OUTPUT --}}
                    <div class="form-group" id="raw-output-wrap">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label class="form-label" style="margin-bottom: 0;">Kết Quả Xử Lý (Output)</label>
                            <button type="button" class="btn btn-secondary btn-sm" data-copy-target="tool-result-output">{{ __('copy_result') }}</button>
                        </div>
                        <textarea id="tool-result-output" class="form-control code-output" readonly placeholder="Kết quả sẽ hiển thị tại đây sau khi thực thi..."></textarea>
                    </div>

                </form>

                {{-- Dynamic Ad Slot: tool_workspace_bottom --}}
                @php
                    $toolBottomAd = \Application\Ad\Services\AdService::getAdForSlot('tool_workspace_bottom');
                @endphp
                @if($toolBottomAd && (($toolBottomAd->type === 'custom_banner' && $toolBottomAd->image_url) || $toolBottomAd->raw_html))
                    <div style="margin-top: 2rem; border-radius: var(--radius-md); overflow: hidden; text-align: center; position: relative;">
                        @if($toolBottomAd->type === 'custom_banner' && $toolBottomAd->image_url)
                            <a href="{{ $toolBottomAd->target_url ?: '#' }}" target="_blank" rel="nofollow sponsored">
                                <img src="{{ $toolBottomAd->image_url }}" alt="{{ $toolBottomAd->name }}" style="max-width: 100%; height: auto; border-radius: var(--radius-sm); display: block; margin: 0 auto;">
                            </a>
                        @elseif($toolBottomAd->raw_html)
                            {!! $toolBottomAd->raw_html !!}
                        @endif
                    </div>
                @endif
            </div>

            {{-- Right: Tool Metadata & Stats Sidebar --}}
            <div>
                <div class="tool-card" style="margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem;">
                        {{ __('specs_title') }}
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.9rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">{{ __('engine') }}:</span>
                            <span class="badge">{{ $tool->engine_type->value }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">{{ __('execution_time') }}:</span>
                            <span id="tool-execution-time" style="color: var(--accent-emerald); font-weight: 700;">&lt; 5 ms</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">Lượt sử dụng:</span>
                            <span style="color: var(--text-main); font-weight: 600;">{{ number_format($tool->execution_count) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">Đánh giá:</span>
                            <span style="color: var(--accent-amber); font-weight: 700;">★ {{ number_format((float)$tool->rating_avg, 2) }}</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.35rem; padding-top: 0.5rem; border-top: 1px solid var(--border-subtle);">
                            <span style="color: var(--text-muted);">{{ __('api_access') }}:</span>
                            <code style="font-size: 0.75rem; background: var(--bg-input); padding: 0.35rem 0.5rem; border-radius: var(--radius-xs); color: var(--accent-cyan);">POST /api/tools/{{ $tool->slug }}/execute</code>
                        </div>
                    </div>
                </div>

                @if(!empty($relatedTools) && $relatedTools->isNotEmpty())
                    <div class="tool-card" style="margin-bottom: 1.5rem;">
                        <h3 style="font-size: 1.15rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem;">
                            {{ __('related_tools') }}
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                            @foreach($relatedTools as $related)
                                <a href="{{ url('/tools/' . $related->slug) }}" style="display: flex; align-items: center; justify-content: space-between; font-size: 0.92rem; color: var(--text-sub);">
                                    <span>{{ $related->display_name }}</span>
                                    <span style="color: var(--accent-cyan);">→</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Dynamic Ad Slot: sidebar_right --}}
                @php
                    $sidebarAd = \Application\Ad\Services\AdService::getAdForSlot('sidebar_right');
                @endphp
                @if($sidebarAd && (($sidebarAd->type === 'custom_banner' && $sidebarAd->image_url) || $sidebarAd->raw_html))
                    <div style="margin-top: 1.5rem; border-radius: var(--radius-md); overflow: hidden; text-align: center;">
                        @if($sidebarAd->type === 'custom_banner' && $sidebarAd->image_url)
                            <a href="{{ $sidebarAd->target_url ?: '#' }}" target="_blank" rel="nofollow sponsored">
                                <img src="{{ $sidebarAd->image_url }}" alt="{{ $sidebarAd->name }}" style="max-width: 100%; height: auto; border-radius: var(--radius-sm); display: block; margin: 0 auto;">
                            </a>
                        @elseif($sidebarAd->raw_html)
                            {!! $sidebarAd->raw_html !!}
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- SEO Longform Content & Documentation --}}
        {{-- SEO Longform Content & Educational Guide --}}
        <div class="tool-card" style="padding: 2.8rem; line-height: 1.75;">
            <div class="tool-guide-body">
                @if($tool->display_description_markdown)
                    {!! \Illuminate\Support\Str::markdown($tool->display_description_markdown) !!}
                @else
                    <h2>{{ __('about_tool', ['name' => $tool->display_name]) }}</h2>
                    <p>{{ $tool->display_summary }}</p>

                    <h3 style="margin-top: 2rem;">{{ __('how_to_use') }}</h3>
                    <ol>
                        <li>{{ __('step_1') }}</li>
                        <li>{{ __('step_2') }}</li>
                        <li>{{ __('step_3') }}</li>
                        <li>{{ __('step_4') }}</li>
                    </ol>

                    <h3 style="margin-top: 2rem;">{{ __('privacy_statement') }}</h3>
                    <p>{{ __('privacy_desc') }}</p>
                @endif
            </div>
        </div>

    </div>
</section>
@endsection
