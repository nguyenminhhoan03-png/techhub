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
window.toggleMobileMenu = function() {
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

    const targetId = copyBtn.getAttribute('data-copy-target');
    const targetEl = document.getElementById(targetId);
    if (!targetEl) return;

    const textToCopy = targetEl.value || targetEl.innerText || '';
    if (!textToCopy) {
      showToast('Không có nội dung để sao chép!', 'error');
      return;
    }

    navigator.clipboard.writeText(textToCopy).then(() => {
      showToast('Đã sao chép vào bộ nhớ tạm (Clipboard)!');
      const origText = copyBtn.innerHTML;
      copyBtn.innerHTML = '✓ Đã chép!';
      setTimeout(() => { copyBtn.innerHTML = origText; }, 2000);
    }).catch(() => {
      showToast('Sao chép thất bại', 'error');
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
    if (!file || !file.type.startsWith('image/')) {
      showToast('Vui lòng chọn tệp hình ảnh hợp lệ (PNG, JPG, WEBP, GIF)', 'error');
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
      base64Input.value = e.target.result;
      if (previewWrap) previewWrap.style.display = 'flex';
      if (previewThumb) previewThumb.src = e.target.result;
      if (previewName) previewName.innerText = file.name;
      if (previewSize) previewSize.innerText = `${(file.size / 1024).toFixed(1)} KB • ${file.type}`;
      showToast(`Đã tải ảnh: ${file.name}`);
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
      showToast('Đã nạp JSON mẫu!');
    });
  }

  const base64Btn = document.getElementById('btn-load-sample-base64');
  if (base64Btn) {
    base64Btn.addEventListener('click', () => {
      const input = document.getElementById('base64-text');
      if (input) input.value = "TechHub - Nền tảng công cụ trực tuyến chuẩn Senior cho Developer";
      showToast('Đã nạp văn bản mẫu!');
    });
  }

  const hashBtn = document.getElementById('btn-load-sample-hash');
  if (hashBtn) {
    hashBtn.addEventListener('click', () => {
      const input = document.getElementById('hash-text');
      if (input) input.value = "Admin@123456#TechHub2026";
      showToast('Đã nạp chuỗi cần băm mẫu!');
    });
  }

  const jwtBtn = document.getElementById('btn-load-sample-jwt');
  if (jwtBtn) {
    jwtBtn.addEventListener('click', () => {
      const input = document.getElementById('jwt-token');
      if (input) {
        input.value = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6Ik5ndXllbiBNaW5oIEhvYW4iLCJlbWFpbCI6ImFkbWluQHRlY2hodWIubG9jYWwiLCJyb2xlIjoiYWRtaW4iLCJpYXQiOjE1MTYyMzkwMjIsImV4cCI6MTc5OTk5OTk5OX0.4zICZ7-dYx-4wQfC-vQ8zN0rK8W-uN5Z8_SAMPLE';
      }
      showToast('Đã nạp JWT Token mẫu!');
    });
  }

  const regexBtn = document.getElementById('btn-load-sample-regex');
  if (regexBtn) {
    regexBtn.addEventListener('click', () => {
      const pattern = document.getElementById('regex-pattern');
      const testText = document.getElementById('regex-test-text');
      if (pattern) pattern.value = '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}';
      if (testText) testText.value = 'Danh sách email liên hệ:\n1. support@techhub.vn\n2. dev-lead@company.com\n3. sales_team@startup.io';
      showToast('Đã nạp Regex trích xuất Email mẫu!');
    });
  }

  const urlBtn = document.getElementById('btn-load-sample-url');
  if (urlBtn) {
    urlBtn.addEventListener('click', () => {
      const input = document.getElementById('url-input');
      if (input) input.value = 'https://techhub.vn/tools?category=developer&search=Định dạng JSON & ddd=true#results';
      showToast('Đã nạp URL mẫu!');
    });
  }

  // SEO Tool Samples
  const serpBtn = document.getElementById('btn-load-sample-serp');
  if (serpBtn) {
    serpBtn.addEventListener('click', () => {
      const title = document.getElementById('serp-title');
      const desc = document.getElementById('serp-desc');
      const url = document.getElementById('serp-url');
      if (title) title.value = 'Hướng Dẫn Tối Ưu SEO Onpage 2026 Toàn Diện — TechHub';
      if (desc) desc.value = 'Khám phá trọn bộ kỹ thuật tối ưu SEO Onpage chuẩn Google: Tối ưu thẻ Meta, cấu trúc Schema JSON-LD, Sitemap XML và tối ưu tốc độ tải trang vượt trội.';
      if (url) url.value = 'https://techhub.vn/kien-thuc/toi-uu-seo-onpage';
      if (typeof updateSerpCounters === 'function') updateSerpCounters();
      showToast('Đã nạp dữ liệu SERP mẫu!');
    });
  }

  const metaBtn = document.getElementById('btn-load-sample-meta');
  if (metaBtn) {
    metaBtn.addEventListener('click', () => {
      const title = document.getElementById('meta-title');
      const desc = document.getElementById('meta-desc');
      const kw = document.getElementById('meta-keywords');
      const canonical = document.getElementById('meta-canonical');
      if (title) title.value = 'TechHub — Nền Tảng Công Cụ Lập Trình & Tiện Ích Trực Tuyến';
      if (desc) desc.value = 'Hơn 20+ công cụ trực tuyến miễn phí cho Developer & SEO: JSON Formatter, Regex, Base64, Schema Generator, SERP Preview với tốc độ dưới 5ms.';
      if (kw) kw.value = 'công cụ lập trình, seo tools, json formatter, schema generator, techhub';
      if (canonical) canonical.value = 'https://techhub.vn';
      showToast('Đã nạp dữ liệu Meta Tags mẫu!');
    });
  }

  const schemaBtn = document.getElementById('btn-load-sample-schema');
  if (schemaBtn) {
    schemaBtn.addEventListener('click', () => {
      const type = document.getElementById('schema-type');
      if (type) type.value = 'Article';
      showToast('Đã nạp cấu hình Schema mẫu!');
    });
  }

  const ogBtn = document.getElementById('btn-load-sample-og');
  if (ogBtn) {
    ogBtn.addEventListener('click', () => {
      const title = document.getElementById('og-title');
      const desc = document.getElementById('og-desc');
      const img = document.getElementById('og-image');
      const url = document.getElementById('og-url');
      if (title) title.value = 'TechHub — Nền Tảng Công Cụ Lập Trình & SEO Trực Tuyến Số 1';
      if (desc) desc.value = 'Trải nghiệm hơn 20+ tiện ích lập trình, máy tính và công cụ tối ưu SEO Onpage tốc độ cực nhanh, bảo mật tuyệt đối không lưu dữ liệu.';
      if (img) img.value = 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1200&h=630&fit=crop';
      if (url) url.value = 'https://techhub.vn';
      showToast('Đã nạp dữ liệu Open Graph mẫu!');
    });
  }

  const robotsBtn = document.getElementById('btn-load-sample-robots');
  if (robotsBtn) {
    robotsBtn.addEventListener('click', () => {
      const preset = document.getElementById('robots-preset');
      const sitemap = document.getElementById('robots-sitemap');
      if (preset) preset.value = 'default';
      if (sitemap) sitemap.value = 'https://techhub.vn/sitemap.xml';
      showToast('Đã nạp mẫu Robots.txt tiêu chuẩn!');
    });
  }

  const sitemapBtn = document.getElementById('btn-load-sample-sitemap');
  if (sitemapBtn) {
    sitemapBtn.addEventListener('click', () => {
      const base = document.getElementById('sitemap-base-url');
      if (base) base.value = 'https://techhub.vn';
      showToast('Đã nạp danh sách URL Sitemap mẫu!');
    });
  }

  const slugBtn = document.getElementById('btn-load-sample-slug');
  if (slugBtn) {
    slugBtn.addEventListener('click', () => {
      const text = document.getElementById('slug-text');
      if (text) text.value = 'Hướng Dẫn Toàn Diện Về Cách Tối Ưu Hóa SEO Onpage Cho Website Năm 2026!';
      showToast('Đã nạp tiêu đề tạo Slug mẫu!');
    });
  }
}

// 9. Interactive Tool Execution Handler & Rich UI Renderer
function initToolForm() {
  const toolForm = document.getElementById('tool-execution-form');
  if (!toolForm) return;

  toolForm.addEventListener('submit', async (e) => {
    e.preventDefault();

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
      submitBtn.innerHTML = '<span class="spinner"></span> Đang xử lý...';
    }

    try {
      const response = await fetch(`/api/tools/${slug}/execute`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ input: inputPayload }),
      });

      const resData = await response.json();

      if (response.ok && resData.success) {
        showToast(`Thực thi thành công trong ${resData.data.execution_time_ms} ms!`, 'success');

        if (executionTimeEl) {
          executionTimeEl.innerText = `${resData.data.execution_time_ms} ms`;
        }

        // Render dedicated interactive visualization
        renderRichOutput(slug, resData.data.result_data, richOutputBox, resultBox);

      } else {
        const errorMsg = resData.message || (resData.errors ? Object.values(resData.errors).flat().join(', ') : 'Lỗi trong quá trình xử lý.');
        showToast(errorMsg, 'error');
        if (resultBox) {
          resultBox.value = `[Lỗi] ${errorMsg}`;
        }
      }
    } catch (err) {
      showToast('Lỗi kết nối mạng: ' + err.message, 'error');
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = submitBtn.dataset.origHtml || 'Thực Thi';
      }
    }
  });
}

// 10. Senior-Grade Visual Output Renderers for ALL Tools
function renderRichOutput(slug, data, richBox, rawTextarea) {
  if (!richBox) return;

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
          <span>🎨 Bảng Mã Màu Trích Xuất (${data.palette_count} màu)</span>
          <span class="badge badge-emerald">Độ chính xác cao</span>
        </div>
        <div class="color-palette-grid">
    `;

    data.palette.forEach((c) => {
      const textColor = c.is_dark ? '#ffffff' : '#0f172a';
      html += `
        <div class="color-card" onclick="navigator.clipboard.writeText('${c.hex}'); showToast('Đã chép mã màu ${c.hex}!');">
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
          💡 Nhấn trực tiếp vào ô màu để sao chép mã HEX vào bộ nhớ tạm.
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
          <span>📊 Thông Số Kỹ Thuật Hình Ảnh</span>
          <span class="badge badge-emerald">Phân tích hoàn tất</span>
        </div>
        <div class="kpi-metric-cards">
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">Kích thước (Pixels)</span>
            <span class="kpi-metric-val" style="color: var(--accent-cyan);">${data.width_px} × ${data.height_px} px</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">Tỷ lệ khung hình</span>
            <span class="kpi-metric-val" style="color: var(--accent-indigo);">${data.aspect_ratio}</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">Dung lượng tệp</span>
            <span class="kpi-metric-val" style="color: var(--accent-emerald);">${data.size_kb} KB (${data.size_mb} MB)</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">Định dạng &amp; Độ sâu</span>
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
    const formatter = new Intl.NumberFormat('vi-VN');
    let scheduleRows = '';

    if (data.amortization_preview && data.amortization_preview.length > 0) {
      data.amortization_preview.forEach((row) => {
        scheduleRows += `
          <tr>
            <td style="font-weight: 700;">Tháng ${row.month}</td>
            <td style="color: var(--accent-cyan); font-family: var(--font-mono);">${formatter.format(row.payment)} đ</td>
            <td style="color: var(--accent-emerald); font-family: var(--font-mono);">${formatter.format(row.principal_paid)} đ</td>
            <td style="color: var(--accent-rose); font-family: var(--font-mono);">${formatter.format(row.interest_paid)} đ</td>
            <td style="font-family: var(--font-mono); font-weight: 600;">${formatter.format(row.remaining_balance)} đ</td>
          </tr>
        `;
      });
    }

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>💰 Bảng Phân Bổ Khoản Vay &amp; Lãi Suất</span>
          <span class="badge badge-emerald">Dư nợ giảm dần</span>
        </div>
        <div class="kpi-metric-cards">
          <div class="kpi-metric-item" style="border-left: 4px solid var(--accent-indigo);">
            <span class="kpi-metric-label">Số tiền trả hàng tháng (EMI)</span>
            <span class="kpi-metric-val" style="color: var(--accent-indigo);">${formatter.format(data.monthly_payment)} VNĐ</span>
          </div>
          <div class="kpi-metric-item" style="border-left: 4px solid var(--accent-rose);">
            <span class="kpi-metric-label">Tổng tiền lãi phải trả</span>
            <span class="kpi-metric-val" style="color: var(--accent-rose);">${formatter.format(data.total_interest)} VNĐ</span>
          </div>
          <div class="kpi-metric-item" style="border-left: 4px solid var(--accent-emerald);">
            <span class="kpi-metric-label">Tổng số tiền tất toán</span>
            <span class="kpi-metric-val" style="color: var(--accent-emerald);">${formatter.format(data.total_payment)} VNĐ</span>
          </div>
        </div>

        <div style="margin-top: 1.5rem;">
          <h4 style="font-size: 1rem; margin-bottom: 0.75rem; color: var(--text-main);">📅 Lịch Trình Trả Nợ Chi Tiết (Amortization Preview)</h4>
          <div class="admin-table-wrap" style="max-height: 260px; overflow-y: auto;">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Kỳ Trả</th>
                  <th>Số Tiền Trả</th>
                  <th>Tiền Gốc</th>
                  <th>Tiền Lãi</th>
                  <th>Dư Nợ Còn Lại</th>
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
          <span>⚡ Kết Quả Tính Phần Trăm</span>
          <span class="badge badge-emerald">Chính xác</span>
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

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🏃 Đánh Giá Thể Trạng Sức Khỏe (BMI)</span>
          <span class="badge ${badgeClass}">${data.category}</span>
        </div>
        <div class="kpi-metric-cards">
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">Điểm số BMI</span>
            <span class="kpi-metric-val" style="color: var(--accent-indigo); font-size: 2.2rem;">${data.bmi_score}</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">Phân loại thể trạng</span>
            <span class="kpi-metric-val" style="font-size: 1.25rem;">${data.category}</span>
          </div>
          <div class="kpi-metric-item">
            <span class="kpi-metric-label">Cân nặng chuẩn lý tưởng</span>
            <span class="kpi-metric-val" style="color: var(--accent-emerald); font-size: 1.35rem;">${data.healthy_weight_range.min_kg} - ${data.healthy_weight_range.max_kg} kg</span>
          </div>
        </div>
        <p style="font-size: 0.88rem; color: var(--text-sub); margin-top: 0.5rem;">
          🩺 <strong>Khuyến cáo:</strong> ${data.health_risk}
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
      ? '<span class="badge badge-danger">● Đã hết hạn (Expired)</span>'
      : '<span class="badge badge-emerald">● Còn hiệu lực (Valid)</span>';

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🔍 Phân Tích Cấu Trúc JWT Token (Thuật toán: ${data.algorithm})</span>
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
              <span>Hết hạn: ${data.expires_at || 'Không giới hạn'}</span>
            </div>
            <div class="jwt-content">${JSON.stringify(data.payload, null, 2)}</div>
          </div>

          <div class="jwt-block jwt-block-signature">
            <div class="jwt-bar">
              <span>SIGNATURE HASH: Xác thực chữ ký</span>
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
          <span>🔒 Danh Sách Mã Băm Cryptographic</span>
          <span class="badge badge-emerald">${Object.keys(data.hashes).length} thuật toán</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.85rem;">
    `;

    for (const [alg, hashVal] of Object.entries(data.hashes)) {
      html += `
        <div style="background: var(--bg-surface-elevated); padding: 0.85rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
          <div style="flex: 1; min-width: 200px;">
            <strong style="text-transform: uppercase; font-size: 0.82rem; color: var(--accent-indigo); display: block; margin-bottom: 0.2rem;">${alg}</strong>
            <code style="font-family: var(--font-mono); font-size: 0.85rem; word-break: break-all; color: var(--text-main);">${hashVal}</code>
          </div>
          <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${hashVal}'); showToast('Đã sao chép mã ${alg.toUpperCase()}!');">📋 Chép</button>
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
              <strong style="color: var(--accent-emerald);">Khớp #${m.match_number}:</strong>
              <span style="font-size: 0.78rem; color: var(--text-muted);">Vị trí offset: ${m.offset}</span>
            </div>
            <code style="font-family: var(--font-mono); font-size: 0.95rem; color: var(--text-main); background: #ffffff; padding: 0.25rem 0.5rem; border-radius: var(--radius-xs); display: block;">${m.full_match}</code>
          </div>
        `;
      });
    }

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>⚡ Kết Quả Khớp Regex: <code>${data.pattern_used}</code></span>
          <span class="badge ${isMatch ? 'badge-emerald' : 'badge-danger'}">${matchCount} kết quả khớp</span>
        </div>
        ${matchCount > 0 ? matchesHtml : '<p style="color: var(--text-muted);">Không tìm thấy kết quả nào khớp với biểu thức regex trong đoạn văn bản.</p>'}
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
          <strong style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Cấu Trúc URL Phân Tích:</strong>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.75rem; margin-top: 0.5rem;">
            ${data.parsed_url.scheme ? `<div class="kpi-metric-item"><span class="kpi-metric-label">Giao thức</span><strong style="color:var(--accent-indigo);">${data.parsed_url.scheme}://</strong></div>` : ''}
            ${data.parsed_url.host ? `<div class="kpi-metric-item"><span class="kpi-metric-label">Host / Domain</span><strong style="color:var(--text-main);">${data.parsed_url.host}</strong></div>` : ''}
            ${data.parsed_url.path ? `<div class="kpi-metric-item"><span class="kpi-metric-label">Path</span><strong style="color:var(--accent-cyan);">${data.parsed_url.path}</strong></div>` : ''}
            ${data.parsed_url.query ? `<div class="kpi-metric-item"><span class="kpi-metric-label">Query</span><code style="font-size:0.8rem;">?${data.parsed_url.query}</code></div>` : ''}
          </div>
        </div>
      `;
    }

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🔗 Kết Quả ${data.action === 'encode' ? 'Mã Hóa' : 'Giải Mã'} URL</span>
          <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${data.result}'); showToast('Đã sao chép kết quả!');">📋 Chép</button>
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
          <span style="font-weight: 600; color: #3c4043;">Đánh giá: ${p.rating_value}/5</span>
          ${p.rating_count ? `<span>(${p.rating_count} bình chọn)</span>` : ''}
        </div>
      `;
    }

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🔍 Mô Phỏng Kết Quả Tìm Kiếm Google (${isMobile ? 'Mobile' : 'Desktop'})</span>
          <span class="badge ${m.seo_score >= 80 ? 'badge-emerald' : 'badge-amber'}">Điểm SEO: ${m.seo_score}/100</span>
        </div>

        {{-- Google SERP Snippet Preview Box --}}
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

        {{-- Metrics & Health Check --}}
        <div class="kpi-metric-cards">
          <div class="kpi-metric-item" style="border-left: 4px solid ${m.title.status === 'optimal' ? 'var(--accent-emerald)' : 'var(--accent-amber)'};">
            <span class="kpi-metric-label">Độ dài Tiêu đề (Title)</span>
            <span class="kpi-metric-val" style="font-size: 1.4rem;">${m.title.char_count} ký tự (~${m.title.pixel_est}px)</span>
            <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 0.25rem;">${m.title.message}</small>
          </div>
          <div class="kpi-metric-item" style="border-left: 4px solid ${m.description.status === 'optimal' ? 'var(--accent-emerald)' : 'var(--accent-amber)'};">
            <span class="kpi-metric-label">Độ dài Mô tả (Description)</span>
            <span class="kpi-metric-val" style="font-size: 1.4rem;">${m.description.char_count} ký tự (~${m.description.pixel_est}px)</span>
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
    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🏷️ Mã Nguồn Thẻ Meta HTML5 (${data.tag_count} thẻ đã sinh)</span>
          <div style="display: flex; gap: 0.5rem;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('meta-raw-box').value); showToast('Đã chép toàn bộ thẻ Meta!');">📋 Chép Toàn Bộ</button>
          </div>
        </div>

        <textarea id="meta-raw-box" class="form-control code-output" readonly style="min-height: 180px; margin-bottom: 1.25rem; font-family: var(--font-mono); font-size: 0.85rem;">${data.meta_html}</textarea>

        <div style="background: var(--bg-surface-elevated); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
          <strong style="font-size: 0.9rem; color: var(--text-main); display: block; margin-bottom: 0.75rem;">📋 Đánh Giá Kiểm Tra SEO Onpage:</strong>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.65rem; font-size: 0.85rem;">
            <div>${audit.has_title ? '✅' : '❌'} Tiêu đề: <strong>${audit.title_length || 0} ký tự</strong> (${audit.title_length_status === 'good' ? '<span style="color:var(--accent-emerald);">Chuẩn</span>' : '<span style="color:var(--accent-amber);">Cần tối ưu</span>'})</div>
            <div>${audit.has_description ? '✅' : '❌'} Mô tả: <strong>${audit.description_length || 0} ký tự</strong> (${audit.description_length_status === 'good' ? '<span style="color:var(--accent-emerald);">Chuẩn</span>' : '<span style="color:var(--accent-amber);">Cần tối ưu</span>'})</div>
            <div>${audit.has_canonical ? '✅' : '⚠️'} Canonical: <strong>${audit.has_canonical ? 'Đã thiết lập' : 'Thiếu canonical'}</strong></div>
            <div>${audit.is_indexable ? '✅' : '🚫'} Chỉ mục: <strong>${audit.is_indexable ? 'Cho phép Index' : 'Chặn Noindex'}</strong></div>
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
          <span>🧩 Dữ Liệu Cấu Trúc Schema JSON-LD (${data.schema_type})</span>
          <div style="display: flex; gap: 0.5rem;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="downloadFile('schema-${data.schema_type.toLowerCase()}.json', document.getElementById('schema-json-box').value, 'application/json');">💾 Tải JSON</button>
            <button type="button" class="btn btn-primary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('schema-script-box').value); showToast('Đã chép mã Script JSON-LD!');">📋 Chép Script Tag</button>
          </div>
        </div>

        <textarea id="schema-script-box" class="form-control code-output" readonly style="min-height: 220px; font-family: var(--font-mono); font-size: 0.85rem; margin-bottom: 1rem;">${data.result}</textarea>
        <textarea id="schema-json-box" style="display:none;">${data.json_ld}</textarea>

        <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.75rem 1rem; border-radius: var(--radius-md); font-size: 0.85rem; color: #34d399;">
          <span>✓ Cú pháp JSON-LD hợp lệ, sẵn sàng dán trực tiếp vào thẻ <code>&lt;head&gt;</code> của trang web.</span>
          <a href="https://validator.schema.org/" target="_blank" rel="noopener noreferrer" style="color: var(--accent-cyan); font-weight: 600; text-decoration: underline;">Kiểm tra với Schema.org ↗</a>
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
          <span>📱 Xem Trước Giao Diện Social Cards (Facebook &amp; Twitter)</span>
          <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('og-raw-box').value); showToast('Đã chép toàn bộ thẻ Open Graph!');">📋 Chép Mã Meta</button>
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

    richBox.innerHTML = `
      <div class="rich-output-card">
        <div class="rich-output-title">
          <span>🤖 Tệp Cấu Hình Robots.txt (${data.line_count} dòng)</span>
          <div style="display: flex; gap: 0.5rem;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="downloadFile('robots.txt', document.getElementById('robots-raw-box').value);">💾 Tải Tệp robots.txt</button>
            <button type="button" class="btn btn-primary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('robots-raw-box').value); showToast('Đã chép nội dung robots.txt!');">📋 Chép Nội Dung</button>
          </div>
        </div>

        <textarea id="robots-raw-box" class="form-control code-output" readonly style="min-height: 180px; font-family: var(--font-mono); font-size: 0.88rem; margin-bottom: 1rem;">${data.robots_txt}</textarea>

        <div style="background: var(--bg-surface-elevated); padding: 0.85rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); font-size: 0.85rem;">
          <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
            <span>🛡️ Chặn AI Bots: <strong>${data.ai_bots_blocked ? 'Đã kích hoạt' : 'Chưa bật'}</strong></span>
            <span>🗺️ Sitemap XML: <strong>${data.has_sitemap ? 'Đã khai báo' : 'Chưa có'}</strong></span>
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
          <td style="padding: 0.6rem 0.75rem; color: var(--text-muted); font-size: 0.8rem;">${e.lastmod || 'Today'}</td>
        </tr>
      `;
    });

    richBox.innerHTML = `
      <div class="rich-output-card" style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: 0 8px 24px rgba(0,0,0,0.05);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-subtle);">
          <div>
            <div style="font-size: 1.2rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem;">
              <span>🎉</span> <span>Sơ Đồ XML Sitemap Đã Sẵn Sàng!</span>
            </div>
            <div style="font-size: 0.88rem; color: var(--text-muted); margin-top: 0.25rem;">
              Đã thu thập <strong>${data.urls_count} trang</strong> • Dung lượng: <strong>${data.size_formatted}</strong> • Chuẩn Sitemaps.org Protocol 0.9
            </div>
          </div>

          <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
            <button type="button" class="btn btn-primary" onclick="downloadFile('sitemap.xml', document.getElementById('sitemap-raw-box').value, 'application/xml');" style="display: flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.15rem; font-weight: 700;">
              <span>📥</span> <span>Tải sitemap.xml</span>
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('sitemap-raw-box').value); showToast('Đã sao chép toàn bộ mã XML Sitemap!');">
              📋 Sao Chép XML
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="const b = document.getElementById('sitemap-raw-box'); b.style.display = b.style.display === 'none' ? 'block' : 'none';">
              👁️ Xem Mã XML
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
                <th style="padding: 0.6rem 0.75rem;">Đường Dẫn URL</th>
                <th style="padding: 0.6rem 0.75rem; width: 90px;">Độ Ưu Tiên</th>
                <th style="padding: 0.6rem 0.75rem; width: 110px;">Tần Suất</th>
                <th style="padding: 0.6rem 0.75rem; width: 100px;">Cập Nhật</th>
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
          <span>🧹 Đã tự động lọc ${data.removed_stop_words.length} từ dừng (Stop words): </span>
          <code style="color: var(--accent-amber);">${data.removed_stop_words.join(', ')}</code>
        </div>
      `;
    }

    richBox.innerHTML = `
      <div class="rich-output-card" style="border-left: 5px solid var(--accent-emerald);">
        <div class="rich-output-title">
          <span>🔗 URL Slug Chuẩn SEO (${data.char_count} ký tự • ${data.word_count} từ)</span>
          <span class="badge badge-emerald">Điểm SEO: ${data.health_score}/100</span>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; background: var(--bg-surface-elevated); padding: 0.85rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
          <code style="font-family: var(--font-mono); font-size: 1.2rem; font-weight: 700; color: var(--accent-indigo); flex: 1; word-break: break-all;">${data.slug}</code>
          <button type="button" class="btn btn-primary btn-sm" onclick="navigator.clipboard.writeText('${data.slug}'); showToast('Đã chép slug URL!');">📋 Chép</button>
        </div>

        ${stopWordsBadge}

        <div style="margin-top: 1.25rem; border-top: 1px solid var(--border-subtle); padding-top: 1rem;">
          <strong style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 0.65rem;">Các Định Dạng Khác:</strong>
          <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${alt.kebab_case}'); showToast('Đã chép kebab-case!');">kebab: <code>${alt.kebab_case}</code></button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${alt.snake_case}'); showToast('Đã chép snake_case!');">snake: <code>${alt.snake_case}</code></button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="navigator.clipboard.writeText('${alt.camel_case}'); showToast('Đã chép camelCase!');">camel: <code>${alt.camel_case}</code></button>
          </div>
        </div>

        <div style="margin-top: 1rem; font-size: 0.85rem; color: var(--accent-emerald);">
          💡 <strong>Đánh giá:</strong> ${data.recommendations ? data.recommendations.join(' ') : 'Cấu trúc tối ưu.'}
        </div>
      </div>
    `;
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

  window.updateSerpCounters = function() {
    if (serpTitle && titleCounter) {
      const len = serpTitle.value.length;
      titleCounter.innerText = `${len} ký tự (~${Math.round(len * 9.6)}px)`;
      titleCounter.style.color = (len >= 50 && len <= 60) ? 'var(--accent-emerald)' : (len > 60 ? 'var(--accent-rose)' : 'var(--accent-amber)');
    }
    if (serpDesc && descCounter) {
      const len = serpDesc.value.length;
      descCounter.innerText = `${len} ký tự (~${Math.round(len * 6.7)}px)`;
      descCounter.style.color = (len >= 120 && len <= 160) ? 'var(--accent-emerald)' : (len > 160 ? 'var(--accent-rose)' : 'var(--accent-amber)');
    }
  };

  if (serpTitle) serpTitle.addEventListener('input', window.updateSerpCounters);
  if (serpDesc) serpDesc.addEventListener('input', window.updateSerpCounters);
  if (serpTitle || serpDesc) window.updateSerpCounters();
}

window.downloadFile = function(filename, content, mimeType = 'text/plain') {
  const blob = new Blob([content], { type: mimeType });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
  showToast(`Đã tải xuống tệp ${filename}!`);
};
