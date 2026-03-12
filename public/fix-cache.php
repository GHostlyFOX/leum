<?php
/**
 * Скрипт для полной очистки кешей Laravel
 * Откройте https://sbor.team/fix-cache.php
 */

// Удаляем все файлы кеша
$paths = [
    __DIR__ . '/../storage/framework/views/*',
    __DIR__ . '/../storage/framework/cache/*',
    __DIR__ . '/../storage/framework/sessions/*',
    __DIR__ . '/../bootstrap/cache/*.php',
];

$deleted = 0;
foreach ($paths as $pattern) {
    $files = glob($pattern);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $deleted++;
        }
    }
}

// Сбрасываем OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
}

echo "✅ Кеш очищен!\n";
echo "Удалено файлов: {$deleted}\n\n";
echo "Теперь проверьте:\n";
echo "1. Главную страницу: /dashboard\n";
echo "2. Страницу сезонов: /club/seasons\n\n";

// Проверяем существование классов
if (class_exists('App\Livewire\Index')) {
    echo "✅ Класс App\Livewire\Index найден\n";
} else {
    echo "❌ Класс App\Livewire\Index НЕ найден\n";
}

if (class_exists('App\Livewire\Seasons')) {
    echo "✅ Класс App\Livewire\Seasons найден\n";
} else {
    echo "❌ Класс App\Livewire\Seasons НЕ найден\n";
}

// Удаляем сам файл для безопасности
unlink(__FILE__);
echo "\n🗑️ Этот файл удалён автоматически\n";
