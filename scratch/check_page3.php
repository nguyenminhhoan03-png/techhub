<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$page = DB::table('pages')->where('id', 3)->first();
if ($page) {
    $draft = json_decode($page->draft_content, true);
    echo "=== DRAFT CSS ===\n";
    echo ($draft['gjs_css'] ?? 'NO CSS') . "\n";
}
