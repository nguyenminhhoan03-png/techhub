#!/bin/bash
set -e

echo "🚀 [TechHub] Bắt đầu quá trình cập nhật / triển khai trên VPS..."

# Lưu lại commit hiện tại đang chạy ổn định
PREV_COMMIT=$(git rev-parse HEAD)
echo "📌 Commit an toàn hiện tại: $PREV_COMMIT"

rollback() {
  echo "❌ [LỖI TRIỂN KHAI] Phát hiện lỗi trong quá trình cập nhật!"
  echo "⏪ Đang tự động Rollback về commit an toàn trước đó: $PREV_COMMIT..."
  git reset --hard $PREV_COMMIT
  docker compose exec -T app php artisan optimize:clear
  docker compose exec -T app php artisan optimize
  echo "⚠️ Đã khôi phục website về trạng thái cũ an toàn thành công."
  exit 1
}
trap rollback ERR

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
echo "⚡ 4. Đang tối ưu hóa bộ nhớ đệm (Autoloader, Config, Routes, Views)..."
docker compose exec -T app composer install --no-dev --optimize-autoloader --no-interaction
docker compose exec -T app php artisan optimize:clear
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache
docker compose exec -T app php artisan event:cache
docker compose exec -T app php artisan optimize

# 5. Phân quyền storage
echo "🔒 5. Đảm bảo phân quyền thư mục storage..."
docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache
docker compose exec -T app chmod -R 775 storage bootstrap/cache

trap - ERR
echo "✅ [TechHub] Triển khai thành công 100%! Website đã sẵn sàng phục vụ."
