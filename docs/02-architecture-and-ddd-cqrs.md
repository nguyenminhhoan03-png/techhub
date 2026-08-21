# 🏛️ 02. Kiến Trúc Clean Architecture, Domain-Driven Design (DDD) & CQRS

Dự án **TechHub** được xây dựng theo kiến trúc nhiều tầng kết hợp giữa **Clean Architecture**, **Domain-Driven Design (DDD)** và mô hình **CQRS (Command Query Responsibility Segregation)**. Cấu trúc này giúp phân tách rõ ràng trách nhiệm giữa nghiệp vụ cốt lõi (Domain) và các công nghệ bên ngoài (Database, Framework, HTTP).

---

## 🏗️ 1. Sơ Đồ Phân Tầng & Dependency Rule (Quy Tắc Phụ Thuộc)

Nguyên tắc bất di bất dịch của Clean Architecture là: **Tầng bên trong KHÔNG BAO GIỜ được phụ thuộc vào tầng bên ngoài.**

```
                      ┌─────────────────────────────────────────┐
                      │           Presentation Layer            │
                      │  (Controllers, API Routes, Resources)   │
                      └────────────────────┬────────────────────┘
                                           │
                                           ▼
                      ┌─────────────────────────────────────────┐
                      │            Application Layer            │
                      │   (Commands, Queries, Handlers, Bus)    │
                      └──────────────┬─────────────┬────────────┘
                                     │             │
                                     ▼             ▼
  ┌─────────────────────────────────────┐      ┌─────────────────────────────────────┐
  │            Domain Layer             │      │        Infrastructure Layer         │
  │  (Entities, Value Objects, Events,  │◄─────┤   (Repositories, Eloquent Models,   │
  │        Repository Contracts)        │      │       External API, Mail/Cache)     │
  └──────────────────▲──────────────────┘      └─────────────────────────────────────┘
                     │                                            │
                     └────────────────────┬───────────────────────┘
                                          │
                      ┌───────────────────┴─────────────────────┐
                      │              Shared Layer               │
                      │ (Cross-cutting Concerns, Middleware,    │
                      │       Common Traits, Value Objects)     │
                      └─────────────────────────────────────────┘
```

---

## 📂 2. Trách Nhiệm Chi Tiết Của Từng Tầng Trong `src/`

### 1. `src/Domain/` — Nghiệp Vụ Cốt Lõi (Core Business)
* **Ý nghĩa**: Chứa toàn bộ quy tắc nghiệp vụ (Business Rules) thuần túy của hệ thống.
* **Đặc tính**: **Hoàn toàn độc lập với Laravel** (Không phụ thuộc vào Controller, Request, Eloquent Database, Middleware).
* **Các thành phần**:
  * **Entities**: Các đối tượng nghiệp vụ có định danh (`User`, `Product`...).
  * **Value Objects**: Các giá trị bất biến mang ý nghĩa nghiệp vụ (ví dụ: `Email`, `Money`, `UserStatus`).
  * **Repository Contracts**: Interface định nghĩa các thao tác lưu trữ mà Domain yêu cầu (ví dụ: `UserRepositoryContract`).
  * **Domain Events**: Sự kiện nghiệp vụ phát sinh khi trạng thái thay đổi (`UserRegisteredEvent`).
  * **Domain Exceptions**: Ngoại lệ nghiệp vụ riêng biệt (`UserAlreadyBannedException`).

### 2. `src/Application/` — Điều Phối Luồng Nghiệp Vụ (Use Cases & CQRS)
* **Ý nghĩa**: Điều phối dòng chảy dữ liệu giữa Presentation, Domain và Infrastructure để thực thi một ca sử dụng (Use Case).
* **Đặc tính**: Tách biệt rõ ràng việc **Ghi (Commands)** và việc **Đọc (Queries)** (CQRS).
* **Các thành phần**:
  * **Commands**: DTO chứa dữ liệu yêu cầu thực hiện hành động làm thay đổi trạng thái (ví dụ: `CreateUserCommand`, `ChangeUserPasswordCommand`).
  * **Command Handlers**: Lớp tiếp nhận Command, gọi Domain Entity và Repository để lưu trạng thái (ví dụ: `CreateUserCommandHandler`).
  * **Queries**: DTO chứa tiêu chí lọc/truy vấn dữ liệu (ví dụ: `GetUserByIdQuery`, `ListUsersQuery`).
  * **Query Handlers**: Lớp tối ưu truy vấn để trả dữ liệu về nhanh nhất.
  * **CommandBus & QueryBus**: Cơ chế điều hướng Command/Query đến đúng Handler tương ứng.

### 3. `src/Infrastructure/` — Cài Đặt Kỹ Thuật (Data Persistence & Third-Party)
* **Ý nghĩa**: Triển khai cụ thể các hợp đồng (Interfaces) do Domain và Application yêu cầu.
* **Các thành phần**:
  * **Persistence (Repositories)**: Cài đặt chi tiết `UserRepositoryContract` bằng Eloquent ORM hoặc Query Builder (`UserRepository.php`).
  * **External Services**: Tích hợp cổng thanh toán (Stripe, VNPay), gửi SMS, Mailer,...
  * **Adapters / Cache**: Cơ chế caching Redis hoặc File.

### 4. `src/Presentation/` — Giao Tiếp Bên Ngoài (Delivery Mechanism)
* **Ý nghĩa**: Điểm tiếp nhận request từ người dùng (HTTP API, Web, CLI Console).
* **Các thành phần**:
  * **Controllers**: Nhận HTTP Request, chuyển thành Command hoặc Query, đẩy vào Bus và trả về Response (ví dụ: `UserController.php`).
  * **Form Requests / Validation**: Kiểm tra tính hợp lệ của HTTP payload.
  * **API Resources / DTOs**: Định dạng dữ liệu trả về cho client.
  * **Routes**: Định nghĩa URL endpoints (`routes/api.php`).

### 5. `src/Shared/` — Tầng Dùng Chung (Cross-cutting Concerns)
* **Ý nghĩa**: Các tiện ích, Base Classes, Traits, Enums, Middleware có thể dùng an toàn xuyên suốt các tầng.
* **Các thành phần**:
  * `Shared/Infrastructure/Http/Middleware/`: `SecurityHeadersMiddleware`, `ForceJsonResponseMiddleware`, `AssignRequestIdMiddleware`.
  * `Shared/Domain/`: Base Value Objects, Enums chung (`StatusEnum`).

---

## 🔄 3. Luồng Xử Lý Một Request Điển Hình (CQRS Write Flow)

Ví dụ: Tạo mới người dùng (`POST /api/users`):

```
Client (HTTP Request: POST /api/users)
   │
   ▼
[Presentation] UserController::store(CreateUserRequest $request)
   │
   ├─► Tạo CreateUserCommand ($name, $email, $password)
   │
   ▼
[Application] CommandBus->dispatch($command)
   │
   ▼
[Application] CreateUserCommandHandler->handle($command)
   │
   ├─► Tạo Domain Entity User
   ├─► Gọi UserRepositoryContract->save($user)
   │
   ▼
[Infrastructure] UserRepository::save() -> Ghi vào CSDL qua Eloquent/DB
   │
   ▼
[Application] Trả về kết quả (User ID / DTO)
   │
   ▼
[Presentation] UserController trả về HTTP 201 Created (JSON Response)
```

---

## 🛡️ 4. Kiểm Soát Kiến Trúc Tự Động Bằng Pest Architecture Tests

Để ngăn ngừa việc phá vỡ cấu trúc phân tầng trong quá trình phát triển tính năng mới, dự án đã cấu hình các bài kiểm tra kiến trúc tại [`tests/Architecture/GlobalsTest.php`](file:///e:/Project_ItWebDev/PHP/techhub/tests/Architecture/GlobalsTest.php):

* ✅ Bắt buộc mọi file trong `src/` phải khai báo `declare(strict_types=1);`.
* ✅ Cấm tuyệt đối sử dụng các hàm debug `dd()`, `dump()`, `ray()`.
* ✅ `Domain` chỉ được phép sử dụng trong `Infrastructure`, `Application`, `Presentation`.
* ✅ `Infrastructure` chỉ được phép gọi trực tiếp từ `Application`.
* ✅ `Presentation` không bao giờ được import vào `Domain`, `Application` hay `Infrastructure`.

Hãy chạy lệnh sau thường xuyên trước khi tạo Pull Request:
```bash
vendor/bin/pest tests/Architecture
```
