# Исправление ошибки Seasons

## Ошибка
```
Invalid route action: [Modules\Club\Http\Controllers\Modules\Club\Http\Livewire\Seasons].
```

## Причина
Laravel не может найти класс `Modules\Club\Http\Livewire\Seasons` из-за проблем с автозагрузкой (нужно выполнить `composer dump-autoload` на сервере).

## Решение

### 1. Временное решение (применено)
Класс `Seasons` перемещён в `app/Livewire/Seasons.php` с namespace `App\Livewire`:
- ✅ Файл создан: `app/Livewire/Seasons.php`
- ✅ View скопирован: `resources/views/livewire/seasons.blade.php`
- ✅ Роуты обновлены: `Modules/Club/Routes/web.php`

### 2. Очистка кеша
Откройте в браузере: `https://sbor.team/fix-cache.php`

Или выполните на сервере:
```bash
php artisan view:clear
php artisan cache:clear
php artisan route:clear
composer dump-autoload  # Обязательно!
```

### 3. Проверка
- Главная страница: https://sbor.team/dashboard
- Сезоны: https://sbor.team/club/seasons

## Структура файлов

```
app/Livewire/
├── Index.php          # Главная страница
└── Seasons.php        # Управление сезонами (временно здесь)

resources/views/livewire/
├── index.blade.php    # View главной страницы
└── seasons.blade.php  # View сезонов

Modules/Club/
├── Http/
│   └── Livewire/
│       └── Seasons.php        # Оригинал (не используется)
│   └── Controllers/
│       └── ClubController.php # CRUD клуба
├── Resources/views/
│   └── seasons.blade.php      # Оригинал view
└── Routes/
    └── web.php                # Роуты (обновлённые)
```

## После выполнения composer dump-autoload

Когда будет возможность выполнить `composer dump-autoload`, можно вернуть Seasons обратно в модуль:

1. Удалить `app/Livewire/Seasons.php`
2. Удалить `resources/views/livewire/seasons.blade.php`
3. Вернуть в `Modules/Club/Routes/web.php`:
   ```php
   use Modules\Club\Http\Livewire\Seasons;
   ```
4. Вернуть регистрацию в `Modules/Club/Providers/ClubServiceProvider.php`:
   ```php
   protected function registerLivewireComponents(): void
   {
       Livewire::component('club::seasons', Seasons::class);
   }
   ```

---

*Обновлено: {{ date('Y-m-d') }}*
