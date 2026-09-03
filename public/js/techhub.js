/**
 * TechHub - Core Frontend Interactive Script
 * Senior-Grade Interactive Tool Workspace & Dynamic UI Renderers
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Khởi tạo tức thì các sự kiện cần tương tác ngay
  initKeyboardShortcuts();
  initCopyButtons();
  initToolForm();

  // 2. Hoãn các tác vụ tính toán DOM & Layout nặng (Swiper, Filter, Search) khi Main Thread rảnh
  const initDeferredComponents = () => {
    initCategoriesSwiper();
    initCategoryLiveFilter();
    initLiveSearch();
    initDropzones();
    initSampleLoaders();
    initSeoLiveCounters();
  };

  if ('requestIdleCallback' in window) {
    requestIdleCallback(initDeferredComponents, { timeout: 1500 });
  } else {
    setTimeout(initDeferredComponents, 30);
  }
});

// Mobile Navigation Toggle
window.toggleMobileMenu = function () {
  const drawer = document.getElementById('mobile-menu-drawer');
  const iconOpen = document.getElementById('icon-menu-open');
  const iconClose = document.getElementById('icon-menu-close');
  if (!drawer) return;

  const isOpen = drawer.classList.contains('open');
  if (isOpen) {
    drawer.classList.remove('open');
    if (iconOpen) iconOpen.style.display = 'block';
    if (iconClose) iconClose.style.display = 'none';
  } else {
    drawer.classList.add('open');
    if (iconOpen) iconOpen.style.display = 'none';
    if (iconClose) iconClose.style.display = 'block';
  }
};

// 1. Initialize Swiper for Category Navigation Bar
function initCategoriesSwiper() {
  const swiperContainers = document.querySelectorAll('.swiper-categories');
  if (swiperContainers.length === 0) return;

  if (typeof Swiper !== 'undefined') {
    swiperContainers.forEach((container) => {
      const swiper = new Swiper(container, {
        slidesPerView: 'auto',
        spaceBetween: 10,
        freeMode: {
          enabled: true,
          sticky: false,
          momentumBounce: true,
        },
        grabCursor: true,
        mousewheel: {
          forceToAxis: true,
          sensitivity: 1,
        },
        navigation: {
          nextEl: container.parentElement.querySelector('.swiper-cat-next'),
          prevEl: container.parentElement.querySelector('.swiper-cat-prev'),
        },
        keyboard: {
          enabled: true,
        },
      });

      const activeSlide = container.querySelector('.cat-tab.active');
      if (activeSlide) {
        const slideIndex = Array.from(container.querySelectorAll('.swiper-slide')).indexOf(activeSlide.closest('.swiper-slide'));
        if (slideIndex >= 0) {
          swiper.slideTo(slideIndex, 300);
        }
      }
    });
  }
}

// 2. Instant Live Category Filtering (No Reload on Homepage/Catalog)
function initCategoryLiveFilter() {
  const categoryTabs = document.querySelectorAll('.cat-tab[data-filter-category]');
  const toolCards = document.querySelectorAll('.tool-card[data-tool-category]');

  if (toolCards.length === 0 || categoryTabs.length === 0) return;

  categoryTabs.forEach((tab) => {
    tab.addEventListener('click', (e) => {
      const filterCat = (tab.getAttribute('data-filter-category') || '').toLowerCase().trim();

      if (window.location.pathname === '/' || window.location.pathname === '/tools') {
        e.preventDefault();

        categoryTabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        toolCards.forEach((card) => {
          const cardCat = (card.getAttribute('data-tool-category') || '').toLowerCase().trim();

          if (!filterCat || cardCat === filterCat) {
            card.style.display = 'flex';
            card.style.opacity = '0';
            card.style.transform = 'translateY(8px)';
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            requestAnimationFrame(() => {
              card.style.opacity = '1';
              card.style.transform = 'translateY(0)';
            });
          } else {
            card.style.display = 'none';
          }
        });

        if (history.pushState) {
          const newUrl = filterCat ? `/tools?category=${filterCat}` : (window.location.pathname === '/' ? '/' : '/tools');
          history.pushState(null, '', newUrl);
        }
      }
    });
  });
}

// 3. Keyboard Shortcuts (Ctrl+K)
function initKeyboardShortcuts() {
  const searchInput = document.getElementById('global-search-input');
  if (!searchInput) return;

  window.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      searchInput.focus();
      searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
}

// 4. Toast Notification
function showToast(message, type = 'success') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span>${message}</span>`;
  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(10px)';
    toast.style.transition = 'all 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

// 5. 1-Click Copy to Clipboard
function initCopyButtons() {
  document.addEventListener('click', (e) => {
    const copyBtn = e.target.closest('[data-copy-target]');
    if (!copyBtn) return;

    const isEn = (window.__locale === 'en');
    const targetId = copyBtn.getAttribute('data-copy-target');
    const targetEl = document.getElementById(targetId);
    if (!targetEl) return;

    const textToCopy = targetEl.value || targetEl.innerText || '';
    if (!textToCopy) {
      showToast(isEn ? 'No content to copy!' : 'Không có nội dung để sao chép!', 'error');
      return;
    }

    navigator.clipboard.writeText(textToCopy).then(() => {
      showToast(isEn ? 'Copied to clipboard!' : 'Đã sao chép vào bộ nhớ tạm (Clipboard)!');
      const origText = copyBtn.innerHTML;
      copyBtn.innerHTML = isEn ? '✓ Copied!' : '✓ Đã chép!';
      setTimeout(() => { copyBtn.innerHTML = origText; }, 2000);
    }).catch(() => {
      showToast(isEn ? 'Copy failed' : 'Sao chép thất bại', 'error');
    });
  });
}

// 6. Live Search for Tools Grid
function initLiveSearch() {
  const searchInput = document.getElementById('global-search-input');
  if (!searchInput) return;

  const toolCards = document.querySelectorAll('.tool-card');

  searchInput.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase().trim();

    toolCards.forEach((card) => {
      const title = (card.getAttribute('data-tool-name') || '').toLowerCase();
      const desc = (card.getAttribute('data-tool-summary') || '').toLowerCase();
      const cat = (card.getAttribute('data-tool-category') || '').toLowerCase();

      if (title.includes(query) || desc.includes(query) || cat.includes(query)) {
        card.style.display = 'flex';
      } else {
        card.style.display = 'none';
      }
    });
  });
}

// 7. Dropzone Drag & Drop with Instant Image Preview
function initDropzones() {
  const dropzone = document.getElementById('file-dropzone');
  const fileInput = document.getElementById('file-input');
  const base64Input = document.getElementById('image-base64-input');
  const previewWrap = document.getElementById('file-preview-wrap');
  const previewThumb = document.getElementById('file-preview-thumb');
  const previewName = document.getElementById('file-preview-name');
  const previewSize = document.getElementById('file-preview-size');

  if (!dropzone || !fileInput || !base64Input) return;

  dropzone.addEventListener('click', () => fileInput.click());

  const handleFile = (file) => {
    const isEn = (window.__locale === 'en');
    if (!file || !file.type.startsWith('image/')) {
      showToast(isEn ? 'Please select a valid image file (PNG, JPG, WEBP, GIF)' : 'Vui lòng chọn tệp hình ảnh hợp lệ (PNG, JPG, WEBP, GIF)', 'error');
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      base64Input.value = e.target.result;
      if (previewWrap) previewWrap.style.display = 'flex';
      if (previewThumb) previewThumb.src = e.target.result;
      if (previewName) previewName.innerText = file.name;
      if (previewSize) previewSize.innerText = `${(file.size / 1024).toFixed(1)} KB • ${file.type}`;
      showToast(isEn ? `Image uploaded: ${file.name}` : `Đã tải ảnh: ${file.name}`);
    };
    reader.readAsDataURL(file);
  };

  fileInput.addEventListener('change', (e) => {
    if (e.target.files && e.target.files[0]) {
      handleFile(e.target.files[0]);
    }
  });

  dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('dragover');
  });

  dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('dragover');
  });

  dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      handleFile(e.dataTransfer.files[0]);
    }
  });
}

// 8. Sample Data Loaders
function initSampleLoaders() {
  const isEn = (window.__locale === 'en');

  const jsonBtn = document.getElementById('btn-load-sample-json');
  if (jsonBtn) {
    jsonBtn.addEventListener('click', () => {
      const sample = {
        name: "TechHub Platform",
        version: "2.0.0",
        architecture: "Clean Architecture + DDD + CQRS",
        features: ["Sub-millisecond latency", "Zero retention", "Full REST API"],
        metrics: { uptime_pct: 99.99, tools_count: 11, is_active: true }
      };
      const input = document.getElementById('json-input');
      if (input) input.value = JSON.stringify(sample, null, 2);
      showToast(isEn ? 'Sample JSON loaded!' : 'Đã nạp JSON mẫu!');
    });
  }

  const base64Btn = document.getElementById('btn-load-sample-base64');
  if (base64Btn) {
    base64Btn.addEventListener('click', () => {
      const input = document.getElementById('base64-text');
      if (input) input.value = isEn ? "TechHub - Senior-Grade Online Developer Utilities & SEO Suite" : "TechHub - Nền tảng công cụ trực tuyến chuẩn Senior cho Developer";
      showToast(isEn ? 'Sample text loaded!' : 'Đã nạp văn bản mẫu!');
    });
  }

  const hashBtn = document.getElementById('btn-load-sample-hash');
  if (hashBtn) {
    hashBtn.addEventListener('click', () => {
      const input = document.getElementById('hash-text');
      if (input) input.value = "Admin@123456#TechHub2026";
      showToast(isEn ? 'Sample hash string loaded!' : 'Đã nạp chuỗi cần băm mẫu!');
    });
  }

  const jwtBtn = document.getElementById('btn-load-sample-jwt');
  if (jwtBtn) {
    jwtBtn.addEventListener('click', () => {
      const input = document.getElementById('jwt-token');
      if (input) {
        input.value = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6Ik5ndXllbiBNaW5oIEhvYW4iLCJlbWFpbCI6ImFkbWluQHRlY2hodWIubG9jYWwiLCJyb2xlIjoiYWRtaW4iLCJpYXQiOjE1MTYyMzkwMjIsImV4cCI6MTc5OTk5OTk5OX0.4zICZ7-dYx-4wQfC-vQ8zN0rK8W-uN5Z8_SAMPLE';
      }
      showToast(isEn ? 'Sample JWT Token loaded!' : 'Đã nạp JWT Token mẫu!');
    });
  }

  const regexBtn = document.getElementById('btn-load-sample-regex');
  if (regexBtn) {
    regexBtn.addEventListener('click', () => {
      const pattern = document.getElementById('regex-pattern');
      const testText = document.getElementById('regex-test-text');
      if (pattern) pattern.value = '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}';
      if (testText) testText.value = isEn ? 'Contact email list:\n1. support@techhub.vn\n2. dev-lead@company.com\n3. sales_team@startup.io' : 'Danh sách email liên hệ:\n1. support@techhub.vn\n2. dev-lead@company.com\n3. sales_team@startup.io';
      showToast(isEn ? 'Sample email extraction regex loaded!' : 'Đã nạp Regex trích xuất Email mẫu!');
    });
  }

  const urlBtn = document.getElementById('btn-load-sample-url');
  if (urlBtn) {
    urlBtn.addEventListener('click', () => {
      const input = document.getElementById('url-input');
      if (input) input.value = isEn ? 'https://techhub.vn/tools?category=developer&search=JSON Formatter & ddd=true#results' : 'https://techhub.vn/tools?category=developer&search=Định dạng JSON & ddd=true#results';
      showToast(isEn ? 'Sample URL loaded!' : 'Đã nạp URL mẫu!');
    });
  }

  // SEO Tool Samples
  const serpBtn = document.getElementById('btn-load-sample-serp');
  if (serpBtn) {
    serpBtn.addEventListener('click', () => {
      const title = document.getElementById('serp-title');
      const desc = document.getElementById('serp-desc');
      const url = document.getElementById('serp-url');
      if (title) title.value = isEn ? 'Comprehensive 2026 Onpage SEO Optimization Guide — TechHub' : 'Hướng Dẫn Tối Ưu SEO Onpage 2026 Toàn Diện — TechHub';
      if (desc) desc.value = isEn ? 'Discover the complete technical checklist for Google Onpage SEO: Meta tags, Schema JSON-LD structured data, XML Sitemaps, and core web vitals optimization.' : 'Khám phá trọn bộ kỹ thuật tối ưu SEO Onpage chuẩn Google: Tối ưu thẻ Meta, cấu trúc Schema JSON-LD, Sitemap XML và tối ưu tốc độ tải trang vượt trội.';
      if (url) url.value = isEn ? 'https://techhub.vn/articles/onpage-seo-guide' : 'https://techhub.vn/kien-thuc/toi-uu-seo-onpage';
      if (typeof updateSerpCounters === 'function') updateSerpCounters();
      showToast(isEn ? 'Sample SERP data loaded!' : 'Đã nạp dữ liệu SERP mẫu!');
    });
  }

  const metaBtn = document.getElementById('btn-load-sample-meta');
  if (metaBtn) {
    metaBtn.addEventListener('click', () => {
      const title = document.getElementById('meta-title');
      const desc = document.getElementById('meta-desc');
      const kw = document.getElementById('meta-keywords');
      const canonical = document.getElementById('meta-canonical');
      if (title) title.value = isEn ? 'TechHub — Developer Utilities & Online Productivity Suite' : 'TechHub — Nền Tảng Công Cụ Lập Trình & Tiện Ích Trực Tuyến';
      if (desc) desc.value = isEn ? 'Over 20+ free online developer & SEO tools: JSON Formatter, Regex, Base64, Schema Generator, SERP Preview with sub-5ms response time.' : 'Hơn 20+ công cụ trực tuyến miễn phí cho Developer & SEO: JSON Formatter, Regex, Base64, Schema Generator, SERP Preview với tốc độ dưới 5ms.';
      if (kw) kw.value = isEn ? 'developer tools, seo tools, json formatter, schema generator, techhub' : 'công cụ lập trình, seo tools, json formatter, schema generator, techhub';
      if (canonical) canonical.value = 'https://techhub.vn';
      showToast(isEn ? 'Sample Meta Tags data loaded!' : 'Đã nạp dữ liệu Meta Tags mẫu!');
    });
  }

  const schemaBtn = document.getElementById('btn-load-sample-schema');
  if (schemaBtn) {
    schemaBtn.addEventListener('click', () => {
      const type = document.getElementById('schema-type');
      if (type) type.value = 'Article';
      showToast(isEn ? 'Sample Schema configuration loaded!' : 'Đã nạp cấu hình Schema mẫu!');
    });
  }

  const ogBtn = document.getElementById('btn-load-sample-og');
  if (ogBtn) {
    ogBtn.addEventListener('click', () => {
      const title = document.getElementById('og-title');
      const desc = document.getElementById('og-desc');
      const img = document.getElementById('og-image');
      const url = document.getElementById('og-url');
      if (title) title.value = isEn ? 'TechHub — #1 Online Developer & SEO Tools Platform' : 'TechHub — Nền Tảng Công Cụ Lập Trình & SEO Trực Tuyến Số 1';
      if (desc) desc.value = isEn ? 'Experience 20+ ultrafast developer utilities, financial calculators, and onpage SEO optimization tools with zero data retention.' : 'Trải nghiệm hơn 20+ tiện ích lập trình, máy tính và công cụ tối ưu SEO Onpage tốc độ cực nhanh, bảo mật tuyệt đối không lưu dữ liệu.';
      if (img) img.value = 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200&h=630&fit=crop';
      if (url) url.value = 'https://techhub.vn';
      showToast(isEn ? 'Sample Open Graph data loaded!' : 'Đã nạp dữ liệu Open Graph mẫu!');
    });
  }

  const robotsBtn = document.getElementById('btn-load-sample-robots');
  if (robotsBtn) {
    robotsBtn.addEventListener('click', () => {
      const preset = document.getElementById('robots-preset');
      const sitemap = document.getElementById('robots-sitemap');
      if (preset) preset.value = 'default';
      if (sitemap) sitemap.value = 'https://techhub.vn/sitemap.xml';
      showToast(isEn ? 'Standard Robots.txt preset loaded!' : 'Đã nạp mẫu Robots.txt tiêu chuẩn!');
    });
  }

  const sitemapBtn = document.getElementById('btn-load-sample-sitemap');
  if (sitemapBtn) {
    sitemapBtn.addEventListener('click', () => {
      const base = document.getElementById('sitemap-base-url');
      if (base) base.value = 'https://techhub.vn';
      showToast(isEn ? 'Sample sitemap URL loaded!' : 'Đã nạp danh sách URL Sitemap mẫu!');
    });
  }

  const slugBtn = document.getElementById('btn-load-sample-slug');
  if (slugBtn) {
    slugBtn.addEventListener('click', () => {
      const text = document.getElementById('slug-text');
      if (text) text.value = isEn ? 'Comprehensive Guide to Onpage SEO Optimization for Websites in 2026!' : 'Hướng Dẫn Toàn Diện Về Cách Tối Ưu Hóa SEO Onpage Cho Website Năm 2026!';
      showToast(isEn ? 'Sample title for slug loaded!' : 'Đã nạp tiêu đề tạo Slug mẫu!');
    });
  }

  const proxyBtn = document.getElementById('btn-load-sample-proxy');
  if (proxyBtn) {
    proxyBtn.addEventListener('click', () => {
      const textarea = document.getElementById('proxy-list');
      if (textarea) {
        textarea.value = `103.152.112.4:8080\n185.199.229.156:8080\nsocks5://178.62.193.19:1080\n192.241.168.188:3128:testuser:testpass`;
        showToast(isEn ? 'Sample proxy list loaded!' : 'Đã nạp danh sách Proxy mẫu!');
      }
    });
  }
}

// 9. Interactive Tool Execution Handler & Rich UI Renderer
function initToolForm() {
  const toolForm = document.getElementById('tool-execution-form');
  if (!toolForm) return;

  toolForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const isEn = (window.__locale === 'en');
    const submitBtn = toolForm.querySelector('button[type="submit"]');
    const slug = toolForm.getAttribute('data-tool-slug');
    const resultBox = document.getElementById('tool-result-output');
    const richOutputBox = document.getElementById('tool-rich-output');
    const executionTimeEl = document.getElementById('tool-execution-time');

    if (!slug) return;

    const formData = new FormData(toolForm);
    const inputPayload = {};

    formData.forEach((value, key) => {
      if (key !== '_token') {
        inputPayload[key] = value;
      }
    });

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.dataset.origHtml = submitBtn.innerHTML;
      submitBtn.innerHTML = `<span class="spinner"></span> ${isEn ? 'Processing...' : 'Đang xử lý...'}`;
    }

    try {
      const response = await fetch(`/api/tools/${slug}/execute`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-Locale': (window.__locale || 'vi'),
        },
        body: JSON.stringify({ input: inputPayload }),
      });

      const resData = await response.json();

      if (response.ok && resData.success) {
        showToast(isEn ? `Executed successfully in ${resData.data.execution_time_ms} ms!` : `Thực thi thành công trong ${resData.data.execution_time_ms} ms!`, 'success');

        if (executionTimeEl) {
          executionTimeEl.innerText = `${resData.data.execution_time_ms} ms`;
        }

        // Render dedicated interactive visualization
        renderRichOutput(slug, resData.data.result_data, richOutputBox, resultBox);

      } else {
        const defaultErr = isEn ? 'An error occurred during processing.' : 'Lỗi trong quá trình xử lý.';
        const errorMsg = resData.message || (resData.errors ? Object.values(resData.errors).flat().join(', ') : defaultErr);
        showToast(errorMsg, 'error');
        if (resultBox) {
          resultBox.value = `[${isEn ? 'Error' : 'Lỗi'}] ${errorMsg}`;
        }
      }
    } catch (err) {
      showToast((isEn ? 'Network error: ' : 'Lỗi kết nối mạng: ') + err.message, 'error');
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = submitBtn.dataset.origHtml || (isEn ? 'Execute' : 'Thực Thi');
      }
    }
  });
}

// 10. Senior-Grade Visual Output Renderers for ALL Tools
function renderRichOutput(slug, data, richBox, rawTextarea) {
  if (!richBox) return;

  const isEn = (window.__locale === 'en');

  // Always update raw textarea fallback
  if (rawTextarea) {
    if (typeof data.result === 'string') {
      rawTextarea.value = data.result;
    } else {
      rawTextarea.value = JSON.stringify(data, null, 2);
    }
  }

  // 1. Image Color Palette Extractor
  if (slug === 'image-color-extractor' && data.palette) {
    richBox.style.display = 'block';
    let html = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🎨 ${isEn ? `Extracted Color Palette (${data.palette_count} colors)` : `Bảng Mã Màu Trích Xuất (${data.palette_count} màu)`}</span>
          <span class="badge badge-emerald">${isEn ? 'High accuracy' : 'Độ chính xác cao'}</span>
        </div>
        <div class="color-palette-grid">
    `;

    data.palette.forEach((c) => {
      const textColor = c.is_dark ? '#ffffff' : '#0f172a';
      const toastMsg = isEn ? `Copied color code ${c.hex}!` : `Đã chép mã màu ${c.hex}!`;
      html += `
        <div class="color-card" onclick="navigator.clipboard.writeText('${c.hex}'); showToast('${toastMsg}');">
          <div class="color-swatch-box" style="background: ${c.hex}; color: ${textColor};">
            ${c.hex}
          </div>
          <div class="color-card-info">
            <span style="color: var(--text-main); font-weight: 700;">${c.hex}</span>
            <span style="color: var(--text-muted); font-size: 0.72rem;">${c.rgb_string}</span>
            <span style="color: var(--text-muted); font-size: 0.72rem;">HSL: ${c.hsl.h}°, ${c.hsl.s}, ${c.hsl.l}</span>
          </div>
        </div>
      `;
    });

    html += `
        </div>
        <small style="color: var(--text-muted); display: block; margin-top: 1rem; text-align: center;">
          💡 ${isEn ? 'Click any color card to copy HEX code to clipboard.' : 'Nhấn trực tiếp vào ô màu để sao chép mã HEX vào bộ nhớ tạm.'}
        </small>
      </div>
    `;
    richBox.innerHTML = html;
    return;
  }

  // 2. Image Metadata & EXIF Inspector
  if (slug === 'image-metadata-inspector') {
    richBox.style.display = 'block';
    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>📊 ${isEn ? 'Image Technical Specifications' : 'Thông Số Kỹ Thuật Hình Ảnh'}</span>
          <span class="badge badge-emerald">${isEn ? 'Analysis complete' : 'Phân tích hoàn tất'}</span>
        </div>
        <div class="kpi-metric-cards">
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">${isEn ? 'Dimensions (Pixels)' : 'Kích thước (Pixels)'}</span>
            <span class="kpi-metric-val" style="color: var(--accent-cyan);">${data.width_px} × ${data.height_px} px</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">${isEn ? 'Aspect Ratio' : 'Tỷ lệ khung hình'}</span>
            <span class="kpi-metric-val" style="color: var(--accent-indigo);">${data.aspect_ratio}</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">${isEn ? 'File Size' : 'Dung lượng tệp'}</span>
            <span class="kpi-metric-val" style="color: var(--accent-emerald);">${data.size_kb} KB (${data.size_mb} MB)</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">${isEn ? 'Format & Color Depth' : 'Định dạng & Độ sâu'}</span>
            <span class="kpi-metric-val" style="color: var(--accent-amber); font-size: 1.15rem;">${data.mime_type} (${data.color_depth_bits}-bit)</span>
          </div>
        </div>
      </div>
    `;
    return;
  }

  // 3. Loan & Mortgage Calculator
  if (slug === 'loan-calculator' && data.monthly_payment) {
    richBox.style.display = 'block';
    const formatter = new Intl.NumberFormat(isEn ? 'en-US' : 'vi-VN');
    const currencySuffix = isEn ? ' USD' : ' đ';
    const currencyPrefix = isEn ? '$' : '';
    let scheduleRows = '';

    if (data.amortization_preview && data.amortization_preview.length > 0) {
      data.amortization_preview.forEach((row) => {
        const monthLabel = isEn ? `Month ${row.month}` : `Tháng ${row.month}`;
        scheduleRows += `
          <tr>
            <td style="font-weight: 700;">${monthLabel}</td>
            <td style="color: var(--accent-cyan); font-family: var(--font-mono);">${currencyPrefix}${formatter.format(row.payment)}${currencySuffix}</td>
            <td style="color: var(--accent-emerald); font-family: var(--font-mono);">${currencyPrefix}${formatter.format(row.principal_paid)}${currencySuffix}</td>
            <td style="color: var(--accent-rose); font-family: var(--font-mono);">${currencyPrefix}${formatter.format(row.interest_paid)}${currencySuffix}</td>
            <td style="font-family: var(--font-mono); font-weight: 600;">${currencyPrefix}${formatter.format(row.remaining_balance)}${currencySuffix}</td>
          </tr>
        `;
      });
    }

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>💰 ${isEn ? 'Loan Amortization Schedule & Interest' : 'Bảng Phân Bổ Khoản Vay & Lãi Suất'}</span>
          <span class="badge badge-emerald">${isEn ? 'Declining balance' : 'Dư nợ giảm dần'}</span>
        </div>
        <div class="kpi-metric-cards">
          <div class="kpi-metric-item" style="border-left: 4px solid var(--accent-indigo);">
            <span class="kpi-metric-label">${isEn ? 'Monthly Payment (EMI)' : 'Số tiền trả hàng tháng (EMI)'}</span>
            <span class="kpi-metric-val" style="color: var(--accent-indigo);">${currencyPrefix}${formatter.format(data.monthly_payment)}${currencySuffix}</span>
          </div>
          <div class="kpi-metric-item" style="border-left: 4px solid var(--accent-rose);">
            <span class="kpi-metric-label">${isEn ? 'Total Interest' : 'Tổng tiền lãi phải trả'}</span>
            <span class="kpi-metric-val" style="color: var(--accent-rose);">${currencyPrefix}${formatter.format(data.total_interest)}${currencySuffix}</span>
          </div>
          <div class="kpi-metric-item" style="border-left: 4px solid var(--accent-emerald);">
            <span class="kpi-metric-label">${isEn ? 'Total Payment' : 'Tổng số tiền tất toán'}</span>
            <span class="kpi-metric-val" style="color: var(--accent-emerald);">${currencyPrefix}${formatter.format(data.total_payment)}${currencySuffix}</span>
          </div>
        </div>

        <div style="margin-top: 1.5rem;">
          <h4 style="font-size: 1rem; margin-bottom: 0.75rem; color: var(--text-main);">📅 ${isEn ? 'Detailed Amortization Schedule' : 'Lịch Trình Trả Nợ Chi Tiết (Amortization Preview)'}</h4>
          <div class="admin-table-wrap" style="max-height: 260px; overflow-y: auto;">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>${isEn ? 'Period' : 'Kỳ Trả'}</th>
                  <th>${isEn ? 'Payment' : 'Số Tiền Trả'}</th>
                  <th>${isEn ? 'Principal' : 'Tiền Gốc'}</th>
                  <th>${isEn ? 'Interest' : 'Tiền Lãi'}</th>
                  <th>${isEn ? 'Remaining Balance' : 'Dư Nợ Còn Lại'}</th>
                </tr>
              </thead>
              <tbody>
                ${scheduleRows}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    `;
    return;
  }

  // 4. Percentage Calculator
  if (slug === 'percentage-calculator' && data.formatted_description) {
    richBox.style.display = 'block';
    richBox.innerHTML = `
      <div class="rich-output-card" style="border-left: 5px solid var(--accent-indigo);">
        <div class="rich-output-title">
          <span>⚡ ${isEn ? 'Percentage Calculation Result' : 'Kết Quả Tính Phần Trăm'}</span>
          <span class="badge badge-emerald">${isEn ? 'Exact' : 'Chính xác'}</span>
        </div>
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--accent-indigo); font-family: var(--font-mono); margin-bottom: 0.5rem;">
          ${data.result}
        </div>
        <div style="font-size: 1rem; color: var(--text-sub); font-weight: 500;">
          💡 ${data.formatted_description}
        </div>
      </div>
    `;
    return;
  }

  // 5. BMI Calculator
  if (slug === 'bmi-calculator' && data.bmi_score) {
    richBox.style.display = 'block';
    let badgeClass = 'badge-emerald';
    if (data.bmi_score < 18.5) badgeClass = 'badge-cyan';
    else if (data.bmi_score > 25) badgeClass = 'badge-danger';

    const catDisplay = isEn ? data.category : (data.category_vi || data.category);

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🏃 ${isEn ? 'Health & BMI Assessment' : 'Đánh Giá Thể Trạng Sức Khỏe (BMI)'}</span>
          <span class="badge ${badgeClass}">${catDisplay}</span>
        </div>
        <div class="kpi-metric-cards">
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">${isEn ? 'BMI Score' : 'Điểm số BMI'}</span>
            <span class="kpi-metric-val" style="color: var(--accent-indigo); font-size: 2.2rem;">${data.bmi_score}</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">${isEn ? 'Classification' : 'Phân loại thể trạng'}</span>
            <span class="kpi-metric-val" style="font-size: 1.25rem;">${catDisplay}</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">${isEn ? 'Ideal Healthy Weight' : 'Cân nặng chuẩn lý tưởng'}</span>
            <span class="kpi-metric-val" style="color: var(--accent-emerald); font-size: 1.35rem;">${data.healthy_weight_range.min_kg} - ${data.healthy_weight_range.max_kg} kg</span>
          </div>
        </div>
        <p style="font-size: 0.88rem; color: var(--text-sub); margin-top: 0.5rem;">
          🩺 <strong>${isEn ? 'Recommendation:' : 'Khuyến cáo:'}</strong> ${data.health_risk}
        </p>
      </div>
    `;
    return;
  }

  // 6. JWT Debugger
  if (slug === 'jwt-debugger' && data.header && data.payload) {
    richBox.style.display = 'block';
    const isExpired = data.is_expired;
    const expBadge = isExpired === true
      ? (isEn ? '<span class="badge badge-danger">● Expired</span>' : '<span class="badge badge-danger">● Đã hết hạn (Expired)</span>')
      : (isEn ? '<span class="badge badge-emerald">● Valid</span>' : '<span class="badge badge-emerald">● Còn hiệu lực (Valid)</span>');

    const expText = data.expires_at || (isEn ? 'No expiration' : 'Không giới hạn');

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🔍 ${isEn ? `JWT Token Structure Analysis (Algorithm: ${data.algorithm})` : `Phân Tích Cấu Trúc JWT Token (Thuật toán: ${data.algorithm})`}</span>
          ${expBadge}
        </div>
        <div class="jwt-inspector-grid">
          <div class="jwt-block jwt-block-header">
            <div class="jwt-bar">
              <span>HEADER: Algorithm &amp; Token Type</span>
              <code>base64Url</code>
            </div>
            <div class="jwt-content">${JSON.stringify(data.header, null, 2)}</div>
          </div>

          <div class="jwt-block jwt-block-payload">
            <div class="jwt-bar">
              <span>PAYLOAD: Data Claims</span>
              <span>${isEn ? 'Expires' : 'Hết hạn'}: ${expText}</span>
            </div>
            <div class="jwt-content">${JSON.stringify(data.payload, null, 2)}</div>
          </div>

          <div class="jwt-block jwt-block-signature">
            <div class="jwt-bar">
              <span>${isEn ? 'SIGNATURE HASH: Signature verification' : 'SIGNATURE HASH: Xác thực chữ ký'}</span>
              <code>${data.algorithm}</code>
            </div>
            <div class="jwt-content">${data.signature_hash}</div>
          </div>
        </div>
      </div>
    `;
    return;
  }

  // 7. Hash Generator (All Hashes Breakdown)
  if (slug === 'hash-generator' && data.hashes) {
    richBox.style.display = 'block';
    let html = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🔒 ${isEn ? 'Cryptographic Hashes Breakdown' : 'Danh Sách Mã Băm Cryptographic'}</span>
          <span class="badge badge-emerald">${isEn ? `${Object.keys(data.hashes).length} algorithms` : `${Object.keys(data.hashes).length} thuật toán`}</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.85rem;">
    `;

    for (const [alg, hashVal] of Object.entries(data.hashes)) {
      const copyToast = isEn ? `Copied ${alg.toUpperCase()} hash!` : `Đã sao chép mã ${alg.toUpperCase()}!`;
      html += `
        <div style="background: var(--bg-surface-elevated); padding: 0.85rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
          <div style="flex: 1; min-width: 200px;">
            <strong style="text-transform: uppercase; font-size: 0.82rem; color: var(--accent-indigo); display: block; margin-bottom: 0.2rem;">${alg}</strong>
            <code style="font-family: var(--font-mono); font-size: 0.85rem; word-break: break-all; color: var(--text-main);">${hashVal}</code>
          </div>
          <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${hashVal}'); showToast('${copyToast}');">📋 ${isEn ? 'Copy' : 'Chép'}</button>
        </div>
      `;
    }

    html += `
        </div>
      </div>
    `;
    richBox.innerHTML = html;
    return;
  }

  // 8. Regex Tester
  if (slug === 'regex-tester' && data.matches !== undefined) {
    richBox.style.display = 'block';
    const isMatch = data.is_match;
    const matchCount = data.total_matches;

    let matchesHtml = '';
    if (matchCount > 0 && data.matches) {
      data.matches.forEach((m) => {
        matchesHtml += `
          <div style="background: var(--bg-surface-elevated); padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); margin-bottom: 0.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
              <strong style="color: var(--accent-emerald);">${isEn ? `Match #${m.match_number}:` : `Khớp #${m.match_number}:`}</strong>
              <span style="font-size: 0.78rem; color: var(--text-muted);">${isEn ? `Offset index: ${m.offset}` : `Vị trí offset: ${m.offset}`}</span>
            </div>
            <code style="font-family: var(--font-mono); font-size: 0.95rem; color: var(--text-main); background: #ffffff; padding: 0.25rem 0.5rem; border-radius: var(--radius-xs); display: block;">${m.full_match}</code>
          </div>
        `;
      });
    }

    const noMatchesText = isEn ? 'No matches found for the regex pattern in the test string.' : 'Không tìm thấy kết quả nào khớp với biểu thức regex trong đoạn văn bản.';

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>⚡ ${isEn ? `Regex Matches: <code>${data.pattern_used}</code>` : `Kết Quả Khớp Regex: <code>${data.pattern_used}</code>`}</span>
          <span class="badge ${isMatch ? 'badge-emerald' : 'badge-danger'}">${isEn ? `${matchCount} matches found` : `${matchCount} kết quả khớp`}</span>
        </div>
        ${matchCount > 0 ? matchesHtml : `<p style="color: var(--text-muted);">${noMatchesText}</p>`}
      </div>
    `;
    return;
  }

  // 9. URL Encoder & Decoder
  if (slug === 'url-encoder-decoder' && data.result) {
    richBox.style.display = 'block';
    let parsedHtml = '';
    if (data.parsed_url) {
      parsedHtml = `
        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-subtle);">
          <strong style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">${isEn ? 'Parsed URL Structure:' : 'Cấu Trúc URL Phân Tích:'}</strong>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
            ${data.parsed_url.scheme ? `<div class="kpi-metric-item"><span class="kpi-metric-label">${isEn ? 'Protocol' : 'Giao thức'}</span><strong style="color:var(--accent-indigo);">${data.parsed_url.scheme}://</strong></div>` : ''}
            ${data.parsed_url.host ? `<div class="kpi-metric-item"><span class="kpi-metric-label">${isEn ? 'Host / Domain' : 'Host / Domain'}</span><strong style="color:var(--text-main);">${data.parsed_url.host}</strong></div>` : ''}
            ${data.parsed_url.path ? `<div class="kpi-metric-item"><span class="kpi-metric-label">${isEn ? 'Path' : 'Path'}</span><strong style="color:var(--accent-cyan);">${data.parsed_url.path}</strong></div>` : ''}
            ${data.parsed_url.query ? `<div class="kpi-metric-item"><span class="kpi-metric-label">${isEn ? 'Query' : 'Query'}</span><code style="font-size:0.8rem;">?${data.parsed_url.query}</code></div>` : ''}
          </div>
        </div>
      `;
    }

    const copyToast = isEn ? 'Copied result!' : 'Đã sao chép kết quả!';
    const actionLabel = data.action === 'encode' ? (isEn ? 'Encoded' : 'Mã Hóa') : (isEn ? 'Decoded' : 'Giải Mã');

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🔗 ${isEn ? `URL ${actionLabel} Result` : `Kết Quả ${actionLabel} URL`}</span>
          <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${data.result}'); showToast('${copyToast}');">📋 ${isEn ? 'Copy' : 'Chép'}</button>
        </div>
        <textarea class="form-control code-output" readonly style="min-height: 90px; margin-bottom: 0.5rem;">${data.result}</textarea>
        ${parsedHtml}
      </div>
    `;
    return;
  }

  // 10. Google SERP Snippet Preview
  if (slug === 'serp-preview' && data.preview) {
    richBox.style.display = 'block';
    const p = data.preview;
    const m = data.metrics;
    const isMobile = p.device === 'mobile';

    let starsHtml = '';
    if (p.rating_value) {
      const stars = '★'.repeat(Math.round(p.rating_value)) + '☆'.repeat(5 - Math.round(p.rating_value));
      starsHtml = `
        <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.85rem; margin-top: 0.25rem; color: #70757a;">
          <span style="color: #fbbc04; font-size: 0.95rem;">${stars}</span>
          <span style="font-weight: 600; color: #3c4043;">${isEn ? `Rating: ${p.rating_value}/5` : `Đánh giá: ${p.rating_value}/5`}</span>
          ${p.rating_count ? `<span>(${p.rating_count} ${isEn ? 'votes' : 'bình chọn'})</span>` : ''}
        </div>
      `;
    }

    const titleCharsLabel = isEn ? `${m.title.char_count} chars (~${m.title.pixel_est}px)` : `${m.title.char_count} ký tự (~${m.title.pixel_est}px)`;
    const descCharsLabel = isEn ? `${m.description.char_count} chars (~${m.description.pixel_est}px)` : `${m.description.char_count} ký tự (~${m.description.pixel_est}px)`;

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🔍 ${isEn ? `Google Search Result Preview (${isMobile ? 'Mobile' : 'Desktop'})` : `Mô Phỏng Kết Quả Tìm Kiếm Google (${isMobile ? 'Mobile' : 'Desktop'})`}</span>
          <span class="badge ${m.seo_score >= 80 ? 'badge-emerald' : 'badge-amber'}">${isEn ? `SEO Score: ${m.seo_score}/100` : `Điểm SEO: ${m.seo_score}/100`}</span>
        </div>

        <div class="serp-preview-box ${isMobile ? 'serp-mobile' : 'serp-desktop'}" style="background: #ffffff; padding: 1.25rem 1.5rem; border-radius: 12px; border: 1px solid #dfe1e5; box-shadow: 0 1px 6px rgba(32,33,36,0.08); font-family: Arial, sans-serif; margin-bottom: 1.5rem;">
          <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.35rem;">
            <div style="width: 24px; height: 24px; border-radius: 50%; background: #f1f3f4; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: #1a73e8;">
              G
            </div>
            <div style="display: flex; flex-direction: column; line-height: 1.2;">
              <span style="font-size: 0.85rem; color: #202124; font-weight: 500;">${p.breadcrumb.split(' › ')[0]}</span>
              <span style="font-size: 0.75rem; color: #4d5156; word-break: break-all;">${p.display_url}</span>
            </div>
          </div>

          <h3 style="font-size: ${isMobile ? '1.15rem' : '1.25rem'}; line-height: 1.35; margin: 0.35rem 0 0.25rem; font-weight: 400;">
            <a href="javascript:void(0)" style="color: #1a0dab; text-decoration: none; display: inline-block;">
              ${p.display_title}
            </a>
          </h3>

          ${starsHtml}

          <p style="font-size: 0.88rem; line-height: 1.55; color: #4d5156; margin: 0.35rem 0 0;">
            ${p.date ? `<span style="color: #70757a; font-weight: 500;">${p.date} — </span>` : ''}${p.display_description}
          </p>
        </div>

        <div class="kpi-metric-cards">
          <div class="kpi-metric-item" style="border-left: 4px solid ${m.title.status === 'optimal' ? 'var(--accent-emerald)' : 'var(--accent-amber)'};">
            <span class="kpi-metric-label">${isEn ? 'Title Length' : 'Độ dài Tiêu đề (Title)'}</span>
            <span class="kpi-metric-val" style="font-size: 1.4rem;">${titleCharsLabel}</span>
            <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 0.25rem;">${m.title.message}</small>
          </div>
          <div class="kpi-metric-item" style="border-left: 4px solid ${m.description.status === 'optimal' ? 'var(--accent-emerald)' : 'var(--accent-amber)'};">
            <span class="kpi-metric-label">${isEn ? 'Description Length' : 'Độ dài Mô tả (Description)'}</span>
            <span class="kpi-metric-val" style="font-size: 1.4rem;">${descCharsLabel}</span>
            <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 0.25rem;">${m.description.message}</small>
          </div>
        </div>
      </div>
    `;
    return;
  }

  // 11. SEO Meta Tag Generator
  if (slug === 'meta-tag-generator' && data.meta_html) {
    richBox.style.display = 'block';
    const audit = data.audit || {};
    const titleOptBadge = audit.title_length_status === 'good' ? (isEn ? '<span style="color:var(--accent-emerald);">Optimal</span>' : '<span style="color:var(--accent-emerald);">Chuẩn</span>') : (isEn ? '<span style="color:var(--accent-amber);">Needs optimization</span>' : '<span style="color:var(--accent-amber);">Cần tối ưu</span>');
    const descOptBadge = audit.description_length_status === 'good' ? (isEn ? '<span style="color:var(--accent-emerald);">Optimal</span>' : '<span style="color:var(--accent-emerald);">Chuẩn</span>') : (isEn ? '<span style="color:var(--accent-amber);">Needs optimization</span>' : '<span style="color:var(--accent-amber);">Cần tối ưu</span>');

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🏷️ ${isEn ? `HTML5 Meta Tags Source Code (${data.tag_count} tags generated)` : `Mã Nguồn Thẻ Meta HTML5 (${data.tag_count} thẻ đã sinh)`}</span>
          <div style="display: flex; gap: 0.5rem;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('meta-raw-box').value); showToast('${isEn ? 'Copied all meta tags!' : 'Đã chép toàn bộ thẻ Meta!'}');">📋 ${isEn ? 'Copy All' : 'Chép Toàn Bộ'}</button>
          </div>
        </div>

        <textarea id="meta-raw-box" class="form-control code-output" readonly style="min-height: 180px; margin-bottom: 1.25rem; font-family: var(--font-mono); font-size: 0.85rem;">${data.meta_html}</textarea>

        <div style="background: var(--bg-surface-elevated); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
          <strong style="font-size: 0.9rem; color: var(--text-main); display: block; margin-bottom: 0.75rem;">📋 ${isEn ? 'Onpage SEO Audit Checklist:' : 'Đánh Giá Kiểm Tra SEO Onpage:'}</strong>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.65rem; font-size: 0.85rem;">
            <div>${audit.has_title ? '✅' : '❌'} ${isEn ? 'Title' : 'Tiêu đề'}: <strong>${audit.title_length || 0} ${isEn ? 'chars' : 'ký tự'}</strong> (${titleOptBadge})</div>
            <div>${audit.has_description ? '✅' : '❌'} ${isEn ? 'Description' : 'Mô tả'}: <strong>${audit.description_length || 0} ${isEn ? 'chars' : 'ký tự'}</strong> (${descOptBadge})</div>
            <div>${audit.has_canonical ? '✅' : '⚠️'} Canonical: <strong>${audit.has_canonical ? (isEn ? 'Configured' : 'Đã thiết lập') : (isEn ? 'Missing canonical' : 'Thiếu canonical')}</strong></div>
            <div>${audit.is_indexable ? '✅' : '🚫'} ${isEn ? 'Indexing' : 'Chỉ mục'}: <strong>${audit.is_indexable ? (isEn ? 'Index Allowed' : 'Cho phép Index') : (isEn ? 'Noindex Blocked' : 'Chặn Noindex')}</strong></div>
          </div>
        </div>
      </div>
    `;
    return;
  }

  // 12. Schema.org JSON-LD Generator
  if (slug === 'schema-generator' && data.json_ld) {
    richBox.style.display = 'block';
    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🧩 ${isEn ? `Schema JSON-LD Structured Data (${data.schema_type})` : `Dữ Liệu Cấu Trúc Schema JSON-LD (${data.schema_type})`}</span>
          <div style="display: flex; gap: 0.5rem;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="downloadFile('schema-${data.schema_type.toLowerCase()}.json', document.getElementById('schema-json-box').value, 'application/json');">💾 ${isEn ? 'Download JSON' : 'Tải JSON'}</button>
            <button type="button" class="btn btn-primary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('schema-script-box').value); showToast('${isEn ? 'Copied JSON-LD Script Tag!' : 'Đã chép mã Script JSON-LD!'}');">📋 ${isEn ? 'Copy Script Tag' : 'Chép Script Tag'}</button>
          </div>
        </div>

        <textarea id="schema-script-box" class="form-control code-output" readonly style="min-height: 220px; font-family: var(--font-mono); font-size: 0.85rem; margin-bottom: 1rem;">${data.result}</textarea>
        <textarea id="schema-json-box" style="display:none;">${data.json_ld}</textarea>

        <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.75rem 1rem; border-radius: var(--radius-md); font-size: 0.85rem; color: #34d399;">
          <span>✓ ${isEn ? 'Valid JSON-LD syntax, ready to paste directly into your webpage <head> tag.' : 'Cú pháp JSON-LD hợp lệ, sẵn sàng dán trực tiếp vào thẻ <head> của trang web.'}</span>
          <a href="https://validator.schema.org/" target="_blank" rel="noopener noreferrer" style="color: var(--accent-cyan); font-weight: 600; text-decoration: underline;">${isEn ? 'Validate with Schema.org ↗' : 'Kiểm tra với Schema.org ↗'}</a>
        </div>
      </div>
    `;
    return;
  }

  // 13. Open Graph & Twitter Cards Generator
  if (slug === 'open-graph-generator' && data.preview) {
    richBox.style.display = 'block';
    const p = data.preview;
    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>📱 ${isEn ? 'Social Cards Live Preview (Facebook & Twitter/X)' : 'Xem Trước Giao Diện Social Cards (Facebook &amp; Twitter)'}</span>
          <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('og-raw-box').value); showToast('${isEn ? 'Copied all Open Graph meta tags!' : 'Đã chép toàn bộ thẻ Open Graph!'}');">📋 ${isEn ? 'Copy Meta Tags' : 'Chép Mã Meta'}</button>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
          
          {{-- Facebook Feed Card Preview --}}
          <div>
            <strong style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem; text-transform: uppercase;">Facebook Feed Preview:</strong>
            <div style="background: #ffffff; border: 1px solid #dadde1; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.06); font-family: Helvetica, Arial, sans-serif;">
              <div style="height: 180px; background-image: url('${p.image_url}'); background-size: cover; background-position: center; background-color: #f0f2f5;"></div>
              <div style="padding: 0.75rem 1rem; background: #f0f2f5; border-top: 1px solid #dadde1;">
                <span style="font-size: 0.75rem; text-transform: uppercase; color: #606770; display: block; font-weight: 500;">${p.domain}</span>
                <strong style="font-size: 0.98rem; color: #1d2129; line-height: 1.3; display: block; margin-top: 0.2rem;">${p.title}</strong>
                <p style="font-size: 0.82rem; color: #606770; line-height: 1.4; margin: 0.25rem 0 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${p.description}</p>
              </div>
            </div>
          </div>

          {{-- Twitter Large Summary Card Preview --}}
          <div>
            <strong style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem; text-transform: uppercase;">Twitter / X Card Preview:</strong>
            <div style="background: #000000; border: 1px solid #2f3336; border-radius: 16px; overflow: hidden; color: #e7e9ea; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
              <div style="height: 180px; background-image: url('${p.image_url}'); background-size: cover; background-position: center; background-color: #16181c;"></div>
              <div style="padding: 0.75rem 1rem; background: #000000;">
                <span style="font-size: 0.8rem; color: #71767b; display: block;">${p.domain}</span>
                <strong style="font-size: 0.95rem; color: #e7e9ea; line-height: 1.3; display: block; margin-top: 0.2rem;">${p.title}</strong>
                <p style="font-size: 0.82rem; color: #71767b; line-height: 1.4; margin: 0.25rem 0 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${p.description}</p>
              </div>
            </div>
          </div>

        </div>

        <textarea id="og-raw-box" class="form-control code-output" readonly style="min-height: 140px; font-family: var(--font-mono); font-size: 0.82rem;">${data.meta_html}</textarea>
      </div>
    `;
    return;
  }

  // 14. Robots.txt Generator & Validator
  if (slug === 'robots-txt-generator' && data.robots_txt) {
    richBox.style.display = 'block';
    let warnHtml = '';
    if (data.warnings && data.warnings.length > 0) {
      data.warnings.forEach((w) => {
        warnHtml += `<div style="color: #fcd34d; margin-top: 0.35rem; font-size: 0.85rem;">⚠️ ${w}</div>`;
      });
    }

    const aiBotsStatus = data.ai_bots_blocked ? (isEn ? 'Enabled' : 'Đã kích hoạt') : (isEn ? 'Disabled' : 'Chưa bật');
    const sitemapStatus = data.has_sitemap ? (isEn ? 'Declared' : 'Đã khai báo') : (isEn ? 'None' : 'Chưa có');

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🤖 ${isEn ? `Robots.txt Configuration File (${data.line_count} lines)` : `Tệp Cấu Hình Robots.txt (${data.line_count} dòng)`}</span>
          <div style="display: flex; gap: 0.5rem;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="downloadFile('robots.txt', document.getElementById('robots-raw-box').value);">💾 ${isEn ? 'Download robots.txt' : 'Tải Tệp robots.txt'}</button>
            <button type="button" class="btn btn-primary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('robots-raw-box').value); showToast('${isEn ? 'Copied robots.txt content!' : 'Đã chép nội dung robots.txt!'}');">📋 ${isEn ? 'Copy Content' : 'Chép Nội Dung'}</button>
          </div>
        </div>

        <textarea id="robots-raw-box" class="form-control code-output" readonly style="min-height: 180px; font-family: var(--font-mono); font-size: 0.88rem; margin-bottom: 1rem;">${data.robots_txt}</textarea>

        <div style="background: var(--bg-surface-elevated); padding: 0.85rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); font-size: 0.85rem;">
          <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
            <span>🛡️ ${isEn ? 'Block AI Bots' : 'Chặn AI Bots'}: <strong>${aiBotsStatus}</strong></span>
            <span>🗺️ ${isEn ? 'XML Sitemap' : 'Sitemap XML'}: <strong>${sitemapStatus}</strong></span>
          </div>
          ${warnHtml}
        </div>
      </div>
    `;
    return;
  }

  // 15. XML Sitemap Generator & Validator (XML-Sitemaps Pro Output)
  if (slug === 'sitemap-generator' && data.xml_sitemap) {
    richBox.style.display = 'block';
    const entries = data.entries_preview || [];
    let rowsHtml = '';
    entries.forEach((e, idx) => {
      rowsHtml += `
        <tr style="border-bottom: 1px solid var(--border-subtle); font-size: 0.85rem;">
          <td style="padding: 0.6rem 0.75rem; color: var(--text-muted);">${idx + 1}</td>
          <td style="padding: 0.6rem 0.75rem; word-break: break-all;">
            <a href="${e.loc}" target="_blank" style="color: var(--accent-cyan); text-decoration: none; font-weight: 500;">
              ${e.loc} ↗
            </a>
          </td>
          <td style="padding: 0.6rem 0.75rem;">
            <span style="background: rgba(37,99,235,0.15); color: #60a5fa; font-weight: 700; padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.78rem;">
              ${e.priority}
            </span>
          </td>
          <td style="padding: 0.6rem 0.75rem; color: var(--text-sub);">${e.changefreq}</td>
          <td style="padding: 0.6rem 0.75rem; color: var(--text-muted); font-size: 0.8rem;">${e.lastmod || (isEn ? 'Today' : 'Hôm nay')}</td>
        </tr>
      `;
    });

    const readyTitle = isEn ? '🎉 XML Sitemap Is Ready!' : '🎉 Sơ Đồ XML Sitemap Đã Sẵn Sàng!';
    const readySubtitle = isEn 
      ? `Crawled <strong>${data.urls_count} pages</strong> • Size: <strong>${data.size_formatted}</strong> • Compliant with Sitemaps.org Protocol 0.9` 
      : `Đã thu thập <strong>${data.urls_count} trang</strong> • Dung lượng: <strong>${data.size_formatted}</strong> • Chuẩn Sitemaps.org Protocol 0.9`;

    richBox.innerHTML = `
      <div class="rich-output-card" style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: 0 8px 24px rgba(0,0,0,0.05);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-subtle);">
          <div>
            <div style="font-size: 1.2rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem;">
              <span>${readyTitle}</span>
            </div>
            <div style="font-size: 0.88rem; color: var(--text-muted); margin-top: 0.25rem;">
              ${readySubtitle}
            </div>
          </div>

          <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
            <button type="button" class="btn btn-primary" onclick="downloadFile('sitemap.xml', document.getElementById('sitemap-raw-box').value, 'application/xml');" style="display: flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.15rem; font-weight: 700;">
              <span>📥</span> <span>${isEn ? 'Download sitemap.xml' : 'Tải sitemap.xml'}</span>
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('sitemap-raw-box').value); showToast('${isEn ? 'Copied all XML Sitemap code!' : 'Đã sao chép toàn bộ mã XML Sitemap!'}');">
              📋 ${isEn ? 'Copy XML' : 'Sao Chép XML'}
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="const b = document.getElementById('sitemap-raw-box'); b.style.display = b.style.display === 'none' ? 'block' : 'none';">
              👁️ ${isEn ? 'View XML Code' : 'Xem Mã XML'}
            </button>
          </div>
        </div>

        <textarea id="sitemap-raw-box" class="form-control code-output" readonly style="display: none; min-height: 200px; font-family: var(--font-mono); font-size: 0.82rem; margin-bottom: 1.25rem;">${data.xml_sitemap}</textarea>

        {{-- Crawled URLs Table --}}
        <div style="border: 1px solid var(--border-subtle); border-radius: var(--radius-md); overflow: hidden; max-height: 320px; overflow-y: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: var(--bg-input); position: sticky; top: 0; z-index: 2;">
              <tr style="border-bottom: 1px solid var(--border-subtle); font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted);">
                <th style="padding: 0.6rem 0.75rem; width: 40px;">#</th>
                <th style="padding: 0.6rem 0.75rem;">${isEn ? 'URL Path' : 'Đường Dẫn URL'}</th>
                <th style="padding: 0.6rem 0.75rem; width: 90px;">${isEn ? 'Priority' : 'Độ Ưu Tiên'}</th>
                <th style="padding: 0.6rem 0.75rem; width: 110px;">${isEn ? 'Frequency' : 'Tần Suất'}</th>
                <th style="padding: 0.6rem 0.75rem; width: 100px;">${isEn ? 'Lastmod' : 'Cập Nhật'}</th>
              </tr>
            </thead>
            <tbody>
              ${rowsHtml}
            </tbody>
          </table>
        </div>

      </div>
    `;
    return;
  }

  // 16. SEO URL Slug Generator
  if (slug === 'slug-generator' && data.slug) {
    richBox.style.display = 'block';
    const alt = data.alternatives || {};
    let stopWordsBadge = '';
    if (data.removed_stop_words && data.removed_stop_words.length > 0) {
      stopWordsBadge = `
        <div style="margin-top: 0.75rem; font-size: 0.82rem; color: var(--text-muted);">
          <span>${isEn ? `🧹 Automatically filtered ${data.removed_stop_words.length} stop words: ` : `🧹 Đã tự động lọc ${data.removed_stop_words.length} từ dừng (Stop words): `}</span>
          <code style="color: var(--accent-amber);">${data.removed_stop_words.join(', ')}</code>
        </div>
      `;
    }

    const titleInfo = isEn ? `SEO URL Slug (${data.char_count} chars • ${data.word_count} words)` : `URL Slug Chuẩn SEO (${data.char_count} ký tự • ${data.word_count} từ)`;

    richBox.innerHTML = `
      <div class="rich-output-card" style="border-left: 5px solid var(--accent-emerald);">
        <div class="rich-output-title">
          <span>🔗 ${titleInfo}</span>
          <span class="badge badge-emerald">${isEn ? `SEO Score: ${data.health_score}/100` : `Điểm SEO: ${data.health_score}/100`}</span>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; background: var(--bg-surface-elevated); padding: 0.85rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
          <code style="font-family: var(--font-mono); font-size: 1.2rem; font-weight: 700; color: var(--accent-indigo); flex: 1; word-break: break-all;">${data.slug}</code>
          <button type="button" class="btn btn-primary btn-sm" onclick="navigator.clipboard.writeText('${data.slug}'); showToast('${isEn ? 'Copied URL slug!' : 'Đã chép slug URL!'}');">📋 ${isEn ? 'Copy' : 'Chép'}</button>
        </div>

        ${stopWordsBadge}

        <div style="margin-top: 1.25rem; border-top: 1px solid var(--border-subtle); padding-top: 1rem;">
          <strong style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 0.65rem;">${isEn ? 'Other Formats:' : 'Các Định Dạng Khác:'}</strong>
          <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${alt.kebab_case}'); showToast('${isEn ? 'Copied kebab-case!' : 'Đã chép kebab-case!'}');">kebab: <code>${alt.kebab_case}</code></button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${alt.snake_case}'); showToast('${isEn ? 'Copied snake_case!' : 'Đã chép snake_case!'}');">snake: <code>${alt.snake_case}</code></button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${alt.camel_case}'); showToast('${isEn ? 'Copied camelCase!' : 'Đã chép camelCase!'}');">camel: <code>${alt.camel_case}</code></button>
          </div>
        </div>

        <div style="margin-top: 1rem; font-size: 0.85rem; color: var(--accent-emerald);">
          💡 <strong>${isEn ? 'Evaluation:' : 'Đánh giá:'}</strong> ${data.recommendations ? data.recommendations.join(' ') : (isEn ? 'Optimal structure.' : 'Cấu trúc tối ưu.')}
        </div>
      </div>
    `;
    return;
  }

  // 19. Proxy Checker
  if (slug === 'proxy-checker' && data.results) {
    richBox.style.display = 'block';

    const getFlag = (cc) => {
      if (!cc || cc.length !== 2) return '🌐';
      try {
        const codePoints = cc.toUpperCase().split('').map(c => 127397 + c.charCodeAt(0));
        return String.fromCodePoint(...codePoints);
      } catch (e) { return '🌐'; }
    };

    let tableRows = '';
    data.results.forEach((p) => {
      const isLive = p.status === 'live';
      const statusBadge = isLive 
        ? '<span class="badge badge-emerald" style="font-weight:700; font-size:0.75rem;">🟢 LIVE</span>' 
        : '<span class="badge badge-rose" style="font-weight:700; font-size:0.75rem;">🔴 DEAD</span>';
      
      let pingBadge = '—';
      if (isLive) {
        let pingColor = 'var(--accent-emerald)';
        if (p.latency_ms > 1500) pingColor = 'var(--accent-rose)';
        else if (p.latency_ms > 500) pingColor = 'var(--accent-amber)';
        pingBadge = `<span style="font-weight:700; color:${pingColor}; font-family:var(--font-mono);">${p.latency_ms} ms</span>`;
      }

      const flag = getFlag(p.country_code);
      const locationStr = isLive 
        ? `<div style="display:flex; align-items:center; gap:0.35rem;"><span>${flag}</span> <span style="font-weight:600;">${p.country}</span>${p.city ? `<small style="color:var(--text-muted);">(${p.city})</small>` : ''}</div><div style="font-size:0.75rem; color:var(--text-muted);">${p.isp || ''}</div>`
        : '<span style="color:var(--text-muted);">—</span>';

      const detailInfo = isLive
        ? `<span class="badge badge-indigo" style="font-size:0.75rem;">${p.anonymity}</span>`
        : `<span style="color:var(--accent-rose); font-size:0.8rem;">${p.error || (isEn ? 'Connection error' : 'Lỗi kết nối')}</span>`;

      tableRows += `
        <tr class="proxy-row proxy-status-${p.status}" style="border-bottom: 1px solid var(--border-subtle);">
          <td style="padding: 0.85rem 0.75rem; text-align: center;">${statusBadge}</td>
          <td style="padding: 0.85rem 0.75rem;">
            <div style="display:flex; align-items:center; gap:0.5rem;">
              <code style="font-family: var(--font-mono); font-weight:700; color: var(--text-main); font-size:0.9rem;">${p.raw}</code>
              <button type="button" class="btn btn-secondary btn-sm" style="padding: 0.15rem 0.4rem; font-size:0.72rem;" onclick="navigator.clipboard.writeText('${p.raw}'); showToast('${isEn ? `Copied: ${p.raw}` : `Đã chép: ${p.raw}`}');">${isEn ? 'Copy' : 'Sao chép'}</button>
            </div>
            ${p.exit_ip && p.exit_ip !== p.proxy.split(':')[0] ? `<div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">${isEn ? 'Exit IP:' : 'IP thoát:'} <code>${p.exit_ip}</code></div>` : ''}
          </td>
          <td style="padding: 0.85rem 0.75rem; text-align: center;">
            <span class="badge" style="background: var(--bg-surface-elevated); color: var(--text-main); font-weight:600; font-size:0.75rem;">${p.protocol}</span>
            ${p.has_auth ? '<span class="badge badge-amber" style="font-size:0.7rem; margin-left:4px;">Auth</span>' : ''}
          </td>
          <td style="padding: 0.85rem 0.75rem; text-align: center;">${pingBadge}</td>
          <td style="padding: 0.85rem 0.75rem;">${locationStr}</td>
          <td style="padding: 0.85rem 0.75rem;">${detailInfo}</td>
        </tr>
      `;
    });

    const liveRate = data.total > 0 ? Math.round((data.live_count / data.total) * 100) : 0;

    richBox.innerHTML = `
      <div class="rich-output-card" style="margin-top: 1rem;">
        <div class="rich-output-title">
          <div style="display:flex; align-items:center; gap:0.6rem;">
            <span style="font-size:1.2rem;">🛡️</span>
            <span style="font-weight:700; font-size:1.1rem; color:var(--text-main);">${isEn ? 'Proxy Test Results' : 'Kết Quả Kiểm Tra Proxy'}</span>
          </div>
          <div style="display:flex; gap:0.5rem;">
            <span class="badge badge-emerald">${isEn ? `Live Rate: ${liveRate}%` : `Tỷ lệ sống: ${liveRate}%`}</span>
          </div>
        </div>

        <!-- Summary KPI Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; margin: 1rem 0 1.25rem;">
          <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 0.85rem; text-align: center;">
            <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">${isEn ? 'Total Proxies' : 'Tổng Proxy'}</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--text-main); margin-top: 0.2rem;">${data.total}</div>
          </div>
          <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: var(--radius-md); padding: 0.85rem; text-align: center;">
            <div style="font-size: 0.78rem; color: var(--accent-emerald); text-transform: uppercase; font-weight: 600;">${isEn ? '🟢 Live' : '🟢 Live (Sống)'}</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--accent-emerald); margin-top: 0.2rem;">${data.live_count}</div>
          </div>
          <div style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: var(--radius-md); padding: 0.85rem; text-align: center;">
            <div style="font-size: 0.78rem; color: var(--accent-rose); text-transform: uppercase; font-weight: 600;">${isEn ? '🔴 Dead' : '🔴 Dead (Chết)'}</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--accent-rose); margin-top: 0.2rem;">${data.dead_count}</div>
          </div>
          <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 0.85rem; text-align: center;">
            <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">${isEn ? '⚡ Avg Latency' : '⚡ Ping Trung Bình'}</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: var(--accent-indigo); margin-top: 0.2rem;">${data.avg_latency_ms} <span style="font-size:0.9rem;">ms</span></div>
          </div>
        </div>

        <!-- Quick Action Buttons & Filter -->
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem; padding:0.6rem 0.85rem; background:var(--bg-surface-elevated); border-radius:var(--radius-md); border:1px solid var(--border-subtle);">
          <div style="display:flex; gap:0.4rem;">
            <button type="button" class="btn btn-secondary btn-sm" id="btn-filter-proxy-all" style="font-weight:700; font-size:0.8rem;" onclick="filterProxyRows('all')">${isEn ? `All (${data.total})` : `Tất cả (${data.total})`}</button>
            <button type="button" class="btn btn-secondary btn-sm" id="btn-filter-proxy-live" style="font-size:0.8rem; color:var(--accent-emerald);" onclick="filterProxyRows('live')">${isEn ? `Live Only (${data.live_count})` : `Chỉ Live (${data.live_count})`}</button>
            <button type="button" class="btn btn-secondary btn-sm" id="btn-filter-proxy-dead" style="font-size:0.8rem; color:var(--accent-rose);" onclick="filterProxyRows('dead')">${isEn ? `Dead Only (${data.dead_count})` : `Chỉ Dead (${data.dead_count})`}</button>
          </div>
          <div style="display:flex; gap:0.5rem;">
            ${data.live_count > 0 ? `
              <button type="button" class="btn btn-primary btn-sm" style="font-size:0.8rem; display:flex; align-items:center; gap:0.35rem;" onclick="navigator.clipboard.writeText(\`${data.live_proxies_text}\`); showToast('${isEn ? `Copied ${data.live_count} live proxies!` : `Đã sao chép ${data.live_count} proxy sống!`}');">
                📋 ${isEn ? 'Copy Live Proxies' : 'Sao Chép Proxy Sống'}
              </button>
              <button type="button" class="btn btn-secondary btn-sm" style="font-size:0.8rem; display:flex; align-items:center; gap:0.35rem;" onclick="downloadFile('live_proxies.txt', \`${data.live_proxies_text}\`);">
                💾 ${isEn ? 'Download .TXT' : 'Tải File .TXT'}
              </button>
            ` : ''}
          </div>
        </div>

        <!-- Result Table -->
        <div style="overflow-x: auto; border: 1px solid var(--border-subtle); border-radius: var(--radius-md);">
          <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
            <thead>
              <tr style="background: var(--bg-surface-elevated); border-bottom: 1px solid var(--border-subtle); color: var(--text-muted); font-size: 0.78rem; text-transform: uppercase;">
                <th style="padding: 0.75rem; text-align: center; width: 85px;">${isEn ? 'Status' : 'Trạng Thái'}</th>
                <th style="padding: 0.75rem;">${isEn ? 'Proxy Address' : 'Địa Chỉ Proxy'}</th>
                <th style="padding: 0.75rem; text-align: center; width: 90px;">${isEn ? 'Protocol' : 'Giao Thức'}</th>
                <th style="padding: 0.75rem; text-align: center; width: 90px;">${isEn ? 'Latency' : 'Độ Trễ'}</th>
                <th style="padding: 0.75rem;">${isEn ? 'Location & ISP' : 'Vị Trí & ISP'}</th>
                <th style="padding: 0.75rem;">${isEn ? 'Anonymity / Error' : 'Mức Ẩn Danh / Lỗi'}</th>
              </tr>
            </thead>
            <tbody>
              ${tableRows}
            </tbody>
          </table>
        </div>
      </div>
    `;

    window.filterProxyRows = function(filter) {
      const rows = document.querySelectorAll('.proxy-row');
      rows.forEach(r => {
        if (filter === 'all') r.style.display = '';
        else if (filter === 'live') r.style.display = r.classList.contains('proxy-status-live') ? '' : 'none';
        else if (filter === 'dead') r.style.display = r.classList.contains('proxy-status-dead') ? '' : 'none';
      });
    };

    return;
  }

  // Default fallback: hide rich box
  richBox.style.display = 'none';
}

// 11. Helper Functions
function initSeoLiveCounters() {
  const serpTitle = document.getElementById('serp-title');
  const serpDesc = document.getElementById('serp-desc');
  const titleCounter = document.getElementById('serp-title-counter');
  const descCounter = document.getElementById('serp-desc-counter');

  window.updateSerpCounters = function () {
    const isEn = (window.__locale === 'en');
    if (serpTitle && titleCounter) {
      const len = serpTitle.value.length;
      titleCounter.innerText = isEn ? `${len} chars (~${Math.round(len * 9.6)}px)` : `${len} ký tự (~${Math.round(len * 9.6)}px)`;
      titleCounter.style.color = (len >= 50 && len <= 60) ? 'var(--accent-emerald)' : (len > 60 ? 'var(--accent-rose)' : 'var(--accent-amber)');
    }
    if (serpDesc && descCounter) {
      const len = serpDesc.value.length;
      descCounter.innerText = isEn ? `${len} chars (~${Math.round(len * 6.7)}px)` : `${len} ký tự (~${Math.round(len * 6.7)}px)`;
      descCounter.style.color = (len >= 120 && len <= 160) ? 'var(--accent-emerald)' : (len > 160 ? 'var(--accent-rose)' : 'var(--accent-amber)');
    }
  };

  if (serpTitle) serpTitle.addEventListener('input', window.updateSerpCounters);
  if (serpDesc) serpDesc.addEventListener('input', window.updateSerpCounters);
  if (serpTitle || serpDesc) window.updateSerpCounters();
}

window.downloadFile = function (filename, content, mimeType = 'text/plain') {
  const isEn = (window.__locale === 'en');
  const blob = new Blob([content], { type: mimeType });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
  showToast(isEn ? `Downloaded file ${filename}!` : `Đã tải xuống tệp ${filename}!`);
};
