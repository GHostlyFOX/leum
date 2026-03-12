<?php
// Очистка ВСЕХ кешей

$paths = [
    '../storage/framework/views/*',
    '../storage/framework/cache/data/*',
    '../storage/framework/cache/*.php',
    '../storage/framework/sessions/*',
    '../bootstrap/cache/*.php',
];

$deleted = 0;
foreach ($paths as $pattern) {
    foreach (glob(__DIR__ . '/' . $pattern) as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted++;
        }
    }
}

if (function_exists('opcache_reset')) {
    opcache_reset();
}

echo "✅ Кеш очищен!\n";
echo "Удалено файлов: {$deleted}\n\n";
echo "Проверьте:\n";
echo "- https://sbor.team/dashboard\n";
echo "- https://sbor.team/club/seasons\n";

unlink(__FILE__);
