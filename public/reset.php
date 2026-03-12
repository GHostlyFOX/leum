<?php
/**
 * Экстренный сброс всех кешей
 */

// Очищаем все файлы кеша
$dirs = [
    '../storage/framework/views/',
    '../storage/framework/cache/',
    '../storage/framework/sessions/',
    '../bootstrap/cache/',
];

foreach ($dirs as $dir) {
    $fullDir = __DIR__ . '/' . $dir;
    if (is_dir($fullDir)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($fullDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() !== 'gitignore') {
                @unlink($file->getRealPath());
            }
        }
    }
}

// Сброс OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
}

// Проверяем загрузку класса
try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    if (class_exists('App\Livewire\Dashboard')) {
        echo "✅ Класс App\Livewire\Dashboard загружается корректно\n";
    } else {
        echo "❌ Класс App\Livewire\Dashboard не найден\n";
    }
    
    echo "\n✅ Все кеши очищены!\n";
    echo "Обновите страницу: https://sbor.team/dashboard\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}

unlink(__FILE__);
