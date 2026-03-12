# Полное исправление роутов и модулей

## Проблемы

1. **Пакет `nwidart/laravel-modules` не установлен** в `vendor/`
2. **Провайдеры модулей не зарегистрированы** в `config/app.php`
3. **Использование `->namespace()`** в RouteServiceProvider модулях

## Решения

### 1. Регистрация модулей в config/app.php

Добавлены все Module Service Providers:
```php
Modules\Auth\Providers\AuthServiceProvider::class,
Modules\Club\Providers\ClubServiceProvider::class,
Modules\File\Providers\FileServiceProvider::class,
Modules\Match\Providers\MatchServiceProvider::class,
Modules\Reference\Providers\ReferenceServiceProvider::class,
Modules\Team\Providers\TeamServiceProvider::class,
Modules\Tournament\Providers\TournamentServiceProvider::class,
Modules\Training\Providers\TrainingServiceProvider::class,
Modules\User\Providers\UserServiceProvider::class,
```

### 2. Удаление namespace() из роутов

Убраны автоматические namespace из всех модулей:
- `Modules\Auth\Providers\RouteServiceProvider.php`
- `Modules\Match\Providers\RouteServiceProvider.php`
- `Modules\Team\Providers\RouteServiceProvider.php`
- `Modules\Tournament\Providers\RouteServiceProvider.php`
- `Modules\Training\Providers\RouteServiceProvider.php`
- `Modules\User\Providers\RouteServiceProvider.php`

Теперь используются полные имена классов (FQN) в роутах.

### 3. Исправление роута Club seasons

В `Modules/Club/Routes/web.php`:
```php
use App\Livewire\Seasons;  // Вместо Modules\Club\Http\Livewire\Seasons
```

Класс `Seasons` временно размещён в `app/Livewire/Seasons.php` для корректной автозагрузки.

## Проверка

Откройте в браузере для очистки кеша:
```
https://sbor.team/clear.php
```

Затем проверьте страницы:
- https://sbor.team/dashboard (главная)
- https://sbor.team/club/seasons (сезоны)

## Рекомендации

Для восстановления работы через пакет `nwidart/laravel-modules`:

```bash
composer require nwidart/laravel-modules
php artisan module:enable
```

Затем можно убрать ручную регистрацию провайдеров из `config/app.php`.

---

*Обновлено: {{ date('Y-m-d') }}*
