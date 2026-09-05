<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Tool\Entities\Tool;
use Domain\Tool\Entities\ToolCategory;
use Domain\Tool\Enums\ToolEngineType;
use Illuminate\Database\Seeder;

class ProxyCheckerToolSeeder extends Seeder
{
    /**
     * Seed only the Proxy Checker tool safely into the database.
     */
    public function run(): void
    {
        $category = ToolCategory::query()->firstOrCreate(
            ['slug' => 'developer'],
            [
                'name' => 'Công cụ Lập trình',
                'description' => 'Bộ tiện ích trực tuyến cần thiết cho Developer: Định dạng JSON, Base64, Hash, Regex, JWT, Proxy.',
                'icon' => 'code-xml',
                'sort_order' => 1,
                'is_active' => true,
                'meta_title' => 'Công cụ Lập trình Trực tuyến & Tiện ích Code - TechHub',
                'meta_description' => 'Hộp công cụ lập trình miễn phí với bộ định dạng JSON, kiểm tra Regex, giải mã JWT, kiểm tra Proxy và tạo mã băm.',
            ]
        );

        Tool::query()->updateOrCreate(
            ['slug' => 'proxy-checker'],
            [
                'category_id' => $category->id,
                'name' => 'Kiểm Tra Proxy (HTTP, HTTPS, SOCKS4, SOCKS5)',
                'meta_title' => 'Proxy Checker Online — Kiểm Tra Trạng Thái Sống/Chết, Tốc Độ & Quốc Gia Proxy | TechHub',
                'meta_description' => 'Công cụ kiểm tra Proxy trực tuyến miễn phí: Test kết nối Live/Dead, đo độ trễ Ping ms, kiểm tra IP đầu ra, quốc gia, ISP và mức độ ẩn danh cho HTTP, HTTPS, SOCKS4, SOCKS5.',
                'summary' => 'Kiểm tra trạng thái sống/chết (Live/Dead), tốc độ phản hồi (Latency), địa chỉ IP đầu ra, quốc gia và mức độ ẩn danh của Proxy đơn lẻ hoặc danh sách hàng loạt.',
                'description_markdown' => '## 📌 Giới Thiệu Công Cụ Kiểm Tra Proxy Trực Tuyến (Proxy Checker)

**Proxy Server** đóng vai trò là cầu nối trung gian giữa máy tính của bạn và Internet, giúp ẩn địa chỉ IP thực tế, vượt qua các rào cản địa lý hoặc quản lý lưu lượng mạng cho mục đích phát triển phần mềm, tự động hóa (crawling/scraping) và kiểm thử ứng dụng.

Công cụ **Proxy Checker** của TechHub giúp lập trình viên, kỹ sư DevOps và người dùng kiểm tra nhanh chất lượng danh sách Proxy:
* **Kiểm tra trạng thái thời gian thực**: Xác định chính xác Proxy còn sống (**Live**) hay đã chết (**Dead** / Timeout / Connection Refused).
* **Đo độ trễ phản hồi (Latency/Ping ms)**: Phân loại tốc độ trực quan: Xanh lá (< 500ms - Rất nhanh), Vàng (< 1500ms - Ổn định) và Đỏ (> 1500ms - Chậm).
* **Định vị địa lý & ISP**: Tự động nhận diện Quốc gia (kèm cờ biểu tượng 🇻🇳, 🇺🇸, 🇸🇬...), Thành phố và Nhà cung cấp dịch vụ mạng (ISP).
* **Xác thực đa giao thức**: Hỗ trợ đầy đủ **HTTP**, **HTTPS**, **SOCKS4** và **SOCKS5**, bao gồm cả Proxy công khai lẫn Proxy yêu cầu tài khoản mật khẩu (`user:pass`).
* **Tiện ích 1-click**: Lọc danh sách chỉ lấy Proxy sống, sao chép hoặc xuất ra tệp `.txt` tức thì.

---

## 🛠️ Hướng Dẫn Sử Dụng Proxy Checker Từng Bước

1. **Bước 1**: Dán danh sách Proxy vào khung nhập (mỗi Proxy trên một dòng, tối đa 20 proxy/lần để đảm bảo tốc độ).
2. **Bước 2**: Các định dạng được hệ thống hỗ trợ:
   - `IP:Port` (Ví dụ: `103.152.112.4:8080`)
   - `IP:Port:User:Pass` (Ví dụ: `103.152.112.4:8080:admin:secret123`)
   - `protocol://IP:Port` (Ví dụ: `socks5://103.152.112.4:1080`)
   - `protocol://user:pass@IP:Port` (Ví dụ: `http://admin:secret123@103.152.112.4:8080`)
3. **Bước 3**: Chọn giao thức mong muốn (mặc định để **Tự động nhận diện**) và mức thời gian chờ Timeout (3s, 5s hoặc 10s).
4. **Bước 4**: Nhấn nút **⚡ Kiểm Tra Danh Sách Proxy** để nhận kết quả phân tích trực quan.

---

## ❓ Câu Hỏi Thường Gặp (FAQ)

### 1. Tại sao Proxy của tôi hiển thị trạng thái Dead dù vẫn dùng được ở nơi khác?
Có thể do Proxy yêu cầu whitelist IP (chỉ cho phép IP máy chủ của bạn kết nối), hoặc cổng mạng bị chặn tường lửa, hoặc thời gian phản hồi vượt quá mức Timeout bạn đã chọn.

### 2. Sự khác biệt giữa Proxy HTTP và SOCKS5 là gì?
Proxy HTTP tối ưu cho lưu lượng duyệt web thông thường, trong khi SOCKS5 hoạt động ở tầng thấp hơn (Transport Layer), hỗ trợ bất kỳ giao thức nào (TCP/UDP), không ghi đè header và có độ trễ thấp hơn.

### 3. Thông tin Proxy của tôi có bị lưu trữ lại trên TechHub không?
Không. Hệ thống hoạt động theo tiêu chuẩn **Zero Retention** — các thông số kết nối chỉ được kiểm tra trong phiên làm việc trên RAM và bị hủy ngay lập tức, tuyệt đối không lưu trữ thông tin User/Password của bạn.',
                'icon' => 'shield-check',
                'engine_type' => ToolEngineType::ServerSync,
                'is_premium_only' => false,
                'is_active' => true,
                'execution_count' => 620,
                'view_count' => 2400,
                'rating_avg' => 4.90,
                'rating_count' => 28,
            ]
        );

        $this->command?->info('✅ Tool [proxy-checker] seeded successfully!');
    }
}
