<?php

declare(strict_types=1);

use Domain\Tool\Tools\Developer\ApiTesterTool;
use Domain\Tool\Tools\Developer\CronGeneratorTool;
use Domain\Tool\Tools\Developer\CssMinifierTool;
use Domain\Tool\Tools\Developer\CsvToJsonTool;
use Domain\Tool\Tools\Developer\HtmlFormatterTool;
use Domain\Tool\Tools\Developer\HttpStatusCheckerTool;
use Domain\Tool\Tools\Developer\JsonToPhpTool;
use Domain\Tool\Tools\Developer\JsonToTypescriptTool;
use Domain\Tool\Tools\Developer\LaravelCrudGeneratorTool;
use Domain\Tool\Tools\Developer\PasswordGeneratorTool;
use Domain\Tool\Tools\Developer\SqlFormatterTool;
use Domain\Tool\Tools\Developer\SqlToLaravelMigrationTool;
use Domain\Tool\Tools\Developer\SqlToLaravelModelTool;
use Domain\Tool\Tools\Developer\TimestampConverterTool;
use Domain\Tool\Tools\Developer\UuidGeneratorTool;
use Domain\Tool\Tools\Developer\XmlToJsonTool;
use Domain\Tool\Tools\Image\ImageCompressorTool;
use Domain\Tool\Tools\Pdf\PdfToExcelTool;

test('json to typescript converts json object to interface', function (): void {
    $tool = new JsonToTypescriptTool();
    $result = $tool->execute([
        'json' => '{"id": 1, "name": "TechHub", "active": true}',
        'root_name' => 'User',
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['result'])->toContain('interface User')
        ->and($result->data['result'])->toContain('id: number;')
        ->and($result->data['result'])->toContain('name: string;')
        ->and($result->data['result'])->toContain('active: boolean;');
});

test('json to php generates array and dto class', function (): void {
    $tool = new JsonToPhpTool();

    // Mode array
    $resArray = $tool->execute([
        'json' => '{"title": "Laravel 12", "views": 100}',
        'mode' => 'array',
    ]);
    expect($resArray->isSuccess)->toBeTrue()
        ->and($resArray->data['result'])->toContain("'title' => 'Laravel 12'");

    // Mode DTO
    $resDto = $tool->execute([
        'json' => '{"title": "Laravel 12", "views": 100}',
        'mode' => 'dto',
        'class_name' => 'ArticleData',
    ]);
    expect($resDto->isSuccess)->toBeTrue()
        ->and($resDto->data['result'])->toContain('class ArticleData')
        ->and($resDto->data['result'])->toContain('public readonly string $title');
});

test('sql formatter beautifies query and uppercases keywords', function (): void {
    $tool = new SqlFormatterTool();
    $result = $tool->execute([
        'sql' => 'select id, name from users where active = 1 order by id desc limit 10;',
        'action' => 'beautify',
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['result'])->toContain('SELECT')
        ->and($result->data['result'])->toContain('FROM')
        ->and($result->data['result'])->toContain('WHERE');
});

test('sql to laravel migration parses create table', function (): void {
    $tool = new SqlToLaravelMigrationTool();
    $sql = "CREATE TABLE `products` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `price` decimal(10,2) NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    );";

    $result = $tool->execute(['sql' => $sql]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['table_name'])->toBe('products')
        ->and($result->data['result'])->toContain("Schema::create('products'")
        ->and($result->data['result'])->toContain('$table->string(');
});

test('sql to laravel model generates model with fillable and casts', function (): void {
    $tool = new SqlToLaravelModelTool();
    $sql = "CREATE TABLE `orders` (
        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
        `user_id` bigint unsigned NOT NULL,
        `total` decimal(10,2) NOT NULL,
        `is_completed` tinyint(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
    );";

    $result = $tool->execute(['sql' => $sql]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['model_name'])->toBe('Order')
        ->and($result->data['result'])->toContain("protected \$table = 'orders'")
        ->and($result->data['result'])->toContain('public function user()');
});

test('laravel crud generator creates full scaffolding bundle', function (): void {
    $tool = new LaravelCrudGeneratorTool();
    $result = $tool->execute([
        'model_name' => 'Customer',
        'fields' => 'name:string, email:string, is_vip:boolean',
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['model'])->toContain('class Customer extends Model')
        ->and($result->data['controller'])->toContain('class CustomerController')
        ->and($result->data['migration'])->toContain("Schema::create('customers'")
        ->and($result->data['store_request'])->toContain('class CustomerStoreRequest');
});

test('uuid generator creates requested amount of uuids', function (): void {
    $tool = new UuidGeneratorTool();
    $result = $tool->execute([
        'version' => 'v4',
        'count' => 5,
        'hyphens' => true,
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['count'])->toBe(5)
        ->and($result->data['uuids'])->toHaveCount(5);
});

test('cron generator explains cron and calculates next runs', function (): void {
    $tool = new CronGeneratorTool();
    $result = $tool->execute([
        'expression' => '0 4 * * 1-5',
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['human_description'])->not->toBeEmpty()
        ->and($result->data['laravel_code'])->toContain("cron('0 4 * * 1-5')")
        ->and($result->data['next_runs'])->toHaveCount(5);
});

test('html formatter beautifies and minifies html markup', function (): void {
    $tool = new HtmlFormatterTool();

    $resBeautify = $tool->execute([
        'html' => '<div><p>TechHub Online</p></div>',
        'action' => 'beautify',
    ]);
    expect($resBeautify->isSuccess)->toBeTrue()
        ->and($resBeautify->data['result'])->toContain('<p>TechHub Online</p>');

    $resMinify = $tool->execute([
        'html' => "<div>\n  <p>TechHub</p>\n</div>",
        'action' => 'minify',
    ]);
    expect($resMinify->isSuccess)->toBeTrue()
        ->and($resMinify->data['result'])->toBe('<div><p>TechHub</p></div>');
});

test('css minifier reduces css size and strips comments', function (): void {
    $tool = new CssMinifierTool();
    $css = "/* Test comment */\n.btn {\n    color: #ffffff;\n    margin: 0px;\n}";

    $result = $tool->execute([
        'css' => $css,
        'action' => 'minify',
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['result'])->not->toContain('/* Test comment */')
        ->and($result->data['result'])->toBe('.btn{color:#fff;margin:0}');
});

test('csv to json converts tabular csv to typed json', function (): void {
    $tool = new CsvToJsonTool();
    $csv = "id,name,price,in_stock\n1,Keyboard,89.50,true\n2,Mouse,45.00,false";

    $result = $tool->execute([
        'csv' => $csv,
        'delimiter' => 'comma',
        'has_headers' => true,
        'parse_numbers' => true,
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['rows_count'])->toBe(2)
        ->and($result->data['result'])->toContain('"name": "Keyboard"')
        ->and($result->data['result'])->toContain('"price": 89.5')
        ->and($result->data['result'])->toContain('"in_stock": true');
});

test('xml to json converts xml tags to json object', function (): void {
    $tool = new XmlToJsonTool();
    $xml = '<?xml version="1.0"?><root><item id="1"><title>Hello TechHub</title></item></root>';

    $result = $tool->execute(['xml' => $xml]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['result'])->toContain('Hello TechHub')
        ->and($result->data['root_element'])->toBe('root');
});

test('api tester blocks internal loopback and private ips for ssrf prevention', function (): void {
    $tool = new ApiTesterTool();

    $resLoopback = $tool->execute([
        'url' => 'http://127.0.0.1:8000/api',
        'method' => 'GET',
    ]);
    expect($resLoopback->isSuccess)->toBeFalse()
        ->and($resLoopback->errorMessage)->toContain('private');

    $resLocalhost = $tool->execute([
        'url' => 'http://localhost/admin',
        'method' => 'GET',
    ]);
    expect($resLocalhost->isSuccess)->toBeFalse()
        ->and($resLocalhost->errorMessage)->toContain('private');
});

test('password generator creates passwords with high entropy', function (): void {
    $tool = new PasswordGeneratorTool();
    $result = $tool->execute([
        'length' => 20,
        'count' => 3,
        'include_uppercase' => true,
        'include_lowercase' => true,
        'include_numbers' => true,
        'include_symbols' => true,
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and(count($result->data['passwords']))->toBe(3)
        ->and($result->data['entropy_bits'])->toBeGreaterThan(90)
        ->and(mb_strlen($result->data['passwords'][0]))->toBe(20);
});

test('timestamp converter converts epoch to date and vice versa', function (): void {
    $tool = new TimestampConverterTool();

    // Epoch to Date
    $resEpoch = $tool->execute([
        'mode' => 'epoch_to_date',
        'timestamp' => 1700000000,
        'timezone' => 'UTC',
    ]);
    expect($resEpoch->isSuccess)->toBeTrue()
        ->and($resEpoch->data['utc_time'])->toContain('2023-11-14 22:13:20')
        ->and($resEpoch->data['epoch_seconds'])->toBe(1700000000);

    // Date to Epoch
    $resDate = $tool->execute([
        'mode' => 'date_to_epoch',
        'datetime_string' => '2023-11-14 22:13:20',
        'timezone' => 'UTC',
    ]);
    expect($resDate->isSuccess)->toBeTrue()
        ->and($resDate->data['epoch_seconds'])->toBe(1700000000);
});

test('pdf to excel parses tab-delimited text into rows and csv', function (): void {
    $tool = new PdfToExcelTool();
    $text = "ID\tProduct\tPrice\n101\tMonitor\t299.00\n102\tDesk\t150.00";

    $result = $tool->execute([
        'raw_text' => $text,
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['rows_count'])->toBe(3)
        ->and($result->data['columns_count'])->toBe(3)
        ->and($result->data['csv_content'])->toContain('ID,Product,Price');
});

test('image compressor compresses valid gd image dataurl', function (): void {
    $tool = new ImageCompressorTool();

    // Create a 100x100 white PNG in memory for testing
    $img = imagecreatetruecolor(100, 100);
    $white = imagecolorallocate($img, 255, 255, 255);
    imagefilledrectangle($img, 0, 0, 100, 100, $white);
    ob_start();
    imagepng($img);
    $bytes = ob_get_clean();
    imagedestroy($img);

    $base64 = 'data:image/png;base64,' . base64_encode($bytes);

    $result = $tool->execute([
        'image_base64' => $base64,
        'quality' => 80,
        'format' => 'webp',
    ]);

    expect($result->isSuccess)->toBeTrue()
        ->and($result->data['format'])->toBe('webp')
        ->and($result->data['width'])->toBe(100)
        ->and($result->data['height'])->toBe(100)
        ->and($result->data['compressed_base64'])->toContain('data:image/webp;base64,');
});

test('http status checker blocks private and loopback networks for ssrf safety', function (): void {
    $tool = new HttpStatusCheckerTool();

    $result = $tool->execute([
        'url' => 'http://127.0.0.1:8080/metrics',
    ]);

    expect($result->isSuccess)->toBeFalse()
        ->and($result->errorMessage)->toContain('Cannot inspect internal');
});
