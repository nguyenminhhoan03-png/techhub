<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Studio Builder Pro - {{ $page->title }} | {{ $page->website->name }}</title>
    <link rel="icon" href="https://muabanwebsite.io.vn/favicon.ico">

    {{-- Google Fonts, Font Awesome & GrapesJS Core CSS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">

    <style>
        :root {
            --bg-canvas-outer: #f1f5f9;
            --bg-studio-dark: #ffffff;
            --bg-studio-panel: #ffffff;
            --bg-studio-card: #ffffff;
            --bg-studio-card-hover: #f8fafc;
            --bg-studio-input: #ffffff;
            --border-studio: #e2e8f0;
            --border-studio-light: #cbd5e1;
            --border-focus: #2563eb;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-glow: rgba(37, 99, 235, 0.2);
            --accent-cyan: #0284c7;
            --accent-cyan-glow: rgba(2, 132, 199, 0.15);
            --accent-emerald: #059669;
            --accent-emerald-glow: rgba(5, 150, 105, 0.15);
            --accent-amber: #d97706;
            --accent-rose: #e11d48;
            --accent-violet: #7c3aed;
            --text-main: #0f172a;
            --text-muted: #475569;
            --text-subtle: #64748b;

            /* GrapesJS Light Studio Variables Override (Eliminates default dark gray & fixes .gjs-one-bg) */
            --gjs-main-color: #ffffff;
            --gjs-primary-color: #ffffff; /* Must be white! GrapesJS uses .gjs-one-bg { background-color: var(--gjs-primary-color); } */
            --gjs-secondary-color: #0f172a; /* Used for text & icons in .gjs-two-color */
            --gjs-tertiary-color: #f8fafc;
            --gjs-quaternary-color: #e2e8f0;
            --gjs-font-color: #0f172a;
            --gjs-font-color-active: #2563eb;
            --gjs-main-dark-color: #ffffff;
            --gjs-secondary-dark-color: #f8fafc;
            --gjs-main-light-color: #f8fafc;
            --gjs-secondary-light-color: #64748b;
            --gjs-soft-light-color: #f1f5f9;
            --gjs-color-blue: #2563eb;
            --gjs-color-highlight: #2563eb;
            --gjs-arrow-color: #64748b;
            --gjs-light-border: #e2e8f0;
            --gjs-font-size: 0.74rem;
            --gjs-main-font: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body, html {
            height: 100%;
            width: 100%;
            max-width: 100vw;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-canvas-outer);
            color: var(--text-main);
            user-select: none;
            -webkit-font-smoothing: antialiased;
        }

        /* Custom Modern Light Scrollbars */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ==========================================================================
           1. TOPBAR HEADER (Clean Studio Bar - Light Theme)
           ========================================================================== */
        .studio-topbar {
            height: 50px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-studio);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 0.85rem;
            position: relative;
            z-index: 1000;
            gap: 0.6rem;
            max-width: 100vw;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .topbar-left, .topbar-center, .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-shrink: 0;
        }

        .btn-topbar {
            background: #ffffff;
            border: 1px solid var(--border-studio);
            color: #334155;
            padding: 0.34rem 0.62rem;
            border-radius: 7px;
            font-size: 0.74rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.16s ease;
            white-space: nowrap;
        }
        .btn-topbar:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: var(--border-studio-light);
            transform: translateY(-1px);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .btn-icon-only {
            padding: 0.34rem 0.52rem;
            font-size: 0.8rem;
        }

        .store-brand-badge {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: #f8fafc;
            border: 1px solid var(--border-studio);
            padding: 0.28rem 0.6rem;
            border-radius: 7px;
            font-size: 0.78rem;
            font-weight: 800;
            color: #0f172a;
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .page-badge-select {
            background: #ffffff;
            border: 1px solid var(--border-studio);
            color: #0f172a;
            padding: 0.3rem 0.6rem;
            border-radius: 7px;
            font-size: 0.74rem;
            font-weight: 700;
            outline: none;
            cursor: pointer;
            max-width: 160px;
            transition: border-color 0.15s ease;
        }
        .page-badge-select:focus {
            border-color: var(--primary);
        }

        .version-tag {
            font-family: monospace;
            font-size: 0.68rem;
            font-weight: 800;
            background: #eff6ff;
            color: var(--primary);
            padding: 2px 7px;
            border-radius: 999px;
            border: 1px solid #bfdbfe;
        }

        /* Device Switcher (Segmented Control) */
        .device-toggle-pill {
            display: flex;
            background: #f1f5f9;
            border: 1px solid var(--border-studio);
            border-radius: 8px;
            padding: 2px;
            gap: 2px;
        }
        .device-btn {
            background: none;
            border: none;
            color: #64748b;
            padding: 0.28rem 0.65rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.73rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.15s ease;
        }
        .device-btn:hover { color: #0f172a; }
        .device-btn.active {
            background: #ffffff;
            color: var(--primary);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            font-weight: 800;
        }

        .lang-toggle-btn {
            background: #f8fafc;
            border: 1px solid var(--border-studio);
            color: #475569;
            padding: 0.3rem 0.55rem;
            border-radius: 7px;
            font-size: 0.73rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.15s ease;
        }
        .lang-toggle-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .status-pill {
            font-size: 0.72rem;
            font-weight: 700;
            color: #059669;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.28rem 0.6rem;
            border-radius: 999px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
        }

        .btn-save-draft {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #2563eb;
            font-weight: 700;
        }
        .btn-save-draft:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }

        .btn-publish-ssg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: #ffffff;
            font-weight: 800;
            letter-spacing: 0.02em;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
        }
        .btn-publish-ssg:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        }

        /* ==========================================================================
           2. WORKSPACE LAYOUT (LEFT 340px - CANVAS FLEX 1 - RIGHT 320px)
           ========================================================================== */
        .studio-workspace {
            display: flex;
            height: calc(100vh - 50px);
            width: 100%;
            max-width: 100vw;
            overflow: hidden;
            position: relative;
        }

        /* LEFT ICON RAIL */
        .left-rail {
            width: 50px;
            background: #ffffff;
            border-right: 1px solid var(--border-studio);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.75rem 0;
            gap: 0.5rem;
            flex-shrink: 0;
            z-index: 30;
        }

        .rail-btn {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: transparent;
            border: 1px solid transparent;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.16s ease;
            position: relative;
        }
        .rail-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
            transform: scale(1.05);
        }
        .rail-btn.active {
            background: #eff6ff;
            color: var(--primary);
            border-color: #bfdbfe;
        }
        .rail-btn.active::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: var(--primary);
            border-radius: 0 4px 4px 0;
        }

        .rail-spacer { flex: 1; }

        /* LEFT DRAWER */
        .left-drawer {
            width: 290px;
            background: #ffffff;
            border-right: 1px solid var(--border-studio);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 25;
            transition: width 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }
        .left-drawer.collapsed {
            width: 0 !important;
            border-right: none !important;
        }

        .drawer-header {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-studio);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .drawer-title {
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .drawer-search {
            padding: 0.65rem 0.9rem;
            border-bottom: 1px solid var(--border-studio);
            background: #f8fafc;
        }
        .drawer-search-input {
            width: 100%;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            padding: 0.42rem 0.7rem;
            border-radius: 7px;
            font-size: 0.74rem;
            outline: none;
            transition: all 0.15s ease;
        }
        .drawer-search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
        }

        .drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 0.85rem;
        }

        /* ==========================================================================
           GRAPESJS BLOCK MANAGER - PURE WHITE STUDIO THEME (Figma / Webflow Quality)
           ========================================================================== */
        .gjs-one-bg {
            background-color: #ffffff !important;
            background: #ffffff !important;
        }
        .gjs-two-bg {
            background-color: #f8fafc !important;
            background: #f8fafc !important;
        }
        .gjs-two-color {
            color: #1e293b !important;
        }

        #grapes-blocks-container {
            background: #ffffff !important;
            padding: 0 !important;
        }

        .gjs-block-categories {
            background: #ffffff !important;
            padding: 0 !important;
        }

        .gjs-block-category {
            background: #ffffff !important;
            border: none !important;
            border-bottom: none !important;
            margin-bottom: 12px !important;
            padding: 0 !important;
        }

        .gjs-blocks-cs {
            background: #ffffff !important;
            padding: 0 !important;
        }

        /* Category Accordion Header */
        .gjs-block-category .gjs-title {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            color: #0f172a !important;
            font-size: 0.74rem !important;
            font-weight: 800 !important;
            padding: 9px 12px !important;
            margin-top: 10px !important;
            margin-bottom: 6px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1) !important;
            user-select: none !important;
            letter-spacing: 0.01em !important;
        }
        .gjs-block-category .gjs-title:hover {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: var(--primary) !important;
        }
        .gjs-block-category.gjs-open .gjs-title {
            background: #eff6ff !important;
            border-color: #bfdbfe !important;
            color: var(--primary) !important;
        }
        .gjs-block-category .gjs-title > * {
            pointer-events: none;
        }
        .gjs-block-category .gjs-title .gjs-caret-icon,
        .gjs-block-category .gjs-title .gjs-caret {
            display: inline-block !important;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), color 0.15s ease !important;
            color: #64748b !important;
        }
        .gjs-block-category.gjs-open .gjs-title .gjs-caret-icon,
        .gjs-block-category.gjs-open .gjs-title .gjs-caret {
            color: var(--primary) !important;
        }

        /* Category Blocks: Thu gọn sạch sẽ khi đóng (Hidden when closed) */
        .gjs-block-category .gjs-blocks-c,
        .gjs-block-category:not(.gjs-open) .gjs-blocks-c,
        .gjs-block-category .gjs-blocks-c[style*="display: none"],
        .gjs-blocks-c[style*="display: none"] {
            display: none !important;
        }

        /* Category Blocks: Mở rộng lưới 2 cột khi sổ ra (Grid when open) */
        .gjs-block-category.gjs-open .gjs-blocks-c {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
            padding: 4px 0 12px 0 !important;
            background: transparent !important;
        }

        /* Top-level blocks nếu không nằm trong category */
        .gjs-blocks-cs > .gjs-blocks-c {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
            padding: 4px 0 12px 0 !important;
            background: transparent !important;
        }

        /* Individual Block Card */
        .gjs-block {
            width: auto !important;
            min-height: 76px !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
            color: #334155 !important;
            padding: 12px 8px !important;
            margin: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            cursor: pointer !important;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1) !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03) !important;
            user-select: none !important;
            position: relative !important;
            overflow: hidden !important;
        }
        .gjs-block::after {
            content: '+ Chèn';
            position: absolute;
            bottom: 3px;
            right: 5px;
            background: #eff6ff;
            color: var(--primary);
            font-size: 9px;
            font-weight: 800;
            padding: 1px 5px;
            border-radius: 4px;
            opacity: 0;
            transform: translateY(4px);
            transition: all 0.15s ease;
            pointer-events: none;
            border: 1px solid #bfdbfe;
        }
        .gjs-block:hover {
            background: #ffffff !important;
            border-color: var(--primary) !important;
            color: var(--primary) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.13) !important;
        }
        .gjs-block:hover::after {
            opacity: 1;
            transform: translateY(0);
        }
        .gjs-block:active {
            cursor: grabbing !important;
            transform: scale(0.97) !important;
        }
        .gjs-block i, .gjs-block svg, .gjs-block span {
            font-size: 1.25rem !important;
            color: inherit !important;
            transition: transform 0.18s ease !important;
        }
        .gjs-block:hover i, .gjs-block:hover svg {
            transform: scale(1.12) !important;
            color: var(--primary) !important;
        }
        .gjs-block-label {
            font-size: 0.70rem !important;
            font-weight: 700 !important;
            margin-top: 6px !important;
            line-height: 1.3 !important;
            color: #1e293b !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }
        .gjs-block:hover .gjs-block-label {
            color: var(--primary) !important;
        }

        .section-template-card {
            background: #ffffff;
            border: 1px solid var(--border-studio);
            border-radius: 10px;
            padding: 0.7rem;
            margin-bottom: 0.65rem;
            cursor: pointer;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            gap: 0.65rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        .section-template-card:hover {
            border-color: var(--primary);
            background: #eff6ff;
            transform: translateX(4px);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.1);
        }
        .section-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #eff6ff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            border: 1px solid #bfdbfe;
        }
        .section-meta-title { font-size: 0.78rem; font-weight: 800; color: #0f172a; margin-bottom: 2px; }
        .section-meta-desc { font-size: 0.66rem; color: #64748b; line-height: 1.35; }

        /* ==========================================================================
           3. CENTER CANVAS (ZOOM & PAN WORKSPACE, RESIZABLE HANDLES)
           ========================================================================== */
        .canvas-area {
            flex: 1 1 0%;
            min-width: 0;
            height: 100%;
            background: #f1f5f9;
            background-image: radial-gradient(rgba(148, 163, 184, 0.4) 1px, transparent 1px);
            background-size: 20px 20px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Canvas Top Toolbar (Zoom, Pan, Width, Inspector Toggle) */
        .canvas-header-bar {
            height: 38px;
            width: 100%;
            background: #ffffff;
            border-bottom: 1px solid var(--border-studio);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 0.85rem;
            font-size: 0.74rem;
            color: var(--text-muted);
            z-index: 15;
            flex-shrink: 0;
            gap: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .canvas-width-pill {
            font-family: monospace;
            font-size: 0.72rem;
            color: var(--primary);
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 1px 8px;
            border-radius: 5px;
            font-weight: 800;
        }

        .canvas-shortcut-pill {
            position: relative;
            font-size: 0.68rem;
            color: #475569;
            background: #f8fafc;
            border: 1px solid var(--border-studio);
            padding: 2px 9px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: none;
        }
        .canvas-shortcut-pill:hover {
            background: #eff6ff;
            color: var(--primary);
            border-color: #bfdbfe;
        }
        .shortcut-tooltip-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: #ffffff;
            border: 1px solid var(--border-studio-light);
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 0.7rem;
            color: #334155;
            white-space: nowrap;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            z-index: 1000;
            pointer-events: none;
            flex-direction: column;
            gap: 6px;
        }
        .canvas-shortcut-pill:hover .shortcut-tooltip-dropdown {
            display: flex;
        }
        .st-row {
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .st-row kbd {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 1px 6px;
            border-radius: 4px;
            color: #0f172a;
            font-size: 0.65rem;
            font-family: monospace;
            font-weight: 700;
        }
        .st-row span {
            color: var(--primary);
            font-weight: 600;
        }

        /* Zoom & Pan Group */
        .canvas-control-group {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            flex-shrink: 0;
            white-space: nowrap;
        }
        .btn-canvas-tool {
            background: #ffffff;
            border: 1px solid var(--border-studio);
            color: #334155;
            padding: 0.22rem 0.52rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.15s ease;
            white-space: nowrap !important;
            flex-shrink: 0 !important;
            height: 28px;
            box-sizing: border-box;
            line-height: 1;
        }
        .btn-canvas-tool:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: var(--border-studio-light);
        }
        .btn-canvas-tool.active {
            background: #eff6ff;
            color: var(--primary);
            border-color: #bfdbfe;
            box-shadow: 0 0 8px rgba(37, 99, 235, 0.15);
        }
        @media (max-width: 1400px) {
            .hide-on-compact {
                display: none !important;
            }
        }

        /* Ẩn badge hiển thị tên "Body" trên khung vẽ */
        .gjs-badge[data-badge="Body"],
        .gjs-badge[data-badge="body"],
        .gjs-badge[data-badge="wrapper"],
        .gjs-badge[data-badge="Wrapper"] {
            display: none !important;
        }

        /* Canvas Interactive Viewport */
        .canvas-interactive-stage {
            flex: 1 1 0%;
            min-height: 0;
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Canvas Viewport Frame */
        .canvas-viewport-wrapper {
            position: relative;
            background: #ffffff;
            height: calc(100% - 28px);
            min-height: 560px;
            border-radius: 8px;
            transition: box-shadow 0.2s ease;
            box-shadow: 0 20px 50px -10px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(15, 23, 42, 0.08);
            transform-origin: center center;
            will-change: transform;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
        }

        .canvas-viewport-wrapper.mode-desktop {
            width: 1200px;
        }
        .canvas-viewport-wrapper.mode-tablet {
            width: 768px;
            border-radius: 14px;
        }
        .canvas-viewport-wrapper.mode-mobile {
            width: 375px;
            border-radius: 20px;
        }

        #gjs-canvas-wrapper {
            width: 100%;
            height: 100%;
            flex: 1;
            background: #ffffff;
            overflow: hidden;
            position: relative;
            border-radius: inherit;
        }

        /* Resizer Drag Handles on Left, Right & Bottom of Canvas Frame */
        .canvas-resizer-handle {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 18px;
            cursor: col-resize;
            z-index: 60;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.9;
            transition: opacity 0.15s, background 0.15s;
        }
        .canvas-resizer-handle:hover {
            opacity: 1;
        }
        .canvas-resizer-handle::after {
            content: '⋮';
            font-size: 14px;
            line-height: 1;
            color: #64748b;
            font-weight: 900;
            width: 10px;
            height: 44px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 99px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }
        .canvas-resizer-handle:hover::after {
            background: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.4);
        }
        .canvas-resizer-handle.handle-left {
            left: -18px;
        }
        .canvas-resizer-handle.handle-right {
            right: -18px;
        }
        .canvas-resizer-handle.handle-bottom {
            top: auto;
            bottom: -18px;
            left: 0;
            right: 0;
            width: 100%;
            height: 18px;
            cursor: row-resize;
        }
        .canvas-resizer-handle.handle-bottom::after {
            content: '⋯';
            width: 52px;
            height: 10px;
            font-size: 14px;
            line-height: 7px;
        }

        /* Pan Drag Overlay */
        #pan-drag-overlay {
            display: none;
            position: absolute;
            inset: 0;
            z-index: 9999;
            cursor: grab;
        }
        body.is-panning #pan-drag-overlay {
            display: block;
            cursor: grabbing;
        }
        body.pan-mode-active #pan-drag-overlay {
            display: block;
            cursor: grab;
        }

        /* Zoom Dropdown Menu */
        .zoom-dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 4px;
            background: #ffffff;
            border: 1px solid var(--border-studio);
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 1000;
            min-width: 130px;
            padding: 4px 0;
        }
        .zoom-dropdown-menu.open { display: block; }
        .zoom-opt {
            padding: 6px 12px;
            font-size: 0.72rem;
            color: #334155;
            cursor: pointer;
            transition: background 0.15s;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .zoom-opt:hover {
            background: #eff6ff;
            color: var(--primary);
        }

        /* ==========================================================================
           4. RIGHT INSPECTOR (STRICTLY DOCKED & TOGGLEABLE)
           ========================================================================== */
        .sidebar-right {
            width: 320px;
            background: #ffffff;
            border-left: 1px solid var(--border-studio);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            height: 100%;
            z-index: 25;
            transition: width 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }
        .sidebar-right.collapsed {
            width: 0 !important;
            border-left: none !important;
        }

        .inspector-tabs {
            display: flex;
            border-bottom: 1px solid var(--border-studio);
            background: #f8fafc;
            flex-shrink: 0;
            padding: 4px 6px;
            gap: 4px;
        }
        .inspector-tab-btn {
            flex: 1;
            padding: 0.55rem 0.3rem;
            text-align: center;
            font-size: 0.74rem;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 6px;
            background: none;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
        }
        .inspector-tab-btn:hover { color: #0f172a; background: #ffffff; }
        .inspector-tab-btn.active {
            color: var(--primary);
            background: #ffffff;
            border-color: #e2e8f0;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .inspector-tab-pane {
            flex: 1;
            overflow-y: auto;
            padding: 0.85rem;
            display: none;
        }
        .inspector-tab-pane.active { display: block; }

        .selected-element-card {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 9px;
            padding: 0.7rem 0.8rem;
            margin-bottom: 0.85rem;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
        }
        .selected-tag-badge {
            font-family: monospace;
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--primary);
            background: #dbeafe;
            border: 1px solid #bfdbfe;
            padding: 2px 7px;
            border-radius: 5px;
        }

        .reorder-actions-bar {
            display: flex;
            gap: 0.3rem;
            margin-top: 0.55rem;
        }
        .btn-move-action {
            flex: 1;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
            padding: 0.35rem 0.2rem;
            border-radius: 6px;
            font-size: 0.66rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .btn-move-action:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
            transform: translateY(-1px);
        }
        .btn-move-action.danger:hover {
            background: var(--accent-rose);
            color: #ffffff;
            border-color: var(--accent-rose);
        }

        /* Smart Contextual Setup Cards */
        .smart-setup-card {
            background: #f8fafc;
            border: 1px solid var(--border-studio);
            border-radius: 10px;
            padding: 0.85rem;
            margin-bottom: 0.85rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        .smart-setup-header {
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0f172a;
            margin-bottom: 0.65rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            padding-bottom: 0.45rem;
            border-bottom: 1px solid var(--border-studio);
        }
        .smart-form-row {
            margin-bottom: 0.65rem;
        }
        .smart-form-row label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.3rem;
        }
        .smart-checkbox-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.45rem;
            margin-bottom: 0.65rem;
            background: #ffffff;
            padding: 0.55rem;
            border-radius: 7px;
            border: 1px solid var(--border-studio);
        }
        .smart-toggle-label {
            font-size: 0.68rem;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            user-select: none;
        }
        .smart-toggle-label input[type="checkbox"] {
            accent-color: var(--primary);
            cursor: pointer;
        }
        .btn-sample-pill {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
            padding: 0.24rem 0.55rem;
            border-radius: 5px;
            font-size: 0.68rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-sample-pill:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        /* Empty State */
        .inspector-empty-state {
            padding: 2.2rem 0.75rem;
            text-align: center;
            color: #64748b;
        }

        /* Visual Spacing Box Model (Webflow Light Style) */
        .box-model-container {
            background: #f8fafc;
            border: 1px solid var(--border-studio);
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 0.85rem;
        }
        .box-model-title {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #475569;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .bm-outer-margin {
            background: rgba(245, 158, 11, 0.08);
            border: 1px dashed rgba(217, 119, 6, 0.5);
            border-radius: 8px;
            padding: 26px 42px;
            position: relative;
            min-height: 155px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bm-inner-padding {
            background: rgba(16, 185, 129, 0.08);
            border: 1px dashed rgba(5, 150, 105, 0.5);
            border-radius: 6px;
            padding: 24px 38px;
            position: relative;
            width: 100%;
            min-height: 98px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bm-center-core {
            background: rgba(2, 132, 199, 0.1);
            border: 1px solid rgba(2, 132, 199, 0.35);
            border-radius: 5px;
            padding: 5px 8px;
            text-align: center;
            font-family: 'JetBrains Mono', Consolas, monospace;
            font-size: 0.65rem;
            font-weight: 800;
            color: #0284c7;
            width: 100%;
            max-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .bm-tag {
            position: absolute;
            font-size: 0.58rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            top: 4px;
            left: 6px;
            user-select: none;
            line-height: 1;
        }
        .tag-margin { color: #b45309; }
        .tag-padding { color: #047857; }
        .bm-input {
            position: absolute;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            font-size: 0.66rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', Consolas, monospace;
            width: 34px;
            height: 18px;
            line-height: 16px;
            text-align: center;
            border-radius: 4px;
            outline: none;
            padding: 0;
            margin: 0;
            transition: all 0.15s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            z-index: 2;
        }
        .bm-input:hover {
            border-color: #94a3b8;
            background: #ffffff;
        }
        .bm-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
            background: #ffffff;
        }
        .bm-pos-top { top: 4px; left: 50%; transform: translateX(-50%); }
        .bm-pos-bottom { bottom: 4px; left: 50%; transform: translateX(-50%); }
        .bm-pos-left { left: 4px; top: 50%; transform: translateY(-50%); }
        .bm-pos-right { right: 4px; top: 50%; transform: translateY(-50%); }

        /* ==========================================================================
           GRAPESJS CANVAS CLEANUP: REMOVE DEFAULT 40px TOP OFFSET & GRAY BACKGROUND
           ========================================================================== */
        :root {
            --gjs-canvas-top: 0px !important;
            --gjs-left-width: 0px !important;
            --gjs-main-color: transparent !important;
        }

        .gjs-cv-canvas {
            width: 100% !important;
            height: 100% !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background-color: #ffffff !important;
            background: #ffffff !important;
            position: absolute !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .gjs-cv-canvas-bg {
            background-color: #ffffff !important;
            background: #ffffff !important;
        }

        .gjs-cv-canvas__frames {
            width: 100% !important;
            height: 100% !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            position: absolute !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .gjs-frame-wrapper {
            width: 100% !important;
            height: 100% !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            position: absolute !important;
            background: #ffffff !important;
        }

        .gjs-frame {
            width: 100% !important;
            height: 100% !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            background: #ffffff !important;
            position: absolute !important;
        }

        /* GrapesJS Floating Toolbar inside Canvas */
        .gjs-pn-panels,
        .gjs-pn-views-container,
        .gjs-pn-views,
        .gjs-pn-commands,
        .gjs-pn-options,
        .gjs-pn-devices-c {
            display: none !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }

        .gjs-toolbar {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 9px !important;
            padding: 3px 4px !important;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.16) !important;
            display: flex !important;
            align-items: center !important;
            gap: 3px !important;
            z-index: 99999 !important;
        }
        .gjs-toolbar-item {
            color: #334155 !important;
            min-width: 28px !important;
            height: 28px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 6px !important;
            font-size: 12px !important;
            cursor: pointer !important;
            transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1) !important;
            user-select: none !important;
        }
        .gjs-toolbar-item:hover {
            background: var(--primary) !important;
            color: #ffffff !important;
            transform: scale(1.06) !important;
        }
        .gjs-toolbar-item.fa-trash:hover {
            background: #ef4444 !important;
            color: #ffffff !important;
        }
        .gjs-toolbar-item.gjs-tlb-tag-badge {
            width: auto !important;
            padding: 0 9px !important;
            background: #eff6ff !important;
            color: var(--primary) !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            border-radius: 6px !important;
            display: flex !important;
            align-items: center !important;
            gap: 5px !important;
            cursor: pointer !important;
            border: 1px solid #bfdbfe !important;
            letter-spacing: 0.03em !important;
        }
        .gjs-toolbar-item.gjs-tlb-tag-badge:hover {
            background: var(--primary) !important;
            color: #ffffff !important;
            border-color: var(--primary) !important;
            transform: none !important;
        }
        .gjs-toolbar-item.fa-arrows,
        .gjs-toolbar-item.gjs-no-touch-actions {
            cursor: grab !important;
            background: #eff6ff !important;
            color: var(--primary) !important;
        }
        .gjs-toolbar-item.fa-arrows:active,
        .gjs-toolbar-item.gjs-no-touch-actions:active {
            cursor: grabbing !important;
        }

        /* 1-Click Landing Page Starter Banner */
        .one-click-starter-card {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1.5px dashed #3b82f6;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
        }
        .one-click-starter-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.16);
            border-color: #2563eb;
            background: linear-gradient(135deg, #e0e7ff 0%, #bfdbfe 100%);
        }

        /* Quick Colors & Gradients Card */
        .quick-color-palette-card {
            background: #ffffff;
            border: 1px solid var(--border-studio);
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }
        .qcp-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.69rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .qcp-target-toggle {
            display: flex;
            gap: 3px;
            background: #f1f5f9;
            padding: 2px;
            border-radius: 6px;
        }
        .qcp-target-btn {
            border: none;
            background: transparent;
            font-size: 0.63rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            cursor: pointer;
            color: #64748b;
            transition: all 0.15s;
        }
        .qcp-target-btn.active {
            background: #ffffff;
            color: var(--primary);
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        }
        .qcp-swatches-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }
        .qcp-swatch {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1px solid rgba(0,0,0,0.12);
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .qcp-swatch:hover {
            transform: scale(1.18);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            z-index: 10;
        }

        /* Quick Style Presets Card (Border Radius, Box Shadow, Alignment) */
        .quick-presets-card {
            background: #ffffff;
            border: 1px solid var(--border-studio);
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }
        .qp-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qp-label {
            font-size: 0.67rem;
            font-weight: 700;
            color: #64748b;
            min-width: 48px;
        }
        .qp-btn-group {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            flex: 1;
        }
        .qp-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.68rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
        }
        .qp-btn:hover {
            background: #eff6ff;
            color: var(--primary);
            border-color: #bfdbfe;
            transform: translateY(-1px);
        }
        .qp-btn:active {
            transform: translateY(0);
        }

        /* GrapesJS Drop Placement Indicator (Elementor-Grade Bright Cyan/Blue Line) */
        :root {
            --gjs-placeholder-background-color: #0284c7 !important;
        }
        .gjs-placeholder {
            z-index: 99999 !important;
            pointer-events: none !important;
        }
        .gjs-placeholder-int, .gjs-com-placeholder-int {
            background: linear-gradient(90deg, #0284c7 0%, #38bdf8 50%, #0284c7 100%) !important;
            border: 1px solid #38bdf8 !important;
            box-shadow: 0 0 14px rgba(2, 132, 199, 0.9), 0 0 28px rgba(56, 189, 248, 0.6) !important;
            height: 4px !important;
            border-radius: 999px !important;
            position: relative !important;
            transition: all 0.1s ease !important;
        }
        .gjs-placeholder-int::after, .gjs-com-placeholder-int::after {
            content: '⬇ Thả khối vào đây' !important;
            position: absolute !important;
            left: 50% !important;
            top: 50% !important;
            transform: translate(-50%, -50%) !important;
            background: #0284c7 !important;
            color: #ffffff !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            padding: 2px 10px !important;
            border-radius: 999px !important;
            white-space: nowrap !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25) !important;
            letter-spacing: 0.02em !important;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            line-height: 1.4 !important;
        }

        /* ==========================================================================
           GRAPESJS STYLE MANAGER - RADIANT WHITE STUDIO THEME (Webflow & Canva Grade)
           ========================================================================== */
        #grapes-styles-container {
            width: 100%;
            background: #ffffff !important;
        }

        .gjs-sm-sectors {
            display: flex !important;
            flex-direction: column !important;
            gap: 10px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* Modern Sector Accordion Card */
        .gjs-sm-sector {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
            overflow: hidden !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .gjs-sm-sector:hover {
            border-color: #cbd5e1 !important;
        }
        .gjs-sm-sector.gjs-sm-open {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03) !important;
            border-color: #cbd5e1 !important;
        }

        /* Sector Header Title */
        .gjs-sm-sector .gjs-sm-sector-title {
            background: #f8fafc !important;
            padding: 10px 14px !important;
            font-size: 0.76rem !important;
            font-weight: 800 !important;
            color: #1e293b !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            border-bottom: 1px solid transparent !important;
            letter-spacing: 0.01em !important;
            transition: all 0.15s ease !important;
            user-select: none !important;
        }
        .gjs-sm-sector .gjs-sm-sector-title:hover {
            background: #f1f5f9 !important;
            color: var(--primary) !important;
        }
        .gjs-sm-sector.gjs-sm-open .gjs-sm-sector-title {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            color: var(--primary) !important;
        }
        .gjs-sm-sector .gjs-sm-sector-title > * {
            pointer-events: none;
        }

        /* Sector Caret Arrow */
        .gjs-sm-sector .gjs-sm-sector-caret {
            width: 15px !important;
            height: 15px !important;
            min-width: 15px !important;
            fill: #64748b !important;
            transform: rotate(-90deg) !important;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), fill 0.15s ease !important;
        }
        .gjs-sm-sector.gjs-sm-open .gjs-sm-sector-caret {
            fill: var(--primary) !important;
            transform: rotate(0deg) !important;
        }

        /* Properties Container: Thu gọn sạch sẽ khi sector đóng (Hidden by default when sector is closed) */
        .gjs-sm-sector > .gjs-sm-properties,
        .gjs-sm-sector:not(.gjs-sm-open) > .gjs-sm-properties,
        .gjs-sm-sector > .gjs-sm-properties[style*="display: none"],
        .gjs-sm-properties[style*="display: none"] {
            display: none !important;
        }

        /* Properties Container: Mở rộng hiển thị khi sector mở (Display grid ONLY when open) */
        .gjs-sm-sector.gjs-sm-open > .gjs-sm-properties {
            padding: 12px 14px !important;
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 12px 12px !important;
            background: #ffffff !important;
            box-sizing: border-box !important;
            width: 100% !important;
        }

        /* Individual Property Wrapper */
        .gjs-sm-property {
            margin: 0 !important;
            min-width: 0 !important;
            box-sizing: border-box !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 5px !important;
            width: 100% !important;
        }

        /* Label Styling */
        .gjs-sm-property .gjs-sm-label {
            font-size: 0.69rem !important;
            font-weight: 700 !important;
            color: #475569 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.03em !important;
            margin-bottom: 2px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        /* Clear Button (Undo / Reset Property) */
        .gjs-sm-clear {
            color: #94a3b8 !important;
            font-size: 0.72rem !important;
            cursor: pointer !important;
            transition: color 0.15s ease !important;
            margin-left: 6px !important;
        }
        .gjs-sm-clear:hover {
            color: #ef4444 !important;
        }

        /* FULL-WIDTH PROPERTIES: Guaranteed span across both columns */
        .gjs-sm-property--full,
        .gjs-sm-property.gjs-sm-composite,
        .gjs-sm-property.gjs-sm-stack,
        .gjs-sm-property.gjs-sm-file,
        .gjs-sm-property__font-family,
        .gjs-sm-property__text-align,
        .gjs-sm-property__display,
        .gjs-sm-property__position,
        .gjs-sm-property__background-image,
        .gjs-sm-property__background-size,
        .gjs-sm-property__background-repeat,
        .gjs-sm-property__background-position,
        .gjs-sm-property__background-attachment,
        .gjs-sm-property__border-radius,
        .gjs-sm-property__border-style,
        .gjs-sm-property__box-shadow,
        .gjs-sm-property__overflow,
        .gjs-sm-property__gap,
        .gjs-sm-property[id*="font-family"],
        .gjs-sm-property[id*="text-align"],
        .gjs-sm-property[id*="display"],
        .gjs-sm-property[id*="position"],
        .gjs-sm-property[id*="background-image"],
        .gjs-sm-property[id*="background-size"],
        .gjs-sm-property[id*="background-repeat"],
        .gjs-sm-property[id*="background-position"],
        .gjs-sm-property[id*="background-attachment"],
        .gjs-sm-property[id*="border-radius"],
        .gjs-sm-property[id*="border-style"],
        .gjs-sm-property[id*="box-shadow"],
        .gjs-sm-property[id*="overflow"],
        .gjs-sm-property[id*="gap"] {
            grid-column: 1 / -1 !important;
            width: 100% !important;
        }

        /* COMPOSITE PROPERTIES (Border Radius 4 corners, Box Shadow, etc.) */
        .gjs-sm-field.gjs-sm-composite {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        /* Sub-properties container inside composite (Top-Left, Top-Right, Bottom-Left, Bottom-Right) */
        .gjs-sm-field.gjs-sm-composite .gjs-sm-properties {
            padding: 0 !important;
            background: transparent !important;
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px 12px !important;
            width: 100% !important;
        }
        .gjs-sm-field.gjs-sm-composite .gjs-sm-property {
            margin: 0 !important;
            min-width: 0 !important;
        }
        .gjs-sm-field.gjs-sm-composite .gjs-sm-label {
            font-size: 0.63rem !important;
            font-weight: 700 !important;
            color: #64748b !important;
        }

        /* INPUT FIELDS (Pure White, Crisp Borders, Modern Typography) */
        .gjs-field {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            color: #0f172a !important;
            font-size: 0.74rem !important;
            font-weight: 600 !important;
            min-height: 32px !important;
            height: 32px !important;
            padding: 0 8px !important;
            display: flex !important;
            align-items: center !important;
            box-sizing: border-box !important;
            position: relative !important;
            width: 100% !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        }
        .gjs-field:focus-within {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.16) !important;
        }
        .gjs-field input {
            color: #0f172a !important;
            font-size: 0.74rem !important;
            font-weight: 600 !important;
            background: transparent !important;
            border: none !important;
            outline: none !important;
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            font-family: inherit !important;
            box-shadow: none !important;
        }
        .gjs-field select {
            color: #0f172a !important;
            font-size: 0.74rem !important;
            font-weight: 600 !important;
            background: #ffffff !important;
            border: none !important;
            outline: none !important;
            width: 100% !important;
            height: 100% !important;
            cursor: pointer !important;
            padding: 0 18px 0 0 !important;
            font-family: inherit !important;
            box-shadow: none !important;
        }
        .gjs-field select option {
            background: #ffffff !important;
            color: #0f172a !important;
            font-size: 0.75rem !important;
            padding: 6px 10px !important;
        }

        /* UNIT SELECTORS (px, %, em, etc.) */
        .gjs-field-unit, .gjs-sm-unit {
            color: var(--primary) !important;
            font-size: 0.68rem !important;
            font-weight: 800 !important;
            background: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
            border-radius: 4px !important;
            padding: 1px 5px !important;
            cursor: pointer !important;
            position: absolute !important;
            right: 20px !important;
            top: 5px !important;
            bottom: 5px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .gjs-field-arrows, .gjs-sm-int-arrows {
            position: absolute !important;
            right: 4px !important;
            top: 0 !important;
            bottom: 0 !important;
            width: 12px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: ns-resize !important;
            gap: 3px !important;
        }
        .gjs-field-arrow-u, .gjs-sm-u-arrow {
            border-bottom: 4px solid #64748b !important;
            border-left: 3px solid transparent !important;
            border-right: 3px solid transparent !important;
            width: 0 !important;
            height: 0 !important;
        }
        .gjs-field-arrow-d, .gjs-sm-d-arrow, .gjs-d-s-arrow {
            border-top: 4px solid #64748b !important;
            border-left: 3px solid transparent !important;
            border-right: 3px solid transparent !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* COLOR PICKER FIELD ENHANCEMENTS */
        .gjs-field-color {
            position: relative !important;
            padding-right: 36px !important;
            cursor: pointer !important;
        }
        .gjs-field-color input {
            cursor: pointer !important;
        }
        .gjs-field-colorp {
            position: absolute !important;
            right: 4px !important;
            top: 4px !important;
            bottom: 4px !important;
            width: 26px !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 5px !important;
            overflow: hidden !important;
            cursor: pointer !important;
            padding: 0 !important;
            background: #ffffff !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.06) !important;
            transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease !important;
        }
        .gjs-field-colorp:hover {
            transform: scale(1.1) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2) !important;
        }
        .gjs-field-colorp-c {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
        }
        .gjs-field-colorp.is-none .gjs-field-colorp-c,
        .gjs-field-color[data-none="true"] .gjs-field-colorp-c {
            background: repeating-conic-gradient(#cbd5e1 0% 25%, #ffffff 0% 50%) 50% / 6px 6px !important;
            position: relative;
        }
        .gjs-field-colorp.is-none::after,
        .gjs-field-color[data-none="true"] .gjs-field-colorp::after {
            content: '✕';
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ef4444;
            font-size: 11px;
            font-weight: 900;
        }

        /* ==========================================================================
           STUDIO PRO COLOR PALETTE POPOVER (FIGMA & CANVA GRADE)
           ========================================================================== */
        .studio-color-popover {
            position: fixed;
            z-index: 100000;
            width: 295px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.2), 0 2px 10px rgba(0, 0, 0, 0.06);
            padding: 14px;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            animation: scpFadeIn 0.16s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes scpFadeIn {
            from { opacity: 0; transform: translateY(-6px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .scp-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .scp-title {
            font-size: 0.78rem;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .scp-close-btn {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 0.95rem;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 4px;
            transition: all 0.15s ease;
        }
        .scp-close-btn:hover {
            color: #ef4444;
            background: #fef2f2;
        }
        .scp-active-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }
        .scp-preview-wrapper {
            position: relative;
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #cbd5e1;
            cursor: pointer;
            box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);
            transition: transform 0.15s ease, border-color 0.15s ease;
        }
        .scp-preview-wrapper:hover {
            transform: scale(1.06);
            border-color: var(--primary);
        }
        .scp-active-preview {
            width: 100%;
            height: 100%;
            background: #0f172a;
        }
        .scp-hex-input {
            flex: 1;
            height: 36px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0 10px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #0f172a;
            font-family: monospace, sans-serif;
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.15s ease, background 0.15s ease;
        }
        .scp-hex-input:focus {
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
        }
        .scp-eyedropper-btn {
            height: 36px;
            width: 36px;
            min-width: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            color: #475569;
            cursor: pointer;
            font-size: 0.82rem;
            transition: all 0.15s ease;
        }
        .scp-eyedropper-btn:hover {
            background: #eff6ff;
            color: var(--primary);
            border-color: #bfdbfe;
        }
        .scp-quick-actions {
            display: flex;
            gap: 6px;
            margin-bottom: 12px;
        }
        .scp-action-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.70rem;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .scp-action-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }
        .scp-action-btn.primary {
            background: #eff6ff;
            color: var(--primary);
            border-color: #bfdbfe;
        }
        .scp-action-btn.primary:hover {
            background: #dbeafe;
        }
        .scp-swatch-checkered {
            width: 12px;
            height: 12px;
            border-radius: 3px;
            border: 1px solid #cbd5e1;
            background: repeating-conic-gradient(#cbd5e1 0% 25%, #ffffff 0% 50%) 50% / 6px 6px;
        }
        .scp-section-label {
            font-size: 0.63rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 7px;
        }
        .scp-swatches-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 6px;
            margin-bottom: 10px;
        }
        .scp-swatch {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.15s ease;
            position: relative;
            padding: 0;
            outline: none;
            box-sizing: border-box;
        }
        .scp-swatch:hover {
            transform: scale(1.18);
            z-index: 2;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            border-color: #ffffff;
        }
        .scp-swatch:active {
            transform: scale(1.05);
        }
        .scp-gradients-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 6px;
        }
        .scp-gradient-swatch {
            width: 100%;
            height: 24px;
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            padding: 0;
            outline: none;
            box-sizing: border-box;
        }
        .scp-gradient-swatch:hover {
            transform: scale(1.12);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
            z-index: 2;
        }

        /* RADIO / SEGMENTED BUTTONS (e.g. Text Align: Left, Center, Right, Justify) */
        .gjs-field-radio, .gjs-radio-items {
            display: flex !important;
            background: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 7px !important;
            padding: 2px !important;
            gap: 2px !important;
            width: 100% !important;
            box-sizing: border-box !important;
            min-height: 32px !important;
        }
        .gjs-radio-item {
            flex: 1 !important;
            text-align: center !important;
            display: flex !important;
        }
        .gjs-radio-item input {
            display: none !important;
        }
        .gjs-radio-item label {
            flex: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 4px 6px !important;
            border-radius: 5px !important;
            color: #64748b !important;
            font-size: 0.70rem !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            background: transparent !important;
        }
        .gjs-radio-item label:hover {
            color: #0f172a !important;
            background: rgba(255, 255, 255, 0.7) !important;
        }
        .gjs-radio-item input:checked + label,
        .gjs-radio-item.active label,
        .gjs-radio-item.gjs-active label {
            background: #ffffff !important;
            color: var(--primary) !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
            font-weight: 800 !important;
        }

        /* Hide GrapesJS default legacy spectrum container so it doesn't conflict with Studio Color Popover */
        .sp-container {
            display: none !important;
        }

        /* Text Align Radio Items with FontAwesome Icons */
        .gjs-sm-property__text-align .gjs-radio-item label,
        .gjs-sm-property[id*="text-align"] .gjs-radio-item label {
            font-size: 0 !important;
            height: 28px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 32px !important;
        }
        .gjs-sm-property__text-align .gjs-radio-item input[value="left"] + label::before,
        .gjs-sm-property[id*="text-align"] .gjs-radio-item input[value="left"] + label::before,
        .gjs-sm-property[id*="text-align"] .gjs-radio-item:nth-child(1) label::before {
            font-family: "Font Awesome 6 Free", "FontAwesome" !important;
            content: "\f036" !important;
            font-weight: 900 !important;
            font-size: 13px !important;
        }
        .gjs-sm-property__text-align .gjs-radio-item input[value="center"] + label::before,
        .gjs-sm-property[id*="text-align"] .gjs-radio-item input[value="center"] + label::before,
        .gjs-sm-property[id*="text-align"] .gjs-radio-item:nth-child(2) label::before {
            font-family: "Font Awesome 6 Free", "FontAwesome" !important;
            content: "\f037" !important;
            font-weight: 900 !important;
            font-size: 13px !important;
        }
        .gjs-sm-property__text-align .gjs-radio-item input[value="right"] + label::before,
        .gjs-sm-property[id*="text-align"] .gjs-radio-item input[value="right"] + label::before,
        .gjs-sm-property[id*="text-align"] .gjs-radio-item:nth-child(3) label::before {
            font-family: "Font Awesome 6 Free", "FontAwesome" !important;
            content: "\f038" !important;
            font-weight: 900 !important;
            font-size: 13px !important;
        }
        .gjs-sm-property__text-align .gjs-radio-item input[value="justify"] + label::before,
        .gjs-sm-property[id*="text-align"] .gjs-radio-item input[value="justify"] + label::before,
        .gjs-sm-property[id*="text-align"] .gjs-radio-item:nth-child(4) label::before {
            font-family: "Font Awesome 6 Free", "FontAwesome" !important;
            content: "\f039" !important;
            font-weight: 900 !important;
            font-size: 13px !important;
        }

        /* Fullscreen Preview Mode */
        body.is-preview-mode .studio-topbar,
        body.is-preview-mode .left-rail,
        body.is-preview-mode .left-drawer,
        body.is-preview-mode .sidebar-right,
        body.is-preview-mode .canvas-header-bar {
            display: none !important;
        }
        body.is-preview-mode .studio-workspace { height: 100vh !important; }
        .preview-exit-pill {
            display: none;
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            background: #0f172a;
            border: 1px solid #334155;
            color: #ffffff;
            padding: 0.6rem 1.2rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
        }
        body.is-preview-mode .preview-exit-pill { display: flex; align-items: center; gap: 0.5rem; }

        /* Modals */
        .studio-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(6px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .studio-modal-backdrop.open { display: flex; }
        .studio-modal-box {
            background: #ffffff;
            border: 1px solid var(--border-studio);
            border-radius: 14px;
            width: 90%;
            max-width: 800px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.18);
            overflow: hidden;
        }

        /* Studio Toast Notifications */
        #studio-toast-container {
            position: fixed;
            top: 48px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999999;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            pointer-events: none;
        }
        .studio-toast {
            pointer-events: auto;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            padding: 8px 18px;
            border-radius: 99px;
            font-size: 0.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 9px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            opacity: 0;
            transform: translateY(-16px) scale(0.92);
            transition: all 0.24s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .studio-toast.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        .studio-toast.toast-success {
            border-color: #10b981;
            color: #065f46;
            background: #ecfdf5;
        }
        .studio-toast.toast-error {
            border-color: #f43f5e;
            color: #9f1239;
            background: #fff1f2;
        }
        .studio-toast.toast-info {
            border-color: #38bdf8;
            color: #0369a1;
            background: #f0f9ff;
        }
        .studio-toast-icon {
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .toast-success .studio-toast-icon { color: #059669; }
        .toast-error .studio-toast-icon { color: #e11d48; }
        .toast-info .studio-toast-icon { color: #0284c7; }
    </style>
</head>
<body>
    {{-- Toast Notification Container --}}
    <div id="studio-toast-container"></div>

    {{-- ==========================================================================
       1. TOPBAR
       ========================================================================== --}}
    <header class="studio-topbar">
        <div class="topbar-left">
            <a href="{{ route('builder.index') }}" class="btn-topbar" title="Quay lại Dashboard">
                <i class="fa fa-chevron-left" style="font-size: 0.7rem;"></i> <span data-i18n="dashboard">Dashboard</span>
            </a>

            <div style="display: flex; align-items: center; gap: 0.4rem;">
                <div class="store-brand-badge" title="{{ $page->website->name }}">
                    <i class="fa fa-store" style="color: var(--accent-cyan); font-size: 0.76rem;"></i>
                    <span>{{ $page->website->name }}</span>
                </div>
                <span style="color: var(--border-studio-light);">•</span>
                <select class="page-badge-select" onchange="handlePageSwitch(this.value)" title="Chuyển trang đang thiết kế">
                    @foreach($page->website->pages as $p)
                        <option value="{{ route('builder.editor', $p->id) }}" {{ $p->id === $page->id ? 'selected' : '' }}>
                            {{ $p->is_home ? '🏠' : '📄' }} {{ $p->title }} ({{ $p->slug }})
                        </option>
                    @endforeach
                    <option value="__NEW_PAGE__">+ Thêm trang mới...</option>
                </select>
                <span class="version-tag">v<span id="version-display">{{ $page->version_number }}</span></span>
            </div>
        </div>

        {{-- Center Device Controls --}}
        <div class="topbar-center">
            <div class="device-toggle-pill">
                <button id="btn-device-desktop" class="device-btn active" onclick="setDeviceMode('desktop')" title="Chế độ Desktop (1200px)">
                    <i class="fa fa-laptop"></i> <span>Desktop</span>
                </button>
                <button id="btn-device-tablet" class="device-btn" onclick="setDeviceMode('tablet')" title="Chế độ Tablet (768px)">
                    <i class="fa fa-tablet-screen-button"></i> <span>Tablet</span>
                </button>
                <button id="btn-device-mobile" class="device-btn" onclick="setDeviceMode('mobile')" title="Chế độ Mobile (375px)">
                    <i class="fa fa-mobile-screen"></i> <span>Mobile</span>
                </button>
            </div>
        </div>

        {{-- Right Actions --}}
        <div class="topbar-right">
            <button class="lang-toggle-btn" onclick="toggleStudioLanguage()" title="Đổi ngôn ngữ giao diện (VI / EN)">
                <span id="lang-flag">🇻🇳</span> <span id="lang-code">VI</span>
            </button>

            <div id="status-indicator" class="status-pill">
                <i class="fa fa-circle" style="font-size: 0.5rem; color: var(--accent-emerald);"></i> <span data-i18n="status_synced">Đã lưu</span>
            </div>

            <button onclick="undoAction()" class="btn-topbar btn-icon-only" title="Hoàn tác (Ctrl+Z)">
                <i class="fa fa-rotate-left"></i>
            </button>
            <button onclick="redoAction()" class="btn-topbar btn-icon-only" title="Khôi phục (Ctrl+Y)">
                <i class="fa fa-rotate-right"></i>
            </button>
            <button onclick="clearCanvasConfirm()" class="btn-topbar btn-icon-only" title="Xóa toàn bộ Canvas">
                <i class="fa fa-trash-can"></i>
            </button>
            <button onclick="togglePreviewMode()" class="btn-topbar" title="Xem thử toàn màn hình">
                <i class="fa fa-eye"></i> <span data-i18n="preview">Xem thử</span>
            </button>
            <button onclick="openCodeModal()" class="btn-topbar btn-icon-only" title="Xem mã nguồn HTML/CSS tĩnh">
                <i class="fa fa-code"></i>
            </button>
            <button onclick="saveDraft(true)" class="btn-topbar btn-save-draft" title="Lưu nháp ngay lập tức">
                <i class="fa fa-floppy-disk"></i> <span data-i18n="save_draft">Lưu Nháp</span>
            </button>
            <button onclick="publishSite()" class="btn-topbar btn-publish-ssg" title="Biên dịch tĩnh SSG lên S3 / Cloudflare CDN">
                <i class="fa fa-rocket"></i> <span data-i18n="publish">Xuất Bản</span>
            </button>
            <a href="{{ route('builder.preview', $page->website->id) }}" target="_blank" class="btn-topbar btn-icon-only" title="Mở trang web trực tiếp">
                <i class="fa fa-arrow-up-right-from-square"></i>
            </a>
        </div>
    </header>

    {{-- ==========================================================================
       2. WORKSPACE
       ========================================================================== --}}
    <div class="studio-workspace">

        {{-- LEFT RAIL --}}
        <aside class="left-rail">
            <button id="rail-btn-elements" class="rail-btn active" onclick="switchDrawerTab('elements')" title="Thành phần & Khối (Elements)">
                <i class="fa fa-plus"></i>
            </button>
            <button id="rail-btn-sections" class="rail-btn" onclick="switchDrawerTab('sections')" title="Mẫu giao diện sẵn (Sections)">
                <i class="fa fa-cubes"></i>
            </button>
            <button id="rail-btn-layers" class="rail-btn" onclick="switchDrawerTab('layers')" title="Cây cấu trúc DOM (Navigator)">
                <i class="fa fa-layer-group"></i>
            </button>
            <button id="rail-btn-pages" class="rail-btn" onclick="switchDrawerTab('pages')" title="Quản lý trang (Pages)">
                <i class="fa fa-file-lines"></i>
            </button>
            <button id="rail-btn-media" class="rail-btn" onclick="switchDrawerTab('media')" title="Thư viện hình ảnh (Media)">
                <i class="fa fa-image"></i>
            </button>

            <div class="rail-spacer"></div>

            <button class="rail-btn" onclick="toggleLeftDrawer()" title="Ẩn/Hiện thanh bên trái">
                <i id="drawer-toggle-icon" class="fa fa-chevron-left"></i>
            </button>
        </aside>

        {{-- LEFT DRAWER --}}
        <aside id="left-drawer" class="left-drawer">
            {{-- TAB 1: ELEMENTS --}}
            <div id="drawer-pane-elements" class="drawer-pane" style="display: flex; flex-direction: column; height: 100%;">
                <div class="drawer-header">
                    <div class="drawer-title">
                        <i class="fa fa-puzzle-piece" style="color: var(--accent-cyan);"></i> <span data-i18n="elements_title">Thành Phần & Khối</span>
                    </div>
                    <span style="font-size: 0.68rem; color: var(--accent-cyan); background: rgba(56, 189, 248, 0.12); padding: 2px 6px; border-radius: 4px; font-weight: 700;">30+ MẪU</span>
                </div>
                <div class="drawer-search">
                    <input type="text" id="filter-elements-input" class="drawer-search-input" placeholder="🔍 Tìm kiếm thành phần..." oninput="filterElements(this.value)">
                </div>
                <div class="drawer-body">
                    <div id="grapes-blocks-container"></div>
                </div>
            </div>

            {{-- TAB 2: SECTIONS --}}
            <div id="drawer-pane-sections" class="drawer-pane" style="display: none; flex-direction: column; height: 100%;">
                <div class="drawer-header">
                    <div class="drawer-title">
                        <i class="fa fa-shapes" style="color: var(--accent-emerald);"></i> <span data-i18n="sections_title">Mẫu Giao Diện Sẵn</span>
                    </div>
                    <span style="font-size: 0.68rem; color: var(--accent-emerald); background: rgba(16, 185, 129, 0.12); padding: 2px 6px; border-radius: 4px; font-weight: 700;">PRO</span>
                </div>
                <div class="drawer-body">
                    <div class="one-click-starter-card" onclick="createFullLandingPage()" title="Tự động tạo trọn gói Header + Hero + Tính Năng + Khách Hàng + Báo Giá + Banner + Chân Trang">
                        <div style="font-size: 1.4rem;">⚡</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 800; font-size: 0.78rem; color: #1e3a8a;">Tạo Trọn Gói Landing Page (1 Click)</div>
                            <div style="font-size: 0.66rem; color: #3b82f6; margin-top: 2px;">Header + Hero + Tính Năng + Báo Giá + Footer</div>
                        </div>
                        <i class="fa fa-arrow-right" style="color: #2563eb; font-size: 0.8rem;"></i>
                    </div>
                    <div id="custom-sections-container"></div>
                </div>
            </div>

            {{-- TAB 3: LAYERS --}}
            <div id="drawer-pane-layers" class="drawer-pane" style="display: none; flex-direction: column; height: 100%;">
                <div class="drawer-header">
                    <div class="drawer-title">
                        <i class="fa fa-layer-group" style="color: var(--accent-cyan);"></i> <span data-i18n="layers_title">Cây Cấu Trúc DOM</span>
                    </div>
                </div>
                <div class="drawer-body">
                    <div id="grapes-layers-container"></div>
                </div>
            </div>

            {{-- TAB 4: PAGES --}}
            <div id="drawer-pane-pages" class="drawer-pane" style="display: none; flex-direction: column; height: 100%;">
                <div class="drawer-header">
                    <div class="drawer-title">
                        <i class="fa fa-file-lines" style="color: var(--primary);"></i> <span data-i18n="pages_title">Danh Sách Trang</span>
                    </div>
                    <button onclick="promptCreateNewPage()" class="btn-topbar" style="padding: 2px 8px; font-size: 0.72rem;">+ Tạo trang</button>
                </div>
                <div class="drawer-body">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach($page->website->pages as $p)
                            <a href="{{ route('builder.editor', $p->id) }}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0.85rem; background: var(--bg-studio-card); border: 1px solid {{ $p->id === $page->id ? 'var(--accent-cyan)' : 'var(--border-studio)' }}; border-radius: 8px; text-decoration: none; color: var(--text-main);">
                                <div>
                                    <div style="font-weight: 700; font-size: 0.84rem;">{{ $p->is_home ? '🏠' : '📄' }} {{ $p->title }}</div>
                                    <div style="font-size: 0.72rem; color: var(--text-subtle);">/{{ $p->slug }}</div>
                                </div>
                                @if($p->id === $page->id)
                                    <span style="font-size: 0.7rem; color: var(--accent-cyan); font-weight: 800;">Đang sửa</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- TAB 5: MEDIA --}}
            <div id="drawer-pane-media" class="drawer-pane" style="display: none; flex-direction: column; height: 100%;">
                <div class="drawer-header">
                    <div class="drawer-title">
                        <i class="fa fa-image" style="color: var(--accent-amber);"></i> <span data-i18n="media_title">Thư Viện Media</span>
                    </div>
                    <label class="btn-topbar" style="padding: 2px 8px; font-size: 0.72rem; cursor: pointer;">
                        + Tải ảnh lên
                        <input type="file" id="media-upload-input" accept="image/*" style="display: none;" onchange="handleMediaUpload(this)">
                    </label>
                </div>
                <div class="drawer-body">
                    <div id="media-gallery-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;"></div>
                </div>
            </div>
        </aside>

        {{-- ======================================================================
           CENTER CANVAS: ZOOM, PAN, & DRAGGABLE VIEWPORT RESIZE HANDLES
           ====================================================================== --}}
        <main class="canvas-area">
            {{-- Canvas Controls Sub-header --}}
            <div class="canvas-header-bar">
                {{-- Left: Current Device & Dimension Tag & Hint --}}
                <div style="display: flex; align-items: center; gap: 0.4rem; flex-shrink: 0;">
                    <span id="canvas-device-label" style="font-weight: 800; color: var(--text-main); display: inline-flex; align-items: center; gap: 0.3rem; white-space: nowrap;">
                        <i class="fa fa-desktop" style="color: var(--accent-cyan);"></i> <span class="hide-on-compact">Desktop</span>
                    </span>
                    <span style="color: #64748b;">•</span>
                    <span id="canvas-width-badge" class="canvas-width-pill">1200px</span>
                    <div class="canvas-shortcut-pill hide-on-compact">
                        <i class="fa-regular fa-keyboard"></i>
                        <span>Phím tắt</span>
                        <div class="shortcut-tooltip-dropdown">
                            <div class="st-row"><kbd>Kéo icon ✥ hoặc khối</kbd>: <span>Kéo đặt vị trí tùy ý</span></div>
                            <div class="st-row"><kbd>Alt</kbd> + <kbd>↑ / ↓</kbd>: <span>Di chuyển khối lên / xuống</span></div>
                            <div class="st-row"><kbd>Space</kbd> + Kéo: <span>Di chuyển toàn bộ bản vẽ</span></div>
                            <div class="st-row"><kbd>Chuột giữa</kbd>: <span>Kéo bản vẽ (Pan)</span></div>
                            <div class="st-row"><kbd>Ctrl</kbd> + Cuộn: <span>Thu phóng (Zoom)</span></div>
                            <div class="st-row"><kbd>Double Click mép đáy</kbd>: <span>Tự khớp chiều cao</span></div>
                        </div>
                    </div>
                </div>

                {{-- Center: Zoom Controls & Pan Tool ("Kéo qua kéo lại") --}}
                <div class="canvas-control-group">
                    {{-- Zoom Tools --}}
                    <button onclick="changeZoom(-10)" class="btn-canvas-tool btn-icon-only" title="Thu nhỏ (Ctrl -)">
                        <i class="fa fa-minus"></i>
                    </button>

                    {{-- Zoom Level Dropdown --}}
                    <div style="position: relative; display: inline-block;">
                        <button id="zoom-level-badge" onclick="toggleZoomMenu(event)" class="btn-canvas-tool" style="min-width: 58px; justify-content: space-between;" title="Nhấp để chọn mức thu phóng">
                            <span id="zoom-text">100%</span> <i class="fa fa-caret-down" style="font-size: 0.65rem; margin-left: 2px;"></i>
                        </button>
                        <div id="zoom-dropdown-menu" class="zoom-dropdown-menu">
                            <div class="zoom-opt" onclick="setZoom(50); closeZoomMenu();"><span>50%</span> <span style="font-size: 0.6rem; color: #64748b;">Nhỏ</span></div>
                            <div class="zoom-opt" onclick="setZoom(75); closeZoomMenu();"><span>75%</span></div>
                            <div class="zoom-opt" onclick="setZoom(100); closeZoomMenu();"><span>100%</span> <span style="font-size: 0.6rem; color: var(--accent-cyan);">Chuẩn</span></div>
                            <div class="zoom-opt" onclick="setZoom(125); closeZoomMenu();"><span>125%</span></div>
                            <div class="zoom-opt" onclick="setZoom(150); closeZoomMenu();"><span>150%</span> <span style="font-size: 0.6rem; color: #64748b;">Lớn</span></div>
                            <div style="border-top: 1px solid var(--border-studio); margin: 3px 0;"></div>
                            <div class="zoom-opt" onclick="zoomToFit(); closeZoomMenu();"><span>⛶ Vừa màn hình</span> <span style="font-size: 0.6rem; color: var(--accent-emerald);">Fit</span></div>
                        </div>
                    </div>

                    <button onclick="changeZoom(10)" class="btn-canvas-tool btn-icon-only" title="Phóng to (Ctrl +)">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button onclick="zoomToFit()" class="btn-canvas-tool" title="Tự động thu phóng vừa màn hình (Fit to Screen)">
                        <i class="fa fa-expand"></i> <span>Fit</span>
                    </button>

                    <span style="color: var(--border-studio-light); margin: 0 0.15rem;">|</span>

                    {{-- Pan Hand Tool ("Kéo bản vẽ qua lại để căn chỉnh") --}}
                    <button id="btn-pan-mode" onclick="togglePanMode()" class="btn-canvas-tool" title="Bật/Tắt chế độ kéo bản vẽ qua lại (Hoặc giữ phím Space / Chuột giữa)">
                        <i class="fa fa-hand"></i> <span>Pan</span>
                    </button>
                    <button onclick="resetPanPosition()" class="btn-canvas-tool btn-icon-only" title="Căn giữa lại bản vẽ (Reset Pan)">
                        <i class="fa fa-crosshairs"></i>
                    </button>

                    <span style="color: var(--border-studio-light); margin: 0 0.15rem;">|</span>

                    {{-- Drag Mode Toggle: Flow Layout (Bố cục chuẩn WordPress) vs Free Drag (Tự do) --}}
                    <div style="display: inline-flex; align-items: center; background: #f1f5f9; padding: 2px; border-radius: 7px; border: 1px solid var(--border-studio); gap: 2px; flex-shrink: 0;">
                        <button id="btn-drag-flow" onclick="setStudioDragMode('flow')" class="btn-canvas-tool active" style="border: none; padding: 0.2rem 0.45rem; font-size: 0.72rem; border-radius: 5px; height: 24px;" title="Chế độ Bố Cục Chuẩn WordPress / Elementor: Kéo thả chèn tự động vào cột hoặc trên/dưới các khối, không bao giờ bị đè chữ">
                            <i class="fa fa-layer-group" style="color: var(--primary);"></i> <span>Bố Cục</span>
                        </button>
                        <button id="btn-drag-absolute" onclick="setStudioDragMode('absolute')" class="btn-canvas-tool" style="border: none; padding: 0.2rem 0.45rem; font-size: 0.72rem; border-radius: 5px; height: 24px;" title="Chế độ Tự Do: Kéo thả theo tọa độ pixel tự do (như Canva / Figma)">
                            <i class="fa fa-arrows-up-down-left-right"></i> <span>Tự Do</span>
                        </button>
                    </div>

                    {{-- WordPress / Elementor Auto Clean & Fix Overlaps --}}
                    <button onclick="autoFixCleanLayout()" class="btn-canvas-tool" style="background: #eff6ff; color: #2563eb; border-color: #bfdbfe; font-weight: 800; height: 28px;" title="Dọn dẹp và sửa lỗi đè khối: Đưa toàn bộ các phần tử bị trôi tọa độ về lại ngay ngắn theo bố cục chuẩn WordPress">
                        <i class="fa fa-wand-magic-sparkles"></i> <span>Căn Bố Cục</span>
                    </button>
                </div>

                {{-- Right: Toggle Right Inspector --}}
                <div style="display: flex; align-items: center; gap: 0.35rem; flex-shrink: 0;">
                    <button id="btn-toggle-inspector" onclick="toggleRightSidebar()" class="btn-canvas-tool" title="Ẩn/Hiện bảng thuộc tính bên phải">
                        <i class="fa fa-sliders"></i>
                        <span id="inspector-toggle-label" class="hide-on-compact">Thuộc Tính</span>
                        <i id="inspector-toggle-icon" class="fa fa-chevron-right" style="font-size: 0.65rem;"></i>
                    </button>
                </div>
            </div>

            {{-- Interactive Stage with Pan Overlay --}}
            <div class="canvas-interactive-stage" id="canvas-interactive-stage">
                {{-- Pan Drag Overlay (Catches mouse events when dragging/panning) --}}
                <div id="pan-drag-overlay"></div>

                {{-- Viewport Frame with Left/Right/Bottom Drag-Resize Handles --}}
                <div id="canvas-viewport" class="canvas-viewport-wrapper mode-desktop">
                    {{-- Left Drag Resize Handle --}}
                    <div class="canvas-resizer-handle handle-left" id="handle-resize-left" title="↔ Kéo qua lại để co giãn chiều rộng bản vẽ (320px - 1920px)"></div>

                    {{-- GrapesJS Mount Container --}}
                    <div id="gjs-canvas-wrapper"></div>

                    {{-- Right Drag Resize Handle --}}
                    <div class="canvas-resizer-handle handle-right" id="handle-resize-right" title="↔ Kéo qua lại để co giãn chiều rộng bản vẽ (320px - 1920px)"></div>

                    {{-- Bottom Drag Resize Handle --}}
                    <div class="canvas-resizer-handle handle-bottom" id="handle-resize-bottom" title="↕ Kéo lên xuống để thay đổi chiều cao bản vẽ (Double click để tự động khớp theo nội dung)"></div>
                </div>
            </div>
        </main>

        {{-- ======================================================================
           RIGHT INSPECTOR SIDEBAR (GUARANTEED VISIBLE, FULLY DOCKED)
           ====================================================================== --}}
        <aside id="sidebar-right" class="sidebar-right">
            {{-- Tabs --}}
            <div class="inspector-tabs">
                <button id="tab-btn-styles" class="inspector-tab-btn active" onclick="switchInspectorTab('styles')">
                    <i class="fa fa-palette"></i> <span data-i18n="tab_styles">Kiểu Dáng</span>
                </button>
                <button id="tab-btn-traits" class="inspector-tab-btn" onclick="switchInspectorTab('traits')">
                    <i class="fa fa-sliders"></i> <span data-i18n="tab_traits">Thuộc Tính</span>
                </button>
            </div>

            {{-- Tab Pane 1: Styles --}}
            <div id="inspector-pane-styles" class="inspector-tab-pane active">

                {{-- Selected Element Header Box --}}
                <div id="selected-element-box" class="selected-element-card" style="display: none;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="selected-tag-name" class="selected-tag-badge">DIV</span>
                        <div id="selected-class-list" style="font-size: 0.72rem; color: var(--text-muted);"></div>
                    </div>

                    {{-- Quick Action Buttons: Move Up, Move Down, Parent, Clone, Delete --}}
                    <div class="reorder-actions-bar">
                        <button class="btn-move-action" onclick="moveComponentUp()" title="Di chuyển khối này lên trên">
                            <i class="fa fa-chevron-up"></i> <span>Lên</span>
                        </button>
                        <button class="btn-move-action" onclick="moveComponentDown()" title="Di chuyển khối này xuống dưới">
                            <i class="fa fa-chevron-down"></i> <span>Xuống</span>
                        </button>
                        <button class="btn-move-action" onclick="selectParentElement()" title="Chọn khối cha bao ngoài">
                            <i class="fa fa-arrow-up"></i> <span>Cha</span>
                        </button>
                        <button class="btn-move-action" onclick="duplicateSelectedElement()" title="Nhân bản khối này">
                            <i class="fa fa-clone"></i> <span>Nhân</span>
                        </button>
                        <button class="btn-move-action danger" onclick="deleteSelectedElement()" title="Xóa khối này">
                            <i class="fa fa-trash"></i> <span>Xóa</span>
                        </button>
                    </div>

                    {{-- Quick Free/Flow Position Mode Toggle for this Element --}}
                    <div style="margin-top: 8px; display: flex; gap: 6px;">
                        <button id="btn-toggle-free-pos" onclick="toggleElementPositionMode()" class="btn-move-action" style="flex: 1; padding: 5px 8px; font-size: 0.72rem; justify-content: center; background: #f8fafc; border: 1px solid var(--border-studio); border-radius: 6px; font-weight: 700;" title="Bật/Tắt chế độ đặt vị trí tự do (Absolute) cho phần tử này để kéo thả bất kỳ đâu trên trang">
                            <i class="fa fa-arrows-up-down-left-right" style="color: var(--primary);"></i> <span id="lbl-pos-mode">Đặt Vị Trí Tự Do (Free)</span>
                        </button>
                    </div>
                </div>

                {{-- Quick Brand Color & Gradient Palette (1-Click Styling) --}}
                <div id="quick-color-palette" class="quick-color-palette-card" style="display: none;">
                    <div class="qcp-header">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-palette" style="color: var(--primary);"></i>
                            <span>BẢNG MÀU & GRADIENT 1-CHẠM</span>
                        </div>
                        <div class="qcp-target-toggle">
                            <button id="qcp-btn-color" class="qcp-target-btn active" onclick="setQcpTarget('color')">Chữ</button>
                            <button id="qcp-btn-bg" class="qcp-target-btn" onclick="setQcpTarget('background-color')">Nền</button>
                        </div>
                    </div>
                    <div class="qcp-swatches-grid">
                        <div class="qcp-swatch" style="background: #0f172a;" title="Đen Slate (#0f172a)" onclick="applyQuickColor('#0f172a')"></div>
                        <div class="qcp-swatch" style="background: #ffffff; border: 1px solid #cbd5e1;" title="Trắng (#ffffff)" onclick="applyQuickColor('#ffffff')"></div>
                        <div class="qcp-swatch" style="background: #64748b;" title="Xám Slate (#64748b)" onclick="applyQuickColor('#64748b')"></div>
                        <div class="qcp-swatch" style="background: #2563eb;" title="Xanh Dương Royal (#2563eb)" onclick="applyQuickColor('#2563eb')"></div>
                        <div class="qcp-swatch" style="background: #0284c7;" title="Xanh Cyan (#0284c7)" onclick="applyQuickColor('#0284c7')"></div>
                        <div class="qcp-swatch" style="background: #059669;" title="Xanh Ngọc Emerald (#059669)" onclick="applyQuickColor('#059669')"></div>
                        <div class="qcp-swatch" style="background: #7c3aed;" title="Tím Violet (#7c3aed)" onclick="applyQuickColor('#7c3aed')"></div>
                        <div class="qcp-swatch" style="background: #e11d48;" title="Đỏ Hồng Rose (#e11d48)" onclick="applyQuickColor('#e11d48')"></div>
                        <div class="qcp-swatch" style="background: #d97706;" title="Cam Hổ Phách (#d97706)" onclick="applyQuickColor('#d97706')"></div>
                        <div class="qcp-swatch" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);" title="Gradient Xanh Dương" onclick="applyQuickColor('linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)', true)"></div>
                        <div class="qcp-swatch" style="background: linear-gradient(135deg, #7c3aed 0%, #db2777 100%);" title="Gradient Tím Hồng" onclick="applyQuickColor('linear-gradient(135deg, #7c3aed 0%, #db2777 100%)', true)"></div>
                        <div class="qcp-swatch" style="background: linear-gradient(135deg, #059669 0%, #0284c7 100%);" title="Gradient Ngọc Biển" onclick="applyQuickColor('linear-gradient(135deg, #059669 0%, #0284c7 100%)', true)"></div>
                        <div class="qcp-swatch" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);" title="Gradient Đêm Đậm" onclick="applyQuickColor('linear-gradient(135deg, #0f172a 0%, #1e293b 100%)', true)"></div>
                        <div class="qcp-swatch" style="background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);" title="Gradient Hoàng Hôn" onclick="applyQuickColor('linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)', true)"></div>
                    </div>
                </div>

                {{-- Quick Presets: Bo Góc, Bóng Đổ & Bố Cục 1-Chạm --}}
                <div id="quick-presets-card" class="quick-presets-card" style="display: none;">
                    <div class="qcp-header">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-magic" style="color: var(--accent-violet);"></i>
                            <span>HIỆU ỨNG & BO GÓC 1-CHẠM</span>
                        </div>
                    </div>
                    <div class="qp-row">
                        <span class="qp-label">Bo góc:</span>
                        <div class="qp-btn-group">
                            <button type="button" class="qp-btn" onclick="applyQuickRadius('0px')" title="Không bo (Vuông)">0</button>
                            <button type="button" class="qp-btn" onclick="applyQuickRadius('6px')" title="Bo nhẹ (6px)">6px</button>
                            <button type="button" class="qp-btn" onclick="applyQuickRadius('12px')" title="Bo vừa (12px)">12px</button>
                            <button type="button" class="qp-btn" onclick="applyQuickRadius('20px')" title="Bo lớn (20px)">20px</button>
                            <button type="button" class="qp-btn" onclick="applyQuickRadius('9999px')" title="Bo tròn viên thuốc (Pill)">Pill</button>
                        </div>
                    </div>
                    <div class="qp-row" style="margin-top: 6px;">
                        <span class="qp-label">Bóng đổ:</span>
                        <div class="qp-btn-group">
                            <button type="button" class="qp-btn" onclick="applyQuickShadow('none')" title="Tắt bóng đổ">Tắt</button>
                            <button type="button" class="qp-btn" onclick="applyQuickShadow('0 2px 8px rgba(0,0,0,0.06)')" title="Bóng nhẹ">Nhẹ</button>
                            <button type="button" class="qp-btn" onclick="applyQuickShadow('0 10px 25px -5px rgba(0,0,0,0.12)')" title="Bóng nổi">Nổi</button>
                            <button type="button" class="qp-btn" onclick="applyQuickShadow('0 20px 35px -8px rgba(0,0,0,0.22)')" title="Bóng đậm">Đậm</button>
                            <button type="button" class="qp-btn" onclick="applyQuickShadow('0 0 25px rgba(37,99,235,0.38)')" title="Hào quang xanh">Glow</button>
                        </div>
                    </div>
                    <div class="qp-row" style="margin-top: 6px;">
                        <span class="qp-label">Bố cục:</span>
                        <div class="qp-btn-group" style="flex: 1;">
                            <button type="button" class="qp-btn" style="flex: 1;" onclick="applyQuickAlign('left')" title="Canh lề trái"><i class="fa fa-align-left"></i></button>
                            <button type="button" class="qp-btn" style="flex: 1;" onclick="applyQuickAlign('center')" title="Căn giữa khối"><i class="fa fa-align-center"></i> Giữa</button>
                            <button type="button" class="qp-btn" style="flex: 1;" onclick="applyQuickAlign('right')" title="Canh lề phải"><i class="fa fa-align-right"></i></button>
                            <button type="button" class="qp-btn" style="flex: 1;" onclick="applyQuickAlign('full')" title="Rộng 100%"><i class="fa fa-arrows-alt-h"></i> 100%</button>
                        </div>
                    </div>
                </div>

                {{-- Empty State (When no element is selected) --}}
                <div id="inspector-empty-state" class="inspector-empty-state">
                    <span style="font-size: 2.2rem; display: inline-block; margin-bottom: 0.6rem;">👆</span>
                    <h4 style="font-size: 0.88rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.4rem;" data-i18n="empty_title">Chưa chọn phần tử nào</h4>
                    <p style="font-size: 0.75rem; line-height: 1.5; color: var(--text-subtle);" data-i18n="empty_desc">
                        Nhấp vào bất kỳ khối nào trên bản vẽ canvas để chỉnh sửa màu sắc, font chữ, kích thước, viền và thuộc tính.
                    </p>
                </div>

                {{-- ==========================================================
                   SMART CONTEXTUAL SETUP CARDS (AUTO ADAPTS TO VIDEO / IMAGE / LINK / TEXT)
                   ========================================================== --}}
                <div id="smart-setup-wrapper" style="display: none;">
                    {{-- 1. VIDEO SETUP --}}
                    <div id="smart-video-setup" class="smart-setup-card" style="display: none;">
                        <div class="smart-setup-header">
                            <i class="fa fa-circle-play" style="color: #f43f5e; font-size: 1rem;"></i>
                            <span>CẤU HÌNH VIDEO (VIDEO SETTINGS)</span>
                        </div>
                        <div class="smart-form-row">
                            <label>Nguồn phát video (Provider):</label>
                            <select id="video-provider-select" class="gjs-field" style="width: 100%;" onchange="handleVideoProviderChange(this.value)">
                                <option value="yt">🎬 YouTube (Khuyên dùng)</option>
                                <option value="so">📁 File MP4 Trực Tiếp / HTML5</option>
                                <option value="vi">🎥 Vimeo</option>
                            </select>
                        </div>
                        <div class="smart-form-row">
                            <label id="video-url-label">Đường dẫn Video YouTube / Link xem:</label>
                            <input type="text" id="video-url-input" class="gjs-field" style="width: 100%;" placeholder="https://www.youtube.com/watch?v=..." oninput="updateVideoComponentSettings()">
                            <div id="video-url-hint" style="font-size: 0.65rem; color: #94a3b8; margin-top: 3px;">
                                Dán link YouTube đầy đủ hoặc ID video.
                            </div>
                        </div>
                        <div class="smart-checkbox-grid">
                            <label class="smart-toggle-label">
                                <input type="checkbox" id="video-opt-autoplay" onchange="updateVideoComponentSettings()">
                                <span>▶ Tự động phát</span>
                            </label>
                            <label class="smart-toggle-label">
                                <input type="checkbox" id="video-opt-loop" onchange="updateVideoComponentSettings()">
                                <span>🔁 Lặp lại</span>
                            </label>
                            <label class="smart-toggle-label">
                                <input type="checkbox" id="video-opt-controls" checked onchange="updateVideoComponentSettings()">
                                <span>🎛️ Nút điều khiển</span>
                            </label>
                            <label class="smart-toggle-label">
                                <input type="checkbox" id="video-opt-muted" onchange="updateVideoComponentSettings()">
                                <span>🔇 Tắt tiếng</span>
                            </label>
                        </div>
                        <div id="video-poster-row" class="smart-form-row" style="display: none;">
                            <label>Ảnh bìa trước khi phát (Poster Image URL):</label>
                            <input type="text" id="video-poster-input" class="gjs-field" style="width: 100%;" placeholder="https://... ảnh bìa" onchange="updateVideoComponentSettings()">
                        </div>
                        <div style="display: flex; gap: 0.35rem; align-items: center; margin-top: 0.4rem;">
                            <span style="font-size: 0.65rem; color: var(--text-subtle);">Mẫu:</span>
                            <button type="button" class="btn-sample-pill" onclick="applySampleVideo('yt')">Link YouTube Mẫu</button>
                            <button type="button" class="btn-sample-pill" onclick="applySampleVideo('mp4')">File MP4 Mẫu</button>
                        </div>
                    </div>

                    {{-- 2. IMAGE SETUP --}}
                    <div id="smart-image-setup" class="smart-setup-card" style="display: none;">
                        <div class="smart-setup-header">
                            <i class="fa fa-image" style="color: #38bdf8; font-size: 1rem;"></i>
                            <span>CẤU HÌNH HÌNH ẢNH (IMAGE SETTINGS)</span>
                        </div>
                        <div class="smart-form-row">
                            <label>Đường dẫn hình ảnh (Image URL):</label>
                            <input type="text" id="smart-img-src" class="gjs-field" style="width: 100%; margin-bottom: 0.4rem;" onchange="updateSmartImageSrc(this.value)">
                            <label class="btn-topbar" style="width: 100%; justify-content: center; cursor: pointer;">
                                <i class="fa fa-cloud-arrow-up"></i> <span>Tải ảnh mới từ máy tính</span>
                                <input type="file" accept="image/*" style="display: none;" onchange="uploadImageForSelected(this)">
                            </label>
                        </div>
                        <div class="smart-form-row">
                            <label>Tỷ lệ hiển thị (Object Fit):</label>
                            <select id="smart-img-fit" class="gjs-field" style="width: 100%;" onchange="updateSmartImageFit(this.value)">
                                <option value="cover">Cover (Cắt vừa khung, đẹp nhất)</option>
                                <option value="contain">Contain (Giữ nguyên tỷ lệ, không cắt)</option>
                                <option value="fill">Fill (Kéo dãn vừa khung)</option>
                                <option value="scale-down">Scale Down</option>
                            </select>
                        </div>
                    </div>

                    {{-- 3. LINK / BUTTON SETUP --}}
                    <div id="smart-link-setup" class="smart-setup-card" style="display: none;">
                        <div class="smart-setup-header">
                            <i class="fa fa-link" style="color: #10b981; font-size: 1rem;"></i>
                            <span>CẤU HÌNH NÚT &amp; LIÊN KẾT (BUTTON &amp; LINK)</span>
                        </div>
                        <div class="smart-form-row">
                            <label>Nhãn nút bấm (Button Text):</label>
                            <input type="text" id="smart-btn-text" class="gjs-field" style="width: 100%;" oninput="updateSmartButtonText(this.value)">
                        </div>
                        <div class="smart-form-row">
                            <label>Chọn trang trong website (Internal Page):</label>
                            <select id="smart-btn-internal-page" class="gjs-field" style="width: 100%; margin-bottom: 0.35rem;" onchange="onSelectInternalPage(this.value)">
                                <option value="">-- Chọn trang nội bộ để tự điền link --</option>
                                @foreach($page->website->pages as $p)
                                    @php
                                        $pageUrl = $p->is_home ? '/' : '/' . ltrim($p->slug, '/');
                                    @endphp
                                    <option value="{{ $pageUrl }}">
                                        {{ $p->is_home ? '🏠' : '📄' }} {{ $p->title }} ({{ $pageUrl }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="smart-form-row">
                            <label>Đích đến liên kết (Href):</label>
                            <input type="text" id="smart-btn-href" class="gjs-field" style="width: 100%;" placeholder="/slug-trang, https://... hoặc #neo" onchange="updateSmartButtonHref(this.value)">
                            <div style="font-size: 0.65rem; color: var(--text-subtle); margin-top: 0.25rem; line-height: 1.35;">
                                💡 <b>/slug</b>: Sang trang khác (vd: <code>/dang-ky</code>)<br>
                                💡 <b>#id</b>: Cuộn tới phần tử cùng trang (vd: <code>#pricing</code>)<br>
                                💡 <b>https://...</b>: Mở liên kết ngoài
                            </div>
                        </div>
                        <label class="smart-toggle-label" style="margin-top: 0.4rem;">
                            <input type="checkbox" id="smart-btn-blank" onchange="updateSmartButtonBlank(this.checked)">
                            <span>Mở liên kết trong tab mới (_blank)</span>
                        </label>
                    </div>

                    {{-- 4. TEXT CONTENT SETUP --}}
                    <div id="smart-text-setup" class="smart-setup-card" style="display: none;">
                        <div class="smart-setup-header">
                            <i class="fa fa-font" style="color: #f59e0b; font-size: 1rem;"></i>
                            <span>NỘI DUNG VĂN BẢN (TEXT CONTENT)</span>
                        </div>
                        <div class="smart-form-row">
                            <label>Sửa chữ trực tiếp:</label>
                            <textarea id="smart-text-content" rows="2" class="gjs-field" style="width: 100%;" oninput="updateSmartTextContent(this.value)"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Visual Box Model (Margin & Padding) --}}
                <div id="box-model-wrapper" class="box-model-container" style="display: none;">
                    <div class="box-model-title">
                        <i class="fa fa-vector-square" style="color: var(--accent-amber);"></i>
                        <span data-i18n="box_model">MÔ HÌNH KHOẢNG CÁCH (SPACING)</span>
                    </div>
                    <div class="bm-outer-margin">
                        <span class="bm-tag tag-margin">MARGIN</span>
                        <input id="bm-m-top" class="bm-input bm-pos-top" placeholder="0" onchange="updateBoxModel('margin-top', this.value)" title="Margin Top">
                        <input id="bm-m-bottom" class="bm-input bm-pos-bottom" placeholder="0" onchange="updateBoxModel('margin-bottom', this.value)" title="Margin Bottom">
                        <input id="bm-m-left" class="bm-input bm-pos-left" placeholder="0" onchange="updateBoxModel('margin-left', this.value)" title="Margin Left">
                        <input id="bm-m-right" class="bm-input bm-pos-right" placeholder="0" onchange="updateBoxModel('margin-right', this.value)" title="Margin Right">

                        <div class="bm-inner-padding">
                            <span class="bm-tag tag-padding">PADDING</span>
                            <input id="bm-p-top" class="bm-input bm-pos-top" placeholder="0" onchange="updateBoxModel('padding-top', this.value)" title="Padding Top">
                            <input id="bm-p-bottom" class="bm-input bm-pos-bottom" placeholder="0" onchange="updateBoxModel('padding-bottom', this.value)" title="Padding Bottom">
                            <input id="bm-p-left" class="bm-input bm-pos-left" placeholder="0" onchange="updateBoxModel('padding-left', this.value)" title="Padding Left">
                            <input id="bm-p-right" class="bm-input bm-pos-right" placeholder="0" onchange="updateBoxModel('padding-right', this.value)" title="Padding Right">

                            <div class="bm-center-core" title="Kích thước thực tế phần tử">
                                <span id="bm-elem-size">AUTO × AUTO</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- GrapesJS Style Manager Mount --}}
                <div id="grapes-styles-container"></div>
            </div>

            {{-- Tab Pane 2: Traits & Attributes --}}
            <div id="inspector-pane-traits" class="inspector-tab-pane">
                <div style="font-size: 0.74rem; color: var(--text-muted); margin-bottom: 0.75rem; font-weight: 700;" data-i18n="traits_header">
                    THUỘC TÍNH & NỘI DUNG:
                </div>

                {{-- Custom Quick Trait Helpers --}}
                <div id="quick-traits-box" style="margin-bottom: 1rem; display: flex; flex-direction: column; gap: 0.65rem;">
                    {{-- Text Quick Edit --}}
                    <div id="qt-text-group" style="display: none;">
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.3rem;" data-i18n="edit_text_label">Sửa nội dung chữ:</label>
                        <textarea id="qt-text-input" rows="2" class="gjs-field" style="width: 100%;" oninput="updateSelectedText(this.value)"></textarea>
                    </div>

                    {{-- Image Quick Edit --}}
                    <div id="qt-image-group" style="display: none;">
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.3rem;" data-i18n="image_url_label">Đường dẫn ảnh (Image URL):</label>
                        <input id="qt-img-src" type="text" class="gjs-field" style="width: 100%; margin-bottom: 0.4rem;" onchange="updateSelectedImageSrc(this.value)">
                        <label class="btn-topbar" style="width: 100%; justify-content: center; cursor: pointer;">
                            <i class="fa fa-folder-open"></i> <span data-i18n="upload_new_img">Tải ảnh mới từ máy tính</span>
                            <input type="file" accept="image/*" style="display: none;" onchange="uploadImageForSelected(this)">
                        </label>
                    </div>

                    {{-- Link Quick Edit --}}
                    <div id="qt-link-group" style="display: none;">
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.3rem;" data-i18n="link_url_label">Đường dẫn liên kết (Href):</label>
                        <input id="qt-link-href" type="text" class="gjs-field" style="width: 100%; margin-bottom: 0.4rem;" placeholder="https://... hoặc #features" onchange="updateSelectedLink(this.value)">
                        <label style="font-size: 0.72rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.4rem;">
                            <input type="checkbox" id="qt-link-blank" onchange="updateSelectedLinkTarget(this.checked)"> <span data-i18n="open_new_tab">Mở trong tab mới (_blank)</span>
                        </label>
                    </div>
                </div>

                <div id="grapes-traits-container"></div>
            </div>
        </aside>

    </div>

    {{-- Exit Preview Mode Floating Pill --}}
    <button class="preview-exit-pill" onclick="togglePreviewMode()">
        <i class="fa fa-xmark"></i> <span data-i18n="exit_preview">Thoát chế độ xem thử</span>
    </button>

    {{-- Code Viewer Modal --}}
    <div id="code-modal" class="studio-modal-backdrop">
        <div class="studio-modal-box">
            <div class="drawer-header" style="padding: 1rem 1.25rem;">
                <div style="font-weight: 800; font-size: 0.95rem; color: var(--text-main);">Mã Nguồn Trang (HTML &amp; CSS Đã Biên Dịch)</div>
                <button onclick="closeCodeModal()" class="btn-topbar btn-icon-only">✕</button>
            </div>
            <div style="padding: 1.25rem; overflow-y: auto; flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.78rem; color: var(--text-muted);">Mã này sẽ được SSG compiler đóng gói tĩnh lên AWS S3:</span>
                    <button onclick="copyCodeContent()" class="btn-topbar" style="padding: 2px 10px; font-size: 0.75rem;">📋 Sao chép mã</button>
                </div>
                <textarea id="code-textarea" style="width: 100%; height: 350px; background: #0f172a; border: 1px solid var(--border-studio); border-radius: 8px; color: #38bdf8; font-family: monospace; font-size: 0.8rem; padding: 0.75rem; resize: none; outline: none;" readonly></textarea>
            </div>
        </div>
    </div>

    {{-- New Page Modal --}}
    <div id="new-page-modal" class="studio-modal-backdrop">
        <div class="studio-modal-box" style="max-width: 480px;">
            <div class="drawer-header" style="padding: 1rem 1.25rem;">
                <div style="font-weight: 800; font-size: 0.95rem; color: var(--text-main);">➕ Tạo Trang Mới Cho Website</div>
                <button onclick="closeNewPageModal()" class="btn-topbar btn-icon-only">✕</button>
            </div>
            <div style="padding: 1.25rem;">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.35rem;">Tiêu đề trang:</label>
                    <input type="text" id="new-page-title" class="gjs-field" style="width: 100%; padding: 0.5rem;" placeholder="Ví dụ: Giới thiệu công ty" oninput="autoGenerateSlug(this.value)">
                </div>
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.35rem;">Đường dẫn (Slug):</label>
                    <input type="text" id="new-page-slug" class="gjs-field" style="width: 100%; padding: 0.5rem;" placeholder="gioi-thieu">
                </div>
                <button onclick="submitCreateNewPage()" class="btn-topbar btn-publish-ssg" style="width: 100%; justify-content: center; padding: 0.6rem;">
                    ✓ Tạo Trang Ngay
                </button>
            </div>
        </div>
    </div>

    {{-- Studio Pro Color Palette Popover (Chuẩn UX Figma/Canva) --}}
    <div id="studio-color-palette-popover" class="studio-color-popover" style="display: none;">
        <div class="scp-header">
            <div class="scp-title">
                <i class="fa fa-palette" style="color: var(--primary);"></i>
                <span id="scp-title-text">BẢNG MÀU THIẾT KẾ</span>
            </div>
            <button type="button" class="scp-close-btn" onclick="closeStudioColorPopover()" title="Đóng bảng màu">✕</button>
        </div>

        {{-- Active Color Preview & Hex Input & EyeDropper --}}
        <div class="scp-active-row">
            <div class="scp-preview-wrapper" onclick="triggerNativeColorPicker()" title="Nhấp để mở bánh xe pha màu chi tiết">
                <div id="scp-active-preview" class="scp-active-preview"></div>
                <input type="color" id="scp-native-color-picker" style="position: absolute; opacity: 0; pointer-events: none; width: 0; height: 0;" oninput="onNativeColorChange(this.value)">
            </div>
            <input type="text" id="scp-hex-input" class="scp-hex-input" placeholder="#000000" maxlength="35" oninput="onHexInputChange(this.value)" onkeydown="if(event.key==='Enter') closeStudioColorPopover()">
            <button type="button" class="scp-eyedropper-btn" onclick="pickColorWithEyeDropper()" title="Ống hút chấm màu trên màn hình (Eyedropper)">
                <i class="fa fa-eye-dropper"></i>
            </button>
        </div>

        {{-- Quick Actions: Trong suốt (None) & Bánh xe màu --}}
        <div class="scp-quick-actions">
            <button type="button" class="scp-action-btn" onclick="applyStudioColor('none')">
                <span class="scp-swatch-checkered"></span>
                <span>Trong suốt (None)</span>
            </button>
            <button type="button" class="scp-action-btn primary" onclick="triggerNativeColorPicker()">
                <i class="fa fa-sliders"></i>
                <span>Pha màu chi tiết...</span>
            </button>
        </div>

        {{-- Section 1: Palette Màu Cơ Bản & Trung Tính --}}
        <div class="scp-section-label">MÀU CƠ BẢN & TRUNG TÍNH</div>
        <div class="scp-swatches-grid" id="scp-neutral-swatches"></div>

        {{-- Section 2: Palette Màu Hiện Đại & Sắc Nét --}}
        <div class="scp-section-label">BẢNG MÀU HIỆN ĐẠI</div>
        <div class="scp-swatches-grid" id="scp-brand-swatches"></div>

        {{-- Section 3: Gradients (Chỉ hiển thị khi chọn màu nền) --}}
        <div id="scp-gradients-section" style="display: none; margin-top: 10px;">
            <div class="scp-section-label">GRADIENT HIỆN ĐẠI (NỀN)</div>
            <div class="scp-gradients-grid" id="scp-gradients-grid"></div>
        </div>
    </div>

    {{-- GrapesJS Core & Official Plugins --}}
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic"></script>
    <script src="https://unpkg.com/grapesjs-plugin-forms"></script>

    <script>
        const pageId = {{ $page->id }};
        const websiteId = {{ $page->website->id }};
        let currentVersion = {{ $page->version_number }};
        const initialContent = @json($page->draft_content);
        let currentLocale = "{{ app()->getLocale() }}";

        // ======================================================================
        // 1. MULTILINGUAL DICTIONARY (VI / EN)
        // ======================================================================
        const I18N = {
            vi: {
                dashboard: "Dashboard",
                add_new_page: "Thêm trang mới...",
                status_synced: "Đã lưu",
                status_saving: "Đang lưu...",
                preview: "Xem thử",
                exit_preview: "Thoát xem thử",
                code: "Code",
                save_draft: "Lưu Nháp",
                publish: "Xuất Bản",
                live: "Live",
                elements_title: "Thành Phần & Khối",
                sections_title: "Mẫu Giao Diện Sẵn",
                layers_title: "Cây Cấu Trúc DOM",
                pages_title: "Danh Sách Trang",
                media_title: "Thư Viện Media",
                tab_styles: "Kiểu Dáng",
                tab_traits: "Thuộc Tính",
                empty_title: "Chưa chọn phần tử nào",
                empty_desc: "Nhấp vào bất kỳ khối nào trên bản vẽ canvas để chỉnh sửa màu sắc, font chữ, kích thước, viền và thuộc tính.",
                box_model: "Mô Hình Khoảng Cách (Spacing)",
                traits_header: "THUỘC TÍNH & NỘI DUNG:",
                edit_text_label: "Sửa nội dung chữ:",
                image_url_label: "Đường dẫn ảnh (Image URL):",
                upload_new_img: "Tải ảnh mới từ máy tính",
                link_url_label: "Đường dẫn liên kết (Href):",
                open_new_tab: "Mở trong tab mới (_blank)",
                alert_saved: "✅ Đã lưu bản thảo trang thành công!",
                alert_conflict: "⚠️ Xung đột phiên bản: Trang này đã được chỉnh sửa tại một tab khác!",
                alert_publish_confirm: "Bạn có muốn xuất bản toàn bộ trang tĩnh SSG lên AWS S3 và Cloudflare CDN không?",
                alert_publish_success: "🎉 XUẤT BẢN THÀNH CÔNG!\nWebsite đã được đẩy lên S3 / CDN.",
            },
            en: {
                dashboard: "Dashboard",
                add_new_page: "Add new page...",
                status_synced: "Saved",
                status_saving: "Saving...",
                preview: "Preview",
                exit_preview: "Exit Preview",
                code: "Code",
                save_draft: "Save Draft",
                publish: "Publish",
                live: "Live",
                elements_title: "Elements & Blocks",
                sections_title: "Pre-built Sections",
                layers_title: "DOM Navigator",
                pages_title: "Pages List",
                media_title: "Media Library",
                tab_styles: "Styles",
                tab_traits: "Settings",
                empty_title: "No element selected",
                empty_desc: "Click any block on the canvas to inspect and customize colors, typography, sizing, borders, and traits.",
                box_model: "Spacing Box Model",
                traits_header: "PROPERTIES & TRAITS:",
                edit_text_label: "Edit text content:",
                image_url_label: "Image Source URL:",
                upload_new_img: "Upload new image from PC",
                link_url_label: "Link Destination (Href):",
                open_new_tab: "Open in new tab (_blank)",
                alert_saved: "✅ Draft saved successfully!",
                alert_conflict: "⚠️ Version conflict: This page was modified in another tab!",
                alert_publish_confirm: "Publish entire static site to AWS S3 & Cloudflare CDN?",
                alert_publish_success: "🎉 PUBLISHED SUCCESSFULLY!\nStatic site is now live on S3 & CDN.",
            }
        };

        function applyI18nTexts(lang) {
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (I18N[lang] && I18N[lang][key]) {
                    el.innerText = I18N[lang][key];
                }
            });
            document.getElementById('lang-flag').innerText = (lang === 'vi' ? '🇻🇳' : '🇬🇧');
            document.getElementById('lang-code').innerText = (lang === 'vi' ? 'VI' : 'EN');
        }

        function toggleStudioLanguage() {
            currentLocale = (currentLocale === 'vi' ? 'en' : 'vi');
            applyI18nTexts(currentLocale);
        }

        // ======================================================================
        // STUDIO TOAST NOTIFICATIONS ENGINE (Replaces annoying browser alerts)
        // ======================================================================
        function showStudioToast(message, type = 'success', duration = 3000) {
            let container = document.getElementById('studio-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'studio-toast-container';
                document.body.appendChild(container);
            }

            const icons = {
                success: '<i class="fa fa-circle-check"></i>',
                error: '<i class="fa fa-circle-exclamation"></i>',
                info: '<i class="fa fa-circle-info"></i>'
            };

            const toast = document.createElement('div');
            toast.className = `studio-toast toast-${type}`;
            toast.innerHTML = `
                <span class="studio-toast-icon">${icons[type] || icons.info}</span>
                <span>${message}</span>
            `;
            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.add('show');
            });

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 260);
            }, duration);
        }

        // ======================================================================
        // 2. KHỞI TẠO GRAPESJS STUDIO (VỚI TOOLBAR DI CHUYỂN HOÀN HẢO)
        // ======================================================================
        const editor = grapesjs.init({
            container: '#gjs-canvas-wrapper',
            height: '100%',
            width: '100%',
            fromElement: false,
            storageManager: false,
            avoidInlineStyle: true,
            dragMode: '',
            selectorManager: {
                componentFirst: true,
            },
            panels: { defaults: [] },
            canvas: {
                styles: [
                    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
                    'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700;900&display=swap'
                ],
                frameStyle: `
                    html, body {
                        margin: 0 !important;
                        padding: 0 !important;
                        background: #ffffff !important;
                        min-height: 100% !important;
                        width: 100% !important;
                        overflow-x: hidden !important;
                        overflow-y: auto !important;
                        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
                    }
                    * {
                        box-sizing: border-box;
                    }
                    /* Loại trừ thẻ body / wrapper để không bao giờ vẽ khung xanh quanh toàn trang */
                    body, [data-gjs-type="wrapper"] {
                        outline: none !important;
                        cursor: default !important;
                    }
                    body:hover, [data-gjs-type="wrapper"]:hover {
                        outline: none !important;
                        background-color: transparent !important;
                    }
                    [data-gjs-type]:not(body):not([data-gjs-type="wrapper"]) {
                        cursor: pointer;
                        transition: outline 0.15s ease;
                    }
                    [data-gjs-type]:not(body):not([data-gjs-type="wrapper"]):hover {
                        outline: 1.5px dashed rgba(37, 99, 235, 0.45) !important;
                        outline-offset: -1px;
                    }
                    [data-gjs-droppable="true"]:not(body):not([data-gjs-type="wrapper"]):hover {
                        outline: 2px dashed #0284c7 !important;
                        background-color: rgba(2, 132, 199, 0.02) !important;
                    }
                    .gjs-hovered:not(body):not([data-gjs-type="wrapper"]) {
                        outline: 2px dashed #2563eb !important;
                    }
                    .gjs-selected:not(body):not([data-gjs-type="wrapper"]) {
                        outline: 2px solid #2563eb !important;
                        outline-offset: -1px;
                    }
                `
            },
            blockManager: {
                appendTo: '#grapes-blocks-container',
            },
            styleManager: {
                appendTo: '#grapes-styles-container',
                sectors: [
                    {
                        name: '📐 Bố Cục & Vị Trí (Layout)',
                        open: true,
                        buildProps: ['position', 'top', 'left', 'right', 'bottom', 'z-index', 'display', 'text-align', 'flex-direction', 'justify-content', 'align-items', 'flex-wrap', 'gap'],
                        properties: [
                            {
                                name: 'Kiểu Vị Trí (Position)',
                                property: 'position',
                                type: 'select',
                                defaults: 'static',
                                options: [
                                    { value: 'static', name: 'Mặc định (Static - theo luồng)' },
                                    { value: 'relative', name: 'Tương đối (Relative)' },
                                    { value: 'absolute', name: 'Tự do (Absolute - kéo thả tùy ý)' },
                                    { value: 'fixed', name: 'Cố định màn hình (Fixed)' },
                                    { value: 'sticky', name: 'Dính mép khi cuộn (Sticky)' },
                                ]
                            },
                            { name: 'Cách Đỉnh (Top)', property: 'top', type: 'integer', units: ['px', '%', 'auto'] },
                            { name: 'Cách Trái (Left)', property: 'left', type: 'integer', units: ['px', '%', 'auto'] },
                            { name: 'Cách Phải (Right)', property: 'right', type: 'integer', units: ['px', '%', 'auto'] },
                            { name: 'Cách Đáy (Bottom)', property: 'bottom', type: 'integer', units: ['px', '%', 'auto'] },
                            { name: 'Thứ Tự Lớp (Z-Index)', property: 'z-index', type: 'integer', defaults: '0' },
                            {
                                name: 'Hiển thị (Display)',
                                property: 'display',
                                type: 'select',
                                defaults: 'block',
                                options: [
                                    { value: 'block', name: 'Block (Khối tiêu chuẩn)' },
                                    { value: 'flex', name: 'Flexbox (Căn chỉnh linh hoạt)' },
                                    { value: 'grid', name: 'Grid (Lưới)' },
                                    { value: 'inline-block', name: 'Inline Block (Ngang hàng)' },
                                    { value: 'inline', name: 'Inline (Nội dòng)' },
                                    { value: 'none', name: 'Ẩn (None)' },
                                ]
                            },
                            {
                                name: 'Căn lề chữ (Text Align)',
                                property: 'text-align',
                                type: 'radio',
                                defaults: 'left',
                                list: [
                                    { value: 'left', name: 'Trái', title: 'Căn trái' },
                                    { value: 'center', name: 'Giữa', title: 'Căn giữa' },
                                    { value: 'right', name: 'Phải', title: 'Căn phải' },
                                    { value: 'justify', name: 'Đều', title: 'Căn đều 2 bên' },
                                ]
                            },
                            {
                                name: 'Hướng Flex (Direction)',
                                property: 'flex-direction',
                                type: 'select',
                                defaults: 'row',
                                options: [
                                    { value: 'row', name: 'Hàng Ngang (Row)' },
                                    { value: 'column', name: 'Cột Dọc (Column)' },
                                    { value: 'row-reverse', name: 'Hàng Ngược' },
                                    { value: 'column-reverse', name: 'Cột Ngược' },
                                ]
                            },
                            {
                                name: 'Căn trục chính (Justify)',
                                property: 'justify-content',
                                type: 'select',
                                defaults: 'flex-start',
                                options: [
                                    { value: 'flex-start', name: 'Bắt đầu (Start)' },
                                    { value: 'center', name: 'Chính giữa (Center)' },
                                    { value: 'flex-end', name: 'Cuối hàng (End)' },
                                    { value: 'space-between', name: 'Giãn 2 đầu (Between)' },
                                    { value: 'space-around', name: 'Giãn đều (Around)' },
                                    { value: 'space-evenly', name: 'Cách đều (Evenly)' },
                                ]
                            },
                            {
                                name: 'Căn trục phụ (Align)',
                                property: 'align-items',
                                type: 'select',
                                defaults: 'stretch',
                                options: [
                                    { value: 'stretch', name: 'Kéo dãn (Stretch)' },
                                    { value: 'center', name: 'Chính giữa (Center)' },
                                    { value: 'flex-start', name: 'Bắt đầu (Start)' },
                                    { value: 'flex-end', name: 'Cuối hàng (End)' },
                                    { value: 'baseline', name: 'Chân dòng (Baseline)' },
                                ]
                            },
                            {
                                name: 'Xuống dòng (Wrap)',
                                property: 'flex-wrap',
                                type: 'select',
                                defaults: 'nowrap',
                                options: [
                                    { value: 'nowrap', name: 'Không xuống dòng' },
                                    { value: 'wrap', name: 'Tự xuống dòng (Wrap)' },
                                ]
                            },
                            { name: 'Khoảng cách Gap', property: 'gap', type: 'integer', units: ['px', 'rem', '%'], defaults: '0px' },
                        ]
                    },
                    {
                        name: '📦 Khoảng Cách (Spacing)',
                        open: true,
                        buildProps: ['margin-top', 'margin-right', 'margin-bottom', 'margin-left', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left'],
                        properties: [
                            { name: 'Margin Đỉnh', property: 'margin-top', type: 'integer', units: ['px', '%', 'auto', 'rem'], defaults: '0px' },
                            { name: 'Margin Đáy', property: 'margin-bottom', type: 'integer', units: ['px', '%', 'auto', 'rem'], defaults: '0px' },
                            { name: 'Margin Trái', property: 'margin-left', type: 'integer', units: ['px', '%', 'auto', 'rem'], defaults: '0px' },
                            { name: 'Margin Phải', property: 'margin-right', type: 'integer', units: ['px', '%', 'auto', 'rem'], defaults: '0px' },
                            { name: 'Padding Đỉnh', property: 'padding-top', type: 'integer', units: ['px', '%', 'rem'], defaults: '0px' },
                            { name: 'Padding Đáy', property: 'padding-bottom', type: 'integer', units: ['px', '%', 'rem'], defaults: '0px' },
                            { name: 'Padding Trái', property: 'padding-left', type: 'integer', units: ['px', '%', 'rem'], defaults: '0px' },
                            { name: 'Padding Phải', property: 'padding-right', type: 'integer', units: ['px', '%', 'rem'], defaults: '0px' },
                        ]
                    },
                    {
                        name: '📏 Kích Thước (Size)',
                        open: true,
                        buildProps: ['width', 'min-width', 'max-width', 'height', 'min-height', 'max-height', 'overflow'],
                        properties: [
                            { name: 'Chiều rộng', property: 'width', type: 'integer', units: ['px', '%', 'vw', 'auto'], defaults: 'auto' },
                            { name: 'Rộng tối đa', property: 'max-width', type: 'integer', units: ['px', '%', 'vw', 'none'], defaults: 'none' },
                            { name: 'Rộng tối thiểu', property: 'min-width', type: 'integer', units: ['px', '%', 'vw', 'auto'], defaults: 'auto' },
                            { name: 'Chiều cao', property: 'height', type: 'integer', units: ['px', '%', 'vh', 'auto'], defaults: 'auto' },
                            { name: 'Cao tối đa', property: 'max-height', type: 'integer', units: ['px', '%', 'vh', 'none'], defaults: 'none' },
                            { name: 'Cao tối thiểu', property: 'min-height', type: 'integer', units: ['px', '%', 'vh', 'auto'], defaults: 'auto' },
                            {
                                name: 'Tràn viền (Overflow)',
                                property: 'overflow',
                                type: 'select',
                                defaults: 'visible',
                                options: [
                                    { value: 'visible', name: 'Hiển thị tràn (Visible)' },
                                    { value: 'hidden', name: 'Cắt bỏ phần tràn (Hidden)' },
                                    { value: 'auto', name: 'Hiện cuộn khi tràn (Auto)' },
                                    { value: 'scroll', name: 'Luôn hiện cuộn (Scroll)' },
                                ]
                            },
                        ]
                    },
                    {
                        name: '🔤 Chữ & Phông (Typography)',
                        open: true,
                        buildProps: ['font-family', 'font-size', 'line-height', 'font-weight', 'color', 'text-align', 'letter-spacing', 'text-transform', 'text-decoration'],
                        properties: [
                            {
                                name: 'Font chữ',
                                property: 'font-family',
                                type: 'select',
                                full: true,
                                defaults: 'Plus Jakarta Sans, sans-serif',
                                options: [
                                    { value: 'Plus Jakarta Sans, sans-serif', name: 'Plus Jakarta Sans (Mặc định)' },
                                    { value: 'Be Vietnam Pro, sans-serif', name: 'Be Vietnam Pro (Tiếng Việt)' },
                                    { value: 'Inter, sans-serif', name: 'Inter' },
                                    { value: 'Roboto, sans-serif', name: 'Roboto' },
                                    { value: 'Montserrat, sans-serif', name: 'Montserrat' },
                                    { value: 'Poppins, sans-serif', name: 'Poppins' },
                                    { value: 'Playfair Display, serif', name: 'Playfair Display (Thanh lịch)' },
                                    { value: 'sans-serif', name: 'System Sans-Serif' },
                                    { value: 'serif', name: 'System Serif' },
                                    { value: 'monospace', name: 'Monospace' },
                                ]
                            },
                            { name: 'Cỡ chữ', property: 'font-size', type: 'integer', units: ['px', 'rem', 'em'], defaults: '16px' },
                            { name: 'Chiều cao dòng', property: 'line-height', type: 'integer', units: ['px', 'em', 'normal'], defaults: 'normal' },
                            {
                                name: 'Độ đậm',
                                property: 'font-weight',
                                type: 'select',
                                defaults: '400',
                                options: [
                                    { value: '300', name: '300 (Nhẹ)' },
                                    { value: '400', name: '400 (Chuẩn)' },
                                    { value: '500', name: '500 (Vừa)' },
                                    { value: '600', name: '600 (Hơi đậm)' },
                                    { value: '700', name: '700 (Đậm)' },
                                    { value: '800', name: '800 (Rất đậm)' },
                                    { value: '900', name: '900 (Đặc)' },
                                ]
                            },
                            { name: 'Màu chữ', property: 'color', type: 'color', defaults: '#0f172a', full: true },
                            {
                                name: 'Căn lề',
                                property: 'text-align',
                                type: 'radio',
                                full: true,
                                defaults: 'left',
                                list: [
                                    { value: 'left', name: 'Trái', title: 'Căn trái' },
                                    { value: 'center', name: 'Giữa', title: 'Căn giữa' },
                                    { value: 'right', name: 'Phải', title: 'Căn phải' },
                                    { value: 'justify', name: 'Đều', title: 'Căn đều 2 bên' },
                                ]
                            },
                            { name: 'Giãn chữ', property: 'letter-spacing', type: 'integer', units: ['px', 'em'], defaults: 'normal' },
                            {
                                name: 'Biến đổi chữ',
                                property: 'text-transform',
                                type: 'select',
                                defaults: 'none',
                                options: [
                                    { value: 'none', name: 'Bình thường' },
                                    { value: 'uppercase', name: 'IN HOA TOÀN BỘ' },
                                    { value: 'lowercase', name: 'in thường toàn bộ' },
                                    { value: 'capitalize', name: 'Viết Hoa Đầu Từ' },
                                ]
                            },
                            {
                                name: 'Gạch chân',
                                property: 'text-decoration',
                                type: 'select',
                                defaults: 'none',
                                options: [
                                    { value: 'none', name: 'Không' },
                                    { value: 'underline', name: 'Gạch chân' },
                                    { value: 'line-through', name: 'Gạch ngang (Xóa)' },
                                ]
                            },
                        ]
                    },
                    {
                        name: '🎨 Nền & Hình Nền (Background)',
                        open: true,
                        buildProps: ['background-color', 'background-image', 'background-size', 'background-repeat', 'background-position', 'background-attachment'],
                        properties: [
                            { name: 'Màu nền', property: 'background-color', type: 'color', full: true },
                            {
                                name: 'Ảnh nền URL',
                                property: 'background-image',
                                type: 'text',
                                defaults: 'none',
                                full: true
                            },
                            {
                                name: 'Kích cỡ ảnh',
                                property: 'background-size',
                                type: 'select',
                                full: true,
                                defaults: 'cover',
                                options: [
                                    { value: 'cover', name: 'Phủ kín khung (Cover - Khuyên dùng)' },
                                    { value: 'contain', name: 'Vừa vặn trong khung (Contain)' },
                                    { value: 'auto', name: 'Kích thước gốc (Auto)' },
                                    { value: '100% 100%', name: 'Kéo dãn vừa khung (100% 100%)' },
                                ]
                            },
                            {
                                name: 'Lặp lại',
                                property: 'background-repeat',
                                type: 'select',
                                full: true,
                                defaults: 'no-repeat',
                                options: [
                                    { value: 'no-repeat', name: 'Không lặp (No-repeat - Khuyên dùng)' },
                                    { value: 'repeat', name: 'Lặp lại toàn bộ (Repeat)' },
                                    { value: 'repeat-x', name: 'Lặp theo chiều ngang (Repeat-X)' },
                                    { value: 'repeat-y', name: 'Lặp theo chiều dọc (Repeat-Y)' },
                                ]
                            },
                            {
                                name: 'Vị trí ảnh',
                                property: 'background-position',
                                type: 'select',
                                full: true,
                                defaults: 'center center',
                                options: [
                                    { value: 'center center', name: 'Chính giữa (Center)' },
                                    { value: 'top center', name: 'Đỉnh giữa (Top Center)' },
                                    { value: 'bottom center', name: 'Đáy giữa (Bottom Center)' },
                                    { value: 'left center', name: 'Trái giữa (Left Center)' },
                                    { value: 'right center', name: 'Phải giữa (Right Center)' },
                                    { value: 'top left', name: 'Góc trên trái (Top Left)' },
                                ]
                            },
                            {
                                name: 'Hiệu ứng cuộn',
                                property: 'background-attachment',
                                type: 'select',
                                full: true,
                                defaults: 'scroll',
                                options: [
                                    { value: 'scroll', name: 'Cuộn theo trang (Scroll)' },
                                    { value: 'fixed', name: 'Đứng yên cố định (Parallax)' },
                                ]
                            },
                        ]
                    },
                    {
                        name: '🔲 Viền & Bo Góc (Borders & Radius)',
                        open: true,
                        buildProps: ['border-radius', 'border-style', 'border-width', 'border-color'],
                        properties: [
                            {
                                name: 'Bo góc tròn (Radius)',
                                property: 'border-radius',
                                type: 'integer',
                                full: true,
                                units: ['px', '%'],
                                defaults: '0px'
                            },
                            {
                                name: 'Kiểu viền',
                                property: 'border-style',
                                type: 'select',
                                full: true,
                                defaults: 'none',
                                options: [
                                    { value: 'none', name: 'Không viền' },
                                    { value: 'solid', name: 'Nét liền (Solid)' },
                                    { value: 'dashed', name: 'Nét đứt (Dashed)' },
                                    { value: 'dotted', name: 'Chấm bi (Dotted)' },
                                    { value: 'double', name: 'Viền đôi (Double)' },
                                ]
                            },
                            { name: 'Độ dày viền', property: 'border-width', type: 'integer', units: ['px'], defaults: '0px' },
                            { name: 'Màu viền', property: 'border-color', type: 'color', defaults: '#e2e8f0', full: true },
                        ]
                    },
                    {
                        name: '✨ Hiệu Ứng & Đổ Bóng (Effects & Shadow)',
                        open: true,
                        buildProps: ['opacity', 'box-shadow', 'cursor'],
                        properties: [
                            {
                                name: 'Độ mờ (Opacity)',
                                property: 'opacity',
                                type: 'slider',
                                defaults: '1',
                                step: 0.05,
                                max: 1,
                                min: 0,
                            },
                            {
                                name: 'Bóng đổ (Box Shadow)',
                                property: 'box-shadow',
                                type: 'select',
                                full: true,
                                defaults: 'none',
                                options: [
                                    { value: 'none', name: 'Tắt bóng đổ (None)' },
                                    { value: '0 2px 8px rgba(0,0,0,0.06)', name: 'Bóng nhẹ (Subtle)' },
                                    { value: '0 10px 25px -5px rgba(0,0,0,0.12)', name: 'Bóng nổi (Floating)' },
                                    { value: '0 20px 35px -8px rgba(0,0,0,0.22)', name: 'Bóng đậm (Deep)' },
                                    { value: '0 0 25px rgba(37,99,235,0.38)', name: 'Hào quang xanh (Blue Glow)' },
                                    { value: '0 0 25px rgba(245,158,11,0.38)', name: 'Hào quang vàng (Gold Glow)' },
                                ]
                            },
                            {
                                name: 'Con trỏ chuột (Cursor)',
                                property: 'cursor',
                                type: 'select',
                                defaults: 'default',
                                options: [
                                    { value: 'default', name: 'Mặc định (Default)' },
                                    { value: 'pointer', name: 'Bàn tay nhấp (Pointer)' },
                                    { value: 'grab', name: 'Nắm kéo (Grab)' },
                                    { value: 'text', name: 'Con trỏ chữ (Text)' },
                                    { value: 'not-allowed', name: 'Cấm (Not Allowed)' },
                                ]
                            }
                        ]
                    }
                ]
            },
            traitManager: {
                appendTo: '#grapes-traits-container',
            },
            layerManager: {
                appendTo: '#grapes-layers-container',
            },
            deviceManager: {
                devices: [
                    { name: 'Desktop', width: '' },
                    { name: 'Tablet', width: '', widthMedia: '992px' },
                    { name: 'Mobile', width: '', widthMedia: '480px' },
                ]
            },
            plugins: ['gjs-blocks-basic', 'gjs-plugin-forms']
        });

        // ======================================================================
        // 3. KHỐI KÉO THẢ (BLOCKS & TEMPLATES)
        // ======================================================================
        const bm = editor.BlockManager;

        bm.add('layout-section', {
            label: 'Khung Chứa (Container)',
            category: '📦 Bố Cục (Layout)',
            media: '<i class="fa fa-square-full" style="font-size: 1.3rem;"></i>',
            content: `
                <section style="padding: 60px 20px; background: #ffffff;">
                    <div style="max-width: 1200px; margin: 0 auto;">
                        <h2 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 12px;">Khung Chứa Nội Dung Mới</h2>
                        <p style="color: #64748b; font-size: 16px;">Kéo thả thêm các thành phần khác vào khung này để thiết kế.</p>
                    </div>
                </section>
            `
        });

        bm.add('layout-2-cols', {
            label: 'Hàng 2 Cột (50/50)',
            category: '📦 Bố Cục (Layout)',
            media: '<i class="fa fa-table-columns" style="font-size: 1.3rem;"></i>',
            content: `
                <div style="display: flex; gap: 24px; flex-wrap: wrap; padding: 30px 0;">
                    <div style="flex: 1; min-width: 280px; padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <h3 style="font-size: 20px; font-weight: 700; color: #1e293b;">Cột 1</h3>
                        <p style="color: #64748b;">Nội dung cột bên trái</p>
                    </div>
                    <div style="flex: 1; min-width: 280px; padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <h3 style="font-size: 20px; font-weight: 700; color: #1e293b;">Cột 2</h3>
                        <p style="color: #64748b;">Nội dung cột bên phải</p>
                    </div>
                </div>
            `
        });

        bm.add('layout-card', {
            label: 'Thẻ Card Hiện Đại',
            category: '📦 Bố Cục (Layout)',
            media: '<i class="fa fa-id-card" style="font-size: 1.3rem;"></i>',
            content: `
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); max-width: 400px; margin: 20px auto;">
                    <span style="font-size: 32px; display: inline-block; margin-bottom: 12px;">🚀</span>
                    <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Tiêu Đề Thẻ</h3>
                    <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 20px;">Mô tả ngắn gọn về dịch vụ hoặc tính năng nổi bật.</p>
                    <a href="#" style="color: #2563eb; font-weight: 700; text-decoration: none;">Xem Chi Tiết →</a>
                </div>
            `
        });

        bm.add('layout-3-cols', {
            label: 'Hàng 3 Cột (33/33/33)',
            category: '📦 Bố Cục (Layout)',
            media: '<i class="fa fa-columns" style="font-size: 1.3rem;"></i>',
            content: `
                <div style="display: flex; gap: 20px; flex-wrap: wrap; padding: 24px 0;">
                    <div style="flex: 1; min-width: 220px; padding: 20px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <h4 style="font-size: 17px; font-weight: 700; color: #1e293b;">Cột 1</h4>
                        <p style="color: #64748b; font-size: 14px;">Nội dung cột trái</p>
                    </div>
                    <div style="flex: 1; min-width: 220px; padding: 20px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <h4 style="font-size: 17px; font-weight: 700; color: #1e293b;">Cột 2</h4>
                        <p style="color: #64748b; font-size: 14px;">Nội dung cột giữa</p>
                    </div>
                    <div style="flex: 1; min-width: 220px; padding: 20px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <h4 style="font-size: 17px; font-weight: 700; color: #1e293b;">Cột 3</h4>
                        <p style="color: #64748b; font-size: 14px;">Nội dung cột phải</p>
                    </div>
                </div>
            `
        });

        bm.add('typo-h1', {
            label: 'Tiêu Đề Lớn (H1)',
            category: '🔤 Văn Bản (Typography)',
            media: '<span style="font-size: 1.2rem; font-weight: 900;">H1</span>',
            content: '<h1 style="font-size: 44px; font-weight: 900; color: #0f172a; line-height: 1.2; margin-bottom: 16px;">Tiêu Đề Lớn Nổi Bật</h1>'
        });

        bm.add('typo-p', {
            label: 'Đoạn Văn Bản (P)',
            category: '🔤 Văn Bản (Typography)',
            media: '<span style="font-size: 1.2rem;">¶</span>',
            content: '<p style="font-size: 16px; color: #475569; line-height: 1.7; margin-bottom: 16px;">Đây là nội dung đoạn văn bản mô tả. Bạn có thể nhấp đúp chuột để chỉnh sửa trực tiếp nội dung này bất kỳ lúc nào.</p>'
        });

        bm.add('btn-primary', {
            label: 'Nút Gradient Chính',
            category: '🔘 Nút Bấm & Liên Kết',
            media: '<i class="fa fa-hand-pointer" style="font-size: 1.2rem;"></i>',
            content: '<a href="#" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; padding: 14px 32px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; display: inline-block; box-shadow: 0 4px 14px rgba(37,99,235,0.35);">Khám Phá Ngay →</a>'
        });

        bm.add('btn-outline', {
            label: 'Nút Viền Khung (Outline)',
            category: '🔘 Nút Bấm & Liên Kết',
            media: '<i class="fa fa-square-arrow-up-right" style="font-size: 1.2rem; color: #2563eb;"></i>',
            content: '<a href="#" style="border: 2px solid #2563eb; color: #2563eb; background: #ffffff; padding: 13px 30px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; display: inline-block;">Xem Chi Tiết →</a>'
        });

        bm.add('media-img', {
            label: 'Hình Ảnh',
            category: '🖼️ Hình Ảnh & Media',
            media: '<i class="fa fa-image" style="font-size: 1.2rem;"></i>',
            content: '<img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&auto=format&fit=crop&q=80" alt="Mockup minh họa" style="width: 100%; max-width: 700px; height: auto; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin: 15px 0;" />'
        });

        bm.add('media-video', {
            label: 'Video (YouTube/MP4)',
            category: '🖼️ Hình Ảnh & Media',
            media: '<i class="fa fa-circle-play" style="font-size: 1.2rem; color: #f43f5e;"></i>',
            content: {
                type: 'video',
                src: 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                style: { height: '350px', width: '100%', 'max-width': '650px', 'border-radius': '12px' }
            }
        });

        // Prebuilt Sections
        const prebuiltSections = [
            {
                id: 'sec-hero-pro',
                name: 'Hero SaaS Hiện Đại (Gam Trắng Sáng)',
                desc: 'Nền trắng gradient xanh nhẹ, nút CTA nổi bật, chuẩn Apple & Stripe',
                icon: '🚀',
                html: `
                    <section style="background: radial-gradient(circle at 50% 0%, #eff6ff 0%, #ffffff 70%); border-bottom: 1px solid #f1f5f9; color: #0f172a; padding: 90px 20px; text-align: center;">
                        <div style="max-width: 900px; margin: 0 auto;">
                            <span style="background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; padding: 6px 18px; border-radius: 999px; font-size: 13px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px;">
                                🚀 NỀN TẢNG KÉO THẢ THẾ HỆ MỚI
                            </span>
                            <h1 style="font-size: 48px; font-weight: 900; line-height: 1.25; margin-bottom: 20px; color: #0f172a; letter-spacing: -0.02em;">
                                Xây Dựng Website Đỉnh Cao Với Tốc Độ Ánh Sáng
                            </h1>
                            <p style="font-size: 18px; color: #475569; line-height: 1.6; margin-bottom: 36px; max-width: 760px; margin-left: auto; margin-right: auto;">
                                Tùy chỉnh trực quan toàn diện. Không cần viết code. Xuất bản tĩnh tức thì lên AWS S3 và Cloudflare CDN.
                            </p>
                            <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                                <a href="#features" style="background: #2563eb; color: #ffffff; padding: 14px 34px; border-radius: 10px; text-decoration: none; font-weight: 800; font-size: 15px; box-shadow: 0 4px 14px rgba(37,99,235,0.35);">
                                    Bắt Đầu Miễn Phí →
                                </a>
                                <a href="#pricing" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a; padding: 14px 30px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    Xem Bảng Giá
                                </a>
                            </div>
                        </div>
                    </section>
                `
            },
            {
                id: 'sec-features-grid',
                name: 'Lưới 3 Tính Năng Nổi Bật',
                desc: '3 Thẻ card trắng bóng đổ nhẹ, biểu tượng màu sắc bắt mắt',
                icon: '💎',
                html: `
                    <section style="padding: 80px 20px; background: #f8fafc; text-align: center;">
                        <div style="max-width: 1140px; margin: 0 auto;">
                            <h2 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 14px;">Tính Năng Vượt Trội</h2>
                            <p style="color: #64748b; font-size: 17px; margin-bottom: 48px;">Mọi công cụ bạn cần để tạo ra trang web chuyển đổi cao.</p>
                            <div style="display: flex; gap: 28px; flex-wrap: wrap; justify-content: center;">
                                <div style="flex: 1; min-width: 280px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 36px 28px; text-align: left; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                                    <div style="font-size: 28px; margin-bottom: 16px;">⚡</div>
                                    <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">Tốc Độ Siêu Tốc</h3>
                                    <p style="color: #64748b; line-height: 1.6; font-size: 15px;">Biên dịch toàn bộ website thành file tĩnh SSG, thời gian tải trang dưới 30ms.</p>
                                </div>
                                <div style="flex: 1; min-width: 280px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 36px 28px; text-align: left; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                                    <div style="font-size: 28px; margin-bottom: 16px;">🎯</div>
                                    <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">Chuẩn SEO 100%</h3>
                                    <p style="color: #64748b; line-height: 1.6; font-size: 15px;">Tự động chèn thẻ OpenGraph, Canonical, Schema Markup JSON-LD, Sitemap.xml và Robots.txt.</p>
                                </div>
                                <div style="flex: 1; min-width: 280px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 36px 28px; text-align: left; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                                    <div style="font-size: 28px; margin-bottom: 16px;">🎨</div>
                                    <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">Kéo Thả Tự Do</h3>
                                    <p style="color: #64748b; line-height: 1.6; font-size: 15px;">Hơn 30+ khối giao diện phong phú, hỗ trợ responsive hoàn hảo trên Desktop, Tablet và Mobile.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                `
            },
            {
                id: 'sec-pricing',
                name: 'Bảng Giá Dịch Vụ (Pricing)',
                desc: '2 Cột Tiêu chuẩn & Chuyên nghiệp có nhãn nổi bật',
                icon: '💰',
                html: `
                    <section style="padding: 80px 20px; background: #ffffff; text-align: center;">
                        <div style="max-width: 960px; margin: 0 auto;">
                            <h2 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 14px;">Bảng Giá Dịch Vụ</h2>
                            <p style="color: #64748b; font-size: 16px; margin-bottom: 48px;">Chọn gói phù hợp nhất với mô hình kinh doanh của bạn</p>
                            <div style="display: flex; gap: 28px; flex-wrap: wrap; justify-content: center;">
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 38px 30px; flex: 1; min-width: 280px; text-align: left; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                                    <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Gói Tiêu Chuẩn</h3>
                                    <div style="font-size: 36px; font-weight: 900; color: #2563eb; margin-bottom: 24px;">199.000đ<span style="font-size: 14px; color: #64748b; font-weight: 500;">/tháng</span></div>
                                    <ul style="list-style: none; padding: 0; margin-bottom: 28px; line-height: 2.2; color: #475569; font-size: 14px;">
                                        <li>✓ 1 Website tùy chỉnh</li>
                                        <li>✓ Subdomain miễn phí</li>
                                        <li>✓ Miễn phí chứng chỉ SSL</li>
                                    </ul>
                                    <a href="#" style="background: #2563eb; color: #ffffff; display: block; text-align: center; padding: 13px; border-radius: 8px; text-decoration: none; font-weight: 700;">Đăng Ký Ngay</a>
                                </div>
                                <div style="background: #ffffff; border: 2px solid #2563eb; border-radius: 18px; padding: 38px 30px; flex: 1; min-width: 280px; text-align: left; position: relative; box-shadow: 0 12px 30px rgba(37,99,235,0.15);">
                                    <span style="position: absolute; top: -12px; right: 24px; background: #2563eb; color: #fff; padding: 4px 14px; border-radius: 999px; font-size: 11px; font-weight: 800;">PHỔ BIẾN NHẤT</span>
                                    <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Gói Chuyên Nghiệp</h3>
                                    <div style="font-size: 36px; font-weight: 900; color: #2563eb; margin-bottom: 24px;">499.000đ<span style="font-size: 14px; color: #64748b; font-weight: 500;">/tháng</span></div>
                                    <ul style="list-style: none; padding: 0; margin-bottom: 28px; line-height: 2.2; color: #475569; font-size: 14px;">
                                        <li>✓ Không giới hạn website</li>
                                        <li>✓ Gắn Custom Domain riêng</li>
                                        <li>✓ Xuất bản tĩnh S3 + CDN</li>
                                        <li>✓ Xóa watermark thương hiệu</li>
                                    </ul>
                                    <a href="#" style="background: #2563eb; color: #ffffff; display: block; text-align: center; padding: 13px; border-radius: 8px; text-decoration: none; font-weight: 700;">Nâng Cấp Pro</a>
                                </div>
                            </div>
                        </div>
                    </section>
                `
            },
            {
                id: 'sec-navbar-pro',
                name: 'Thanh Điều Hướng (Header Nav Sticky)',
                desc: 'Thanh menu hiện đại hiệu ứng kính mờ, Logo, Links và nút Hành động',
                icon: '🧭',
                html: `
                    <header style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 100; padding: 16px 24px;">
                        <div style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 10px; font-weight: 900; font-size: 20px; color: #0f172a;">
                                <span style="background: #2563eb; color: #fff; width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; font-size: 16px;">✦</span>
                                <span>TechHub</span>
                            </div>
                            <nav style="display: flex; gap: 28px; align-items: center; font-size: 15px; font-weight: 600;">
                                <a href="#features" style="color: #475569; text-decoration: none;">Tính Năng</a>
                                <a href="#pricing" style="color: #475569; text-decoration: none;">Bảng Giá</a>
                                <a href="#testimonials" style="color: #475569; text-decoration: none;">Đánh Giá</a>
                                <a href="#contact" style="color: #475569; text-decoration: none;">Liên Hệ</a>
                            </nav>
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <a href="#" style="background: #2563eb; color: #ffffff; padding: 10px 22px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                                    Dùng Thử Miễn Phí
                                </a>
                            </div>
                        </div>
                    </header>
                `
            },
            {
                id: 'sec-testimonials',
                name: 'Đánh Giá Khách Hàng (Social Proof)',
                desc: '3 Thẻ phản hồi 5 sao uy tín kèm ảnh avatar và trích dẫn thực tế',
                icon: '⭐',
                html: `
                    <section id="testimonials" style="padding: 85px 20px; background: #ffffff; text-align: center;">
                        <div style="max-width: 1140px; margin: 0 auto;">
                            <span style="color: #2563eb; font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; display: inline-block;">ĐÁNH GIÁ TỪ KHÁCH HÀNG</span>
                            <h2 style="font-size: 36px; font-weight: 900; color: #0f172a; margin-bottom: 16px;">Được Tin Tưởng Bởi Hơn 5,000+ Khách Hàng</h2>
                            <p style="color: #64748b; font-size: 17px; margin-bottom: 48px;">Xem trải nghiệm thực tế từ các doanh nghiệp đang sử dụng nền tảng của chúng tôi.</p>
                            <div style="display: flex; gap: 24px; flex-wrap: wrap; justify-content: center;">
                                <div style="flex: 1; min-width: 280px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 32px 24px; text-align: left; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                                    <div style="color: #f59e0b; margin-bottom: 16px; font-size: 18px;">★★★★★</div>
                                    <p style="color: #334155; line-height: 1.6; font-size: 15px; margin-bottom: 20px;">"Tốc độ tải trang nhanh đến kinh ngạc. Doanh số tăng 45% sau khi chuyển toàn bộ Landing Page sang hệ thống tĩnh này."</p>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;" alt="Avatar">
                                        <div>
                                            <div style="font-weight: 800; font-size: 15px; color: #0f172a;">Nguyễn Mai Hương</div>
                                            <div style="color: #64748b; font-size: 13px;">Founder tại BeautySpa VN</div>
                                        </div>
                                    </div>
                                </div>
                                <div style="flex: 1; min-width: 280px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 32px 24px; text-align: left; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                                    <div style="color: #f59e0b; margin-bottom: 16px; font-size: 18px;">★★★★★</div>
                                    <p style="color: #334155; line-height: 1.6; font-size: 15px; margin-bottom: 20px;">"Không cần biết code vẫn tự dựng được trang bán khóa học siêu xịn. Kéo thả mượt mà, lưu trữ S3 siêu an tâm."</p>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;" alt="Avatar">
                                        <div>
                                            <div style="font-weight: 800; font-size: 15px; color: #0f172a;">Trần Minh Tuấn</div>
                                            <div style="color: #64748b; font-size: 13px;">Giám Đốc Marketing EduTech</div>
                                        </div>
                                    </div>
                                </div>
                                <div style="flex: 1; min-width: 280px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 32px 24px; text-align: left; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                                    <div style="color: #f59e0b; margin-bottom: 16px; font-size: 18px;">★★★★★</div>
                                    <p style="color: #334155; line-height: 1.6; font-size: 15px; margin-bottom: 20px;">"Trước đây dùng WordPress cứ vài tháng lại bị hack hoặc lỗi plugin. Giờ sang đây website tải êm ru, bảo mật tuyệt đối."</p>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=80" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;" alt="Avatar">
                                        <div>
                                            <div style="font-weight: 800; font-size: 15px; color: #0f172a;">Hoàng Gia Bảo</div>
                                            <div style="color: #64748b; font-size: 13px;">CEO Bất Động Sản Landmark</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                `
            },
            {
                id: 'sec-cta-banner',
                name: 'Banner Kêu Gọi (CTA Gradient)',
                desc: 'Banner xanh hoàng gia sang trọng thúc đẩy chuyển đổi hành động ngay',
                icon: '📣',
                html: `
                    <section style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #1d4ed8 100%); color: #ffffff; padding: 80px 20px; text-align: center;">
                        <div style="max-width: 800px; margin: 0 auto;">
                            <h2 style="font-size: 38px; font-weight: 900; line-height: 1.25; margin-bottom: 18px; color: #ffffff;">
                                Sẵn Sàng Bứt Phá Doanh Số Cùng Website Đỉnh Cao?
                            </h2>
                            <p style="font-size: 18px; opacity: 0.9; margin-bottom: 36px; line-height: 1.6;">
                                Tham gia cùng hàng nghìn nhà sáng tạo và doanh nghiệp đang tăng trưởng vượt bậc với nền tảng của chúng tôi.
                            </p>
                            <a href="#" style="background: #ffffff; color: #1d4ed8; padding: 16px 38px; border-radius: 12px; text-decoration: none; font-weight: 800; font-size: 16px; display: inline-block; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                                Bắt Đầu Ngay Hôm Nay →
                            </a>
                        </div>
                    </section>
                `
            },
            {
                id: 'sec-footer-pro',
                name: 'Chân Trang (Footer Pro)',
                desc: 'Chân trang 4 cột chuyên nghiệp với liên kết và bản quyền',
                icon: '⚡',
                html: `
                    <footer style="background: #0f172a; color: #94a3b8; padding: 60px 20px 30px 20px; font-size: 14px;">
                        <div style="max-width: 1140px; margin: 0 auto; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 30px; border-bottom: 1px solid #1e293b; padding-bottom: 40px;">
                            <div style="max-width: 320px;">
                                <h4 style="color: #ffffff; font-size: 18px; font-weight: 800; margin-bottom: 12px;">TechHub Studio</h4>
                                <p style="line-height: 1.6;">Nền tảng kiến tạo website thế hệ mới với hiệu năng tĩnh siêu việt.</p>
                            </div>
                            <div>
                                <h5 style="color: #ffffff; font-weight: 700; margin-bottom: 12px;">Sản Phẩm</h5>
                                <div style="line-height: 2;"><a href="#" style="color: #94a3b8; text-decoration: none;">Tính Năng</a><br><a href="#" style="color: #94a3b8; text-decoration: none;">Bảng Giá</a><br><a href="#" style="color: #94a3b8; text-decoration: none;">Kho Giao Diện</a></div>
                            </div>
                            <div>
                                <h5 style="color: #ffffff; font-weight: 700; margin-bottom: 12px;">Công Ty</h5>
                                <div style="line-height: 2;"><a href="#" style="color: #94a3b8; text-decoration: none;">Về Chúng Tôi</a><br><a href="#" style="color: #94a3b8; text-decoration: none;">Blog Công Nghệ</a><br><a href="#" style="color: #94a3b8; text-decoration: none;">Liên Hệ</a></div>
                            </div>
                        </div>
                        <div style="max-width: 1140px; margin: 20px auto 0 auto; text-align: center; font-size: 13px; color: #64748b;">
                            © 2026 TechHub Inc. Bảo lưu mọi quyền.
                        </div>
                    </footer>
                `
            }
        ];

        // 1-Click Starter: Generate complete 7-section Landing Page
        function createFullLandingPage() {
            if (editor.getComponents().length > 0) {
                if (!confirm('Khởi tạo trọn gói Landing Page sẽ tạo sẵn 7 Section (Header, Hero, Tính Năng, Báo Giá, Đánh Giá, Banner, Chân Trang). Bạn có muốn tiếp tục không?')) {
                    return;
                }
            }
            // Sort standard order: Navbar, Hero, Features, Testimonials, Pricing, CTA, Footer
            const orderedIds = ['sec-navbar-pro', 'sec-hero-pro', 'sec-features-grid', 'sec-testimonials', 'sec-pricing', 'sec-cta-banner', 'sec-footer-pro'];
            const fullHtml = orderedIds.map(id => {
                const s = prebuiltSections.find(x => x.id === id);
                return s ? s.html : '';
            }).filter(Boolean).join('\n');

            editor.setComponents(fullHtml);
            showStudioToast('🚀 Đã khởi tạo trọn gói Landing Page hoàn chỉnh (7 Section)!', 'success');
        }

        // Render sections
        const secContainer = document.getElementById('custom-sections-container');
        prebuiltSections.forEach(sec => {
            // Register in BlockManager so user can also drag & drop it directly onto the canvas!
            bm.add('prebuilt-' + sec.id, {
                label: sec.name,
                category: '🎨 Khối Mẫu Sẵn (Sections)',
                media: `<span style="font-size: 1.3rem;">${sec.icon}</span>`,
                content: sec.html
            });

            const card = document.createElement('div');
            card.className = 'section-template-card';
            card.innerHTML = `
                <div class="section-icon-badge">${sec.icon}</div>
                <div style="flex: 1;">
                    <div class="section-meta-title">${sec.name}</div>
                    <div class="section-meta-desc">${sec.desc}</div>
                </div>
                <button class="btn-topbar" style="padding: 3px 8px; font-size: 0.68rem; margin-left: 6px; border-color: rgba(56,189,248,0.4); color: var(--accent-cyan);" title="Chèn vào bản vẽ">+ Chèn</button>
            `;
            card.onclick = () => {
                let added;
                if (currentSelectedModel && currentSelectedModel.parent && currentSelectedModel.parent()) {
                    const parent = currentSelectedModel.parent();
                    const at = currentSelectedModel.index() + 1;
                    added = parent.append(sec.html, { at });
                } else {
                    added = editor.addComponents(sec.html);
                }
                if (added) {
                    const comp = Array.isArray(added) ? added[0] : added;
                    editor.select(comp);
                    const el = comp.getEl();
                    if (el && el.scrollIntoView) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                showStudioToast(`✨ Đã chèn mẫu "${sec.name}" vào trang!`, 'success');
            };
            secContainer.appendChild(card);
        });

        // 1-Click Insert for Blocks in left panel
        const blocksContainer = document.getElementById('grapes-blocks-container');
        if (blocksContainer) {
            blocksContainer.addEventListener('click', (e) => {
                const blockEl = e.target.closest('.gjs-block');
                if (!blockEl) return;
                
                const label = blockEl.querySelector('.gjs-block-label')?.innerText?.trim();
                const allBlocks = bm.getAll().models;
                let block = allBlocks.find(b => b.get('label') === label || blockEl.getAttribute('title') === b.get('label'));
                if (!block) {
                    const blockId = blockEl.getAttribute('data-id') || blockEl.id;
                    block = allBlocks.find(b => blockId && (b.getId() === blockId || blockId.endsWith(b.getId())));
                }

                if (block) {
                    const content = block.get('content');
                    if (!content) return;
                    
                    let added;
                    if (currentSelectedModel && currentSelectedModel.parent && currentSelectedModel.parent()) {
                        const parent = currentSelectedModel.parent();
                        const at = currentSelectedModel.index() + 1;
                        added = parent.append(content, { at });
                    } else {
                        added = editor.addComponents(content);
                    }
                    
                    if (added) {
                        const comp = Array.isArray(added) ? added[0] : added;
                        editor.select(comp);
                        try {
                            const el = comp.getEl();
                            if (el && el.scrollIntoView) {
                                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        } catch (err) {}
                        showStudioToast(`✨ Đã thêm "${block.get('label') || 'khối'}" vào trang!`, 'success');
                    }
                }
            });
        }

        // Quick Color Palette logic
        let activeQcpTarget = 'color';
        function setQcpTarget(target) {
            activeQcpTarget = target;
            const btnColor = document.getElementById('qcp-btn-color');
            const btnBg = document.getElementById('qcp-btn-bg');
            if (btnColor && btnBg) {
                if (target === 'color') {
                    btnColor.classList.add('active');
                    btnBg.classList.remove('active');
                } else {
                    btnBg.classList.add('active');
                    btnColor.classList.remove('active');
                }
            }
        }

        // ======================================================================
        // STUDIO COLOR PALETTE POPOVER CONTROLLER (FIGMA & CANVA GRADE UX)
        // ======================================================================
        const SCP_NEUTRALS = [
            { name: 'Trắng tinh khiết', value: '#ffffff' },
            { name: 'Xám sáng Slate 50', value: '#f8fafc' },
            { name: 'Xám nhạt Slate 100', value: '#f1f5f9' },
            { name: 'Xám viền Slate 200', value: '#e2e8f0' },
            { name: 'Xám Slate 400', value: '#94a3b8' },
            { name: 'Xám đậm Slate 500', value: '#64748b' },
            { name: 'Xám tối Slate 700', value: '#334155' },
            { name: 'Đen Slate 900', value: '#0f172a' },
        ];

        const SCP_BRANDS = [
            { name: 'Xanh Royal TechHub', value: '#2563eb' },
            { name: 'Xanh Blue sáng', value: '#3b82f6' },
            { name: 'Xanh Sky', value: '#0284c7' },
            { name: 'Xanh Ngọc Cyan', value: '#06b6d4' },
            { name: 'Xanh Ngọc Emerald', value: '#10b981' },
            { name: 'Xanh Lá Green', value: '#16a34a' },
            { name: 'Xanh Chuối Lime', value: '#84cc16' },
            { name: 'Vàng Amber', value: '#f59e0b' },
            { name: 'Cam Rực Rỡ', value: '#f97316' },
            { name: 'Đỏ Ruby', value: '#ef4444' },
            { name: 'Đỏ Thẫm Rose', value: '#e11d48' },
            { name: 'Hồng Pink', value: '#ec4899' },
            { name: 'Tím Hồng Fuchsia', value: '#d946ef' },
            { name: 'Tím Violet', value: '#8b5cf6' },
            { name: 'Chàm Indigo', value: '#6366f1' },
            { name: 'Đen Tuyệt Đối', value: '#000000' },
        ];

        const SCP_GRADIENTS = [
            { name: 'Ocean Blue', value: 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)' },
            { name: 'Purple Glow', value: 'linear-gradient(135deg, #6366f1 0%, #a855f7 100%)' },
            { name: 'Cyan Breeze', value: 'linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%)' },
            { name: 'Sunset Warm', value: 'linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)' },
            { name: 'Emerald Forest', value: 'linear-gradient(135deg, #10b981 0%, #059669 100%)' },
            { name: 'Dark Slate', value: 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)' },
        ];

        let currentScpTarget = {
            fieldEl: null,
            inputEl: null,
            colorpEl: null,
            colorpcEl: null,
            propName: null,
        };

        function detectPropNameFromField(fieldEl) {
            const propEl = fieldEl.closest('.gjs-sm-property');
            if (propEl) {
                const classMatch = propEl.className.match(/gjs-sm-property__([a-zA-Z0-9_-]+)/);
                if (classMatch && classMatch[1]) return classMatch[1];

                if (propEl.id) {
                    const idMatch = propEl.id.match(/gjs-sm-([a-zA-Z0-9_-]+)/);
                    if (idMatch && idMatch[1]) return idMatch[1];
                }

                const labelEl = propEl.querySelector('.gjs-sm-label, label');
                if (labelEl) {
                    const text = labelEl.innerText.trim().toLowerCase();
                    if (text.includes('màu chữ') || text.includes('text color')) return 'color';
                    if (text.includes('màu nền') || text.includes('background color')) return 'background-color';
                    if (text.includes('màu viền') || text.includes('border color')) return 'border-color';
                }
            }
            return 'color';
        }

        function initStudioColorPopover() {
            // Render Swatches
            const neutralGrid = document.getElementById('scp-neutral-swatches');
            if (neutralGrid && neutralGrid.children.length === 0) {
                SCP_NEUTRALS.forEach(c => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'scp-swatch';
                    btn.style.backgroundColor = c.value;
                    btn.title = `${c.name} (${c.value})`;
                    btn.onclick = (e) => { e.stopPropagation(); applyStudioColor(c.value); };
                    neutralGrid.appendChild(btn);
                });
            }

            const brandGrid = document.getElementById('scp-brand-swatches');
            if (brandGrid && brandGrid.children.length === 0) {
                SCP_BRANDS.forEach(c => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'scp-swatch';
                    btn.style.backgroundColor = c.value;
                    btn.title = `${c.name} (${c.value})`;
                    btn.onclick = (e) => { e.stopPropagation(); applyStudioColor(c.value); };
                    brandGrid.appendChild(btn);
                });
            }

            const gradGrid = document.getElementById('scp-gradients-grid');
            if (gradGrid && gradGrid.children.length === 0) {
                SCP_GRADIENTS.forEach(g => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'scp-gradient-swatch';
                    btn.style.backgroundImage = g.value;
                    btn.title = g.name;
                    btn.onclick = (e) => { e.stopPropagation(); applyStudioColor(g.value, true); };
                    gradGrid.appendChild(btn);
                });
            }

            // Delegated click on GrapesJS styles container
            const stylesContainer = document.getElementById('grapes-styles-container');
            if (stylesContainer) {
                stylesContainer.addEventListener('click', (e) => {
                    const colorField = e.target.closest('.gjs-field-color');
                    if (!colorField) return;
                    e.stopPropagation();
                    e.preventDefault();
                    openStudioColorPopover(colorField);
                });
            }

            // Click outside to close
            document.addEventListener('mousedown', (e) => {
                const popover = document.getElementById('studio-color-palette-popover');
                if (!popover || popover.style.display === 'none') return;
                if (popover.contains(e.target)) return;
                if (currentScpTarget.fieldEl && currentScpTarget.fieldEl.contains(e.target)) return;
                closeStudioColorPopover();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeStudioColorPopover();
            });

            // Update swatches on sector clicks
            document.addEventListener('click', (e) => {
                if (e.target.closest('.gjs-sm-sector-title')) {
                    setTimeout(updateAllColorFieldSwatches, 100);
                }
            });
        }

        function openStudioColorPopover(fieldEl) {
            const popover = document.getElementById('studio-color-palette-popover');
            if (!popover) return;

            const inputEl = fieldEl.querySelector('input');
            const colorpEl = fieldEl.querySelector('.gjs-field-colorp');
            const colorpcEl = fieldEl.querySelector('.gjs-field-colorp-c');
            const propName = detectPropNameFromField(fieldEl);

            currentScpTarget = {
                fieldEl,
                inputEl,
                colorpEl,
                colorpcEl,
                propName,
            };

            // Set Title according to property
            const titleText = document.getElementById('scp-title-text');
            if (titleText) {
                if (propName === 'color') titleText.innerText = 'BẢNG MÀU CHỮ';
                else if (propName === 'background-color') titleText.innerText = 'BẢNG MÀU NỀN';
                else if (propName === 'border-color') titleText.innerText = 'BẢNG MÀU VIỀN';
                else titleText.innerText = 'BẢNG MÀU THIẾT KẾ';
            }

            // Toggle Gradients Section (only for background-color)
            const gradSection = document.getElementById('scp-gradients-section');
            if (gradSection) {
                gradSection.style.display = (propName === 'background-color') ? 'block' : 'none';
            }

            // Sync current value to preview
            const curVal = inputEl ? inputEl.value.trim() : '';
            syncPopoverPreview(curVal);

            // Display & position cleanly
            popover.style.display = 'block';
            const rect = fieldEl.getBoundingClientRect();
            const popoverWidth = 295;
            const popoverHeight = popover.offsetHeight || 380;

            let left = rect.left - popoverWidth - 14;
            if (left < 10) {
                left = Math.max(10, rect.right - popoverWidth);
            }
            let top = rect.top;
            if (top + popoverHeight > window.innerHeight - 20) {
                top = Math.max(10, window.innerHeight - popoverHeight - 20);
            }

            popover.style.left = `${left}px`;
            popover.style.top = `${top}px`;
        }

        function closeStudioColorPopover() {
            const popover = document.getElementById('studio-color-palette-popover');
            if (popover) popover.style.display = 'none';
            currentScpTarget = {
                fieldEl: null,
                inputEl: null,
                colorpEl: null,
                colorpcEl: null,
                propName: null,
            };
        }

        function syncPopoverPreview(val) {
            const preview = document.getElementById('scp-active-preview');
            const hexInput = document.getElementById('scp-hex-input');
            const nativePicker = document.getElementById('scp-native-color-picker');

            if (hexInput) hexInput.value = val;
            if (preview) {
                if (!val || val === 'none' || val === 'transparent') {
                    preview.style.background = 'repeating-conic-gradient(#cbd5e1 0% 25%, #ffffff 0% 50%) 50% / 6px 6px';
                } else if (val.startsWith('linear-gradient')) {
                    preview.style.background = val;
                } else {
                    preview.style.background = val;
                }
            }
            if (nativePicker && val && val.startsWith('#')) {
                if (val.length === 7) nativePicker.value = val;
                else if (val.length === 4) nativePicker.value = `#${val[1]}${val[1]}${val[2]}${val[2]}${val[3]}${val[3]}`;
            }
        }

        function applyStudioColor(val, isGradient = false) {
            const selected = editor.getSelected();
            const propName = currentScpTarget.propName || (activeQcpTarget || 'color');

            syncPopoverPreview(val);

            // 1. Apply to GrapesJS Model and Canvas DOM Element
            if (selected) {
                const el = selected.getEl ? selected.getEl() : null;
                if (isGradient) {
                    selected.addStyle({ 'background-image': val });
                    if (el && el.style) el.style.backgroundImage = val;
                } else {
                    if (val === 'none' || val === 'transparent') {
                        selected.addStyle({ [propName]: 'none' });
                        if (el && el.style) {
                            if (propName === 'background-color') el.style.backgroundColor = 'transparent';
                            else el.style[propName] = 'transparent';
                        }
                    } else {
                        selected.addStyle({ [propName]: val });
                        if (el && el.style) el.style[propName] = val;
                        if (propName === 'background-color') {
                            const curBgImg = selected.getStyle()['background-image'];
                            if (curBgImg && curBgImg.includes('gradient')) {
                                selected.addStyle({ 'background-image': 'none' });
                                if (el && el.style) el.style.backgroundImage = 'none';
                            }
                        }
                    }
                }
            }

            // 2. Update Input & Swatch UI
            if (currentScpTarget.inputEl) {
                currentScpTarget.inputEl.value = isGradient ? 'gradient' : val;
                currentScpTarget.inputEl.dispatchEvent(new Event('change', { bubbles: true }));
                currentScpTarget.inputEl.dispatchEvent(new Event('input', { bubbles: true }));
            }

            // 3. Update GrapesJS StyleManager model if available
            try {
                const sm = editor.StyleManager;
                const propModel = sm.getProperty(propName) ||
                                 sm.getProperty('🔤 Chữ & Phông (Typography)', propName) ||
                                 sm.getProperty('🎨 Nền & Hình Nền (Background)', propName) ||
                                 sm.getProperty('🔲 Viền & Bo Góc (Borders & Radius)', propName);
                if (propModel && propModel.setValue) {
                    propModel.setValue(isGradient ? 'gradient' : val);
                }
            } catch(e) {}

            updateAllColorFieldSwatches();
            showStudioToast(`🎨 Đã chọn màu: ${isGradient ? 'Gradient' : val}`, 'success', 1500);
        }

        function onHexInputChange(val) {
            val = val.trim();
            if (!val) return;
            applyStudioColor(val);
        }

        function onNativeColorChange(val) {
            if (!val) return;
            applyStudioColor(val);
        }

        function triggerNativeColorPicker() {
            const nativePicker = document.getElementById('scp-native-color-picker');
            if (nativePicker) {
                if (typeof nativePicker.showPicker === 'function') {
                    try {
                        nativePicker.showPicker();
                    } catch(e) {
                        nativePicker.click();
                    }
                } else {
                    nativePicker.click();
                }
            }
        }

        async function pickColorWithEyeDropper() {
            if ('EyeDropper' in window) {
                try {
                    const eyeDropper = new EyeDropper();
                    const result = await eyeDropper.open();
                    if (result && result.sRGBHex) {
                        applyStudioColor(result.sRGBHex);
                        showStudioToast(`🎨 Đã hút màu: ${result.sRGBHex}`, 'success', 2000);
                    }
                } catch (err) {
                    // Eyedropper dismissed
                }
            } else {
                triggerNativeColorPicker();
            }
        }

        function updateAllColorFieldSwatches() {
            document.querySelectorAll('.gjs-field-color').forEach(field => {
                const input = field.querySelector('input');
                const colorp = field.querySelector('.gjs-field-colorp');
                const colorpc = field.querySelector('.gjs-field-colorp-c');
                if (!input || !colorp) return;

                const val = (input.value || '').trim().toLowerCase();
                if (!val || val === 'none' || val === 'transparent' || val === 'rgba(0, 0, 0, 0)') {
                    field.setAttribute('data-none', 'true');
                    colorp.classList.add('is-none');
                    if (colorpc) colorpc.style.backgroundColor = 'transparent';
                } else {
                    field.removeAttribute('data-none');
                    colorp.classList.remove('is-none');
                    if (colorpc) colorpc.style.backgroundColor = val;
                }
            });
        }

        function applyQuickColor(colorValue, isGradient = false) {
            if (!currentSelectedModel) {
                showStudioToast('⚠️ Vui lòng nhấp chọn một phần tử trên Canvas trước!', 'warning');
                return;
            }
            const el = currentSelectedModel.getEl();
            if (isGradient) {
                currentSelectedModel.addStyle({ 'background-image': colorValue, 'background-color': 'transparent' });
                if (el && el.style) { el.style.backgroundImage = colorValue; el.style.backgroundColor = 'transparent'; }
            } else {
                if (activeQcpTarget === 'color') {
                    currentSelectedModel.addStyle({ 'color': colorValue });
                    if (el && el.style) { el.style.color = colorValue; }
                    try {
                        const sm = editor.StyleManager;
                        const prop = sm.getProperty('color') || sm.getProperty('🔤 Chữ & Phông (Typography)', 'color');
                        if (prop && prop.setValue) prop.setValue(colorValue);
                    } catch(e) {}
                } else {
                    currentSelectedModel.addStyle({ 'background-color': colorValue, 'background-image': 'none' });
                    if (el && el.style) { el.style.backgroundColor = colorValue; el.style.backgroundImage = 'none'; }
                    try {
                        const sm = editor.StyleManager;
                        const prop = sm.getProperty('background-color') || sm.getProperty('🎨 Nền & Hình Nền (Background)', 'background-color');
                        if (prop && prop.setValue) prop.setValue(colorValue);
                    } catch(e) {}
                }
            }
            updateAllColorFieldSwatches();
            showStudioToast(`🎨 Đã áp dụng màu mới cho phần tử!`, 'success');
        }

        function applyQuickRadius(rad) {
            if (!currentSelectedModel) {
                showStudioToast('⚠️ Vui lòng nhấp chọn một phần tử trên Canvas trước!', 'warning');
                return;
            }
            currentSelectedModel.addStyle({ 'border-radius': rad });
            const el = currentSelectedModel.getEl();
            if (el && el.style) el.style.borderRadius = rad;
            showStudioToast(`✨ Đã đặt bo góc: ${rad}`, 'success');
        }

        function applyQuickShadow(shadow) {
            if (!currentSelectedModel) {
                showStudioToast('⚠️ Vui lòng nhấp chọn một phần tử trên Canvas trước!', 'warning');
                return;
            }
            currentSelectedModel.addStyle({ 'box-shadow': shadow });
            const el = currentSelectedModel.getEl();
            if (el && el.style) el.style.boxShadow = shadow;
            showStudioToast(`✨ Đã áp dụng hiệu ứng bóng đổ!`, 'success');
        }

        function applyQuickAlign(type) {
            if (!currentSelectedModel) {
                showStudioToast('⚠️ Vui lòng nhấp chọn một phần tử trên Canvas trước!', 'warning');
                return;
            }
            const el = currentSelectedModel.getEl();
            if (type === 'left') {
                currentSelectedModel.addStyle({ 'margin-left': '0', 'margin-right': 'auto', 'text-align': 'left' });
                if (el && el.style) { el.style.marginLeft = '0'; el.style.marginRight = 'auto'; el.style.textAlign = 'left'; }
            } else if (type === 'center') {
                currentSelectedModel.addStyle({ 'margin-left': 'auto', 'margin-right': 'auto', 'text-align': 'center' });
                if (el && el.style) { el.style.marginLeft = 'auto'; el.style.marginRight = 'auto'; el.style.textAlign = 'center'; }
            } else if (type === 'right') {
                currentSelectedModel.addStyle({ 'margin-left': 'auto', 'margin-right': '0', 'text-align': 'right' });
                if (el && el.style) { el.style.marginLeft = 'auto'; el.style.marginRight = '0'; el.style.textAlign = 'right'; }
            } else if (type === 'full') {
                currentSelectedModel.addStyle({ 'width': '100%', 'max-width': '100%' });
                if (el && el.style) { el.style.width = '100%'; el.style.maxWidth = '100%'; }
            }
            showStudioToast(`📐 Đã căn chỉnh bố cục!`, 'success');
        }

        // Global Keyboard Shortcuts
        window.addEventListener('keydown', (e) => {
            const activeTag = document.activeElement ? document.activeElement.tagName : '';
            const isInputActive = ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeTag) || (document.activeElement && document.activeElement.isContentEditable);
            
            let isIframeEditing = false;
            try {
                const frameDoc = editor.Canvas.getDocument();
                if (frameDoc && frameDoc.activeElement && frameDoc.activeElement.isContentEditable) {
                    isIframeEditing = true;
                }
            } catch (err) {}

            // Ctrl + S: Quick Save Draft
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                e.preventDefault();
                saveDraft(true);
                return;
            }

            if (isInputActive || isIframeEditing) return;

            // Delete / Backspace: Delete selected element
            if (e.key === 'Delete' || e.key === 'Backspace') {
                if (currentSelectedModel && !['wrapper', 'body'].includes(currentSelectedModel.get('type'))) {
                    e.preventDefault();
                    deleteSelectedElement();
                }
            }

            // Ctrl + D: Duplicate selected element
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'd') {
                if (currentSelectedModel && !['wrapper', 'body'].includes(currentSelectedModel.get('type'))) {
                    e.preventDefault();
                    duplicateSelectedElement();
                }
            }

            // Escape: Deselect
            if (e.key === 'Escape') {
                editor.select(null);
            }
        });

        // Load initial content
        if (initialContent && initialContent.gjs_project) {
            editor.loadProjectData(initialContent.gjs_project);
        } else if (initialContent && initialContent.gjs_html) {
            editor.setComponents(initialContent.gjs_html);
            if (initialContent.gjs_css) {
                editor.setStyle(initialContent.gjs_css);
            }
        } else {
            editor.setComponents(prebuiltSections[0].html);
        }

        // ======================================================================
        // 4. STYLE NORMALIZATION & COMPONENT SELECTION HANDLERS
        // ======================================================================
        function expandShorthands(styleObj) {
            if (!styleObj) return {};

            // 1. Mở rộng khoảng cách ngoài margin: top [right] [bottom] [left]
            if (styleObj.margin && typeof styleObj.margin === 'string') {
                const parts = styleObj.margin.trim().split(/\s+/);
                if (parts.length === 1) {
                    styleObj['margin-top'] = parts[0];
                    styleObj['margin-right'] = parts[0];
                    styleObj['margin-bottom'] = parts[0];
                    styleObj['margin-left'] = parts[0];
                } else if (parts.length === 2) {
                    styleObj['margin-top'] = parts[0];
                    styleObj['margin-right'] = parts[1];
                    styleObj['margin-bottom'] = parts[0];
                    styleObj['margin-left'] = parts[1];
                } else if (parts.length === 3) {
                    styleObj['margin-top'] = parts[0];
                    styleObj['margin-right'] = parts[1];
                    styleObj['margin-bottom'] = parts[2];
                    styleObj['margin-left'] = parts[1];
                } else if (parts.length === 4) {
                    styleObj['margin-top'] = parts[0];
                    styleObj['margin-right'] = parts[1];
                    styleObj['margin-bottom'] = parts[2];
                    styleObj['margin-left'] = parts[3];
                }
                delete styleObj.margin;
            }

            // 2. Mở rộng khoảng cách trong padding: top [right] [bottom] [left]
            if (styleObj.padding && typeof styleObj.padding === 'string') {
                const parts = styleObj.padding.trim().split(/\s+/);
                if (parts.length === 1) {
                    styleObj['padding-top'] = parts[0];
                    styleObj['padding-right'] = parts[0];
                    styleObj['padding-bottom'] = parts[0];
                    styleObj['padding-left'] = parts[0];
                } else if (parts.length === 2) {
                    styleObj['padding-top'] = parts[0];
                    styleObj['padding-right'] = parts[1];
                    styleObj['padding-bottom'] = parts[0];
                    styleObj['padding-left'] = parts[1];
                } else if (parts.length === 3) {
                    styleObj['padding-top'] = parts[0];
                    styleObj['padding-right'] = parts[1];
                    styleObj['padding-bottom'] = parts[2];
                    styleObj['padding-left'] = parts[1];
                } else if (parts.length === 4) {
                    styleObj['padding-top'] = parts[0];
                    styleObj['padding-right'] = parts[1];
                    styleObj['padding-bottom'] = parts[2];
                    styleObj['padding-left'] = parts[3];
                }
                delete styleObj.padding;
            }

            // 3. Mở rộng màu nền và hình nền
            if (styleObj.background && typeof styleObj.background === 'string') {
                const bg = styleObj.background.trim();
                if (bg.startsWith('#') || bg.startsWith('rgb') || bg.startsWith('hsl') || ['transparent', 'white', 'black'].includes(bg)) {
                    if (!styleObj['background-color']) styleObj['background-color'] = bg;
                    delete styleObj.background;
                } else if (bg.includes('url(')) {
                    if (!styleObj['background-image']) styleObj['background-image'] = bg;
                    delete styleObj.background;
                }
            }

            // 4. Mở rộng viền border
            if (styleObj.border && typeof styleObj.border === 'string') {
                const borderParts = styleObj.border.trim().split(/\s+/);
                if (borderParts.length === 3) {
                    if (!styleObj['border-width']) styleObj['border-width'] = borderParts[0];
                    if (!styleObj['border-style']) styleObj['border-style'] = borderParts[1];
                    if (!styleObj['border-color']) styleObj['border-color'] = borderParts[2];
                    delete styleObj.border;
                } else if (borderParts.length === 1 && borderParts[0] === 'none') {
                    styleObj['border-style'] = 'none';
                    delete styleObj.border;
                }
            }

            return styleObj;
        }

        function cleanAndNormalizeComponentModel(comp) {
            if (!comp) return;
            try {
                let styleObj = Object.assign({}, comp.getStyle ? comp.getStyle() : {});

                // 1. Trích xuất inline style từ thẻ DOM iframe nếu có
                const el = comp.getEl ? comp.getEl() : null;
                if (el && el.getAttribute) {
                    const inlineStyleStr = el.getAttribute('style');
                    if (inlineStyleStr && typeof inlineStyleStr === 'string' && inlineStyleStr.trim() !== '') {
                        inlineStyleStr.split(';').forEach(rule => {
                            const parts = rule.split(':');
                            if (parts.length >= 2) {
                                const prop = parts[0].trim().toLowerCase();
                                const val = parts.slice(1).join(':').trim();
                                if (prop && val) {
                                    styleObj[prop] = val;
                                }
                            }
                        });
                        el.removeAttribute('style');
                    }
                }

                // Trích xuất inline style từ attributes model
                const attrs = comp.getAttributes ? Object.assign({}, comp.getAttributes()) : {};
                if (attrs && attrs.style) {
                    const inlineStyleStr = attrs.style;
                    if (typeof inlineStyleStr === 'string' && inlineStyleStr.trim() !== '') {
                        inlineStyleStr.split(';').forEach(rule => {
                            const parts = rule.split(':');
                            if (parts.length >= 2) {
                                const prop = parts[0].trim().toLowerCase();
                                const val = parts.slice(1).join(':').trim();
                                if (prop && val) {
                                    styleObj[prop] = val;
                                }
                            }
                        });
                    }
                    delete attrs.style;
                    comp.setAttributes(attrs);
                }

                // 2. Mở rộng shorthand (margin, padding, border, background)
                styleObj = expandShorthands(styleObj);

                // 3. Tối ưu ảnh nền mặc định: Không bao giờ bị lặp ảnh ngang/dọc
                if (styleObj['background-image'] && styleObj['background-image'] !== 'none') {
                    if (!styleObj['background-size']) styleObj['background-size'] = 'cover';
                    if (!styleObj['background-repeat']) styleObj['background-repeat'] = 'no-repeat';
                    if (!styleObj['background-position']) styleObj['background-position'] = 'center center';
                }

                // 4. Lưu lại toàn bộ vào style model chuẩn GrapesJS
                if (comp.setStyle) {
                    comp.setStyle(styleObj);
                }
            } catch (e) {
                console.warn('cleanAndNormalizeComponentModel warn:', e);
            }
        }

        function normalizeComponentTree(root) {
            if (!root) return;
            cleanAndNormalizeComponentModel(root);
            if (root.components) {
                root.components().forEach(child => normalizeComponentTree(child));
            }
        }

        let currentSelectedModel = null;

        editor.on('component:selected', (model) => {
            if (!model) return;
            cleanAndNormalizeComponentModel(model);
            currentSelectedModel = model;

            const tagRaw = (model.get('tagName') || model.get('type') || 'DIV').toUpperCase();
            if (model === editor.getWrapper() || tagRaw === 'BODY' || tagRaw === 'WRAPPER') {
                model.set('toolbar', []);
                return;
            }
            let tagIcon = '📦';
            if (['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(tagRaw)) tagIcon = '🔤';
            else if (tagRaw === 'P' || tagRaw === 'SPAN') tagIcon = '📝';
            else if (tagRaw === 'A' || tagRaw === 'BUTTON') tagIcon = '🔘';
            else if (tagRaw === 'IMG' || tagRaw === 'IMAGE') tagIcon = '🖼️';
            else if (tagRaw === 'VIDEO') tagIcon = '🎬';
            else if (tagRaw === 'SECTION') tagIcon = '📑';
            else if (tagRaw === 'CONTAINER') tagIcon = '🍱';

            const toolbarItems = [
                {
                    attributes: {
                        class: 'gjs-tlb-tag-badge',
                        title: 'Thẻ: <' + tagRaw.toLowerCase() + '> — Bấm để chọn khối cha bao ngoài'
                    },
                    command: 'core:component-exit',
                    label: `${tagIcon} ${tagRaw}`
                },
                {
                    attributes: {
                        class: 'gjs-no-touch-actions fa fa-arrows-up-down-left-right',
                        draggable: 'true',
                        title: 'Giữ chuột và kéo để di chuyển khối (Drag to Move)',
                        style: 'cursor: grab;'
                    },
                    command: 'tlb-move'
                },
                {
                    attributes: { class: 'fa fa-chevron-up', title: 'Di chuyển lên trên (Alt + ↑)' },
                    command: () => moveComponentUp()
                },
                {
                    attributes: { class: 'fa fa-chevron-down', title: 'Di chuyển xuống dưới (Alt + ↓)' },
                    command: () => moveComponentDown()
                },
                {
                    attributes: { class: 'fa fa-clone', title: 'Nhân bản khối (Ctrl + D)' },
                    command: () => duplicateSelectedElement()
                },
                {
                    attributes: { class: 'fa fa-trash', title: 'Xóa khối (Delete)' },
                    command: () => deleteSelectedElement()
                }
            ];

            // Inline edit for text elements
            if (['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'P', 'SPAN', 'A', 'BUTTON'].includes(tagRaw)) {
                toolbarItems.splice(1, 0, {
                    attributes: { class: 'fa fa-pencil-alt', title: 'Sửa chữ trực tiếp (Nhấp để gõ)' },
                    command: (ed) => {
                        const comp = ed.getSelected();
                        if (comp) {
                            try {
                                const el = comp.getEl();
                                if (el) {
                                    el.contentEditable = 'true';
                                    el.focus();
                                }
                            } catch(e) {}
                        }
                    }
                });
            }

            model.set('toolbar', toolbarItems);

            // Hiển thị Card thông tin phần tử ở thanh Inspector bên phải
            const selBox = document.getElementById('selected-element-box');
            if (selBox) selBox.style.display = 'block';
            const emptyState = document.getElementById('inspector-empty-state');
            if (emptyState) emptyState.style.display = 'none';
            const boxModel = document.getElementById('box-model-wrapper');
            if (boxModel) boxModel.style.display = 'block';
            const qcp = document.getElementById('quick-color-palette');
            if (qcp) qcp.style.display = 'block';
            const qPresets = document.getElementById('quick-presets-card');
            if (qPresets) qPresets.style.display = 'block';

            const tag = tagRaw;
            const tagNameEl = document.getElementById('selected-tag-name');
            if (tagNameEl) tagNameEl.innerText = tag;

            const classes = model.getClasses();
            const classListEl = document.getElementById('selected-class-list');
            if (classListEl) classListEl.innerText = classes.length ? `.${classes.join(' .')}` : '';

            // Update Box Model & Position Mode label
            updateBoxModelInputs(model);
            updatePosModeButton(model);

            // Cấu hình thông minh theo loại phần tử (Video, Image, Button, Text)
            setupContextualInspector(model);

            // Cập nhật các ô swatch màu (hiển thị caro cho màu 'none' / 'transparent')
            setTimeout(updateAllColorFieldSwatches, 60);
        });

        editor.on('component:deselected', (prevModel) => {
            currentSelectedModel = null;
            closeStudioColorPopover();
            const selBox = document.getElementById('selected-element-box');
            if (selBox) selBox.style.display = 'none';
            const emptyState = document.getElementById('inspector-empty-state');
            if (emptyState) emptyState.style.display = 'block';
            const smartWrap = document.getElementById('smart-setup-wrapper');
            if (smartWrap) smartWrap.style.display = 'none';
            const boxModel = document.getElementById('box-model-wrapper');
            if (boxModel) boxModel.style.display = 'none';
            const qcp = document.getElementById('quick-color-palette');
            if (qcp) qcp.style.display = 'none';
            const qPresets = document.getElementById('quick-presets-card');
            if (qPresets) qPresets.style.display = 'none';
            toggleTypographySectorVisibility(true);
        });

        // ======================================================================
        // DRAG MODE & POSITIONING ENGINE ("KÉO THẢ VÀO VỊ TRÍ MONG MUỐN")
        // ======================================================================
        function setStudioDragMode(mode) {
            const btnFlow = document.getElementById('btn-drag-flow');
            const btnAbs = document.getElementById('btn-drag-absolute');
            if (mode === 'absolute') {
                if (editor.setDragMode) editor.setDragMode('absolute');
                if (btnFlow && btnAbs) {
                    btnAbs.classList.add('active');
                    btnFlow.classList.remove('active');
                }
                showStudioToast('🎯 Đã bật Chế độ Kéo Tự Do! Bạn có thể nhấp giữ và kéo bất kỳ phần tử nào đến vị trí mong muốn.', 'info', 3000);
            } else {
                if (editor.setDragMode) editor.setDragMode('');
                if (btnFlow && btnAbs) {
                    btnFlow.classList.add('active');
                    btnAbs.classList.remove('active');
                }
                showStudioToast('📐 Đã chuyển sang Chế độ Bố Cục! Kéo thả sẽ tự động căn vào cột và khung chứa.', 'info', 3000);
            }
        }

        function toggleElementPositionMode() {
            if (!currentSelectedModel) return;
            const style = currentSelectedModel.getStyle();
            const currentPos = style['position'] || 'static';
            if (currentPos === 'absolute') {
                currentSelectedModel.addStyle({
                    'position': 'relative',
                    'top': '',
                    'left': '',
                    'right': '',
                    'bottom': '',
                    'z-index': ''
                });
                showStudioToast('📐 Đã chuyển phần tử về Bố cục khối (Relative/Flow)', 'info');
            } else {
                const parent = currentSelectedModel.parent();
                if (parent) {
                    const pStyle = parent.getStyle();
                    if (!pStyle['position'] || pStyle['position'] === 'static') {
                        parent.addStyle({ 'position': 'relative' });
                    }
                }
                const el = currentSelectedModel.getEl();
                let top = 20, left = 20;
                if (el) {
                    const rect = el.getBoundingClientRect();
                    top = Math.round(rect.top);
                    left = Math.round(rect.left);
                }
                currentSelectedModel.addStyle({
                    'position': 'absolute',
                    'top': top + 'px',
                    'left': left + 'px',
                    'z-index': '10'
                });
                showStudioToast('🎯 Đã bật Vị Trí Tự Do! Giờ bạn có thể kéo thả phần tử này đến bất kỳ vị trí nào.', 'success');
            }
            updateBoxModelInputs(currentSelectedModel);
            updatePosModeButton(currentSelectedModel);
        }

        function updatePosModeButton(model) {
            const btn = document.getElementById('lbl-pos-mode');
            if (!btn || !model) return;
            const style = model.getStyle();
            const isAbs = (style['position'] === 'absolute');
            btn.innerText = isAbs ? 'Đổi Về Bố Cục (Flow)' : 'Đặt Vị Trí Tự Do (Free)';
        }

        // TỰ ĐỘNG CĂN CHUẨN BỐ CỤC CHUẨN WORDPRESS / ELEMENTOR (CLEAN OVERLAPS & ABSOLUTE POSITIONS)
        function autoFixCleanLayout() {
            if (typeof editor === 'undefined') return;
            const wrapper = editor.getWrapper();
            if (!wrapper) return;
            let fixedCount = 0;

            function cleanModel(comp) {
                if (!comp) return;
                const style = comp.getStyle ? comp.getStyle() : {};
                if (style['position'] === 'absolute' || style['position'] === 'fixed') {
                    comp.addStyle({
                        'position': '',
                        'top': '',
                        'left': '',
                        'right': '',
                        'bottom': '',
                        'z-index': ''
                    });
                    fixedCount++;
                }
                if (comp.components) {
                    comp.components().forEach(child => cleanModel(child));
                }
            }

            cleanModel(wrapper);
            setStudioDragMode('flow');
            if (currentSelectedModel) {
                updateBoxModelInputs(currentSelectedModel);
                updatePosModeButton(currentSelectedModel);
            }
            showStudioToast(`✨ Đã căn chuẩn lại bố cục (${fixedCount} phần tử)! Toàn bộ khối đã trở về dòng chảy chuẩn WordPress/Elementor, tự co giãn và không bị đè khối.`, 'success', 4500);
        }

        // REORDER FUNCTIONS: MOVE UP & MOVE DOWN
        function moveComponentUp() {
            if (!currentSelectedModel) return;
            const parent = currentSelectedModel.parent();
            if (!parent) return;
            const collection = parent.components();
            const idx = collection.indexOf(currentSelectedModel);
            if (idx > 0) {
                const detached = collection.remove(currentSelectedModel);
                collection.add(detached, { at: idx - 1 });
                editor.select(detached);
            }
        }

        function moveComponentDown() {
            if (!currentSelectedModel) return;
            const parent = currentSelectedModel.parent();
            if (!parent) return;
            const collection = parent.components();
            const idx = collection.indexOf(currentSelectedModel);
            if (idx < collection.length - 1) {
                const detached = collection.remove(currentSelectedModel);
                collection.add(detached, { at: idx + 1 });
                editor.select(detached);
            }
        }

        function selectParentElement() {
            if (!currentSelectedModel) return;
            const parent = currentSelectedModel.parent();
            if (parent) editor.select(parent);
        }

        function duplicateSelectedElement() {
            if (!currentSelectedModel) return;
            const clone = currentSelectedModel.clone();
            const index = currentSelectedModel.index();
            currentSelectedModel.parent().append(clone, { at: index + 1 });
            editor.select(clone);
        }

        function deleteSelectedElement() {
            if (!currentSelectedModel) return;
            currentSelectedModel.remove();
        }

        // ======================================================================
        // SMART REAL-TIME STYLE SYNC & AUTO-FLEX ENGINE ("CHỈNH LÀ ĂN NGAY")
        // ======================================================================
        editor.on('component:styleUpdate', (model, prop) => {
            if (!model) return;
            const style = model.getStyle() || {};

            // Cập nhật trực tiếp lên DOM element của Canvas để người dùng thấy thay đổi ngay lập tức
            const el = model.getEl ? model.getEl() : null;
            if (el && prop && style[prop] !== undefined && style[prop] !== null) {
                try {
                    el.style[prop] = style[prop];
                } catch(e) {}
            }

            // 1. TỰ ĐỘNG KÍCH HOẠT FLEXBOX KHI ĐIỀU CHỈNH CÁC TRỤC FLEX:
            const flexProps = ['justify-content', 'align-items', 'flex-direction', 'flex-wrap', 'gap'];
            if (flexProps.includes(prop) && style[prop] && style[prop] !== '') {
                const currentDisplay = style['display'] || 'block';
                if (!['flex', 'inline-flex', 'grid'].includes(currentDisplay)) {
                    model.addStyle({ 'display': 'flex' });
                    if (el && el.style) el.style.display = 'flex';
                    try {
                        const sm = editor.StyleManager;
                        const propDisplay = sm.getProperty('📐 Bố Cục & Vị Trí (Layout)', 'display');
                        if (propDisplay) propDisplay.setValue('flex');
                    } catch (e) {}
                }
            }

            // 2. TỰ ĐỘNG ĐỒNG BỘ CĂN GIỮA CHỮ (TEXT ALIGN):
            if (prop === 'justify-content') {
                const val = style['justify-content'];
                const tag = (model.get('tagName') || '').toLowerCase();
                const isText = ['h1','h2','h3','h4','h5','h6','p','span','a','div'].includes(tag);
                if (isText && (val === 'center' || val === 'flex-start' || val === 'flex-end')) {
                    const alignVal = (val === 'flex-start') ? 'left' : (val === 'flex-end' ? 'right' : 'center');
                    model.addStyle({ 'text-align': alignVal });
                    if (el && el.style) el.style.textAlign = alignVal;
                    try {
                        const sm = editor.StyleManager;
                        const propAlign1 = sm.getProperty('📐 Bố Cục & Vị Trí (Layout)', 'text-align');
                        if (propAlign1) propAlign1.setValue(alignVal);
                    } catch (e) {}
                    try {
                        const sm = editor.StyleManager;
                        const propAlign2 = sm.getProperty('🔤 Chữ & Phông (Typography)', 'text-align');
                        if (propAlign2) propAlign2.setValue(alignVal);
                    } catch (e) {}
                }
            }

            // 3. TỰ ĐỘNG XÓA GRADIENT CŨ KHI CHỌN MÀU NỀN ĐƠN SẮC:
            if (prop === 'background-color' && style['background-color']) {
                const bgImg = style['background-image'] || '';
                if (bgImg.includes('gradient')) {
                    model.addStyle({ 'background-image': 'none' });
                    if (el && el.style) el.style.backgroundImage = 'none';
                    try {
                        const sm = editor.StyleManager;
                        const propBgImg = sm.getProperty('🎨 Nền & Hình Nền (Background)', 'background-image');
                        if (propBgImg) propBgImg.setValue('none');
                    } catch (e) {}
                }
            }

            // 4. TỰ ĐỘNG ĐẶT COVER & NO-REPEAT CHO HÌNH NỀN:
            if (prop === 'background-image' && style['background-image'] && style['background-image'] !== 'none') {
                if (!style['background-size'] || style['background-size'] === 'auto') {
                    model.addStyle({ 'background-size': 'cover' });
                    if (el && el.style) el.style.backgroundSize = 'cover';
                }
                if (!style['background-repeat'] || style['background-repeat'] === 'repeat') {
                    model.addStyle({ 'background-repeat': 'no-repeat' });
                    if (el && el.style) el.style.backgroundRepeat = 'no-repeat';
                }
                if (!style['background-position']) {
                    model.addStyle({ 'background-position': 'center center' });
                    if (el && el.style) el.style.backgroundPosition = 'center center';
                }
            }

            // 5. ĐỒNG BỘ THƯỚC ĐO MÔ HÌNH KHOẢNG CÁCH (BOX MODEL WIDGET):
            if (['margin-top','margin-bottom','margin-left','margin-right','padding-top','padding-bottom','padding-left','padding-right'].includes(prop)) {
                updateBoxModelInputs(model);
            }
        });

        // Đảm bảo mọi thay đổi từ StyleManager được cập nhật tức thì trên DOM canvas và Box Model
        editor.on('style:property:update', (prop, changes) => {
            const selected = editor.getSelected();
            if (!selected) return;

            try {
                // Lấy đúng CSS Property ID (ví dụ: 'color', 'font-size') — TUYỆT ĐỐI KHÔNG dùng prop.getName() vì đó là nhãn Tiếng Việt!
                let pName = null;
                if (prop) {
                    if (typeof prop.getProperty === 'function') pName = prop.getProperty();
                    else if (typeof prop.getId === 'function') pName = prop.getId();
                    else if (typeof prop.get === 'function') pName = prop.get('property') || prop.get('id');
                }

                let pVal = null;
                if (prop) {
                    if (typeof prop.getValue === 'function') pVal = prop.getValue();
                    else if (typeof prop.get === 'function') pVal = prop.get('value');
                }
                if (changes && typeof changes === 'object' && changes.value !== undefined) {
                    pVal = changes.value;
                }

                if (pName && pVal !== undefined && pVal !== null) {
                    // Áp dụng trực tiếp vào DOM Element để người dùng thấy phản hồi tức thì
                    const el = selected.getEl ? selected.getEl() : null;
                    if (el && el.style) {
                        try {
                            el.style[pName] = pVal;
                        } catch(e) {}
                    }

                    // Tự động tối ưu hình nền: Khi chọn ảnh nền, tự động đặt cover và no-repeat để không bị lặp ảnh
                    if (pName === 'background-image' && pVal && pVal !== 'none') {
                        // Tự động bọc url("...") nếu người dùng chỉ gõ/dán đường link ảnh trơn
                        if (!pVal.startsWith('url(') && !pVal.startsWith('linear-gradient(')) {
                            pVal = `url("${pVal}")`;
                            selected.addStyle({ 'background-image': pVal });
                            if (el && el.style) el.style.backgroundImage = pVal;
                        }

                        const curStyle = selected.getStyle() || {};
                        if (!curStyle['background-size'] || curStyle['background-size'] === 'auto') {
                            selected.addStyle({ 'background-size': 'cover' });
                            if (el && el.style) el.style.backgroundSize = 'cover';
                        }
                        if (!curStyle['background-repeat'] || curStyle['background-repeat'] === 'repeat') {
                            selected.addStyle({ 'background-repeat': 'no-repeat' });
                            if (el && el.style) el.style.backgroundRepeat = 'no-repeat';
                        }
                        if (!curStyle['background-position']) {
                            selected.addStyle({ 'background-position': 'center center' });
                            if (el && el.style) el.style.backgroundPosition = 'center center';
                        }
                    }

                    // Tự động đồng bộ ô hiển thị màu (swatch)
                    if (['color', 'background-color', 'border-color'].includes(pName)) {
                        setTimeout(updateAllColorFieldSwatches, 30);
                    }
                }
            } catch (err) {
                console.warn('style:property:update sync error:', err);
            }

            // Đồng bộ thước đo Box Model và nút chế độ vị trí
            updateBoxModelInputs(selected);
            updatePosModeButton(selected);
        });

        // ======================================================================
        // SMART CONTEXTUAL INSPECTOR & TYPOGRAPHY VISIBILITY
        // ======================================================================
        function toggleTypographySectorVisibility(show) {
            document.querySelectorAll('.gjs-sm-sector').forEach(sector => {
                const titleEl = sector.querySelector('.gjs-sm-sector-title');
                if (titleEl && (titleEl.innerText.includes('Chữ & Phông') || titleEl.innerText.includes('Typography'))) {
                    sector.style.display = show ? 'block' : 'none';
                }
            });
        }

        function setupContextualInspector(model) {
            const wrapper = document.getElementById('smart-setup-wrapper');
            const videoCard = document.getElementById('smart-video-setup');
            const imageCard = document.getElementById('smart-image-setup');
            const linkCard = document.getElementById('smart-link-setup');
            const textCard = document.getElementById('smart-text-setup');

            // Ẩn tất cả thẻ con trước
            videoCard.style.display = 'none';
            imageCard.style.display = 'none';
            linkCard.style.display = 'none';
            textCard.style.display = 'none';

            const type = model.get('type') || '';
            const tag = (model.get('tagName') || '').toUpperCase();
            const attrs = model.getAttributes() || {};
            const src = model.get('src') || attrs.src || '';

            const isVideo = type === 'video' || tag === 'VIDEO' || (tag === 'IFRAME' && (src.includes('youtube') || src.includes('vimeo') || src.includes('embed')));
            const isImage = tag === 'IMG' || type === 'image';
            const isLink = tag === 'A' || tag === 'BUTTON';
            const isText = ['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'P', 'SPAN'].includes(tag);

            if (isVideo) {
                wrapper.style.display = 'block';
                videoCard.style.display = 'block';
                // Ẩn bảng Typography cho Video vì video không chứa chữ
                toggleTypographySectorVisibility(false);

                // Điền thông tin video hiện tại
                const provider = model.get('provider') || (src.includes('vimeo') ? 'vi' : (src.includes('youtube') || src.includes('youtu.be') ? 'yt' : 'so'));
                document.getElementById('video-provider-select').value = provider;
                handleVideoProviderChange(provider);
                document.getElementById('video-url-input').value = src;

                const autoplay = !!(model.get('autoplay') || attrs.autoplay);
                const loop = !!(model.get('loop') || attrs.loop);
                const controls = model.get('controls') !== false && model.get('controls') !== 0 && attrs.controls !== false;
                const muted = !!(model.get('muted') || attrs.muted);
                const poster = model.get('poster') || attrs.poster || '';

                document.getElementById('video-opt-autoplay').checked = autoplay;
                document.getElementById('video-opt-loop').checked = loop;
                document.getElementById('video-opt-controls').checked = controls;
                document.getElementById('video-opt-muted').checked = muted;
                document.getElementById('video-poster-input').value = poster;

            } else if (isImage) {
                wrapper.style.display = 'block';
                imageCard.style.display = 'block';
                // Ẩn bảng Typography cho hình ảnh
                toggleTypographySectorVisibility(false);

                document.getElementById('smart-img-src').value = src;
                const style = model.getStyle() || {};
                document.getElementById('smart-img-fit').value = style['object-fit'] || 'cover';

            } else if (isLink) {
                wrapper.style.display = 'block';
                linkCard.style.display = 'block';
                toggleTypographySectorVisibility(true);

                document.getElementById('smart-btn-text').value = model.getInnerHTML ? model.getInnerHTML() : '';
                const curHref = attrs.href || '';
                document.getElementById('smart-btn-href').value = curHref;
                document.getElementById('smart-btn-blank').checked = (attrs.target === '_blank');

                const pagePicker = document.getElementById('smart-btn-internal-page');
                if (pagePicker) {
                    pagePicker.value = curHref;
                }

            } else if (isText) {
                wrapper.style.display = 'block';
                textCard.style.display = 'block';
                toggleTypographySectorVisibility(true);

                document.getElementById('smart-text-content').value = model.getInnerHTML ? model.getInnerHTML() : '';

            } else {
                wrapper.style.display = 'none';
                toggleTypographySectorVisibility(true);
            }
        }

        // ======================================================================
        // VIDEO SETTINGS EVENT HANDLERS
        // ======================================================================
        function handleVideoProviderChange(provider) {
            const urlLabel = document.getElementById('video-url-label');
            const urlInput = document.getElementById('video-url-input');
            const urlHint = document.getElementById('video-url-hint');
            const posterRow = document.getElementById('video-poster-row');

            if (provider === 'yt') {
                urlLabel.innerText = 'Đường dẫn Video YouTube / Link xem:';
                urlInput.placeholder = 'https://www.youtube.com/watch?v=... hoặc ID video';
                urlHint.innerText = 'Dán link YouTube (hoặc ID video) để tự động nhúng video chuẩn HD.';
                posterRow.style.display = 'none';
            } else if (provider === 'so') {
                urlLabel.innerText = 'Đường dẫn File Video (.mp4 / .webm):';
                urlInput.placeholder = 'https://.../sample-video.mp4';
                urlHint.innerText = 'Đường dẫn trực tiếp tới file video MP4, WebM hoặc Ogg.';
                posterRow.style.display = 'block';
            } else if (provider === 'vi') {
                urlLabel.innerText = 'Đường dẫn Video Vimeo (URL hoặc ID):';
                urlInput.placeholder = 'https://vimeo.com/...';
                urlHint.innerText = 'Dán link hoặc mã số video trên Vimeo.';
                posterRow.style.display = 'none';
            }
            updateVideoComponentSettings();
        }

        function extractYouTubeId(url) {
            if (!url) return '';
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : url;
        }

        function updateVideoComponentSettings() {
            if (!currentSelectedModel) return;
            const model = currentSelectedModel;
            const isGjsVideo = model.get('type') === 'video';
            const tag = (model.get('tagName') || '').toUpperCase();

            const provider = document.getElementById('video-provider-select').value;
            let url = document.getElementById('video-url-input').value.trim();
            const autoplay = document.getElementById('video-opt-autoplay').checked;
            const loop = document.getElementById('video-opt-loop').checked;
            const controls = document.getElementById('video-opt-controls').checked;
            const muted = document.getElementById('video-opt-muted').checked;
            const poster = document.getElementById('video-poster-input').value.trim();

            if (isGjsVideo) {
                model.set('provider', provider);
                model.set('src', url);
                model.set('autoplay', autoplay ? 1 : 0);
                model.set('loop', loop ? 1 : 0);
                model.set('controls', controls ? 1 : 0);
                model.set('muted', muted ? 1 : 0);
                if (poster) model.set('poster', poster);

                try {
                    const view = model.getView();
                    if (view && view.render) view.render();
                } catch(e) {}
            } else if (tag === 'VIDEO') {
                const attrs = { src: url };
                if (autoplay) attrs.autoplay = true; else delete attrs.autoplay;
                if (loop) attrs.loop = true; else delete attrs.loop;
                if (controls) attrs.controls = true; else delete attrs.controls;
                if (muted) attrs.muted = true; else delete attrs.muted;
                if (poster) attrs.poster = poster; else delete attrs.poster;
                model.addAttributes(attrs);
            } else if (tag === 'IFRAME') {
                let embedSrc = url;
                if (provider === 'yt' && !url.includes('embed')) {
                    const ytId = extractYouTubeId(url);
                    embedSrc = `https://www.youtube.com/embed/${ytId}?autoplay=${autoplay?1:0}&loop=${loop?1:0}&controls=${controls?1:0}&mute=${muted?1:0}`;
                } else if (provider === 'vi' && !url.includes('player.vimeo')) {
                    const viId = url.replace(/[^0-9]/g, '');
                    embedSrc = `https://player.vimeo.com/video/${viId}?autoplay=${autoplay?1:0}&loop=${loop?1:0}`;
                }
                model.addAttributes({ src: embedSrc });
            }
        }

        function applySampleVideo(type) {
            const provSelect = document.getElementById('video-provider-select');
            const urlInput = document.getElementById('video-url-input');
            const posterInput = document.getElementById('video-poster-input');

            if (type === 'yt') {
                provSelect.value = 'yt';
                handleVideoProviderChange('yt');
                urlInput.value = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
            } else {
                provSelect.value = 'so';
                handleVideoProviderChange('so');
                urlInput.value = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4';
                posterInput.value = 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&auto=format&fit=crop&q=80';
            }
            updateVideoComponentSettings();
        }

        // SMART IMAGE, BUTTON, TEXT HANDLERS
        function updateSmartImageSrc(url) {
            if (!currentSelectedModel) return;
            currentSelectedModel.addAttributes({ src: url });
        }

        function updateSmartImageFit(fit) {
            if (!currentSelectedModel) return;
            currentSelectedModel.addStyle({ 'object-fit': fit });
        }

        function updateSmartButtonText(val) {
            if (!currentSelectedModel) return;
            currentSelectedModel.components(val);
        }

        function updateSmartButtonHref(val) {
            if (!currentSelectedModel) return;
            currentSelectedModel.addAttributes({ href: val });
            const pagePicker = document.getElementById('smart-btn-internal-page');
            if (pagePicker && pagePicker.value !== val) {
                pagePicker.value = val;
            }
        }

        function onSelectInternalPage(val) {
            if (!val) return;
            const hrefInput = document.getElementById('smart-btn-href');
            if (hrefInput) {
                hrefInput.value = val;
                updateSmartButtonHref(val);
                showStudioToast(`🔗 Đã liên kết tới trang: ${val}`, 'info', 2000);
            }
        }

        function updateSmartButtonBlank(isBlank) {
            if (!currentSelectedModel) return;
            currentSelectedModel.addAttributes({ target: isBlank ? '_blank' : '_self' });
        }

        function updateSmartTextContent(val) {
            if (!currentSelectedModel) return;
            currentSelectedModel.components(val);
        }

        function updateBoxModelInputs(model) {
            const style = model.getStyle();
            const cleanVal = (v) => (v !== undefined && v !== null && v !== '') ? String(v).replace(/px$/, '') : '0';
            
            document.getElementById('bm-m-top').value = cleanVal(style['margin-top']);
            document.getElementById('bm-m-bottom').value = cleanVal(style['margin-bottom']);
            document.getElementById('bm-m-left').value = cleanVal(style['margin-left']);
            document.getElementById('bm-m-right').value = cleanVal(style['margin-right']);

            document.getElementById('bm-p-top').value = cleanVal(style['padding-top']);
            document.getElementById('bm-p-bottom').value = cleanVal(style['padding-bottom']);
            document.getElementById('bm-p-left').value = cleanVal(style['padding-left']);
            document.getElementById('bm-p-right').value = cleanVal(style['padding-right']);

            const sizeBadge = document.getElementById('bm-elem-size');
            if (sizeBadge) {
                const el = model.getEl();
                if (el) {
                    const rect = el.getBoundingClientRect();
                    sizeBadge.innerText = Math.round(rect.width) + ' × ' + Math.round(rect.height);
                } else {
                    sizeBadge.innerText = 'AUTO × AUTO';
                }
            }
        }

        function updateBoxModel(prop, val) {
            if (!currentSelectedModel) return;
            let finalVal = String(val).trim();
            if (finalVal !== '' && !isNaN(finalVal)) {
                finalVal = finalVal + 'px';
            }
            currentSelectedModel.addStyle({ [prop]: finalVal });

            const el = currentSelectedModel.getEl();
            if (el && el.style) {
                try {
                    el.style[prop] = finalVal;
                } catch(e) {}
            }

            // Đồng bộ sang thanh Kiểu Dáng (StyleManager) nếu đang mở
            try {
                const sm = editor.StyleManager;
                const propModel = sm.getProperty('📦 Khoảng Cách (Spacing)', prop);
                if (propModel && propModel.setValue) {
                    propModel.setValue(finalVal);
                }
            } catch(e) {}

            setTimeout(() => {
                if (currentSelectedModel) {
                    const el = currentSelectedModel.getEl();
                    const sizeBadge = document.getElementById('bm-elem-size');
                    if (el && sizeBadge) {
                        const rect = el.getBoundingClientRect();
                        sizeBadge.innerText = Math.round(rect.width) + ' × ' + Math.round(rect.height);
                    }
                }
            }, 50);
        }

        // ======================================================================
        // 5. ZOOM & PAN ENGINE ("KÉO QUA KÉO LẠI & PHÓNG TO THU NHỎ")
        // ======================================================================
        let currentZoom = 100;
        let panX = 0;
        let panY = 0;
        let isPanModeActive = false;
        let isSpacePressed = false;
        let isDraggingPan = false;
        let panStartX = 0;
        let panStartY = 0;

        function updateViewportTransform() {
            const viewport = document.getElementById('canvas-viewport');
            if (!viewport) return;
            // Áp dụng Pan X, Y và Zoom đồng bộ
            viewport.style.transform = `translate(${panX}px, ${panY}px) scale(${currentZoom / 100})`;
        }

        function setZoom(zoom) {
            currentZoom = Math.min(200, Math.max(30, Math.round(zoom)));
            const badge = document.getElementById('zoom-text') || document.getElementById('zoom-level-badge');
            if (badge) badge.innerText = currentZoom + '%';
            updateViewportTransform();
            try {
                if (typeof editor !== 'undefined' && editor.Canvas && editor.Canvas.setZoom) {
                    editor.Canvas.setZoom(currentZoom);
                }
            } catch (err) {}
        }

        function changeZoom(delta) {
            setZoom(currentZoom + delta);
        }

        function resetZoom() {
            setZoom(100);
        }

        function zoomToFit() {
            const stage = document.getElementById('canvas-interactive-stage');
            const viewport = document.getElementById('canvas-viewport');
            if (!stage || !viewport) return;

            const stageW = stage.clientWidth - 80;
            const stageH = stage.clientHeight - 40;
            const viewportW = viewport.offsetWidth || 1200;
            const viewportH = viewport.offsetHeight || 600;

            const scaleW = (stageW > 0 && viewportW > 0) ? (stageW / viewportW) : 1;
            const scaleH = (stageH > 0 && viewportH > 0) ? (stageH / viewportH) : 1;
            const bestScale = Math.min(scaleW, scaleH, 1);

            const fitZoom = Math.floor(bestScale * 100);
            setZoom(Math.max(30, Math.min(100, fitZoom)));
            resetPanPosition();
        }

        function toggleZoomMenu(e) {
            e.stopPropagation();
            const menu = document.getElementById('zoom-dropdown-menu');
            if (menu) menu.classList.toggle('open');
        }

        function closeZoomMenu() {
            const menu = document.getElementById('zoom-dropdown-menu');
            if (menu) menu.classList.remove('open');
        }

        window.addEventListener('click', () => {
            closeZoomMenu();
        });

        function togglePanMode() {
            isPanModeActive = !isPanModeActive;
            const btn = document.getElementById('btn-pan-mode');
            if (isPanModeActive) {
                btn.classList.add('active');
                document.body.classList.add('pan-mode-active');
                document.getElementById('pan-drag-overlay').style.display = 'block';
            } else {
                btn.classList.remove('active');
                document.body.classList.remove('pan-mode-active');
                if (!isDraggingPan) {
                    document.getElementById('pan-drag-overlay').style.display = 'none';
                }
            }
        }

        function resetPanPosition() {
            panX = 0;
            panY = 0;
            updateViewportTransform();
        }

        function startPan(clientX, clientY) {
            isDraggingPan = true;
            panStartX = clientX - panX;
            panStartY = clientY - panY;
            document.body.classList.add('is-panning');
            document.getElementById('pan-drag-overlay').style.display = 'block';
        }

        function doPan(clientX, clientY) {
            if (!isDraggingPan) return;
            panX = clientX - panStartX;
            panY = clientY - panStartY;
            updateViewportTransform();
        }

        function endPan() {
            if (isDraggingPan) {
                isDraggingPan = false;
                document.body.classList.remove('is-panning');
                if (!isPanModeActive && !isSpacePressed) {
                    document.getElementById('pan-drag-overlay').style.display = 'none';
                }
            }
        }

        // Global shortcuts for Space, Zoom, and Component Navigation
        window.addEventListener('keydown', (e) => {
            const isEditing = e.target.matches('input, textarea, select') || e.target.isContentEditable;
            if (e.code === 'Space' && !isEditing) {
                e.preventDefault();
                isSpacePressed = true;
                document.body.classList.add('pan-mode-active');
                document.getElementById('pan-drag-overlay').style.display = 'block';
            }
            if (e.ctrlKey && (e.key === '=' || e.key === '+')) {
                e.preventDefault();
                changeZoom(10);
            }
            if (e.ctrlKey && e.key === '-') {
                e.preventDefault();
                changeZoom(-10);
            }
            if (e.ctrlKey && e.key === '0') {
                e.preventDefault();
                resetZoom();
            }

            // Phím tắt di chuyển khối khi đang chọn phần tử
            if (!isEditing && currentSelectedModel) {
                if (e.altKey && e.key === 'ArrowUp') {
                    e.preventDefault();
                    moveComponentUp();
                } else if (e.altKey && e.key === 'ArrowDown') {
                    e.preventDefault();
                    moveComponentDown();
                } else if (e.ctrlKey && (e.key === 'd' || e.key === 'D')) {
                    e.preventDefault();
                    duplicateSelectedElement();
                } else if (e.key === 'Delete') {
                    e.preventDefault();
                    deleteSelectedElement();
                }
            }
        });

        window.addEventListener('keyup', (e) => {
            if (e.code === 'Space') {
                isSpacePressed = false;
                if (!isPanModeActive) {
                    document.body.classList.remove('pan-mode-active');
                    if (!isDraggingPan) {
                        document.getElementById('pan-drag-overlay').style.display = 'none';
                    }
                }
            }
        });

        // Stage pan events
        const panOverlay = document.getElementById('pan-drag-overlay');
        const stageEl = document.getElementById('canvas-interactive-stage');

        panOverlay.addEventListener('mousedown', (e) => {
            startPan(e.clientX, e.clientY);
        });

        stageEl.addEventListener('mousedown', (e) => {
            if (e.button === 1 || isPanModeActive || isSpacePressed) {
                e.preventDefault();
                startPan(e.clientX, e.clientY);
            }
        });

        window.addEventListener('mousemove', (e) => {
            if (isDraggingPan) {
                doPan(e.clientX, e.clientY);
            }
        });

        window.addEventListener('mouseup', () => {
            endPan();
        });

        // Mouse Wheel on Stage
        stageEl.addEventListener('wheel', (e) => {
            if (e.ctrlKey) {
                e.preventDefault();
                const delta = e.deltaY < 0 ? 8 : -8;
                changeZoom(delta);
            } else if (e.shiftKey) {
                e.preventDefault();
                panX -= e.deltaY;
                updateViewportTransform();
            } else if (isPanModeActive || isSpacePressed) {
                e.preventDefault();
                panY -= e.deltaY;
                panX -= e.deltaX;
                updateViewportTransform();
            } else {
                // Cuộn chuột trên nền sân khấu canvas tự động Pan Y (Lên / Xuống)
                e.preventDefault();
                panY -= e.deltaY;
                updateViewportTransform();
            }
        }, { passive: false });

        // IFRAME EVENT BRIDGING: Catch all mouse wheel & drag events from inside GrapesJS iframe
        function bindIframePanAndZoom() {
            try {
                const frame = editor.Canvas.getFrameEl();
                if (!frame || !frame.contentDocument) return;
                const doc = frame.contentDocument;

                // Wheel inside iframe
                doc.addEventListener('wheel', (e) => {
                    if (e.ctrlKey) {
                        e.preventDefault();
                        e.stopPropagation();
                        changeZoom(e.deltaY < 0 ? 8 : -8);
                    } else if (e.shiftKey) {
                        e.preventDefault();
                        e.stopPropagation();
                        panX -= e.deltaY;
                        updateViewportTransform();
                    } else if (isPanModeActive || isSpacePressed) {
                        e.preventDefault();
                        e.stopPropagation();
                        panY -= e.deltaY;
                        panX -= e.deltaX;
                        updateViewportTransform();
                    }
                    // Mặc định: Cho phép cuộn trang web mượt mà tự nhiên không bị chặn
                }, { passive: false });

                // Middle Click or Space drag inside iframe
                doc.addEventListener('mousedown', (e) => {
                    if (e.button === 1 || isPanModeActive || isSpacePressed) {
                        e.preventDefault();
                        e.stopPropagation();
                        const rect = frame.getBoundingClientRect();
                        startPan(rect.left + e.clientX, rect.top + e.clientY);
                    }
                });

                doc.addEventListener('mousemove', (e) => {
                    if (isDraggingPan) {
                        const rect = frame.getBoundingClientRect();
                        doPan(rect.left + e.clientX, rect.top + e.clientY);
                    }
                });

                doc.addEventListener('mouseup', () => {
                    if (isDraggingPan) endPan();
                });

                // Keydown/keyup inside iframe for Space & Ctrl Zoom
                doc.addEventListener('keydown', (e) => {
                    const isEditing = e.target.isContentEditable || ['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName);
                    if (e.code === 'Space' && !isEditing) {
                        e.preventDefault();
                        isSpacePressed = true;
                        document.body.classList.add('pan-mode-active');
                        document.getElementById('pan-drag-overlay').style.display = 'block';
                    }
                    if (e.ctrlKey && (e.key === '=' || e.key === '+')) {
                        e.preventDefault();
                        changeZoom(10);
                    }
                    if (e.ctrlKey && e.key === '-') {
                        e.preventDefault();
                        changeZoom(-10);
                    }
                    if (e.ctrlKey && e.key === '0') {
                        e.preventDefault();
                        resetZoom();
                    }

                    // Phím tắt di chuyển khối khi con trỏ ở trong iframe
                    if (!isEditing && currentSelectedModel) {
                        if (e.altKey && e.key === 'ArrowUp') {
                            e.preventDefault();
                            moveComponentUp();
                        } else if (e.altKey && e.key === 'ArrowDown') {
                            e.preventDefault();
                            moveComponentDown();
                        } else if (e.ctrlKey && (e.key === 'd' || e.key === 'D')) {
                            e.preventDefault();
                            duplicateSelectedElement();
                        } else if (e.key === 'Delete') {
                            e.preventDefault();
                            deleteSelectedElement();
                        }
                    }
                });

                doc.addEventListener('keyup', (e) => {
                    if (e.code === 'Space') {
                        isSpacePressed = false;
                        if (!isPanModeActive) {
                            document.body.classList.remove('pan-mode-active');
                            if (!isDraggingPan) {
                                document.getElementById('pan-drag-overlay').style.display = 'none';
                            }
                        }
                    }
                });
            } catch (err) {
                console.warn('Iframe event binding warning:', err);
            }
        }

        editor.on('load', () => {
            bindIframePanAndZoom();
            initStudioColorPopover();
            setTimeout(updateAllColorFieldSwatches, 300);
            try {
                const wrapper = editor.getWrapper();
                if (wrapper) {
                    wrapper.set({
                        hoverable: false,
                        badgable: false,
                        highlightable: false,
                        selectable: false,
                    });
                }
            } catch (err) {}
            try {
                const frame = editor.Canvas.getFrameEl();
                if (frame && frame.contentDocument) {
                    const doc = frame.contentDocument;
                    const style = doc.createElement('style');
                    style.innerHTML = `
                        html, body {
                            margin: 0 !important;
                            padding: 0 !important;
                            background: #ffffff !important;
                            min-height: 100% !important;
                            width: 100% !important;
                            overflow-x: hidden !important;
                            overflow-y: auto !important;
                            scrollbar-width: thin;
                            scrollbar-color: #cbd5e1 transparent;
                        }
                        body, [data-gjs-type="wrapper"] {
                            overflow-y: auto !important;
                            overflow-x: hidden !important;
                            outline: none !important;
                        }
                        body:hover, [data-gjs-type="wrapper"]:hover {
                            outline: none !important;
                            background-color: transparent !important;
                        }
                        /* Vô hiệu hóa pointer-events trên iframe nhúng (Map/Video) khi đang ở chế độ sửa để có thể nhấp chọn và kéo thả trực tiếp */
                        body:not(.is-preview-mode) iframe {
                            pointer-events: none !important;
                        }
                        [data-gjs-type="video"], [data-gjs-type="map"] {
                            cursor: grab !important;
                        }
                        .gjs-selected:not(body):not([data-gjs-type="wrapper"]) {
                            outline: 2px solid #2563eb !important;
                            outline-offset: -2px !important;
                            cursor: grab !important;
                        }
                        .gjs-selected:active {
                            cursor: grabbing !important;
                        }
                        ::-webkit-scrollbar { width: 6px; }
                        ::-webkit-scrollbar-track { background: transparent; }
                        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
                        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
                    `;
                    doc.head.appendChild(style);
                }
            } catch(e) {}
            setTimeout(() => {
                try {
                    normalizeComponentTree(editor.getWrapper());
                } catch(e) {}
                zoomToFit();
                updateDimensionBadge(viewportEl.offsetWidth, viewportEl.offsetHeight);
            }, 200);
        });

        editor.on('component:add', (comp) => {
            if (comp) {
                try {
                    normalizeComponentTree(comp);
                } catch(e) {}
            }
        });

        // ======================================================================
        // 6. INTERACTIVE DRAGGABLE RESIZER HANDLES (WIDTH & HEIGHT)
        // ======================================================================
        const handleLeft = document.getElementById('handle-resize-left');
        const handleRight = document.getElementById('handle-resize-right');
        const handleBottom = document.getElementById('handle-resize-bottom');
        const viewportEl = document.getElementById('canvas-viewport');
        const widthBadge = document.getElementById('canvas-width-badge');

        let isResizing = false;
        let resizeStartX = 0;
        let resizeStartY = 0;
        let resizeStartWidth = 0;
        let resizeStartHeight = 0;
        let resizeSide = 'right';

        function updateDimensionBadge(w, h) {
            if (!widthBadge) return;
            const roundW = Math.round(w);
            const roundH = Math.round(h);
            widthBadge.innerText = `${roundW} × ${roundH}px`;
        }

        function startResize(e, side) {
            e.stopPropagation();
            isResizing = true;
            resizeSide = side;
            resizeStartX = e.clientX;
            resizeStartY = e.clientY;
            resizeStartWidth = viewportEl.offsetWidth;
            resizeStartHeight = viewportEl.offsetHeight;
            document.body.style.cursor = side === 'bottom' ? 'row-resize' : 'col-resize';
        }

        if (handleRight) handleRight.addEventListener('mousedown', (e) => startResize(e, 'right'));
        if (handleLeft) handleLeft.addEventListener('mousedown', (e) => startResize(e, 'left'));
        if (handleBottom) {
            handleBottom.addEventListener('mousedown', (e) => startResize(e, 'bottom'));
            handleBottom.addEventListener('dblclick', () => {
                autoFitCanvasHeight();
            });
        }

        function autoFitCanvasHeight() {
            try {
                const frame = editor.Canvas.getFrameEl();
                if (frame && frame.contentDocument && frame.contentDocument.body) {
                    const scrollH = frame.contentDocument.body.scrollHeight;
                    const targetH = Math.max(560, Math.min(3500, scrollH + 30));
                    viewportEl.style.height = targetH + 'px';
                    updateDimensionBadge(viewportEl.offsetWidth, targetH);
                    showStudioToast(`📏 Chiều cao tự động khớp nội dung: ${targetH}px`, 'info', 2000);
                }
            } catch (err) {
                viewportEl.style.height = 'calc(100% - 28px)';
                updateDimensionBadge(viewportEl.offsetWidth, viewportEl.offsetHeight);
            }
        }

        window.addEventListener('mousemove', (e) => {
            if (!isResizing) return;
            if (resizeSide === 'bottom') {
                const diffY = e.clientY - resizeStartY;
                const newH = Math.max(400, Math.min(3500, resizeStartHeight + diffY));
                viewportEl.style.height = newH + 'px';
                updateDimensionBadge(viewportEl.offsetWidth, newH);
            } else {
                const diff = (e.clientX - resizeStartX) * (resizeSide === 'right' ? 2 : -2);
                const newW = Math.max(320, Math.min(1920, resizeStartWidth + diff));
                viewportEl.style.width = newW + 'px';
                updateDimensionBadge(newW, viewportEl.offsetHeight);
                const devLabel = document.getElementById('canvas-device-label');
                if (devLabel) {
                    if (newW >= 992) {
                        devLabel.innerHTML = '<i class="fa fa-desktop" style="color: var(--accent-cyan);"></i> <span>Desktop</span>';
                    } else if (newW >= 768) {
                        devLabel.innerHTML = '<i class="fa fa-tablet-screen-button" style="color: var(--accent-cyan);"></i> <span>Tablet</span>';
                    } else {
                        devLabel.innerHTML = '<i class="fa fa-mobile-screen-button" style="color: var(--accent-cyan);"></i> <span>Mobile</span>';
                    }
                }
            }
        });

        window.addEventListener('mouseup', () => {
            if (isResizing) {
                isResizing = false;
                document.body.style.cursor = '';
                editor.refresh();
            }
        });

        // Double-click resize handle to reset
        if (handleRight) handleRight.addEventListener('dblclick', () => setDeviceMode('desktop'));
        if (handleLeft) handleLeft.addEventListener('dblclick', () => setDeviceMode('desktop'));

        // ======================================================================
        // 7. RESPONSIVE DEVICES & INSPECTOR TOGGLE
        // ======================================================================
        function setDeviceMode(device) {
            const viewport = document.getElementById('canvas-viewport');
            const label = document.getElementById('canvas-device-label');
            const widthBadge = document.getElementById('canvas-width-badge');

            document.querySelectorAll('.device-btn').forEach(b => b.classList.remove('active'));

            if (device === 'desktop') {
                editor.setDevice('Desktop');
                viewport.className = 'canvas-viewport-wrapper mode-desktop';
                viewport.style.width = '1200px';
                document.getElementById('btn-device-desktop').classList.add('active');
                if (label) label.innerHTML = '<i class="fa fa-desktop" style="color: var(--accent-cyan);"></i> <span>Desktop</span>';
                updateDimensionBadge(1200, viewport.offsetHeight);
                zoomToFit();
            } else if (device === 'tablet') {
                editor.setDevice('Tablet');
                viewport.className = 'canvas-viewport-wrapper mode-tablet';
                viewport.style.width = '768px';
                document.getElementById('btn-device-tablet').classList.add('active');
                if (label) label.innerHTML = '<i class="fa fa-tablet-screen-button" style="color: var(--accent-cyan);"></i> <span>Tablet</span>';
                updateDimensionBadge(768, viewport.offsetHeight);
                setZoom(100);
            } else if (device === 'mobile') {
                editor.setDevice('Mobile');
                viewport.className = 'canvas-viewport-wrapper mode-mobile';
                viewport.style.width = '375px';
                document.getElementById('btn-device-mobile').classList.add('active');
                if (label) label.innerHTML = '<i class="fa fa-mobile-screen-button" style="color: var(--accent-cyan);"></i> <span>Mobile</span>';
                updateDimensionBadge(375, viewport.offsetHeight);
                setZoom(100);
            }
            resetPanPosition();
            editor.refresh();
        }

        function toggleLeftDrawer() {
            const drawer = document.getElementById('left-drawer');
            const icon = document.getElementById('drawer-toggle-icon');
            if (drawer.classList.contains('collapsed')) {
                drawer.classList.remove('collapsed');
                icon.className = 'fa fa-chevron-left';
            } else {
                drawer.classList.add('collapsed');
                icon.className = 'fa fa-chevron-right';
            }
            setTimeout(() => editor.refresh(), 200);
        }

        function toggleRightSidebar() {
            const sidebar = document.getElementById('sidebar-right');
            const icon = document.getElementById('inspector-toggle-icon');
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                icon.className = 'fa fa-chevron-right';
            } else {
                sidebar.classList.add('collapsed');
                icon.className = 'fa fa-chevron-left';
            }
            setTimeout(() => editor.refresh(), 200);
        }

        function switchDrawerTab(tab) {
            const drawer = document.getElementById('left-drawer');
            drawer.classList.remove('collapsed');
            document.getElementById('drawer-toggle-icon').className = 'fa fa-chevron-left';

            document.querySelectorAll('.rail-btn').forEach(btn => btn.classList.remove('active'));
            const activeRailBtn = document.getElementById(`rail-btn-${tab}`);
            if (activeRailBtn) activeRailBtn.classList.add('active');

            document.querySelectorAll('.drawer-pane').forEach(p => p.style.display = 'none');
            const targetPane = document.getElementById(`drawer-pane-${tab}`);
            if (targetPane) targetPane.style.display = 'flex';

            if (tab === 'media') loadMediaAssets();
            setTimeout(() => editor.refresh(), 200);
        }

        function switchInspectorTab(tab) {
            document.querySelectorAll('.inspector-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.inspector-tab-pane').forEach(p => p.classList.remove('active'));

            if (tab === 'styles') {
                document.getElementById('tab-btn-styles').classList.add('active');
                document.getElementById('inspector-pane-styles').classList.add('active');
            } else if (tab === 'traits') {
                document.getElementById('tab-btn-traits').classList.add('active');
                document.getElementById('inspector-pane-traits').classList.add('active');
            }
        }

        function togglePreviewMode() {
            document.body.classList.toggle('is-preview-mode');
            setTimeout(() => editor.refresh(), 100);
        }

        function filterElements(query) {
            const q = query.toLowerCase().trim();
            document.querySelectorAll('.gjs-block').forEach(el => {
                const text = el.innerText.toLowerCase();
                el.style.display = text.includes(q) ? '' : 'none';
            });
        }

        function undoAction() { editor.UndoManager.undo(); }
        function redoAction() { editor.UndoManager.redo(); }

        function clearCanvasConfirm() {
            if (confirm('Bạn có chắc chắn muốn xóa toàn bộ nội dung trên bản vẽ?')) {
                editor.setComponents('');
            }
        }

        // ======================================================================
        // 8. CODE MODAL & AUTOSAVE
        // ======================================================================
        function openCodeModal() {
            const html = editor.getHtml();
            const css = editor.getCss();
            document.getElementById('code-textarea').value = `<!-- HTML -->\n${html}\n\n/* SCOPED CSS */\n${css}`;
            document.getElementById('code-modal').classList.add('open');
        }

        function closeCodeModal() {
            document.getElementById('code-modal').classList.remove('open');
        }

        function copyCodeContent() {
            const textarea = document.getElementById('code-textarea');
            textarea.select();
            document.execCommand('copy');
            showStudioToast('✓ Đã sao chép mã nguồn vào clipboard!', 'success');
        }

        let autoSaveTimer = null;
        editor.on('change:changesCount', () => {
            const pill = document.getElementById('status-indicator');
            pill.style.color = 'var(--accent-amber)';
            pill.style.borderColor = 'rgba(245, 158, 11, 0.3)';
            pill.style.background = 'rgba(245, 158, 11, 0.12)';
            pill.innerHTML = '<i class="fa fa-spinner fa-spin"></i> <span>' + (I18N[currentLocale].status_saving) + '</span>';

            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                saveDraft(false);
            }, 2500);
        });

        async function saveDraft(showAlert = true) {
            const pill = document.getElementById('status-indicator');
            pill.innerHTML = '<i class="fa fa-spinner fa-spin"></i> <span>' + (I18N[currentLocale].status_saving) + '</span>';

            const payload = {
                content_json: {
                    gjs_project: editor.getProjectData(),
                    gjs_html: editor.getHtml(),
                    gjs_css: editor.getCss()
                },
                base_version: currentVersion
            };

            try {
                const res = await fetch(`/api/builder/pages/${pageId}/autosave`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    currentVersion = data.data.new_version_number;
                    document.getElementById('version-display').innerText = currentVersion;
                    pill.style.color = 'var(--accent-emerald)';
                    pill.style.borderColor = 'rgba(16, 185, 129, 0.25)';
                    pill.style.background = 'rgba(16, 185, 129, 0.12)';
                    pill.innerHTML = '<i class="fa fa-circle-check"></i> <span>' + (I18N[currentLocale].status_synced) + '</span>';
                    if (showAlert) showStudioToast(I18N[currentLocale].alert_saved, 'success');
                } else if (res.status === 409) {
                    showStudioToast(I18N[currentLocale].alert_conflict, 'error');
                    pill.innerHTML = '<i class="fa fa-triangle-exclamation"></i> <span>Xung đột</span>';
                }
            } catch (err) {
                pill.innerHTML = '<i class="fa fa-xmark"></i> <span>Lỗi lưu</span>';
            }
        }

        async function publishSite() {
            if (!confirm(I18N[currentLocale].alert_publish_confirm)) return;
            await saveDraft(false);

            try {
                const res = await fetch(`/api/builder/websites/${websiteId}/publish`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showStudioToast(I18N[currentLocale].alert_publish_success + ' (v' + data.data.version_tag + ')', 'success');
                    window.open(`/builder/preview/${websiteId}`, '_blank');
                } else {
                    showStudioToast('Lỗi: ' + (data.message || 'Không xác định'), 'error');
                }
            } catch (e) {
                showStudioToast('Lỗi kết nối khi xuất bản!', 'error');
            }
        }

        // ======================================================================
        // 9. PAGES & MEDIA MANAGEMENT
        // ======================================================================
        function handlePageSwitch(val) {
            if (val === '__NEW_PAGE__') promptCreateNewPage();
            else window.location.href = val;
        }

        function promptCreateNewPage() {
            document.getElementById('new-page-title').value = '';
            document.getElementById('new-page-slug').value = '';
            document.getElementById('new-page-modal').classList.add('open');
        }

        function closeNewPageModal() {
            document.getElementById('new-page-modal').classList.remove('open');
        }

        function autoGenerateSlug(title) {
            const slug = title.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[đĐ]/g, 'd')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim().replace(/\s+/g, '-');
            document.getElementById('new-page-slug').value = slug;
        }

        async function submitCreateNewPage() {
            const title = document.getElementById('new-page-title').value.trim();
            const slug = document.getElementById('new-page-slug').value.trim();
            if (!title || !slug) return;

            try {
                const res = await fetch('/api/builder/pages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ website_id: websiteId, title, slug })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    window.location.href = `/builder/editor/${data.data.id}`;
                } else {
                    showStudioToast('Error: ' + (data.message || 'Failed'), 'error');
                }
            } catch (e) {
                showStudioToast('Connection error', 'error');
            }
        }

        async function loadMediaAssets() {
            const grid = document.getElementById('media-gallery-grid');
            grid.innerHTML = '<div style="grid-column: span 2; color: var(--text-subtle); text-align: center; padding: 20px;">Đang tải ảnh...</div>';

            try {
                const res = await fetch(`/api/builder/assets?website_id=${websiteId}`);
                const data = await res.json();
                grid.innerHTML = '';

                if (data.data && data.data.length > 0) {
                    data.data.forEach(item => {
                        const card = document.createElement('div');
                        card.style.cssText = 'border: 1px solid var(--border-studio); border-radius: 8px; overflow: hidden; cursor: pointer; background: var(--bg-studio-card);';
                        card.innerHTML = `
                            <img src="${item.url}" style="width: 100%; height: 80px; object-fit: cover; display: block;" />
                            <div style="font-size: 0.68rem; color: #94a3b8; padding: 4px 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${item.filename}</div>
                        `;
                        card.onclick = () => {
                            if (currentSelectedModel && currentSelectedModel.get('tagName') === 'IMG') {
                                currentSelectedModel.addAttributes({ src: item.url });
                                document.getElementById('qt-img-src').value = item.url;
                            } else {
                                editor.addComponents(`<img src="${item.url}" style="max-width: 100%; border-radius: 8px;" />`);
                            }
                            showStudioToast('✓ Đã chèn ảnh vào bản vẽ!', 'success');
                        };
                        grid.appendChild(card);
                    });
                } else {
                    grid.innerHTML = '<div style="grid-column: span 2; color: var(--text-subtle); text-align: center; padding: 20px; font-size: 0.8rem;">Chưa có ảnh nào.</div>';
                }
            } catch (e) {
                grid.innerHTML = '<div style="grid-column: span 2; color: var(--accent-rose); text-align: center; padding: 20px;">Lỗi tải ảnh!</div>';
            }
        }

        async function handleMediaUpload(input) {
            if (!input.files || !input.files[0]) return;
            const formData = new FormData();
            formData.append('file', input.files[0]);
            formData.append('website_id', websiteId);

            try {
                const res = await fetch('/api/builder/assets/upload', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    showStudioToast('✓ Tải ảnh lên thành công!', 'success');
                    loadMediaAssets();
                } else {
                    showStudioToast('Lỗi: ' + (data.message || 'Upload failed'), 'error');
                }
            } catch (e) {
                showStudioToast('Connection error', 'error');
            }
        }

        async function uploadImageForSelected(input) {
            if (!input.files || !input.files[0]) return;
            const formData = new FormData();
            formData.append('file', input.files[0]);
            formData.append('website_id', websiteId);

            try {
                const res = await fetch('/api/builder/assets/upload', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    if (currentSelectedModel) {
                        currentSelectedModel.addAttributes({ src: data.data.url });
                        document.getElementById('qt-img-src').value = data.data.url;
                    }
                    showStudioToast('✓ Đã cập nhật ảnh mới cho phần tử!', 'success');
                } else {
                    showStudioToast('Lỗi: ' + (data.message || 'Upload failed'), 'error');
                }
            } catch (e) {
                showStudioToast('Connection error', 'error');
            }
        }

        // Apply initial language
        applyI18nTexts(currentLocale);
    </script>
</body>
</html>
