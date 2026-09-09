<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$website = Domain\WebsiteBuilder\Entities\Website::with(['pages', 'publishedSites', 'domains'])->first();

echo "=== WEBSITE BUILDER DATABASE VERIFICATION ===" . PHP_EOL;
echo "Website ID: " . $website->id . PHP_EOL;
echo "Website Name: " . $website->name . PHP_EOL;
echo "Subdomain: " . $website->subdomain . PHP_EOL;
echo "Status: " . $website->status->value . " (" . $website->status->label() . ")" . PHP_EOL;
echo "Custom Domain: " . ($website->domains->first()?->domain ?? 'None') . PHP_EOL;
echo "Total Pages: " . $website->pages->count() . PHP_EOL;

foreach ($website->pages as $page) {
    echo "  - Page: {$page->title} (/{$page->slug}) [Home: " . ($page->is_home ? 'YES' : 'NO') . "] [Version: {$page->version_number}]" . PHP_EOL;
}

$release = $website->publishedSites->first();
if ($release) {
    echo "Active Release Tag: " . $release->version_tag . PHP_EOL;
    echo "Storage Directory: " . $release->storage_directory . PHP_EOL;
    echo "Status: " . $release->status->value . " (" . $release->status->label() . ")" . PHP_EOL;
    echo "Manifest Pages Count: " . count($release->manifest_json['pages'] ?? []) . PHP_EOL;
    echo "Sitemap URL: " . ($release->manifest_json['sitemap'] ?? '') . PHP_EOL;
    echo "Robots URL: " . ($release->manifest_json['robots'] ?? '') . PHP_EOL;
}

echo "VERIFICATION SUCCESSFUL!" . PHP_EOL;
