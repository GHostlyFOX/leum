<?php
// Экстренная очистка ВСЕГО
$dirs = [
    '../storage/framework/views/',
    '../storage/framework/cache/',
    '../storage/framework/sessions/',
    '../bootstrap/cache/',
];

foreach ($dirs as $dir) {
    $fullDir = __DIR__ . '/' . $dir;
    if (is_dir($fullDir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($fullDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            if ($file->isFile()) {
                unlink($file->getRealPath());
            }
        }
    }
}

if (function_exists('opcache_reset')) {
    opcache_reset();
}

echo "OK";
unlink(__FILE__);
