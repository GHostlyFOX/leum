# Исправление функционала Seasons

## Сделанные изменения:

### 1. Перенос Seasons в модуль Club
- **Создано**: `Modules/Club/Http/Livewire/Seasons.php`
- **Namespace**: `Modules\Club\Http\Livewire`
- **Удалено**: `app/Livewire/Seasons.php` (старый файл)

### 2. Обновлены роуты модуля Club
- **Файл**: `Modules/Club/Routes/web.php`
- Изменён `use` с `App\Livewire\Seasons` на `Modules\Club\Http\Livewire\Seasons`

### 3. Обновлен ClubServiceProvider
- Добавлена регистрация Livewire компонента: `Livewire::component('club::seasons', Seasons::class)`

### 4. View файл
- **Расположение**: `Modules/Club/Resources/views/seasons.blade.php`
- Использует layout: `layouts.app`

## Необходимые действия на сервере:

### Очистка всех кешей:
```bash
# Очистка кеша представлений
php artisan view:clear

# Очистка кеша конфигурации
php artisan config:clear

# Очистка кеша роутов
php artisan route:clear

# Очистка кеша приложения
php artisan cache:clear

# Перегенерация автозагрузки Composer
composer dump-autoload

# Перекеширование (опционально, для production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Проверка:

### URL для проверки:
- Сезоны: https://sbor.team/club/seasons
- Дашборд: https://sbor.team/dashboard

### Ожидаемое поведение:
- `/dashboard` - должен открываться без ошибок
- `/club/seasons` - должен отображать список сезонов клуба

## Структура файлов:

```
Modules/Club/
├── Http/
│   └── Livewire/
│       └── Seasons.php          # Основной компонент
├── Resources/
│   └── views/
│       └── seasons.blade.php    # Шаблон
├── Routes/
│   └── web.php                  # Роуты
└── Providers/
    └── ClubServiceProvider.php  # Регистрация компонентов
```

## Возможные проблемы:

### Ошибка "Class not found"
- Выполнить `composer dump-autoload`
- Проверить namespace в файле

### Ошибка "Cannot declare class"
- Возможно, старый файл `app/Livewire/Seasons.php` всё ещё существует
- Проверить и удалить дубликаты
- Очистить все кеши

### 404 на странице /club/seasons
- Проверить, что модуль Club активирован
- Проверить `modules_statuses.json`
