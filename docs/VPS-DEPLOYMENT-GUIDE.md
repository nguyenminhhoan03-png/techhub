# 🚀 Hướng Dẫn Triển Khai TechHub Pro Lên VPS (Production-Ready)

Tài liệu chi tiết hướng dẫn đưa dự án **TechHub Pro** lên máy chủ VPS (Ubuntu 22.04 / 24.04 LTS) bằng **Docker** hoặc **LEMP Stack** kèm chứng chỉ **SSL HTTPS Miễn Phí**.

---

## 📋 Yêu Cầu Cấu Hình VPS Tối Thiểu

| Thông số | Tối thiểu | Khuyên dùng |
|---|---|---|
| **OS** | Ubuntu 22.04 / 24.04 LTS | Ubuntu 24.04 LTS |
| **CPU** | 1 Core | 2 Cores |
| **RAM** | 1 GB (Bật 2GB Swap) | 2 GB - 4 GB |
| **SSD** | 20 GB | 40 GB+ |
| **Domain** | Trỏ A Record về IP VPS | Ví dụ: `techhub.vn` |

---

## 🌟 PHƯƠNG PHÁP 1: Triển Khai Bằng Docker (Khuyên Dùng — Nhanh & Cô Lập Nhất)

### Bước 1: Kết nối SSH vào VPS & Cập nhật hệ điều hành

```bash
ssh root@YOUR_VPS_IP

# Cập nhật packages
apt update && apt upgrade -y

# Cài đặt các công cụ cơ bản
apt install -y curl git ufw fail2ban htop unzip
```

---

### Bước 2: Tạo Swap RAM 2GB (Rất quan trọng cho VPS 1GB - 2GB RAM)

```bash
fallocate -l 2G /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap sw 0 0' >> /etc/fstab
```

---

### Bước 3: Cài đặt Docker & Docker Compose trên VPS

```bash
# Cài đặt Docker chính thức từ script của Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# Kiểm tra phiên bản
docker --version
docker compose version
```

---

### Bước 4: Mở Port Tường Lửa (UFW Firewall)

```bash
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable -y
```

---

### Bước 5: Clone Dự Án TechHub & Cấu Hình Môi Trường

```bash
# Tạo thư mục và clone mã nguồn
cd /var/www
git clone https://github.com/nguyenminhhoan03-png/techhub.git
cd techhub

# Tạo file .env từ template Docker
cp .env.docker.example .env
```

Mở file `.env` để chỉnh sửa các thông số Production:

```bash
nano .env
```
*(Chỉnh sửa: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://yourdomain.com`, `DB_PASSWORD=Mật_Khẩu_Mạnh`, API Key Gemini/OpenAI nếu có).*

---

### Bước 6: Khởi Động Toàn Bộ Hệ Thống

```bash
# Cấp quyền chạy cho deploy script
chmod +x deploy.sh docker/php/docker-entrypoint.sh

# Build và khởi chạy các container
docker compose up -d --build

# Chạy Migration & Seed dữ liệu ban đầu
docker compose exec app php artisan migrate --seed --force

# Import kho game HTML5 tự động
docker compose exec app php artisan games:import --amount=200

# Tối ưu hóa hiệu năng cho môi trường Production
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan optimize
```

---

### Bước 7: Cài Đặt Domain & SSL HTTPS Miễn Phí (Certbot)

Cài Nginx ngoài máy chủ VPS làm Reverse Proxy và cấp SSL Let's Encrypt:

```bash
apt install -y nginx certbot python3-certbot-nginx

# Tạo file cấu hình Nginx cho tên miền của bạn
nano /etc/nginx/sites-available/techhub
```

Dán nội dung sau vào (Thay `yourdomain.com` bằng tên miền thật của bạn):

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;

    location / {
        proxy_pass http://127.0.0.1:8088;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

Kích hoạt cấu hình và cấp chứng chỉ SSL:

```bash
ln -s /etc/nginx/sites-available/techhub /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx

# Tự động tạo chứng chỉ SSL HTTPS
certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

---

## 🔄 Cập Nhật Code Lần Sau (Chỉ 1 Dòng Lệnh)

Mỗi khi bạn commit & push code mới lên GitHub, trên VPS bạn chỉ cần chạy:

```bash
cd /var/www/techhub
./deploy.sh
```

---

## ⏰ Cấu Hình Cronjob Tự Động Trên VPS

Mở bảng Cronjob trên VPS:

```bash
crontab -e
```

Thêm 2 dòng sau vào cuối:

```cron
# Tự động chạy Laravel Schedule mỗi phút
* * * * * cd /var/www/techhub && docker compose exec -T app php artisan schedule:run >> /dev/null 2>&1

# Tự động import thêm game mới mỗi ngày lúc 2h sáng
0 2 * * * cd /var/www/techhub && docker compose exec -T app php artisan games:import --amount=30 >> /dev/null 2>&1
```
