@extends('admin.layouts.app')

@section('title', 'AI Content Generator & Crawler Studio')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1>AI Auto-Writer &amp; <span class="gradient-text">Crawler Studio</span></h1>
        <p style="margin-top: 0.25rem;">Tự động hóa nghiên cứu thông số, đối đầu phần cứng thời gian thực và cào dữ liệu từ web bằng AI Agent.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary btn-sm" style="border-color: var(--accent-indigo); color: #818cf8;">
            <x-heroicon-o-key style="width: 1.2em; height: 1.2em;" /> Cấu Hình API Key
        </a>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary btn-sm">
            ← Danh Sách Bài Viết
        </a>
    </div>
</div>

{{-- AI Engine Status Alert --}}
<div style="margin-bottom: 2rem; background: {{ $hasLiveAiKey ? 'rgba(16, 185, 129, 0.08)' : 'rgba(245, 158, 11, 0.08)' }}; border: 1px solid {{ $hasLiveAiKey ? 'rgba(16, 185, 129, 0.25)' : 'rgba(245, 158, 11, 0.25)' }}; border-radius: var(--radius-lg); padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <span style="font-size: 1.5rem;">{{ $hasLiveAiKey ? '🤖' : '💡' }}</span>
        <div>
            <div style="font-weight: 700; font-size: 0.95rem; color: {{ $hasLiveAiKey ? 'var(--accent-emerald)' : 'var(--accent-amber)' }};">
                {{ $hasLiveAiKey ? 'Live AI Engine Đang Kích Hoạt: ' . strtoupper($activeProvider) . ' (' . $activeModel . ')' : 'Chế Độ Dự Phòng (Deterministic Fallback Engine)' }}
            </div>
            <div style="font-size: 0.82rem; color: var(--text-sub); margin-top: 0.15rem;">
                {{ $hasLiveAiKey ? 'Tất cả tác vụ phân tích và viết bài đang được gửi trực tiếp tới mô hình LLM thời gian thực.' : 'Hệ thống đang hoạt động với bộ tổng hợp thông minh. Bạn có thể thêm GEMINI_API_KEY tại mục Cài Đặt để kích hoạt AI thật 100%.' }}
            </div>
        </div>
    </div>
    @if(!$hasLiveAiKey)
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
            Thêm API Key Ngay →
        </a>
    @endif
</div>

{{-- Interactive Studio Card --}}
<div class="tool-panel" style="padding: 2rem; margin-bottom: 2.5rem;">
    
    {{-- Studio Navigation Tabs --}}
    <div style="display: flex; gap: 1rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 1rem; margin-bottom: 1.75rem;">
        <button type="button" id="tab-btn-specs" class="btn btn-primary btn-sm" onclick="switchStudioTab('specs')">
            ⚡ So Sánh Đối Đầu Linh Kiện Tự Do (Dynamic AI Versus)
        </button>
        <button type="button" id="tab-btn-crawl" class="btn btn-secondary btn-sm" onclick="switchStudioTab('crawl')">
            🕷️ Cào Dữ Liệu Nguồn Web &amp; AI Viết Lại (Universal Scraper)
        </button>
    </div>

    {{-- TAB 1: DYNAMIC SPEC GENERATOR --}}
    <div id="studio-tab-specs">
        <form id="form-generate-specs" onsubmit="handleGenerateSpecs(event)">
            @csrf
            
            <div style="margin-bottom: 1.25rem;">
                <span style="font-size: 0.88rem; color: var(--text-muted);">
                    💡 <strong>So Sánh Động:</strong> Nhập tên 2 linh kiện bất kỳ (CPU, GPU, Điện thoại, Laptop...). AI sẽ tự động nghiên cứu thông số thực tế, tính điểm benchmark và xuất bản bài đối đầu chi tiết.
                </span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label" style="font-weight: 700; color: var(--accent-indigo);">1. Tên Thiết Bị / Linh Kiện A</label>
                    <input type="text" 
                           id="input-name-a" 
                           name="name_a" 
                           list="hardware-suggestions" 
                           class="form-control" 
                           placeholder="Ví dụ: Apple M4 Max, NVIDIA RTX 5090, Intel Core Ultra 9 285K..." 
                           required 
                           style="font-weight: 600;">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 700; color: var(--accent-cyan);">2. Tên Thiết Bị / Linh Kiện B</label>
                    <input type="text" 
                           id="input-name-b" 
                           name="name_b" 
                           list="hardware-suggestions" 
                           class="form-control" 
                           placeholder="Ví dụ: Apple M3 Max, NVIDIA RTX 4090, AMD Ryzen 9 9950X..." 
                           required 
                           style="font-weight: 600;">
                </div>
            </div>

            {{-- Datalist Autocomplete Suggestions --}}
            <datalist id="hardware-suggestions">
                @foreach($products as $p)
                    <option value="{{ $p->full_name }}">{{ $p->brand?->name }} - {{ $p->category?->name }}</option>
                @endforeach
                <option value="Apple M4 Max"></option>
                <option value="Apple M3 Max"></option>
                <option value="Intel Core Ultra 9 285K"></option>
                <option value="Intel Core i9 14900K"></option>
                <option value="AMD Ryzen 7 9800X3D"></option>
                <option value="AMD Ryzen 7 7800X3D"></option>
                <option value="NVIDIA GeForce RTX 5090 32GB"></option>
                <option value="NVIDIA GeForce RTX 4090 24GB"></option>
                <option value="NVIDIA GeForce RTX 5080 16GB"></option>
                <option value="NVIDIA GeForce RTX 4080 Super 16GB"></option>
                <option value="Snapdragon 8 Elite"></option>
                <option value="Apple A18 Pro"></option>
            </datalist>

            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <span style="font-size: 0.82rem; color: var(--text-muted);">Gợi ý so sánh nhanh:</span>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="setComparisonPair('Apple M4 Max', 'Intel Core Ultra 9 285K')">M4 Max vs Core Ultra 9</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="setComparisonPair('NVIDIA GeForce RTX 5090', 'NVIDIA GeForce RTX 4090')">RTX 5090 vs RTX 4090</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="setComparisonPair('AMD Ryzen 7 9800X3D', 'AMD Ryzen 7 7800X3D')">Ryzen 9800X3D vs 7800X3D</button>
                </div>

                <button type="submit" id="btn-submit-specs" class="btn btn-primary">
                    ⚡ Bắt Đầu Phân Tích &amp; Sinh Bài So Sánh Bằng AI
                </button>
            </div>
        </form>
    </div>

    {{-- TAB 2: CRAWL & REWRITE (MULTI-URL BATCH SUPPORT) --}}
    <div id="studio-tab-crawl" style="display: none;">
        <form id="form-crawl-rewrite" onsubmit="handleCrawlRewrite(event)">
            @csrf
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                    <label class="form-label" style="font-weight: 700; margin-bottom: 0;">
                        Danh Sách URL Cần Cào &amp; AI Viết Lại (Hỗ trợ 1 hoặc nhiều link)
                    </label>
                    <span id="url-count-badge" class="badge" style="background: rgba(99, 102, 241, 0.12); color: var(--accent-indigo); font-weight: 700;">0 URL</span>
                </div>
                <textarea id="input-source-urls" 
                          name="source_urls" 
                          class="form-control" 
                          rows="5" 
                          placeholder="Dán một hoặc nhiều link bài báo vào đây, mỗi dòng một link URL. Ví dụ:&#10;https://vietnix.vn/kafka-la-gi/&#10;https://www.tomshardware.com/pc-components/gpus/...&#10;https://theverge.com/..." 
                          required 
                          style="font-family: var(--font-mono); font-size: 0.88rem; line-height: 1.5;"></textarea>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; flex-wrap: wrap; gap: 0.75rem;">
                    <small style="color: var(--text-muted); font-size: 0.82rem;">
                        💡 <strong>Mẹo:</strong> Bạn có thể dán 1 link hoặc nhiều link cùng lúc (mỗi link 1 dòng). Hệ thống sẽ tự động cào và dùng AI viết từng bài một cách an toàn.
                    </small>
                    <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; cursor: pointer; color: var(--text-main); font-weight: 600;">
                        <input type="checkbox" id="check-auto-publish" checked style="accent-color: var(--accent-indigo); width: 16px; height: 16px;">
                        Tự động xuất bản bài viết lên website sau khi AI viết xong
                    </label>
                </div>
            </div>

            <button type="submit" id="btn-submit-crawl" class="btn btn-primary" style="padding: 0.75rem 1.75rem;">
                🕷️ Bắt Đầu Cào Dữ Liệu &amp; AI Tái Cấu Trúc Ngay
            </button>
        </form>

        {{-- BATCH PROGRESS & LIVE RESULT TABLE --}}
        <div id="batch-progress-wrap" style="display: none; margin-top: 2rem; background: var(--bg-surface); border: 1px solid var(--border-medium); border-radius: var(--radius-md); padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <h4 style="margin: 0; color: var(--text-main); font-size: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span class="spinner" id="batch-spinner" style="width: 16px; height: 16px; display: inline-block;"></span>
                    <span id="batch-status-text">Đang chuẩn bị xử lý hàng loạt...</span>
                </h4>
                <span id="batch-percent-badge" class="badge badge-indigo">0%</span>
            </div>
            
            <div style="width: 100%; height: 8px; background: var(--border-subtle); border-radius: 4px; overflow: hidden; margin-bottom: 1.25rem;">
                <div id="batch-progress-bar" style="width: 0%; height: 100%; background: linear-gradient(90deg, var(--accent-indigo), var(--accent-cyan)); transition: width 0.3s ease;"></div>
            </div>

            <div class="admin-table-wrap" style="margin: 0; border: none;">
                <table class="admin-table" style="font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th>Link Nguồn</th>
                            <th>Tiêu Đề Bài Viết AI</th>
                            <th style="width: 130px;">Thời Gian</th>
                            <th style="width: 140px;">Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody id="batch-table-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- LIVE GENERATED RESULT & EDITORIAL APPROVAL CONTAINER --}}
    <div id="generated-result-wrap" style="display: none; margin-top: 2.5rem; border-top: 2px dashed var(--border-medium); padding-top: 2rem;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 style="color: var(--text-main); font-size: 1.25rem;">📝 Bản Xem Trước &amp; Duyệt Xuất Bản Bài Viết AI</h3>
                <div style="display: flex; gap: 0.5rem; align-items: center; margin-top: 0.25rem;">
                    <span id="gen-latency-badge" class="badge badge-emerald">Tạo thành công</span>
                    <span id="gen-source-badge" class="badge badge-indigo">Live AI</span>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.ai_studio.save_article') }}" method="POST">
            @csrf
            <input type="hidden" id="gen-job-id" name="job_id" value="">
            <input type="hidden" id="gen-faqs-json" name="faqs_json" value="">

            <div class="form-group">
                <label class="form-label">Tiêu đề bài viết (Title chuẩn SEO)</label>
                <input type="text" id="gen-title" name="title" class="form-control" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label">Loại bài viết</label>
                    <select id="gen-type" name="type" class="form-control" required>
                        <option value="comparison">So Sánh Đối Đầu</option>
                        <option value="review">Đánh Giá Chi Tiết</option>
                        <option value="buying_guide">Tư Vấn Mua Sắm</option>
                        <option value="news">Tin Công Nghệ</option>
                        <option value="article">Bài Viết Chuẩn</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Chuyên mục</label>
                    <select name="category_id" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Trạng thái xuất bản</label>
                    <select name="status" class="form-control" required>
                        <option value="published">🚀 Xuất bản ngay (Published)</option>
                        <option value="draft">📁 Lưu bản nháp (Draft)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Ảnh đại diện (Featured Image URL)</label>
                <input type="url" id="gen-image" name="featured_image_url" class="form-control" placeholder="https://images.unsplash.com/photo-...">
            </div>

            <div class="form-group">
                <label class="form-label">Tóm tắt ngắn (Meta Excerpt)</label>
                <textarea id="gen-excerpt" name="excerpt" class="form-control" style="min-height: 70px;" required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Nội dung bài viết hoàn chỉnh (Markdown Body)</label>
                <textarea id="gen-markdown" name="content_markdown" class="form-control" style="min-height: 420px; font-family: var(--font-mono); font-size: 0.9rem;" required></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">
                    🚀 Phê Duyệt &amp; Lưu Bài Viết Lên Website
                </button>
            </div>
        </form>

    </div>

</div>

{{-- Recent AI Jobs Log --}}
<div class="admin-table-wrap">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1.1rem; color: var(--text-main);">Lịch Sử Tác Vụ AI &amp; Crawler Gần Nhất</h3>
        <span class="badge badge-emerald">{{ $recentJobs->count() }} tác vụ</span>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Chủ Đề / Tác Vụ</th>
                <th>Loại Tác Vụ</th>
                <th>Thời Gian Xử Lý</th>
                <th>Bài Viết Xuất Bản</th>
                <th>Thời Gian Chạy</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentJobs as $job)
                <tr>
                    <td>
                        <strong style="color: var(--text-main); display: block;">{{ $job->target_topic ?? $job->source_url }}</strong>
                        @if($job->source_url)
                            <a href="{{ $job->source_url }}" target="_blank" style="font-size: 0.75rem; color: var(--accent-cyan);">{{ Str::limit($job->source_url, 45) }} ↗</a>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $job->job_type === 'vs_specs_generator' ? 'badge-cyan' : 'badge-amber' }}">
                            {{ $job->job_type === 'vs_specs_generator' ? 'So Sánh Specs' : 'Cào Web & Viết Lại' }}
                        </span>
                    </td>
                    <td style="font-family: var(--font-mono); color: var(--accent-emerald);">
                        {{ $job->execution_time_ms }} ms
                    </td>
                    <td>
                        @if($job->article)
                            <a href="{{ route('articles.show', $job->article->slug) }}" target="_blank" style="color: var(--accent-indigo); font-weight: 600; font-size: 0.85rem;">
                                {{ Str::limit($job->article->title, 35) }} ↗
                            </a>
                        @else
                            <span style="color: var(--text-muted); font-size: 0.82rem;">Chưa lưu</span>
                        @endif
                    </td>
                    <td style="font-size: 0.82rem; color: var(--text-muted);">
                        {{ $job->created_at->diffForHumans() }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">
                        Chưa có lịch sử sinh bài nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function switchStudioTab(tab) {
    const specsTab = document.getElementById('studio-tab-specs');
    const crawlTab = document.getElementById('studio-tab-crawl');
    const btnSpecs = document.getElementById('tab-btn-specs');
    const btnCrawl = document.getElementById('tab-btn-crawl');

    if (tab === 'specs') {
        specsTab.style.display = 'block';
        crawlTab.style.display = 'none';
        btnSpecs.className = 'btn btn-primary btn-sm';
        btnCrawl.className = 'btn btn-secondary btn-sm';
    } else {
        specsTab.style.display = 'none';
        crawlTab.style.display = 'block';
        btnSpecs.className = 'btn btn-secondary btn-sm';
        btnCrawl.className = 'btn btn-primary btn-sm';
    }
}

function setComparisonPair(nameA, nameB) {
    document.getElementById('input-name-a').value = nameA;
    document.getElementById('input-name-b').value = nameB;
}

async function handleGenerateSpecs(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-submit-specs');
    const nameA = document.getElementById('input-name-a').value.trim();
    const nameB = document.getElementById('input-name-b').value.trim();

    if (!nameA || !nameB) {
        showToast('Vui lòng nhập tên 2 linh kiện để so sánh!', 'error');
        return;
    }
    if (nameA.toLowerCase() === nameB.toLowerCase()) {
        showToast('Tên 2 linh kiện phải khác nhau!', 'error');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> AI đang phân tích dữ liệu và sinh bài viết...';

    try {
        const response = await fetch("{{ route('admin.ai_studio.generate_specs') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name_a: nameA, name_b: nameB })
        });

        const res = await response.json();
        if (response.ok && res.success) {
            populateResult(res.data, res.job_id, res.execution_time_ms, 'comparison');
            showToast('AI đã hoàn tất bài so sánh đối đầu!');
        } else {
            showToast(res.message || 'Lỗi khi sinh bài so sánh.', 'error');
        }
    } catch (err) {
        showToast('Lỗi kết nối: ' + err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '⚡ Bắt Đầu Phân Tích &amp; Sinh Bài So Sánh Bằng AI';
    }
}

// Update URL counter on input
document.addEventListener('DOMContentLoaded', function () {
    const urlsInput = document.getElementById('input-source-urls');
    const badge = document.getElementById('url-count-badge');
    if (urlsInput && badge) {
        urlsInput.addEventListener('input', function () {
            const urls = urlsInput.value.split('\n').map(u => u.trim()).filter(u => u.startsWith('http://') || u.startsWith('https://'));
            badge.textContent = `${urls.length} URL`;
            badge.style.background = urls.length > 1 ? 'rgba(16, 185, 129, 0.12)' : 'rgba(99, 102, 241, 0.12)';
            badge.style.color = urls.length > 1 ? 'var(--accent-emerald)' : 'var(--accent-indigo)';
        });
    }
});

async function handleCrawlRewrite(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-submit-crawl');
    const urlsInput = document.getElementById('input-source-urls');
    const isAutoPublish = document.getElementById('check-auto-publish')?.checked ?? true;

    const rawUrls = urlsInput.value.split('\n').map(u => u.trim()).filter(u => u.length > 0);
    const validUrls = rawUrls.filter(u => u.startsWith('http://') || u.startsWith('https://'));

    if (validUrls.length === 0) {
        showToast('Vui lòng nhập ít nhất 1 đường link URL hợp lệ (bắt đầu bằng http:// hoặc https://)!', 'error');
        return;
    }

    // CASE 1: Single URL processing
    if (validUrls.length === 1) {
        const url = validUrls[0];
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Đang cào dữ liệu và kích hoạt AI phân tích...';

        try {
            const response = await fetch("{{ route('admin.ai_studio.crawl_rewrite') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    source_url: url,
                    auto_save: isAutoPublish
                })
            });

            const res = await response.json();
            if (response.ok && res.success) {
                populateResult(res.data, res.job_id, res.execution_time_ms, 'news');
                if (res.article) {
                    showToast(`🎉 AI đã viết và xuất bản bài viết thành công!`);
                } else {
                    showToast('Đã cào dữ liệu và AI viết lại thành công! Vui lòng duyệt bài bên dưới.');
                }
            } else {
                showToast(res.message || 'Lỗi khi cào dữ liệu từ URL.', 'error');
            }
        } catch (err) {
            showToast('Lỗi kết nối: ' + err.message, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '🕷️ Bắt Đầu Cào Dữ Liệu &amp; AI Tái Cấu Trúc Ngay';
        }
        return;
    }

    // CASE 2: Multi-URL Batch processing
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner"></span> Đang xử lý hàng loạt 0/${validUrls.length}...`;

    const batchWrap = document.getElementById('batch-progress-wrap');
    const progressBar = document.getElementById('batch-progress-bar');
    const percentBadge = document.getElementById('batch-percent-badge');
    const statusText = document.getElementById('batch-status-text');
    const tableBody = document.getElementById('batch-table-body');
    const previewWrap = document.getElementById('generated-result-wrap');

    if (previewWrap) previewWrap.style.display = 'none';
    batchWrap.style.display = 'block';
    tableBody.innerHTML = '';
    batchWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });

    let successCount = 0;

    for (let i = 0; i < validUrls.length; i++) {
        const curUrl = validUrls[i];
        const percent = Math.round((i / validUrls.length) * 100);
        progressBar.style.width = `${percent}%`;
        percentBadge.textContent = `${percent}%`;
        statusText.textContent = `Đang xử lý link ${i + 1}/${validUrls.length}: ${curUrl.substring(0, 45)}...`;
        btn.innerHTML = `<span class="spinner"></span> Đang xử lý ${i + 1}/${validUrls.length}...`;

        // Add placeholder row
        const row = document.createElement('tr');
        row.id = `batch-row-${i}`;
        row.innerHTML = `
            <td><strong>#${i + 1}</strong></td>
            <td><a href="${curUrl}" target="_blank" style="color: var(--accent-cyan); font-size: 0.8rem;">${curUrl.substring(0, 45)}... ↗</a></td>
            <td id="batch-title-${i}" style="color: var(--text-muted);">⏳ Đang cào & AI phân tích viết bài...</td>
            <td id="batch-time-${i}">--</td>
            <td id="batch-status-${i}"><span class="badge badge-amber">Đang chạy</span></td>
        `;
        tableBody.appendChild(row);

        try {
            const response = await fetch("{{ route('admin.ai_studio.crawl_rewrite') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    source_url: curUrl,
                    auto_save: isAutoPublish
                })
            });

            const res = await response.json();

            if (response.ok && res.success) {
                successCount++;
                const titleCell = document.getElementById(`batch-title-${i}`);
                const timeCell = document.getElementById(`batch-time-${i}`);
                const statusCell = document.getElementById(`batch-status-${i}`);

                const viewUrl = res.article?.url || '#';
                titleCell.innerHTML = `<a href="${viewUrl}" target="_blank" style="color: var(--text-main); font-weight: 600; text-decoration: underline;">${res.data.title} ↗</a>`;
                timeCell.innerHTML = `<span style="font-family: var(--font-mono); color: var(--accent-emerald);">${res.execution_time_ms} ms</span>`;
                statusCell.innerHTML = res.article 
                    ? `<span class="badge badge-emerald">● Đã xuất bản</span>` 
                    : `<span class="badge badge-indigo">● Đã xong</span>`;
            } else {
                document.getElementById(`batch-title-${i}`).innerHTML = `<span style="color: var(--accent-rose);">${res.message || 'Lỗi cào dữ liệu'}</span>`;
                document.getElementById(`batch-status-${i}`).innerHTML = `<span class="badge badge-rose">✕ Thất bại</span>`;
            }
        } catch (err) {
            document.getElementById(`batch-title-${i}`).innerHTML = `<span style="color: var(--accent-rose);">${err.message}</span>`;
            document.getElementById(`batch-status-${i}`).innerHTML = `<span class="badge badge-rose">✕ Lỗi mạng</span>`;
        }
    }

    progressBar.style.width = '100%';
    percentBadge.textContent = '100%';
    percentBadge.className = 'badge badge-emerald';
    statusText.innerHTML = `🎉 Đã hoàn tất cào và AI viết ${successCount}/${validUrls.length} bài viết!`;
    document.getElementById('batch-spinner').style.display = 'none';

    btn.disabled = false;
    btn.innerHTML = '🕷️ Bắt Đầu Cào Dữ Liệu &amp; AI Tái Cấu Trúc Ngay';
    showToast(`🎉 Xong! Đã xuất bản ${successCount}/${validUrls.length} bài viết thành công lên website!`);
}

function populateResult(data, jobId, latencyMs, defaultType) {
    const wrap = document.getElementById('generated-result-wrap');
    document.getElementById('gen-job-id').value = jobId || '';
    document.getElementById('gen-title').value = data.title || '';
    document.getElementById('gen-excerpt').value = data.excerpt || '';
    document.getElementById('gen-markdown').value = data.content_markdown || '';
    document.getElementById('gen-type').value = defaultType || 'comparison';
    if (data.featured_image_url) {
        document.getElementById('gen-image').value = data.featured_image_url;
    }
    if (data.faqs) {
        document.getElementById('gen-faqs-json').value = JSON.stringify(data.faqs);
    }
    document.getElementById('gen-latency-badge').innerText = `Sinh thành công trong ${latencyMs} ms`;
    
    const sourceBadge = document.getElementById('gen-source-badge');
    if (data.is_live_ai) {
        sourceBadge.className = 'badge badge-emerald';
        sourceBadge.innerText = '🟢 Live LLM (Gemini/OpenAI)';
    } else {
        sourceBadge.className = 'badge badge-amber';
        sourceBadge.innerText = '🟡 Fallback Synthesizer';
    }

    wrap.style.display = 'block';
    wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>
@endsection
