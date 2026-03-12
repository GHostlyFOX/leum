<?php
/**
 * Полная очистка всех кешей
 */

// Функция для рекурсивного удаления
function deleteFiles($pattern) {
    $files = glob($pattern);
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    return $count;
}

// Удаляем всё из storage/framework
$storagePaths = [
    __DIR__ . '/../storage/framework/views/*',
    __DIR__ . '/../storage/framework/cache/data/*',
    __DIR__ . '/../storage/framework/sessions/*',
    __DIR__ . '/../storage/framework/cache/*.php',
];

$deleted = 0;
foreach ($storagePaths as $path) {
    $deleted += deleteFiles($path);
}

// Удаляем bootstrap/cache (routes, config, packages)
$bootstrapCache = __DIR__ . '/../bootstrap/cache/';
if (is_dir($bootstrapCache)) {
    $files = glob($bootstrapCache . '*.php');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted++;
        }
    }
}

// Сброс OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
}

echo "✅ Кеш полностью очищен!\n";
echo "Удалено файлов: {$deleted}\n\n";

// Удаляем сам файл
unlink(__FILE__);
echo "🗑️ Файл удалён\n";
