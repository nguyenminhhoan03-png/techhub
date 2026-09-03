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
@section('canonical_url', app()->getLocale() === 'en' ? url('/tools/' . $tool->slug) . '?lang=en' : url('/tools/' . $tool->slug))
@section('meta_keywords', $tool->display_name . ', ' . ($tool->category?->display_name ?? __('tools.common.online_tool')) . ', ' . $tool->slug . ', ' . __('tools.common.free_online_tools') . ', ' . ($tool->category?->name ?? ''))
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
                                <label for="json-input" class="form-label" style="margin-bottom: 0;">{{ __('tools.json-formatter.ui.input_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-json"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.json-formatter.ui.btn_load_sample') }}</button>
                            </div>
                            <textarea id="json-input" name="json" class="form-control" style="min-height: 140px; font-family: var(--font-mono);" placeholder='{"hello": "world", "status": true, "version": 2.0}' required></textarea>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            <div>
                                <label for="json-action" class="form-label" style="margin-bottom: 0.25rem;">{{ __('tools.json-formatter.ui.action_label') }}</label>
                                <select id="json-action" name="action" class="form-control" style="width: 180px;">
                                    <option value="beautify">{{ __('tools.json-formatter.ui.action_beautify') }}</option>
                                    <option value="minify">{{ __('tools.json-formatter.ui.action_minify') }}</option>
                                    <option value="validate">{{ __('tools.json-formatter.ui.action_validate') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="json-indent" class="form-label" style="margin-bottom: 0.25rem;">{{ __('tools.json-formatter.ui.indent_label') }}</label>
                                <select id="json-indent" name="indent_size" class="form-control" style="width: 130px;">
                                    <option value="2">{{ __('tools.json-formatter.ui.indent_2_spaces') }}</option>
                                    <option value="4">{{ __('tools.json-formatter.ui.indent_4_spaces') }}</option>
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
                                <label for="base64-text" class="form-label" style="margin-bottom: 0;">{{ __('tools.base64-encode-decode.ui.input_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-base64"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.base64-encode-decode.ui.btn_load_sample') }}</button>
                            </div>
                            <textarea id="base64-text" name="text" class="form-control" style="min-height: 130px; font-family: var(--font-mono);" placeholder="{{ __('tools.base64-encode-decode.ui.input_placeholder') }}" required></textarea>
                        </div>

                        <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="action" value="encode" checked>
                                <span>{{ __('tools.base64-encode-decode.ui.action_encode') }}</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="action" value="decode">
                                <span>{{ __('tools.base64-encode-decode.ui.action_decode') }}</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="url_safe" value="1">
                                <span>{{ __('tools.base64-encode-decode.ui.opt_url_safe') }}</span>
                            </label>
                            <button type="submit" class="btn btn-primary" style="margin-left: auto;">{{ __('run_tool') }}</button>
                        </div>

                    {{-- 3. HASH GENERATOR --}}
                    @elseif($tool->slug === 'hash-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="hash-text" class="form-label" style="margin-bottom: 0;">{{ __('tools.hash-generator.ui.input_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-hash"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.hash-generator.ui.btn_load_sample') }}</button>
                            </div>
                            <textarea id="hash-text" name="text" class="form-control" style="min-height: 110px; font-family: var(--font-mono);" placeholder="{{ __('tools.hash-generator.ui.input_placeholder') }}" required></textarea>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="hash-alg" class="form-label">{{ __('tools.hash-generator.ui.algorithm_label') }}</label>
                                <select id="hash-alg" name="algorithm" class="form-control">
                                    <option value="all">{{ __('tools.hash-generator.ui.alg_all') }}</option>
                                    <option value="sha256">{{ __('tools.hash-generator.ui.alg_sha256') }}</option>
                                    <option value="md5">{{ __('tools.hash-generator.ui.alg_md5') }}</option>
                                    <option value="sha1">{{ __('tools.hash-generator.ui.alg_sha1') }}</option>
                                    <option value="sha512">{{ __('tools.hash-generator.ui.alg_sha512') }}</option>
                                    <option value="bcrypt">{{ __('tools.hash-generator.ui.alg_bcrypt') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="hash-secret" class="form-label">{{ __('tools.hash-generator.ui.secret_label') }}</label>
                                <input type="text" id="hash-secret" name="secret_key" class="form-control" placeholder="{{ __('tools.hash-generator.ui.secret_placeholder') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-lock-closed style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.hash-generator.ui.btn_submit') }}</button>

                    {{-- 4. JWT DEBUGGER --}}
                    @elseif($tool->slug === 'jwt-debugger')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="jwt-token" class="form-label" style="margin-bottom: 0;">{{ __('tools.jwt-debugger.ui.input_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-jwt"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.jwt-debugger.ui.btn_load_sample') }}</button>
                            </div>
                            <textarea id="jwt-token" name="token" class="form-control" style="min-height: 120px; font-family: var(--font-mono); font-size: 0.85rem;" placeholder="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlY2hIdWIiLCJpYXQiOjE1MTYyMzkwMjJ9..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-magnifying-glass style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.jwt-debugger.ui.btn_submit') }}</button>

                    {{-- 5. REGEX TESTER --}}
                    @elseif($tool->slug === 'regex-tester')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="regex-pattern" class="form-label" style="margin-bottom: 0;">{{ __('tools.regex-tester.ui.pattern_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-regex"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.regex-tester.ui.btn_load_sample') }}</button>
                            </div>
                            <div style="display: flex; gap: 0.75rem; align-items: center;">
                                <span style="font-family: var(--font-mono); font-weight: 700; font-size: 1.2rem; color: var(--text-muted);">/</span>
                                <input type="text" id="regex-pattern" name="pattern" class="form-control" style="font-family: var(--font-mono);" placeholder="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" value="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required>
                                <span style="font-family: var(--font-mono); font-weight: 700; font-size: 1.2rem; color: var(--text-muted);">/</span>
                                <input type="text" id="regex-flags" name="flags" class="form-control" style="width: 80px; font-family: var(--font-mono);" placeholder="gmi" value="gmi">
                            </div>
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 0.35rem;">{{ __('tools.regex-tester.ui.flags_help') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="regex-test-text" class="form-label">{{ __('tools.regex-tester.ui.test_text_label') }}</label>
                            <textarea id="regex-test-text" name="test_text" class="form-control" style="min-height: 120px; font-family: var(--font-mono);" placeholder="{{ __('tools.regex-tester.ui.test_text_placeholder') }}" required>{{ __('tools.regex-tester.ui.sample_test_text') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.regex-tester.ui.btn_submit') }}</button>

                    {{-- 6. URL ENCODER & DECODER --}}
                    @elseif($tool->slug === 'url-encoder-decoder')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="url-input" class="form-label" style="margin-bottom: 0;">{{ __('tools.url-encoder-decoder.ui.input_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-url"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.url-encoder-decoder.ui.btn_load_sample') }}</button>
                            </div>
                            <textarea id="url-input" name="url" class="form-control" style="min-height: 120px; font-family: var(--font-mono);" placeholder="https://techhub.vn/search?q=công cụ lập trình & ddd=true#tools" required></textarea>
                        </div>

                        <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="action" value="encode" checked>
                                <span>{{ __('tools.url-encoder-decoder.ui.action_encode') }}</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="radio" name="action" value="decode">
                                <span>{{ __('tools.url-encoder-decoder.ui.action_decode') }}</span>
                            </label>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-left: auto;">
                                <label for="url-standard" class="form-label" style="margin-bottom: 0;">{{ __('tools.url-encoder-decoder.ui.standard_label') }}</label>
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
                                <label for="loan-principal" class="form-label">{{ __('tools.loan-calculator.ui.principal_label') }}</label>
                                <input type="number" id="loan-principal" name="principal" class="form-control" value="500000000" min="1000000" step="10000000" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">{{ __('tools.loan-calculator.ui.principal_hint') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="loan-rate" class="form-label">{{ __('tools.loan-calculator.ui.rate_label') }}</label>
                                <input type="number" id="loan-rate" name="annual_interest_rate" class="form-control" value="8.5" min="0.1" step="0.1" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">{{ __('tools.loan-calculator.ui.rate_hint') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="loan-term" class="form-label">{{ __('tools.loan-calculator.ui.term_label') }}</label>
                                <input type="number" id="loan-term" name="term_months" class="form-control" value="60" min="1" step="6" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">{{ __('tools.loan-calculator.ui.term_hint') }}</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.loan-calculator.ui.btn_submit') }}</button>

                    {{-- 8. PERCENTAGE CALCULATOR --}}
                    @elseif($tool->slug === 'percentage-calculator')
                        <div class="form-group">
                            <label for="pct-mode" class="form-label">{{ __('tools.percentage-calculator.ui.mode_label') }}</label>
                            <select id="pct-mode" name="mode" class="form-control">
                                <option value="percent_of">{{ __('tools.percentage-calculator.ui.mode_percent_of') }}</option>
                                <option value="is_what_percent">{{ __('tools.percentage-calculator.ui.mode_is_what_percent') }}</option>
                                <option value="increase_decrease">{{ __('tools.percentage-calculator.ui.mode_increase_decrease') }}</option>
                            </select>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="pct-val-a" class="form-label">{{ __('tools.percentage-calculator.ui.val_a_label') }}</label>
                                <input type="number" id="pct-val-a" name="value_a" class="form-control" value="20" step="any" required>
                            </div>
                            <div class="form-group">
                                <label for="pct-val-b" class="form-label">{{ __('tools.percentage-calculator.ui.val_b_label') }}</label>
                                <input type="number" id="pct-val-b" name="value_b" class="form-control" value="500000" step="any" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.percentage-calculator.ui.btn_submit') }}</button>

                    {{-- 9. BMI CALCULATOR --}}
                    @elseif($tool->slug === 'bmi-calculator')
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label for="bmi-unit" class="form-label">{{ __('tools.bmi-calculator.ui.unit_label') }}</label>
                                <select id="bmi-unit" name="unit_system" class="form-control">
                                    <option value="metric">{{ __('tools.bmi-calculator.ui.unit_metric') }}</option>
                                    <option value="imperial">{{ __('tools.bmi-calculator.ui.unit_imperial') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bmi-height" class="form-label">{{ __('tools.bmi-calculator.ui.height_label') }}</label>
                                <input type="number" id="bmi-height" name="height" class="form-control" value="172" step="any" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">cm</small>
                            </div>
                            <div class="form-group">
                                <label for="bmi-weight" class="form-label">{{ __('tools.bmi-calculator.ui.weight_label') }}</label>
                                <input type="number" id="bmi-weight" name="weight" class="form-control" value="65" step="any" required>
                                <small style="color: var(--text-muted); font-size: 0.8rem;">kg</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.bmi-calculator.ui.btn_submit') }}</button>

                    {{-- 10. IMAGE METADATA & EXIF --}}
                    @elseif($tool->slug === 'image-metadata-inspector')
                        <div class="form-group">
                            <label class="form-label">{{ __('tools.image-metadata-inspector.ui.upload_label') }}</label>
                            <div class="dropzone" id="file-dropzone">
                                <span style="font-size: 2.5rem; display: flex; justify-content: center; margin-bottom: 0.5rem;"><x-heroicon-o-photo style="width: 1em; height: 1em;" /></span>
                                <strong style="font-size: 1.05rem; color: var(--text-main);">{{ __('tools.image-metadata-inspector.ui.dropzone_title') }}</strong>
                                <span style="color: var(--text-muted); display: block; margin-top: 0.25rem;">{{ __('tools.image-metadata-inspector.ui.dropzone_desc') }}</span>
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

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-magnifying-glass style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.image-metadata-inspector.ui.btn_submit') }}</button>

                    {{-- 11. IMAGE COLOR PALETTE EXTRACTOR --}}
                    @elseif($tool->slug === 'image-color-extractor')
                        <div class="form-group">
                            <label class="form-label">{{ __('tools.image-color-extractor.ui.upload_label') }}</label>
                            <div class="dropzone" id="file-dropzone">
                                <span style="font-size: 2.5rem; display: flex; justify-content: center; margin-bottom: 0.5rem;"><x-heroicon-o-swatch style="width: 1em; height: 1em;" /></span>
                                <strong style="font-size: 1.05rem; color: var(--text-main);">{{ __('tools.image-color-extractor.ui.dropzone_title') }}</strong>
                                <span style="color: var(--text-muted); display: block; margin-top: 0.25rem;">{{ __('tools.image-color-extractor.ui.dropzone_desc') }}</span>
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
                                <label for="palette-size" class="form-label" style="margin-bottom: 0.25rem;">{{ __('tools.image-color-extractor.ui.size_label') }}</label>
                                <select id="palette-size" name="palette_size" class="form-control" style="width: 160px;">
                                    <option value="5" selected>{{ __('tools.image-color-extractor.ui.opt_5') }}</option>
                                    <option value="3">{{ __('tools.image-color-extractor.ui.opt_3') }}</option>
                                    <option value="8">{{ __('tools.image-color-extractor.ui.opt_8') }}</option>
                                    <option value="10">{{ __('tools.image-color-extractor.ui.opt_10') }}</option>
                                </select>
                            </div>
                            <div style="margin-top: 1.5rem;">
                                <button type="submit" class="btn btn-primary"><x-heroicon-s-swatch style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.image-color-extractor.ui.btn_submit') }}</button>
                            </div>
                        </div>

                    {{-- 12. GOOGLE SERP PREVIEW --}}
                    @elseif($tool->slug === 'serp-preview')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="serp-title" class="form-label" style="margin-bottom: 0;">{{ __('tools.serp-preview.ui.title_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-serp"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.serp-preview.ui.btn_load_sample') }}</button>
                            </div>
                            <input type="text" id="serp-title" name="title" class="form-control" placeholder="{{ __('tools.serp-preview.ui.title_placeholder') }}" value="{{ __('tools.serp-preview.ui.title_sample') }}" required>
                            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-top: 0.35rem;">
                                <span>{{ __('tools.serp-preview.ui.title_hint') }}</span>
                                <span id="serp-title-counter" style="font-weight: 600; color: var(--accent-indigo);">0 {{ __('tools.serp-preview.ui.chars_unit') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="serp-desc" class="form-label">{{ __('tools.serp-preview.ui.desc_label') }}</label>
                            <textarea id="serp-desc" name="description" class="form-control" style="min-height: 90px;" placeholder="{{ __('tools.serp-preview.ui.desc_placeholder') }}" required>{{ __('tools.serp-preview.ui.desc_sample') }}</textarea>
                            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-top: 0.35rem;">
                                <span>{{ __('tools.serp-preview.ui.desc_hint') }}</span>
                                <span id="serp-desc-counter" style="font-weight: 600; color: var(--accent-indigo);">0 {{ __('tools.serp-preview.ui.chars_unit') }}</span>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="serp-url" class="form-label">{{ __('tools.serp-preview.ui.url_label') }}</label>
                                <input type="url" id="serp-url" name="url" class="form-control" value="https://techhub.vn/kien-thuc/toi-uu-seo-onpage" required>
                            </div>
                            <div>
                                <label for="serp-site-name" class="form-label">{{ __('tools.serp-preview.ui.site_name_label') }}</label>
                                <input type="text" id="serp-site-name" name="site_name" class="form-control" value="{{ __('tools.serp-preview.ui.site_name_sample') }}">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; background: var(--bg-surface-elevated); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                            <div>
                                <label for="serp-device" class="form-label">{{ __('tools.serp-preview.ui.device_label') }}</label>
                                <select id="serp-device" name="device" class="form-control">
                                    <option value="desktop" selected>{{ __('tools.serp-preview.ui.device_desktop') }}</option>
                                    <option value="mobile">{{ __('tools.serp-preview.ui.device_mobile') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="serp-date" class="form-label">{{ __('tools.serp-preview.ui.date_label') }}</label>
                                <input type="text" id="serp-date" name="date" class="form-control" placeholder="21 thg 8, 2026" value="21 thg 8, 2026">
                            </div>
                            <div>
                                <label for="serp-rating-val" class="form-label">{{ __('tools.serp-preview.ui.rating_val_label') }}</label>
                                <input type="number" id="serp-rating-val" name="rating_value" class="form-control" value="4.9" step="0.1" min="1" max="5">
                            </div>
                            <div>
                                <label for="serp-rating-cnt" class="form-label">{{ __('tools.serp-preview.ui.rating_cnt_label') }}</label>
                                <input type="number" id="serp-rating-cnt" name="rating_count" class="form-control" value="128" min="1">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.serp-preview.ui.btn_submit') }}</button>

                    {{-- 13. META TAG GENERATOR --}}
                    @elseif($tool->slug === 'meta-tag-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="meta-title" class="form-label" style="margin-bottom: 0;">{{ __('tools.meta-tag-generator.ui.title_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-meta"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.meta-tag-generator.ui.btn_load_sample') }}</button>
                            </div>
                            <input type="text" id="meta-title" name="title" class="form-control" placeholder="{{ __('tools.meta-tag-generator.ui.title_placeholder') }}" value="{{ __('tools.meta-tag-generator.ui.title_sample') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="meta-desc" class="form-label">{{ __('tools.meta-tag-generator.ui.desc_label') }}</label>
                            <textarea id="meta-desc" name="description" class="form-control" style="min-height: 80px;" placeholder="{{ __('tools.meta-tag-generator.ui.desc_placeholder') }}" required>{{ __('tools.meta-tag-generator.ui.desc_sample') }}</textarea>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="meta-keywords" class="form-label">{{ __('tools.meta-tag-generator.ui.keywords_label') }}</label>
                                <input type="text" id="meta-keywords" name="keywords" class="form-control" value="{{ __('tools.meta-tag-generator.ui.keywords_sample') }}">
                            </div>
                            <div>
                                <label for="meta-canonical" class="form-label">{{ __('tools.meta-tag-generator.ui.canonical_label') }}</label>
                                <input type="url" id="meta-canonical" name="canonical_url" class="form-control" value="https://techhub.vn">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="meta-author" class="form-label">{{ __('tools.meta-tag-generator.ui.author_label') }}</label>
                                <input type="text" id="meta-author" name="author" class="form-control" value="{{ __('tools.meta-tag-generator.ui.author_sample') }}">
                            </div>
                            <div>
                                <label for="meta-lang" class="form-label">{{ __('tools.meta-tag-generator.ui.language_label') }}</label>
                                <input type="text" id="meta-lang" name="language" class="form-control" value="{{ app()->getLocale() }}">
                            </div>
                        </div>

                        <div style="background: var(--bg-surface-elevated); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 700;">{{ __('tools.meta-tag-generator.ui.robots_label') }}</label>
                            <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; margin-bottom: 0.75rem;">
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                    <input type="radio" name="robots_index" value="index" checked>
                                    <span>{{ __('tools.meta-tag-generator.ui.opt_index') }}</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                    <input type="radio" name="robots_index" value="noindex">
                                    <span>{{ __('tools.meta-tag-generator.ui.opt_noindex') }}</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                    <input type="radio" name="robots_follow" value="follow" checked>
                                    <span>{{ __('tools.meta-tag-generator.ui.opt_follow') }}</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                                    <input type="radio" name="robots_follow" value="nofollow">
                                    <span>{{ __('tools.meta-tag-generator.ui.opt_nofollow') }}</span>
                                </label>
                            </div>
                            <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.88rem;">
                                    <input type="checkbox" name="robots_noarchive" value="1">
                                    <span>{{ __('tools.meta-tag-generator.ui.opt_noarchive') }}</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer; font-size: 0.88rem;">
                                    <input type="checkbox" name="robots_nosnippet" value="1">
                                    <span>{{ __('tools.meta-tag-generator.ui.opt_nosnippet') }}</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.meta-tag-generator.ui.btn_submit') }}</button>

                    {{-- 14. SCHEMA GENERATOR --}}
                    @elseif($tool->slug === 'schema-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="schema-type" class="form-label" style="margin-bottom: 0;">{{ __('tools.schema-generator.ui.type_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-schema"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.schema-generator.ui.btn_load_sample') }}</button>
                            </div>
                            <select id="schema-type" name="schema_type" class="form-control" style="font-weight: 600;">
                                <option value="Article" selected>{{ __('tools.schema-generator.ui.type_article') }}</option>
                                <option value="FAQPage">{{ __('tools.schema-generator.ui.type_faq') }}</option>
                                <option value="Product">{{ __('tools.schema-generator.ui.type_product') }}</option>
                                <option value="LocalBusiness">{{ __('tools.schema-generator.ui.type_local') }}</option>
                                <option value="BreadcrumbList">{{ __('tools.schema-generator.ui.type_breadcrumb') }}</option>
                                <option value="SoftwareApplication">{{ __('tools.schema-generator.ui.type_software') }}</option>
                                <option value="Organization">{{ __('tools.schema-generator.ui.type_org') }}</option>
                            </select>
                        </div>

                        {{-- Schema Dynamic Subfields Container --}}
                        <div id="schema-subfields-wrap" style="margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label class="form-label">{{ __('tools.schema-generator.ui.headline_label') }}</label>
                                <input type="text" name="headline" class="form-control" value="{{ __('tools.schema-generator.ui.headline_sample') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('tools.schema-generator.ui.desc_label') }}</label>
                                <textarea name="description" class="form-control" style="min-height: 70px;">{{ __('tools.schema-generator.ui.desc_sample') }}</textarea>
                            </div>
                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">{{ __('tools.schema-generator.ui.url_label') }}</label>
                                    <input type="url" name="url" class="form-control" value="https://techhub.vn/blog/toi-uu-toc-do-website">
                                </div>
                                <div>
                                    <label class="form-label">{{ __('tools.schema-generator.ui.image_url_label') }}</label>
                                    <input type="url" name="image_url" class="form-control" value="https://techhub.vn/images/blog-banner.jpg">
                                </div>
                            </div>
                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">{{ __('tools.schema-generator.ui.author_label') }}</label>
                                    <input type="text" name="author_name" class="form-control" value="TechHub Senior Developer">
                                </div>
                                <div>
                                    <label class="form-label">{{ __('tools.schema-generator.ui.publisher_label') }}</label>
                                    <input type="text" name="publisher_name" class="form-control" value="TechHub">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('tools.schema-generator.ui.faq_label') }}</label>
                                <textarea name="faq_text" class="form-control" style="min-height: 90px; font-family: var(--font-mono); font-size: 0.85rem;" placeholder="{{ __('tools.schema-generator.ui.faq_placeholder') }}">{{ __('tools.schema-generator.ui.faq_sample') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.schema-generator.ui.btn_submit') }}</button>

                    {{-- 15. OPEN GRAPH & TWITTER CARD GENERATOR --}}
                    @elseif($tool->slug === 'open-graph-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="og-title" class="form-label" style="margin-bottom: 0;">{{ __('tools.open-graph-generator.ui.title_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-og"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.open-graph-generator.ui.btn_load_sample') }}</button>
                            </div>
                            <input type="text" id="og-title" name="title" class="form-control" placeholder="{{ __('tools.open-graph-generator.ui.title_placeholder') }}" value="{{ __('tools.open-graph-generator.ui.title_sample') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="og-desc" class="form-label">{{ __('tools.open-graph-generator.ui.desc_label') }}</label>
                            <textarea id="og-desc" name="description" class="form-control" style="min-height: 80px;" placeholder="{{ __('tools.open-graph-generator.ui.desc_placeholder') }}" required>{{ __('tools.open-graph-generator.ui.desc_sample') }}</textarea>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="og-image" class="form-label">{{ __('tools.open-graph-generator.ui.image_label') }}</label>
                                <input type="url" id="og-image" name="image_url" class="form-control" placeholder="https://example.com/banner-1200x630.png" value="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200&h=630&fit=crop" required>
                            </div>
                            <div>
                                <label for="og-url" class="form-label">{{ __('tools.open-graph-generator.ui.url_label') }}</label>
                                <input type="url" id="og-url" name="url" class="form-control" value="https://techhub.vn" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <label for="og-site-name" class="form-label">{{ __('tools.open-graph-generator.ui.site_name_label') }}</label>
                                <input type="text" id="og-site-name" name="site_name" class="form-control" value="TechHub">
                            </div>
                            <div>
                                <label for="og-type" class="form-label">{{ __('tools.open-graph-generator.ui.type_label') }}</label>
                                <select id="og-type" name="og_type" class="form-control">
                                    <option value="website" selected>{{ __('tools.open-graph-generator.ui.type_website') }}</option>
                                    <option value="article">{{ __('tools.open-graph-generator.ui.type_article') }}</option>
                                    <option value="product">{{ __('tools.open-graph-generator.ui.type_product') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="twitter-card" class="form-label">{{ __('tools.open-graph-generator.ui.twitter_card_label') }}</label>
                                <select id="twitter-card" name="twitter_card" class="form-control">
                                    <option value="summary_large_image" selected>{{ __('tools.open-graph-generator.ui.card_large') }}</option>
                                    <option value="summary">{{ __('tools.open-graph-generator.ui.card_summary') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="twitter-site" class="form-label">{{ __('tools.open-graph-generator.ui.twitter_site_label') }}</label>
                                <input type="text" id="twitter-site" name="twitter_site" class="form-control" placeholder="@techhub_vn" value="@techhub_vn">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.open-graph-generator.ui.btn_submit') }}</button>

                    {{-- 16. ROBOTS.TXT GENERATOR --}}
                    @elseif($tool->slug === 'robots-txt-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="robots-preset" class="form-label" style="margin-bottom: 0;">{{ __('tools.robots-txt-generator.ui.preset_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-robots"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.robots-txt-generator.ui.btn_load_sample') }}</button>
                            </div>
                            <select id="robots-preset" name="preset" class="form-control" style="font-weight: 600;">
                                <option value="default" selected>{{ __('tools.robots-txt-generator.ui.preset_default') }}</option>
                                <option value="allow_all">{{ __('tools.robots-txt-generator.ui.preset_allow_all') }}</option>
                                <option value="block_all">{{ __('tools.robots-txt-generator.ui.preset_block_all') }}</option>
                                <option value="block_ai_bots">{{ __('tools.robots-txt-generator.ui.preset_block_ai') }}</option>
                                <option value="wordpress">{{ __('tools.robots-txt-generator.ui.preset_wordpress') }}</option>
                                <option value="laravel">{{ __('tools.robots-txt-generator.ui.preset_laravel') }}</option>
                            </select>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="robots-disallow" class="form-label">{{ __('tools.robots-txt-generator.ui.disallow_label') }}</label>
                                <textarea id="robots-disallow" name="disallow_paths" class="form-control" style="min-height: 110px; font-family: var(--font-mono); font-size: 0.88rem;">/admin/
/login
/api/private/
/telescope/
/tmp/</textarea>
                            </div>
                            <div class="form-group">
                                <label for="robots-allow" class="form-label">{{ __('tools.robots-txt-generator.ui.allow_label') }}</label>
                                <textarea id="robots-allow" name="allow_paths" class="form-control" style="min-height: 110px; font-family: var(--font-mono); font-size: 0.88rem;">/public/
/css/
/js/
/images/
/assets/</textarea>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="robots-sitemap" class="form-label">{{ __('tools.robots-txt-generator.ui.sitemap_label') }}</label>
                                <input type="url" id="robots-sitemap" name="sitemap_url" class="form-control" value="https://techhub.vn/sitemap.xml">
                            </div>
                            <div>
                                <label for="robots-delay" class="form-label">{{ __('tools.robots-txt-generator.ui.delay_label') }}</label>
                                <input type="number" id="robots-delay" name="crawl_delay" class="form-control" placeholder="{{ __('tools.robots-txt-generator.ui.delay_placeholder') }}" min="0" max="60">
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="block_ai_crawlers" value="1" checked>
                                <span style="font-weight: 600; color: var(--text-main);">{{ __('tools.robots-txt-generator.ui.block_ai_label') }}</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.robots-txt-generator.ui.btn_submit') }}</button>

                    {{-- 17. SITEMAP XML GENERATOR (XML-Sitemaps Standard) --}}
                    @elseif($tool->slug === 'sitemap-generator')
                        <div style="text-align: center; margin-bottom: 2rem; padding: 1rem 0;">
                            <h2 style="font-size: 2.2rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.6rem; letter-spacing: -0.5px;">
                                {{ __('tools.sitemap-generator.ui.hero_title') }}
                            </h2>
                            <p style="color: var(--text-sub); font-size: 1.05rem; max-width: 620px; margin: 0 auto; line-height: 1.6;">
                                {{ __('tools.sitemap-generator.ui.hero_desc') }}
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
                                    placeholder="{{ __('tools.sitemap-generator.ui.domain_placeholder') }}" 
                                    value="{{ request()->root() }}"
                                    required
                                >
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 0.85rem 1.85rem; font-size: 1rem; font-weight: 700; border-radius: var(--radius-md); display: flex; align-items: center; gap: 0.5rem; white-space: nowrap;">
                                <span>🚀</span> <span>{{ __('tools.sitemap-generator.ui.btn_generate') }}</span>
                            </button>
                        </div>

                        {{-- Collapsible Settings Toggle --}}
                        <div style="text-align: right; margin-bottom: 1.5rem;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('sitemap-advanced-settings').classList.toggle('hidden-settings');" style="font-size: 0.85rem; padding: 0.35rem 0.85rem; color: var(--text-muted);">
                                {{ __('tools.sitemap-generator.ui.btn_advanced') }}
                            </button>
                        </div>

                        {{-- Collapsible Advanced Settings Panel --}}
                        <div id="sitemap-advanced-settings" class="hidden-settings" style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 1.75rem; transition: all 0.3s ease;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
                                <div>
                                    <label for="sitemap-freq" class="form-label" style="font-size: 0.85rem;">{{ __('tools.sitemap-generator.ui.freq_label') }}</label>
                                    <select id="sitemap-freq" name="default_changefreq" class="form-control">
                                        <option value="daily">{{ __('tools.sitemap-generator.ui.freq_daily') }}</option>
                                        <option value="weekly" selected>{{ __('tools.sitemap-generator.ui.freq_weekly') }}</option>
                                        <option value="monthly">{{ __('tools.sitemap-generator.ui.freq_monthly') }}</option>
                                        <option value="always">{{ __('tools.sitemap-generator.ui.freq_always') }}</option>
                                        <option value="hourly">{{ __('tools.sitemap-generator.ui.freq_hourly') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="sitemap-priority" class="form-label" style="font-size: 0.85rem;">{{ __('tools.sitemap-generator.ui.priority_label') }}</label>
                                    <select id="sitemap-priority" name="default_priority" class="form-control">
                                        <option value="1.0">{{ __('tools.sitemap-generator.ui.priority_10') }}</option>
                                        <option value="0.8" selected>{{ __('tools.sitemap-generator.ui.priority_08') }}</option>
                                        <option value="0.6">{{ __('tools.sitemap-generator.ui.priority_06') }}</option>
                                        <option value="0.5">{{ __('tools.sitemap-generator.ui.priority_05') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="sitemap-max" class="form-label" style="font-size: 0.85rem;">{{ __('tools.sitemap-generator.ui.max_urls_label') }}</label>
                                    <select id="sitemap-max" name="max_urls" class="form-control">
                                        <option value="50">{{ __('tools.sitemap-generator.ui.opt_50') }}</option>
                                        <option value="100" selected>{{ __('tools.sitemap-generator.ui.opt_100') }}</option>
                                        <option value="250">{{ __('tools.sitemap-generator.ui.opt_250') }}</option>
                                        <option value="500">{{ __('tools.sitemap-generator.ui.opt_500') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle);">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.88rem; color: var(--text-main);">
                                    <input type="checkbox" name="include_lastmod" value="1" checked>
                                    <span>{{ __('tools.sitemap-generator.ui.lastmod_label') }}</span>
                                </label>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('sitemap-manual-wrap').style.display = document.getElementById('sitemap-manual-wrap').style.display === 'none' ? 'block' : 'none';" style="font-size: 0.8rem;">
                                    {{ __('tools.sitemap-generator.ui.btn_manual_mode') }}
                                </button>
                            </div>

                            <div id="sitemap-manual-wrap" style="display: none; margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px dashed var(--border-subtle);">
                                <label for="sitemap-urls" class="form-label" style="font-size: 0.85rem;">{{ __('tools.sitemap-generator.ui.manual_label') }}</label>
                                <textarea id="sitemap-urls" name="urls_list" class="form-control" style="min-height: 110px; font-family: var(--font-mono); font-size: 0.85rem;" placeholder="/\n/tools\n/articles\n/games"></textarea>
                            </div>
                        </div>

                        {{-- Trust Stats Bar (xml-sitemaps.com style) --}}
                        <div style="display: flex; justify-content: center; align-items: center; gap: 2rem; flex-wrap: wrap; margin-bottom: 2rem; color: var(--text-muted); font-size: 0.88rem; font-weight: 500;">
                            <span>{{ __('tools.sitemap-generator.ui.badge_free') }}</span>
                            <span>{{ __('tools.sitemap-generator.ui.badge_crawler') }}</span>
                            <span>{{ __('tools.sitemap-generator.ui.badge_standards') }}</span>
                            <span>{{ __('tools.sitemap-generator.ui.badge_export') }}</span>
                        </div>

                        <style>
                            .hidden-settings { display: none !important; }
                        </style>

                    {{-- 18. SEO SLUG GENERATOR --}}
                    @elseif($tool->slug === 'slug-generator')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label for="slug-text" class="form-label" style="margin-bottom: 0;">{{ __('tools.slug-generator.ui.input_label') }}</label>
                                <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-slug"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.slug-generator.ui.btn_load_sample') }}</button>
                            </div>
                            <textarea id="slug-text" name="text" class="form-control" style="min-height: 100px;" placeholder="{{ __('tools.slug-generator.ui.input_placeholder') }}" required>{{ __('tools.slug-generator.ui.input_sample') }}</textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label for="slug-sep" class="form-label">{{ __('tools.slug-generator.ui.separator_label') }}</label>
                                <select id="slug-sep" name="separator" class="form-control">
                                    <option value="-" selected>{{ __('tools.slug-generator.ui.sep_hyphen') }}</option>
                                    <option value="_">{{ __('tools.slug-generator.ui.sep_underscore') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="slug-case" class="form-label">{{ __('tools.slug-generator.ui.case_label') }}</label>
                                <select id="slug-case" name="case_format" class="form-control">
                                    <option value="lowercase" selected>lowercase (kebab-case)</option>
                                    <option value="uppercase">UPPERCASE</option>
                                    <option value="camel">camelCase</option>
                                    <option value="pascal">PascalCase</option>
                                    <option value="snake">snake_case</option>
                                </select>
                            </div>
                            <div>
                                <label for="slug-max-len" class="form-label">{{ __('tools.slug-generator.ui.max_len_label') }}</label>
                                <input type="number" id="slug-max-len" name="max_length" class="form-control" value="80" min="20" max="200">
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="remove_stop_words" value="1" checked>
                                <span style="font-weight: 600; color: var(--text-main);">{{ __('tools.slug-generator.ui.stop_words_label') }}</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;"><x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.slug-generator.ui.btn_submit') }}</button>

                    {{-- 19. PROXY CHECKER --}}
                    @elseif($tool->slug === 'proxy-checker')
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                                <label for="proxy-list" class="form-label" style="margin-bottom: 0; font-weight: 600;">{{ __('tools.proxy-checker.ui.input_label') }}</label>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button type="button" class="btn btn-secondary btn-sm" id="btn-load-sample-proxy"><x-heroicon-o-clipboard-document style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" /> {{ __('tools.proxy-checker.ui.btn_load_sample') }}</button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('proxy-list').value = '';">{{ __('tools.proxy-checker.ui.btn_clear') }}</button>
                                </div>
                            </div>
                            <textarea id="proxy-list" name="proxies" class="form-control" style="min-height: 140px; font-family: var(--font-mono); font-size: 0.88rem;" placeholder="103.152.112.4:8080&#10;192.241.168.188:3128:username:password&#10;socks5://185.199.229.156:1080&#10;http://user:pass@178.62.193.19:8080" required></textarea>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.4rem; font-size: 0.78rem; color: var(--text-muted);">
                                <span>{{ __('tools.proxy-checker.ui.supported_formats') }}</span>
                                <code style="padding: 0.1rem 0.35rem; background: var(--bg-surface-elevated); border-radius: 4px;">IP:Port</code>
                                <code style="padding: 0.1rem 0.35rem; background: var(--bg-surface-elevated); border-radius: 4px;">IP:Port:User:Pass</code>
                                <code style="padding: 0.1rem 0.35rem; background: var(--bg-surface-elevated); border-radius: 4px;">socks5://IP:Port</code>
                                <code style="padding: 0.1rem 0.35rem; background: var(--bg-surface-elevated); border-radius: 4px;">http://user:pass@IP:Port</code>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div>
                                <label for="proxy-protocol" class="form-label">{{ __('tools.proxy-checker.ui.protocol_label') }}</label>
                                <select id="proxy-protocol" name="protocol" class="form-control" style="font-weight: 600;">
                                    <option value="auto" selected>{{ __('tools.proxy-checker.ui.protocol_auto') }}</option>
                                    <option value="http">{{ __('tools.proxy-checker.ui.protocol_http') }}</option>
                                    <option value="socks5">{{ __('tools.proxy-checker.ui.protocol_socks5') }}</option>
                                    <option value="socks4">{{ __('tools.proxy-checker.ui.protocol_socks4') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="proxy-timeout" class="form-label">{{ __('tools.proxy-checker.ui.timeout_label') }}</label>
                                <select id="proxy-timeout" name="timeout" class="form-control">
                                    <option value="3">{{ __('tools.proxy-checker.ui.timeout_3s') }}</option>
                                    <option value="5" selected>{{ __('tools.proxy-checker.ui.timeout_5s') }}</option>
                                    <option value="10">{{ __('tools.proxy-checker.ui.timeout_10s') }}</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap; margin-bottom: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
                                <span>{{ __('tools.proxy-checker.ui.help_security') }}</span>
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.75rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                                <x-heroicon-s-bolt style="width: 1.2em; height: 1.2em; display: inline-block; vertical-align: middle;" />
                                <span>{{ __('tools.proxy-checker.ui.btn_submit') }}</span>
                            </button>
                        </div>
                    @else
                        {{-- Generic Fallback Form --}}
                        <div class="form-group">
                            <label class="form-label">{{ __('input_data') }}</label>
                            <textarea name="input_text" class="form-control" style="min-height: 120px;" placeholder="{{ __('enter_data') }}..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;">{{ __('run_tool') }}</button>
                    @endif

                    {{-- RICH VISUAL OUTPUT CONTAINER (Rendered by JS) --}}
                    <div id="tool-rich-output" style="display: none; margin-bottom: 1.5rem;"></div>

                    {{-- CODE / RAW TEXTAREA OUTPUT --}}
                    <div class="form-group" id="raw-output-wrap">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label class="form-label" style="margin-bottom: 0;">{{ __('output_label') }}</label>
                            <button type="button" class="btn btn-secondary btn-sm" data-copy-target="tool-result-output">{{ __('copy_result') }}</button>
                        </div>
                        <textarea id="tool-result-output" class="form-control code-output" readonly placeholder="{{ __('output_placeholder') }}"></textarea>
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
                            <span style="color: var(--text-muted);">{{ __('usage_count') }}:</span>
                            <span style="color: var(--text-main); font-weight: 600;">{{ number_format($tool->execution_count) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">{{ __('rating') }}:</span>
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
