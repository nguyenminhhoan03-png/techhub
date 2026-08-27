<?php
$enPath = 'e:/Project_ItWebDev/PHP/techhub/lang/en/tools.php';
$viPath = 'e:/Project_ItWebDev/PHP/techhub/lang/vi/tools.php';

function updateFile($filePath, $lang) {
    $data = include $filePath;
    $newContent = "<?php\n\ndeclare(strict_types=1);\n\nreturn [\n";
    if ($lang === 'en') {
        $newContent .= "    'common' => [\n";
        $newContent .= "        'online_tool' => 'online tools',\n";
        $newContent .= "        'free_online_tools' => 'free online tools, developer utilities, TechHub',\n";
        $newContent .= "    ],\n";
    } else {
        $newContent .= "    'common' => [\n";
        $newContent .= "        'online_tool' => 'công cụ online',\n";
        $newContent .= "        'free_online_tools' => 'công cụ trực tuyến miễn phí, tiện ích lập trình, TechHub',\n";
        $newContent .= "    ],\n";
    }

    foreach ($data as $key => $item) {
        if ($key === 'common') continue;
        $name = str_replace("'", "\'", $item['name']);
        $summary = str_replace("'", "\'", $item['summary']);
        $desc = str_replace("'", "\'", $item['description_markdown']);
        
        $metaTitle = $lang === 'en' ? "{$name} Online — 100% Free | TechHub" : "{$name} Online — Miễn Phí 100% | TechHub";
        $metaDesc = $summary;
        
        $newContent .= "    '{$key}' => [\n";
        $newContent .= "        'name' => '{$name}',\n";
        $newContent .= "        'summary' => '{$summary}',\n";
        $newContent .= "        'description_markdown' => '{$desc}',\n";
        $newContent .= "        'meta_title' => '{$metaTitle}',\n";
        $newContent .= "        'meta_description' => '{$metaDesc}',\n";
        $newContent .= "    ],\n";
    }
    
    $newContent .= "];\n";
    file_put_contents($filePath, $newContent);
    echo "Updated {$filePath}\n";
}

updateFile($enPath, 'en');
updateFile($viPath, 'vi');

?>
