# 🌐 06. Chuẩn Thiết Kế REST API & Xử Lý Lỗi (API Standards)

Tài liệu này quy định cấu trúc thiết kế các Endpoint API, chuẩn định dạng JSON Response, xử lý ngoại lệ tập trung và phân trang tại **TechHub**.

---

## 🎯 1. Nguyên Tắc Thiết Kế RESTful API

1. **Sử dụng danh từ số nhiều cho Resource**:
   * ✅ `GET /api/users`
   * ✅ `POST /api/users`
   * ✅ `GET /api/users/{id}`
   * ✅ `PATCH /api/users/{id}`
   * ✅ `DELETE /api/users/{id}`
2. **Sử dụng đúng HTTP Method**:
   * `GET`: Truy vấn dữ liệu (không thay đổi trạng thái hệ thống).
   * `POST`: Tạo mới tài nguyên.
   * `PATCH`: Cập nhật một phần tài nguyên.
   * `PUT`: Cập nhật toàn bộ tài nguyên.
   * `DELETE`: Xóa tài nguyên.
3. **Mã trạng thái HTTP (HTTP Status Codes)**:
   * `200 OK`: Truy vấn, cập nhật thành công.
   * `201 Created`: Tạo mới tài nguyên thành công.
   * `204 No Content`: Xóa thành công (không có body trả về).
   * `400 Bad Request`: Payload sai định dạng.
   * `401 Unauthorized`: Chưa đăng nhập hoặc Token không hợp lệ.
   * `403 Forbidden`: Đã đăng nhập nhưng không có quyền truy cập.
   * `404 Not Found`: Không tìm thấy tài nguyên.
   * `422 Unprocessable Content`: Dữ liệu gửi lên không vượt qua Validation.
   * `429 Too Many Requests`: Vượt quá giới hạn Rate Limit.
   * `500 Internal Server Error`: Lỗi máy chủ không mong muốn.

---

## 📦 2. Cấu Trúc JSON Response Thống Nhất (Envelope Format)

Tất cả API trả về đều tuân theo chuẩn định dạng nhất quán:

### Response Thành Công (Success)
```json
{
  "success": true,
  "message": "User retrieved successfully.",
  "data": {
    "id": "9b2d861e-128a-4950-8b1b-74b78fe2fa4d",
    "name": "Alex Johnson",
    "email": "alex@example.com",
    "created_at": "2026-08-21T07:00:00Z"
  }
}
```

### Response Phân Trang (Paginated)
```json
{
  "success": true,
  "data": [
    {
      "id": "1",
      "name": "Alex Johnson"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 120,
    "last_page": 8
  },
  "links": {
    "first": "/api/users?page=1",
    "last": "/api/users?page=8",
    "prev": null,
    "next": "/api/users?page=2"
  }
}
```

---

## ⚠️ 3. Cấu Trúc Xử Lý Lỗi Tập Trung (Standard Error Schema)

Hệ thống đã cấu hình bộ xử lý ngoại lệ tự động tại [`bootstrap/app.php`](file:///e:/Project_ItWebDev/PHP/techhub/bootstrap/app.php), đảm bảo **mọi lỗi** đều trả về JSON với cấu trúc chuẩn và kèm theo mã truy vết `request_id`:

### 1. Lỗi Dữ Liệu Không Hợp Lệ (422 Validation Error)
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "code": "VALIDATION_ERROR",
  "errors": {
    "email": [
      "The email field must be a valid email address."
    ],
    "password": [
      "The password field must contain at least one uppercase and one lowercase letter."
    ]
  },
  "request_id": "8f3b2075-80db-432d-8e68-085dbb15ca50"
}
```

### 2. Lỗi Vượt Quá Giới Hạn Truy Cập (429 Rate Limit Exceeded)
```json
{
  "success": false,
  "message": "Too many requests. Please slow down.",
  "code": "RATE_LIMIT_EXCEEDED",
  "request_id": "8f3b2075-80db-432d-8e68-085dbb15ca50"
}
```

### 3. Lỗi Không Tìm Thấy Tài Nguyên (404 Not Found)
```json
{
  "success": false,
  "message": "Resource or endpoint not found.",
  "code": "NOT_FOUND",
  "request_id": "8f3b2075-80db-432d-8e68-085dbb15ca50"
}
```

### 4. Lỗi Chưa Xác Thực (401 Unauthenticated)
```json
{
  "success": false,
  "message": "Unauthenticated.",
  "code": "UNAUTHENTICATED",
  "request_id": "8f3b2075-80db-432d-8e68-085dbb15ca50"
}
```

---

## 🛡️ 4. Tự Động Ép Header `Accept: application/json`

Nhờ [`ForceJsonResponseMiddleware`](file:///e:/Project_ItWebDev/PHP/techhub/src/Shared/Infrastructure/Http/Middleware/ForceJsonResponseMiddleware.php) được gắn vào nhóm route `api`, ngay cả khi client (Postman, Mobile App, Frontend) quên gửi header `Accept: application/json`, Laravel vẫn sẽ tự động xử lý và trả về JSON chuẩn thay vì HTML hoặc chuyển hướng trang.
