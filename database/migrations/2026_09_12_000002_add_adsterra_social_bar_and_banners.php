<?php

declare(strict_types=1);

use Application\Setting\Services\SettingService;
use Domain\Ad\Entities\Advertisement;
use Domain\Setting\Entities\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Social Bar Settings (Right above </body>)
        SettingService::set(
            key: 'adsterra_social_bar_enabled',
            value: '1',
            group: 'ads',
            label: 'Trạng Thái Hiển Thị Social Bar',
            type: 'boolean'
        );

        Setting::query()->where('key', 'adsterra_social_bar_enabled')->update([
            'description' => 'Chọn Bật để kích hoạt Social Bar trên toàn bộ website, chọn Tắt để tạm dừng/ẩn ngay lập tức.',
        ]);

        SettingService::set(
            key: 'adsterra_social_bar_script',
            value: 'https://pl29185068.profitableratecpmnetwork.com/55/e8/26/55e826ce1e422334693b249b9893e1d8.js',
            group: 'ads',
            label: 'Đường Dẫn Script Social Bar',
            type: 'text'
        );

        Setting::query()->where('key', 'adsterra_social_bar_script')->update([
            'description' => 'Mã nguồn tiện ích Social Bar từ Adsterra được chèn tự động ngay trước thẻ đóng </body>.',
        ]);

        // 2. Banner 160x300 (Sidebar Right)
        Advertisement::query()->updateOrCreate(
            ['name' => 'Adsterra Sidebar Banner (160x300)'],
            [
                'slot' => 'sidebar_right',
                'type' => 'adsense_html',
                'raw_html' => <<<HTML
<script>
  atOptions = {
    'key' : 'ac112409fa4da9e86592a0fd8b02de40',
    'format' : 'iframe',
    'height' : 300,
    'width' : 160,
    'params' : {}
  };
</script>
<script src="https://www.highrevenueformat.com/ac112409fa4da9e86592a0fd8b02de40/invoke.js"></script>
HTML,
                'is_active' => true,
            ]
        );

        // 3. Banner 468x60 (Tool Workspace Bottom)
        Advertisement::query()->updateOrCreate(
            ['name' => 'Adsterra Tool Bottom Banner (468x60)'],
            [
                'slot' => 'tool_workspace_bottom',
                'type' => 'adsense_html',
                'raw_html' => <<<HTML
<script>
  atOptions = {
    'key' : '8e0590ae1d91be1338da3dd5ae92fa30',
    'format' : 'iframe',
    'height' : 60,
    'width' : 468,
    'params' : {}
  };
</script>
<script src="https://www.highrevenueformat.com/8e0590ae1d91be1338da3dd5ae92fa30/invoke.js"></script>
HTML,
                'is_active' => true,
            ]
        );

        // 4. Banner 320x50 (Footer Banner)
        Advertisement::query()->updateOrCreate(
            ['name' => 'Adsterra Footer Banner (320x50)'],
            [
                'slot' => 'footer_banner',
                'type' => 'adsense_html',
                'raw_html' => <<<HTML
<script>
  atOptions = {
    'key' : '77553296a9cb444cb2448a8cf820b909',
    'format' : 'iframe',
    'height' : 50,
    'width' : 320,
    'params' : {}
  };
</script>
<script src="https://www.highrevenueformat.com/77553296a9cb444cb2448a8cf820b909/invoke.js"></script>
HTML,
                'is_active' => true,
            ]
        );

        Cache::flush();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::query()->whereIn('key', ['adsterra_social_bar_script', 'adsterra_social_bar_enabled'])->delete();
        Advertisement::query()->where('name', 'like', 'Adsterra%')->forceDelete();
        Cache::flush();
    }
};
