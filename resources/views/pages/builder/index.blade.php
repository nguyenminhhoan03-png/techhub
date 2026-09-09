@extends('layouts.app')

@section('meta_title', app()->getLocale() === 'en' ? 'Website Builder - Visual Drag & Drop Studio | muabanwebsite.io.vn' : 'Website Builder - Nền Tảng Kéo Thả Trực Quan | muabanwebsite.io.vn')
@section('meta_description', app()->getLocale() === 'en' ? 'Build professional landing pages in minutes with visual drag-and-drop. High-speed static publishing powered by AWS S3 & Cloudflare CDN.' : 'Xây dựng website landing page chuyên nghiệp trong vài phút với trình kéo thả trực quan. Xuất bản tĩnh tốc độ cao với AWS S3 và Cloudflare CDN.')
@section('canonical_url', route('builder.index'))

@section('content')
<section class="builder-dashboard-section" style="padding: 2.5rem 0 5rem;">
    <div class="container">

        {{-- Breadcrumb --}}
        <div class="breadcrumb" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
            <a href="{{ url('/') }}" style="color: var(--text-sub, #475569); text-decoration: none;">{{ __('home') }}</a>
            <span style="color: #cbd5e1;">/</span>
            <span style="color: var(--text-main, #0f172a); font-weight: 700;">🌐 {{ __('builder_title') }}</span>
        </div>

        {{-- Hero Header Banner --}}
        <div style="background: linear-gradient(135deg, #090d16 0%, #111827 50%, #1e1b4b 100%); border: 1px solid rgba(59, 130, 246, 0.35); border-radius: var(--radius-xl, 18px); padding: 2.5rem 2.25rem; margin-bottom: 2.5rem; position: relative; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.15);">
            <div style="position: absolute; right: -60px; top: -60px; width: 320px; height: 320px; background: radial-gradient(circle, rgba(59, 130, 246, 0.3) 0%, transparent 70%); pointer-events: none;"></div>
            <div style="position: absolute; left: 25%; bottom: -80px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(14, 165, 233, 0.2) 0%, transparent 70%); pointer-events: none;"></div>

            <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.75rem;">
                <div style="max-width: 720px;">
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.45); padding: 0.35rem 0.95rem; border-radius: 999px; font-size: 0.8rem; font-weight: 800; color: #93c5fd; margin-bottom: 1rem; letter-spacing: 0.02em;">
                        <span>⚡</span> <span>{{ __('builder_hero_badge') }}</span>
                    </div>
                    <h1 style="font-size: 2.3rem; font-weight: 900; color: #ffffff; line-height: 1.25; margin-bottom: 0.85rem; letter-spacing: -0.02em;">
                        {{ __('builder_hero_h1') }}
                    </h1>
                    <p style="color: #cbd5e1; font-size: 1rem; line-height: 1.7; margin-bottom: 0;">
                        {{ __('builder_hero_desc') }}
                    </p>
                </div>

                <div>
                    <button onclick="document.getElementById('modal-create-site').style.display='flex'" class="btn btn-primary" style="background: linear-gradient(135deg, #2563eb, #0284c7); border: none; font-weight: 800; font-size: 1rem; padding: 0.9rem 1.8rem; border-radius: 12px; box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35); display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #ffffff;">
                        <span style="font-size: 1.15rem;">✨</span> <span>{{ __('builder_create_btn') }}</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Stats Bar (High Contrast Light Cards) --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.25rem; margin-bottom: 2.75rem;">
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.4rem 1.6rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <div style="color: #64748b; font-size: 0.88rem; font-weight: 700; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                    <span>📁</span> <span>{{ __('builder_total_sites') }}</span>
                </div>
                <div style="font-size: 2.2rem; font-weight: 900; color: #0f172a; line-height: 1;">{{ $websites->count() }}</div>
            </div>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.4rem 1.6rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <div style="color: #059669; font-size: 0.88rem; font-weight: 700; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                    <span>🟢</span> <span>{{ __('builder_published_sites') }}</span>
                </div>
                <div style="font-size: 2.2rem; font-weight: 900; color: #059669; line-height: 1;">{{ $websites->where('status.value', 'published')->count() }}</div>
            </div>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.4rem 1.6rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <div style="color: #0284c7; font-size: 0.88rem; font-weight: 700; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                    <span>🌐</span> <span>{{ __('builder_custom_domains') }}</span>
                </div>
                <div style="font-size: 2.2rem; font-weight: 900; color: #0284c7; line-height: 1;">{{ $websites->sum(fn($w) => $w->domains->count()) }}</div>
            </div>

            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.4rem 1.6rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <div style="color: #d97706; font-size: 0.88rem; font-weight: 700; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;">
                    <span>☁️</span> <span>{{ __('builder_storage_infra') }}</span>
                </div>
                <div style="font-size: 1.3rem; font-weight: 800; color: #d97706; line-height: 1.3; margin-top: 0.3rem;">AWS S3 + Cloudflare</div>
            </div>
        </div>

        {{-- Website List Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.6rem; margin: 0;">
                <span>📂</span> <span>{{ __('builder_site_list') }}</span>
            </h2>
            <span style="color: #64748b; font-size: 0.9rem; font-weight: 600;">{{ __('builder_projects_ready', ['count' => $websites->count()]) }}</span>
        </div>

        @if($websites->isEmpty())
            <div style="background: #ffffff; border: 2px dashed #cbd5e1; border-radius: 20px; padding: 4.5rem 2rem; text-align: center;">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">🌐</div>
                <h3 style="color: #0f172a; font-size: 1.35rem; font-weight: 800; margin-bottom: 0.6rem;">{{ __('builder_empty_title') }}</h3>
                <p style="color: #64748b; max-width: 480px; margin: 0 auto 1.75rem; font-size: 0.95rem; line-height: 1.6;">
                    {{ __('builder_empty_desc') }}
                </p>
                <button onclick="document.getElementById('modal-create-site').style.display='flex'" class="btn btn-primary" style="background: #2563eb; color: #fff; font-weight: 700; padding: 0.8rem 1.8rem; border-radius: 10px; border: none; cursor: pointer;">
                    {{ __('builder_create_now') }}
                </button>
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 1.75rem;">
                @foreach($websites as $website)
                    @php
                        $homePage = $website->pages->firstWhere('is_home', true) ?: $website->pages->first();
                        $latestRelease = $website->publishedSites->first();
                    @endphp
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 1.85rem; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.05);" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 30px rgba(37, 99, 235, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.05)';">
                        <div>
                            {{-- Top Status Bar --}}
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                                @if($website->status->value === 'published')
                                    <span style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; font-size: 0.8rem; font-weight: 800; padding: 0.3rem 0.75rem; border-radius: 999px; display: inline-flex; align-items: center; gap: 0.4rem;">
                                        <span style="width: 7px; height: 7px; background: #10b981; border-radius: 50%;"></span> {{ __('builder_status_published') }}
                                    </span>
                                @else
                                    <span style="background: #fffbeb; border: 1px solid #fde68a; color: #92400e; font-size: 0.8rem; font-weight: 800; padding: 0.3rem 0.75rem; border-radius: 999px;">
                                        📝 {{ __('builder_status_draft') }}
                                    </span>
                                @endif

                                <span style="color: #64748b; font-size: 0.82rem; font-weight: 700; background: #f1f5f9; padding: 0.25rem 0.65rem; border-radius: 6px;">
                                    {{ __('builder_pages_count', ['count' => $website->pages->count()]) }}
                                </span>
                            </div>

                            {{-- Website Title (Crisp Dark Text) --}}
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 0.85rem; line-height: 1.35; letter-spacing: -0.01em;">
                                {{ $website->name }}
                            </h3>

                            {{-- Subdomain Box --}}
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 0.85rem;">
                                <div style="font-size: 0.72rem; color: #64748b; font-weight: 700; margin-bottom: 0.2rem; text-transform: uppercase;">{{ __('builder_system_subdomain') }}</div>
                                <div style="font-family: monospace; font-size: 0.9rem; color: #0284c7; font-weight: 700; word-break: break-all;">
                                    🔗 https://{{ $website->subdomain }}.muabanwebsite.io.vn
                                </div>
                            </div>

                            {{-- Custom Domain Box --}}
                            @if($website->domains->isNotEmpty())
                                <div style="background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.1rem;">
                                    <div style="font-size: 0.72rem; color: #6d28d9; font-weight: 700; margin-bottom: 0.2rem; text-transform: uppercase;">{{ __('builder_custom_domain_attached') }}</div>
                                    <div style="font-family: monospace; font-size: 0.9rem; color: #5b21b6; font-weight: 700;">
                                        🌐 https://{{ $website->domains->first()->domain }}
                                    </div>
                                </div>
                            @endif

                            {{-- Pages Chips --}}
                            <div style="margin-top: 1rem; margin-bottom: 1.5rem;">
                                <div style="font-size: 0.78rem; color: #64748b; font-weight: 700; margin-bottom: 0.5rem;">{{ __('builder_pages_in_site') }}</div>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                    @foreach($website->pages as $p)
                                        <a href="{{ route('builder.editor', $p->id) }}" style="background: #f1f5f9; border: 1px solid #e2e8f0; color: #1e293b; padding: 0.35rem 0.75rem; border-radius: 8px; font-size: 0.82rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.background='#f1f5f9'; this.style.borderColor='#e2e8f0';">
                                            <span>{{ $p->is_home ? '🏠' : '📄' }}</span>
                                            <span>{{ $p->title }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div style="border-top: 1px solid #f1f5f9; padding-top: 1.35rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            @if($homePage)
                                <a href="{{ route('builder.editor', $homePage->id) }}" class="btn btn-primary" style="flex: 1; min-width: 140px; font-size: 0.92rem; font-weight: 800; text-align: center; text-decoration: none; padding: 0.75rem 1.2rem; border-radius: 10px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; border: none; display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);">
                                    <span>🎨</span> <span>{{ __('builder_open_editor') }}</span>
                                </a>
                            @endif

                            <a href="{{ route('builder.preview', $website->id) }}" target="_blank" class="btn btn-secondary" style="font-size: 0.92rem; font-weight: 700; text-decoration: none; padding: 0.75rem 1.2rem; border-radius: 10px; background: #ffffff; color: #334155; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 0.45rem; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#cbd5e1';">
                                <span>👁️</span> <span>{{ __('builder_view_live') }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

{{-- Modal Tạo Website Mới (Light Theme Clean & Crisp) --}}
<div id="modal-create-site" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 99999; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; width: 100%; max-width: 520px; padding: 2.25rem; box-shadow: 0 25px 60px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="color: #0f172a; font-size: 1.35rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <span>✨</span> <span>{{ __('builder_modal_create_title') }}</span>
            </h3>
            <button onclick="document.getElementById('modal-create-site').style.display='none'" style="background: none; border: none; color: #64748b; font-size: 1.8rem; cursor: pointer; line-height: 1;">&times;</button>
        </div>

        <form id="form-create-site" onsubmit="handleCreateSite(event)">
            <div style="margin-bottom: 1.35rem;">
                <label style="display: block; color: #334155; font-weight: 700; font-size: 0.92rem; margin-bottom: 0.55rem;">{{ __('builder_modal_site_name') }}</label>
                <input type="text" id="site-name" required placeholder="{{ __('builder_modal_site_name_ph') }}" style="width: 100%; background: #f8fafc; border: 1px solid #cbd5e1; color: #0f172a; padding: 0.85rem 1.1rem; border-radius: 10px; font-size: 1rem; box-sizing: border-box; outline: none;" onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label style="display: block; color: #334155; font-weight: 700; font-size: 0.92rem; margin-bottom: 0.55rem;">{{ __('builder_modal_subdomain') }}</label>
                <div style="display: flex; align-items: center;">
                    <input type="text" id="site-subdomain" placeholder="{{ __('builder_modal_subdomain_ph') }}" style="flex: 1; background: #f8fafc; border: 1px solid #cbd5e1; border-right: none; color: #0f172a; padding: 0.85rem 1.1rem; border-radius: 10px 0 0 10px; font-size: 1rem; box-sizing: border-box; outline: none;">
                    <span style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 0.85rem 1rem; border-radius: 0 10px 10px 0; font-size: 0.88rem; font-weight: 600;">.muabanwebsite.io.vn</span>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.85rem;">
                <button type="button" onclick="document.getElementById('modal-create-site').style.display='none'" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 0.75rem 1.4rem; border-radius: 10px; font-weight: 700; cursor: pointer;">{{ __('builder_modal_cancel') }}</button>
                <button type="submit" id="btn-submit-site" style="background: linear-gradient(135deg, #2563eb, #0284c7); color: #ffffff; border: none; padding: 0.75rem 1.75rem; border-radius: 10px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 14px rgba(37,99,235,0.35);">{{ __('builder_modal_submit') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
async function handleCreateSite(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-submit-site');
    btn.disabled = true;
    btn.innerText = "{{ __('builder_modal_creating') }}";

    const name = document.getElementById('site-name').value;
    const subdomain = document.getElementById('site-subdomain').value;

    try {
        const res = await fetch('/api/builder/websites', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, subdomain })
        });

        const data = await res.json();
        if (data.success) {
            const homePage = data.data.pages.find(p => p.is_home) || data.data.pages[0];
            window.location.href = `/builder/editor/${homePage.id}`;
        } else {
            alert('Error: ' + (data.message || 'Unable to create website'));
            btn.disabled = false;
            btn.innerText = "{{ __('builder_modal_submit') }}";
        }
    } catch (err) {
        alert("{{ app()->getLocale() === 'en' ? 'Server connection error!' : 'Lỗi kết nối tới máy chủ!' }}");
        btn.disabled = false;
        btn.innerText = "{{ __('builder_modal_submit') }}";
    }
}
</script>
@endsection
