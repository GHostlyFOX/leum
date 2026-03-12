<?php
/**
 * Полная очистка всех кешей
 */

// 1. Очистка файлов кеша
$paths = [
    __DIR__ . '/../storage/framework/views/*',
    __DIR__ . '/../storage/framework/cache/data/*',
    __DIR__ . '/../storage/framework/cache/*.php',
    __DIR__ . '/../storage/framework/sessions/*',
    __DIR__ . '/../bootstrap/cache/*.php',
];

$deleted = 0;
foreach ($paths as $pattern) {
    foreach (glob($pattern) as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted++;
        }
    }
}

// 2. Сброс OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
}

// 3. Сброс APCu
if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
}

echo "✅ Кеш полностью очищен!\n";
echo "Удалено файлов: {$deleted}\n";
echo "OPcache: " . (function_exists('opcache_reset') ? 'сброшен' : 'недоступен') . "\n";
echo "\nПроверьте: https://sbor.team/dashboard\n";

// Удаляем сам файл
unlink(__FILE__);
