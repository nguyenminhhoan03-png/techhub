# TechHub — Development Notes & Lessons Learned

> Tài liệu nội bộ. Ghi lại toàn bộ bug đã fix, pattern đã thiết lập, và quy tắc cần tuân thủ để tránh lặp lại lỗi cũ.

---

## 📋 Mục Lục

1. [Môi trường & Docker](#1-môi-trường--docker)
2. [Database & Seeding](#2-database--seeding)
3. [Laravel Config Chuẩn](#3-laravel-config-chuẩn)
4. [Performance — Lighthouse Rules](#4-performance--lighthouse-rules)
5. [Game Iframe — Xử Lý Black Screen](#5-game-iframe--xử-lý-black-screen)
6. [CSS Architecture](#6-css-architecture)
7. [JavaScript Patterns](#7-javascript-patterns)
8. [Deployment Checklist](#8-deployment-checklist)
9. [Lỗi Thường Gặp & Fix Nhanh](#9-lỗi-thường-gặp--fix-nhanh)

---

## 1. Môi trường & Docker

### .env chuẩn

```dotenv
# SAI: APP_ENV=product hoặc APP_ENV=local trên production
# ĐÚNG:
APP_ENV=production       # production server
APP_ENV=local            # máy dev cá nhân

APP_DEBUG=false          # LUÔN false trên production
APP_PORT=80              # port Nginx expose ra ngoài
```

### DB Host trong Docker

DB credentials trong docker-compose.yml PHẢI khớp với .env:

```yaml
# docker-compose.yml
mysql:
  environment:
    MYSQL_DATABASE: ${DB_DATABASE}
    MYSQL_USER: ${DB_USERNAME}
    MYSQL_PASSWORD: ${DB_PASSWORD}
```

```dotenv
# .env
DB_HOST=mysql       # tên service trong docker-compose, KHÔNG phải 127.0.0.1
DB_DATABASE=techhub
DB_USERNAME=techhub_user
DB_PASSWORD=techhub_secret
```

> Lỗi hay gặp: dùng DB_HOST=127.0.0.1 → không kết nối được từ container app sang mysql.

### Cache & Session — Tránh Lock Wait Timeout

```dotenv
# CACHE_STORE=database → gây "Lock wait timeout exceeded" khi seeding nhiều record
# Dùng file cache:
CACHE_STORE=file
SESSION_DRIVER=file
```

### Cloudflare Error 521

Nguyên nhân: Nginx không bind port 80 do APP_PORT sai.
Fix: APP_PORT=80 → docker compose down && docker compose up -d

---

## 2. Database & Seeding

### Lock Wait Timeout khi Factory

```
SQLSTATE[HY000]: General error: 1205 Lock wait timeout exceeded
```

Fix: Đổi CACHE_STORE=file trước khi seed.

Seed từng batch nhỏ:
```bash
php artisan db:seed --class=GameSeeder
```

### Thứ tự Seeder với Relationship

```php
// DatabaseSeeder.php — parent TRƯỚC, child SAU
Category::factory(10)->create();
Game::factory(100)->create();
```

---

## 3. Laravel Config Chuẩn

### Artisan Optimize — Chạy sau mỗi lần deploy

```bash
php artisan optimize          # cache config + routes + views
php artisan view:cache        # precompile tất cả Blade templates
php artisan config:cache      # cache .env values
php artisan route:cache       # cache route definitions
```

> Sau khi sửa .env, PHẢI chạy php artisan config:cache để apply.

### Clear Cache khi debug

```bash
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
```

---

## 4. Performance — Lighthouse Rules

### ĐỪNG viết inline style nếu không cần

Inline style="" trên mỗi element gây Style & Layout recalculation tốn kém.
Lighthouse báo: Minimize main-thread work — 6.4s
- Style & Layout: 1,564ms
- Rendering: 1,530ms

```html
<!-- SAI: 400+ inline styles gây main-thread bottleneck -->
<div style="display: flex; align-items: center; gap: 1rem;">

<!-- ĐÚNG: dùng CSS class trong techhub.css -->
<div class="gs-header">
```

Ngoại lệ hợp lệ — inline style chỉ khi giá trị là dynamic từ PHP:
```html
<!-- OK: màu từ DB, không thể đưa vào CSS static -->
<span style="background: {{ $game->category->color }}22; color: {{ $game->category->color }};">
```

### @keyframes KHÔNG viết inline trong Blade

```html
<!-- SAI: parse lại mỗi page load -->
<style>@keyframes spin { ... }</style>

<!-- ĐÚNG: khai báo 1 lần trong techhub.css -->
```

### CSS Class Naming Convention — Game Show Page

Prefix gs- (game-show) để tránh conflict:

| Class               | Mục đích                          |
|---------------------|-----------------------------------|
| .gs-left-col        | Cột trái layout                   |
| .gs-header          | Header row: title + buttons       |
| .gs-title           | H1 tên game                       |
| .gs-meta-row        | Row chứa badges                   |
| .gs-meta-plays      | Hiển thị lượt chơi                |
| .gs-meta-rating     | Hiển thị rating                   |
| .gs-btn-row         | Row chứa buttons điều khiển       |
| .btn-icon           | Button có icon + text             |
| .gs-iframe-wrap     | Wrapper ngoài cùng của iframe     |
| .gs-game-loader     | Overlay loading spinner           |
| .gs-loader-spinner  | Vòng xoay loading                 |
| .gs-loader-text     | Text "Đang khởi chạy..."          |
| .gs-loader-hint     | Text phụ nhỏ hơn                 |
| .gs-fallback-banner | Banner cảnh báo black screen      |
| .gs-fallback-btn    | Nút reload trong banner           |
| .gs-fallback-close  | Nút đóng banner                   |
| .gs-game-frame      | Chính iframe game                 |

---

## 5. Game Iframe — Xử Lý Black Screen

### Root Cause (3 tầng)

Tầng 1 — onload bắn quá sớm:
iframe.onload kích hoạt ngay khi nhận HTTP response đầu tiên — Canvas engine
bên trong vẫn chưa init xong. Loader ẩn nhưng game vẫn đen.

Tầng 2 — Canvas init sai kích thước (0×0):
Khi trang load, CSS layout chưa settle → iframe chưa có kích thước thực →
game engine init canvas = 0×0 → không render.
window.dispatchEvent(resize) KHÔNG đi vào được cross-origin iframe.

Tầng 3 — Cross-origin barrier:
Không thể đọc hay gửi event vào iframe từ domain khác (gamemonetize.com).

### Fix Đã Implement (show.blade.php)

```javascript
// State flags
let _gameLoaded = false;
let _fallbackTimer = null;

// 1. Delay 800ms sau onload để canvas có thời gian paint frame đầu
function handleGameLoaded() {
    if (_fallbackTimer) clearTimeout(_fallbackTimer);
    setTimeout(() => {
        _gameLoaded = true;
        // ẩn loader...
    }, 800);
}

// 2. FIX CHÍNH: blank src → 350ms delay → load src thật
// Lần load thứ 2, container đã settle CSS → canvas init đúng kích thước
document.addEventListener('DOMContentLoaded', () => {
    const frame = document.getElementById('game-frame');
    const originalSrc = '{{ $game->engine_path }}';
    frame.src = 'about:blank';
    setTimeout(() => {
        frame.src = originalSrc;
        _startFallbackTimer();
    }, 350);
});

// 3. Sau 6s nếu vẫn chưa load → hiện banner gợi ý reload
function _startFallbackTimer() {
    _fallbackTimer = setTimeout(() => {
        if (!_gameLoaded) {
            document.getElementById('game-fallback-banner').style.display = 'block';
        }
    }, 6000);
}
```

> ĐỪNG dùng window.dispatchEvent(new Event('resize')) để trigger canvas resize
> trong iframe — không có tác dụng với cross-origin iframe.

---

## 6. CSS Architecture

### File chính: public/css/techhub.css

Cấu trúc sections (theo thứ tự):
```
1.  CSS Variables / Design Tokens       (~50 dòng)
2.  Reset & Base Styles                 (~80 dòng)
3.  Typography                          (~60 dòng)
4.  Layout Utilities                    (~100 dòng)
5.  Components (buttons, cards...)      (~300 dòng)
6.  Navigation & Header                 (~150 dòng)
7.  Footer                              (~100 dòng)
8.  Page-specific (homepage, games)     (~300 dòng)
9.  Admin                               (~150 dòng)
10. Animations & Keyframes              (~50 dòng)
11. Senior Gaming Portal Design         (~300 dòng) ← game cards
12. Game Show Page (gs-*)               (~220 dòng) ← added
13. Game Guide Body (.game-guide-body)  (~220 dòng) ← added
```

### Styled Markdown — .game-guide-body

Dùng cho section hướng dẫn cách chơi:
```html
<div class="game-guide-body">
    {!! Str::markdown($game->description_markdown) !!}
</div>
```

Elements được style:
- h1, h2 → accent bar tím (gradient #6366f1 → #8b5cf6)
- h3, h4 → accent bar cyan (gradient #06b6d4 → #3b82f6)
- ul li  → glowing cyan dot bullet
- ol li  → gradient badge tròn tím
- code   → chip tím nhạt #a5b4fc
- pre    → dark block, border-left tím
- blockquote → border-left vàng, bg amber mờ

---

## 7. JavaScript Patterns

### File chính: public/js/techhub.js

> CẢNH BÁO: File này là vanilla JS thuần.
> KHÔNG được paste code Blade hoặc PHP vào đây.
> Lỗi đã xảy ra: comment Blade {{-- ... --}} bị paste vào file JS
> → break toàn bộ template literal.

### Naming Convention

- Hàm public (gọi từ HTML onclick): camelCase không prefix
  Ví dụ: handleGameLoaded(), reloadGameFrame(), toggleFullscreen()
- Hàm private/internal: prefix _
  Ví dụ: _startFallbackTimer(), _gameLoaded

### State flags cho game page

```javascript
let _gameLoaded = false;    // true sau khi canvas đã paint
let _fallbackTimer = null;  // reference đến setTimeout của fallback banner
```

Luôn reset cả hai khi reloadGameFrame() được gọi.

---

## 8. Deployment Checklist

### Trước khi push lên production

- [ ] APP_ENV=production trong .env trên server
- [ ] APP_DEBUG=false
- [ ] APP_PORT=80
- [ ] DB_HOST=mysql (tên docker service, không phải IP)
- [ ] CACHE_STORE=file (tránh lock timeout)

### Sau khi deploy code mới

```bash
php artisan optimize        # bắt buộc
php artisan view:cache      # precompile Blade
php artisan storage:link    # nếu có storage link mới
```

### Docker commands

```bash
docker compose up -d          # khởi chạy containers
docker compose exec app bash  # vào container app
docker compose logs -f app    # xem logs realtime
docker compose down           # dừng (data DB giữ trong volume)
docker compose ps             # kiểm tra status
```

---

## 9. Lỗi Thường Gặp & Fix Nhanh

| Triệu chứng                        | Nguyên nhân                         | Fix                                              |
|------------------------------------|-------------------------------------|--------------------------------------------------|
| Cloudflare 521                     | APP_PORT sai / Nginx không chạy     | APP_PORT=80, kiểm tra docker compose ps          |
| DB connection refused              | DB_HOST=127.0.0.1 trong Docker      | Đổi DB_HOST=mysql                                |
| Lock wait timeout khi seed         | CACHE_STORE=database                | Đổi CACHE_STORE=file                             |
| Game hiển thị màn đen              | iframe init trước khi CSS settle    | Delay 350ms + watchdog 6s (đã fix)               |
| JS template literal lỗi            | Blade comment trong file .js        | KHÔNG paste Blade syntax vào .js                 |
| Config cũ sau khi sửa .env         | Config được cache                   | php artisan config:clear && config:cache          |
| View không cập nhật                | View cache cũ                       | php artisan view:clear && view:cache              |
| Lighthouse main-thread cao (>3s)   | Inline style="" quá nhiều           | Chuyển sang CSS class trong techhub.css           |
| window.dispatchEvent(resize) fail  | Cross-origin iframe barrier         | Dùng phương pháp reload src (blank → original)   |
| Orphan containers warning          | Service bị rename trong compose     | docker compose up -d --remove-orphans             |

---

*Cập nhật lần cuối: 2026-08-24*
