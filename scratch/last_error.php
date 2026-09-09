<?php
$content = file_get_contents('storage/logs/laravel.log');
preg_match_all('/\[\d{4}-\d{2}-\d{2}[^\]]+\]\s+local\.ERROR:\s+([^\n]+)/', $content, $matches);
if (!empty($matches[1])) {
    echo "LAST ERROR: " . end($matches[1]) . PHP_EOL;
} else {
    echo "No match found" . PHP_EOL;
}
