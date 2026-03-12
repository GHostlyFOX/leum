<?php
require __DIR__.'/../vendor/autoload.php';

// Загружаем приложение
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Проверка классов ===\n";
echo "App\\Livewire\\Seasons: " . (class_exists('App\\Livewire\\Seasons') ? '✅ Есть' : '❌ Нет') . "\n";
echo "App\\Livewire\\Index: " . (class_exists('App\\Livewire\\Index') ? '✅ Есть' : '❌ Нет') . "\n";

echo "\n=== Роуты модуля Club ===\n";
$routes = Route::getRoutes();
$clubRoutes = [];
foreach ($routes as $route) {
    if (str_contains($route->uri(), 'club')) {
        $clubRoutes[] = [
            'uri' => $route->uri(),
            'action' => $route->getActionName(),
        ];
    }
}
print_r($clubRoutes);

echo "\n=== Все роуты (первые 10) ===\n";
$allRoutes = [];
foreach ($routes as $route) {
    $allRoutes[] = $route->uri() . ' => ' . $route->getActionName();
}
print_r(array_slice($allRoutes, 0, 10));

// Удаляем файл
unlink(__FILE__);
