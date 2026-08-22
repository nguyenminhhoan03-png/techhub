#!/bin/bash
set -e

echo "🚀 [TechHub] Bắt đầu quá trình cập nhật / triển khai trên VPS..."

# 1. Kéo mã nguồn mới nhất từ Git
echo "📥 1. Đang pull code mới nhất từ nhánh main..."
git pull origin main

# 2. Build và khởi động lại Docker Containers
echo "🐳 2. Đang build và cập nhật containers..."
docker compose up -d --build

# 3. Chạy Migration database
echo "🗄️ 3. Đang cập nhật database migration..."
docker compose exec -T app php artisan migrate --force

# 4. Tối ưu hóa Cache & Config cho Production
echo "⚡ 4. Đang tối ưu hóa bộ nhớ đệm (Config, Routes, Views)..."
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose exec -T app php artisan optimize

# 5. Phân quyền storage
echo "🔒 5. Đảm bảo phân quyền thư mục storage..."
docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache
docker compose exec -T app chmod -R 775 storage bootstrap/cache

echo "✅ [TechHub] Triển khai thành công 100%! Website đã sẵn sàng phục vụ."
