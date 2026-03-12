<?php
$paths = [
    '../storage/framework/views/*',
    '../storage/framework/cache/*',
];
foreach ($paths as $pattern) {
    foreach (glob(__DIR__ . '/' . $pattern) as $file) {
        if (is_file($file)) unlink($file);
    }
}
if (function_exists('opcache_reset')) opcache_reset();
echo "✅ Кеш очищен!";
unlink(__FILE__);
