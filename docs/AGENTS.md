# Правила работы с проектом «Сбор» (sbor.team)

## Для AI-агентов

Данный документ описывает правила работы с проектом. Все изменения должны соответствовать этим правилам.

---

## 1. Документирование изменений

### ⚠️ ВСЕ изменения записывать только в `docs/CHANGELOG.md`

**Запрещено:**
- Создавать отдельные файлы для описания изменений
- Дублировать информацию в других местах
- Вести changelog в комментариях кода

**Обязательно:**
- Каждое значимое изменение фиксировать в `docs/CHANGELOG.md`
- Следовать формату Keep a Changelog
- Указывать добавленные/измененные/удаленные файлы
- Указывать маршруты для новых страниц

### Формат записи в CHANGELOG:
```markdown
### Added
- **Название фичи**: краткое описание
  - `путь/к/файлу.php` — что делает
  - `путь/к/шаблону.blade.php` — шаблон
  - Маршрут: `/url/path` — доступ
```

---

## 2. Структура проекта

### 📁 Документация — `docs/`
Вся документация проекта находится в папке `docs/`:
- `docs/TZ.md` — Техническое задание
- `docs/CHANGELOG.md` — История изменений
- `docs/c4/roadmap.md` — Дорожная карта
- `docs/ui-guidelines.md` — UI Guidelines (цветовая палитра)
- `docs/openapi/*.yaml` — API спецификация
- `docs/examples/` — Примеры использования

### 📁 База данных — `database/`
Все миграции находятся в `database/migrations/`:
- Именование: `YYYY_MM_DD_HHMMSS_description.php`
- Использовать `Schema::create()` для новых таблиц
- Добавлять foreign keys с `->cascadeOnDelete()` где логично
- Добавлять комментарии к таблицам через `DB::statement()`

### 📁 Модули — `Modules/`
Весь функционал реализуется через модули Laravel:
```
Modules/
├── Auth/         # Аутентификация
├── User/         # Пользователи, профили
├── Club/         # Клубы
├── Team/         # Команды, сезоны, инвайты
├── Training/     # Тренировки, площадки, объявления
├── Match/        # Матчи, live-события
├── Tournament/   # Турниры
├── Reference/    # Справочники
└── File/         # Файловое хранилище
```

**Структура модуля:**
```
Modules/ModuleName/
├── Config/
├── Database/
│   └── Migrations/
├── Http/
│   ├── Controllers/
│   │   └── V1/           # API контроллеры v1
│   ├── Requests/         # Form Request валидация
│   └── Resources/        # API Resources
├── Models/               # Eloquent модели
├── Resources/
│   └── views/            # Blade шаблоны (если есть)
├── Routes/
│   ├── api_v1.php        # API маршруты
│   └── web.php           # Web маршруты
└── Services/             # Бизнес-логика
```

### 📁 Интерфейсы — `app/Livewire/` и `resources/views/`
Веб-интерфейс реализуется на:
- **Laravel Livewire** — для интерактивных компонентов
- **Blade** — для шаблонов

**Livewire компоненты:**
- Расположение: `app/Livewire/*.php`
- Шаблоны: `resources/views/livewire/*.blade.php`
- Использовать `#[Layout('layouts.app')]`
- Использовать атрибуты для валидации

---

## 3. UI Guidelines — Обязательно к применению

### Цветовая палитра (из `docs/ui-guidelines.md`)

| Цвет | HEX | Использование |
|------|-----|---------------|
| **Primary** | `#8fbd56` | Кнопки, активные элементы |
| **Primary Dark** | `#6d9e3a` | Hover состояния |
| **Primary Light** | `#f0fdf4` | Фон выделенных блоков |
| **Background** | `#f8f9fa` | Фон страницы |
| **Card** | `#ffffff` | Фон карточек |
| **Text Primary** | `#1f2937` | Основной текст |
| **Text Secondary** | `#6b7280` | Вторичный текст |
| **Border** | `#e5e7eb` | Границы |

### CSS-классы (использовать при разработке)

**Кнопки:**
```css
/* Primary */
.btn-primary-custom {
    background: #8fbd56;
    border: none;
    color: #fff;
    font-weight: 600;
    padding: 10px 22px;
    border-radius: 10px;
}
.btn-primary-custom:hover { background: #6d9e3a; }

/* Outline */
.btn-outline-custom {
    background: #fff;
    border: 1.5px solid #8fbd56;
    color: #6d9e3a;
    font-weight: 600;
    padding: 8px 20px;
    border-radius: 10px;
}
.btn-outline-custom:hover { background: #f0fdf4; }
```

**Карточки:**
```css
.card-custom {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 20px;
    transition: all 0.2s;
}
.card-custom:hover {
    border-color: #8fbd56;
    box-shadow: 0 4px 16px rgba(143, 189, 86, 0.08);
}
```

**Формы:**
```css
.form-control-custom {
    border-radius: 10px;
    border: 1.5px solid #e5e7eb;
    padding: 10px 14px;
    font-size: 0.9rem;
}
.form-control-custom:focus {
    border-color: #8fbd56;
    box-shadow: 0 0 0 3px rgba(143, 189, 86, 0.1);
}
```

### Принципы UI:
1. **Консистентность** — только цвета из палитры
2. **Иерархия** — Primary для главных действий
3. **Отступы** — кратные 4px (4, 8, 12, 16, 20, 24)
4. **Скругление** — 8-12px для кнопок, 12-16px для карточек
5. **Иконки** — Feather Icons (24x24, stroke-width="2")

---

## 4. API разработка

### OpenAPI спецификация
- Все endpoints документировать в `docs/openapi/*.yaml`
- Именование файлов: `{module}.yaml`
- Версионирование: `v1` (текущая)

### Контроллеры
- Расположение: `Modules/{Module}/Http/Controllers/V1/`
- Формат ответа: JSON API (resources)
- Коды ошибок: 400, 401, 403, 404, 422, 500

### Роуты
- API: `Modules/{Module}/Routes/api_v1.php`
- Префикс: `api/v1/`
- Middleware: `auth:sanctum` для защищенных

---

## 5. Код-стиль

### PHP (Laravel)
- PSR-12
- Строгая типизация: `declare(strict_types=1);`
- Type hints для параметров и возвращаемых значений
- PHPDoc для сложных методов

### Именование
- Классы: `PascalCase`
- Методы/переменные: `camelCase`
- Константы: `UPPER_SNAKE_CASE`
- Таблицы БД: `snake_case`, множественное число

---

## 6. Git коммиты (для человека)

Формат: `type: описание на русском`

Типы:
- `feat:` — новая функциональность
- `fix:` — исправление бага
- `docs:` — документация
- `refactor:` — рефакторинг
- `chore:` — технические задачи

Примеры:
```
feat: импорт игроков из Excel
fix: исправлена валидация телефона в регистрации
docs: обновлен CHANGELOG
```

---

## 7. Чеклист перед завершением задачи

- [ ] Код протестирован локально
- [ ] Изменения записаны в `docs/CHANGELOG.md`
- [ ] UI соответствует `docs/ui-guidelines.md`
- [ ] API endpoints документированы (если добавлены)
- [ ] Миграции протестированы (`php artisan migrate:fresh --seed`)
- [ ] Нет жестко закодированных значений (всё в конфигах/константах)
- [ ] Права доступа проверены (Policies/Gates)

---

*Документ создан: 2026-03-13*  
*Последнее обновление: 2026-03-13*
