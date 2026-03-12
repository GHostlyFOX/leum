# Changelog — Сбор (sbor.team)

Все значимые изменения проекта документируются в этом файле.
Формат основан на [Keep a Changelog](https://keepachangelog.com/ru/1.0.0/).

---

## [Unreleased]

### Added

- Добавлены C4 диаграммы компонентов для Web и Mobile приложений
- Добавлено техническое задание в формате Markdown (`TZ.md`)
- Добавлены примеры использования API (curl и Postman коллекция)
- **Новые OpenAPI спецификации:** `venue.yaml`, `season.yaml`, `invite.yaml`, `announcement.yaml`, `event-response.yaml`, `recurring-training.yaml`
- **Полная реализация REST API по OpenAPI контрактам:** 8 модулей, 90+ endpoints, 6 моделей, 6 API Resources, 6 контроллеров (подробности см. ниже в разделе «API Implementation»)
- **Модуль Seasons (Livewire):** полный CRUD сезонов — создание, редактирование, удаление с подтверждением и предупреждением о каскадном удалении тренировок и турниров
- Модальное окно создания сезона с главной страницы (онбординг-чеклист)
- Фильтрация сезонов по статусу (запланирован / активный / архивный)
- Привязка команд к сезонам (M:N через `season_teams`)
- **Главная страница клуба (`/club`):** дашборд со статистикой, командами (с количеством игроков), активными сезонами, тренерами и площадками
- **Новый логотип:** зелёно-чёрная цветовая схема (`#74bc1f` / `#000000`), inline SVG в шапке сайта, favicon для браузера

### Changed

- Реструктуризация документации: удалена папка `docs/api/`, оставлена только `docs/openapi/`
- `roadmap.puml` заменён на `roadmap.md` — Mermaid Gantt + Markdown вместо PlantUML
- Класс `App\Livewire\Index` переименован в `App\Livewire\Dashboard` (решение конфликта классов)
- Маршрут главной: `Route::get('dashboard', Dashboard::class)`
- Обновлена цветовая гамма всего интерфейса на единую зелёную палитру (`#8fbd56` / `#6d9e3a`)
- Удалены `->namespace()` из `mapWebRoutes()` во всех RouteServiceProvider модулей для корректной работы Livewire-маршрутов
- Все Module Service Providers зарегистрированы в `config/app.php`

### Fixed

- **C4 Diagrams:** исправлены includes для офлайн-работы — C4-PlantUML библиотека скачана локально
- **match.yaml:** добавлены поля `game_location`, `score_home`, `score_away`, `score_mode`; `is_away` deprecated
- **user.yaml:** добавлены поля `onboarded_at` и `timezone`
- **team.yaml:** добавлено поле `team_color` (HEX цвет)
- **reference.yaml:** добавлены endpoints `/refs/training-types` и `/refs/tournament-types`
- **Навигация:** ссылка «Главная» в боковом меню исправлена — `url('index')` → `url('dashboard')`
- **Конфликт классов «Cannot declare class App\Livewire\Index»:** файлы `public/fix-cache.php` и `public/reset.php` вызывали `class_exists('App\Livewire\Index')`, провоцируя двойную загрузку. Решение — переименование класса в `Dashboard`, очистка старого файла
- **Создание сезона (POST без результата):** `DB::table('seasons')->insert()` падал молча из-за NOT NULL constraint на `sport_type_id` и PostgreSQL ENUM типа `season_status`. Заменён на `Season::create(...)` с try/catch и проверкой `sport_type_id` клуба
- **Роут /club/seasons — Invalid route action:** класс `Seasons` перемещён в `app/Livewire/Seasons.php` для корректной автозагрузки без `composer dump-autoload`
- **Проверка ролей при создании сезона:** расширена с `role_id = 7` (admin) до `whereIn([7, 8])` (admin + coach) с fallback на любую запись в `team_members` и fallback на первый клуб для админов системы
- **Дубликаты классов:** удалён `Modules/Club/Http/Livewire/Seasons.php`, оставлен `app/Livewire/Seasons.php`

### API Completeness

Достигнуто полное соответствие между OpenAPI спецификациями и миграциями БД (16 миграций = 100% покрытие API).

---

## Обновление до Livewire 3

### Изменения зависимостей

- PHP: `^8.0` → `^8.1`
- Laravel Framework: `^9.2` → `^10.0`
- Livewire: `^2.10` → `^3.0`

### Исправления

- **Single Root Element:** Livewire 3 требует одного корневого HTML-элемента. Исправлено в 8 файлах: `index.blade.php`, `login.blade.php`, `landing.blade.php`, `onboarding.blade.php`, `users-list.blade.php`, `mail-inbox.blade.php`, `projects.blade.php`, `tasks-list.blade.php`
- **wire:model.defer:** заменён на `wire:model` (в Livewire 3 deferred — поведение по умолчанию). Проверены все 127 Livewire views

| Livewire 2 | Livewire 3 | Статус |
|------------|------------|--------|
| Несколько корневых элементов | Один корневой элемент | Исправлено |
| `wire:model.defer` | `wire:model` | Исправлено |
| `wire:model.live` | Без изменений | Проверено |

---

## Обновление цветовой гаммы интерфейса

### Палитра

| Роль | Цвет | HEX |
|------|------|-----|
| Primary | Зелёный | `#8fbd56` |
| Primary Dark | Тёмно-зелёный | `#6d9e3a` |
| Primary Light | Светло-зелёный | `#f0fdf4` |
| Background | Светло-серый | `#f8f9fa` |
| Text | Тёмно-серый | `#1f2937` |
| Border | Серый | `#e5e7eb` |

### Обновлённые файлы

- `resources/views/livewire/index.blade.php` — onboarding header, кнопки, badges
- `resources/views/livewire/invite-modal.blade.php` — tabs, inputs, кнопки
- `resources/views/livewire/join-team.blade.php` — card header, кнопки
- `resources/views/livewire/landing.blade.php` — chart color

---

## API Implementation Summary

### Модули и Endpoints (90+)

**Auth API** — register, login, refresh, logout, forgot-password, reset-password

**User API** — me, users CRUD, player-profile, coach-profile, players CRUD, coaches CRUD

**Club API** — clubs CRUD

**Team API** — teams CRUD, members, seasons CRUD (7 endpoints), invite-links (6 endpoints)

**Training API** — trainings CRUD + cancel + attendance, venues CRUD, announcements CRUD + publish, event-responses (7 endpoints), recurring-trainings CRUD + generate

**Match API** — matches CRUD + start/end + events + lineup

**Tournament API** — tournaments CRUD + registerTeam

**Reference API** — sport-types, club-types, user-roles, positions, dominant-feet, kinship-types, match-event-types, countries, cities, training-types, tournament-types

### Созданные файлы

- **Модели:** Season, InviteLink, Announcement, EventResponse
- **API Resources:** SeasonResource, VenueResource, InviteLinkResource, AnnouncementResource, EventResponseResource, RecurringTrainingResource
- **Контроллеры:** SeasonController, InviteController, VenueController, AnnouncementController, EventResponseController, RecurringTrainingController
- **Трейты:** ApiResponse

### Защита API

Все endpoints защищены `auth:sanctum` + `permission:*` (Spatie Permission).

### Формат ответов

- Успех (200/201): `{ "data": {...}, "message": "..." }`
- Список: `{ "data": [...], "meta": { "current_page", "last_page", "per_page", "total" } }`
- Ошибка (422): `{ "success": false, "message": "Validation failed", "errors": {...} }`

---

## Исправление роутов и модулей

### Проблемы

1. Пакет `nwidart/laravel-modules` не установлен в `vendor/`
2. Провайдеры модулей не были зарегистрированы в `config/app.php`
3. Использование `->namespace()` в RouteServiceProvider модулей

### Решения

- Все Module Service Providers добавлены в `config/app.php`: Auth, Club, File, Match, Reference, Team, Tournament, Training, User
- Удалены `->namespace()` из RouteServiceProvider: Auth, Match, Team, Tournament, Training, User
- Все route-файлы обновлены на FQN-синтаксис (полные имена классов)
- Дубликат `Modules/Club/Http/Livewire/Seasons.php` удалён

---

## Анализ проекта (7 марта 2026)

### Общее состояние на момент анализа

- **База данных:** 4 миграции, 40+ таблиц — 100% готова
- **Документация:** C4, ER, journey maps — 95%
- **Модели:** ~20% (15 стабов)
- **Бизнес-логика:** ~5%
- **API:** ~1%
- **Тесты:** 0%

### Выявленные проблемы

- Модели использовали устаревшие имена таблиц и колонок
- Опечатки (`sity` → `city`, `coutry` → `country`)
- Несоответствие имён справочников (`ref_type_sport` → `ref_sport_types`)
- 127 нерелевантных Livewire-компонентов от UI-кита
- Отсутствие сервисного слоя, пустые контроллеры

### Рекомендованная структура

```
Modules/
├── Auth/     (Services, Controllers, Routes)
├── Club/     (ClubService, TeamService)
├── Training/ (TrainingService, AttendanceService, VenueService)
├── Match/    (MatchService, TournamentService, MatchEventService)
├── Statistics/
├── Notification/ (EmailService, PushService, TelegramService)
└── Directory/    (Reference data)
```

---

## План запуска

### Сроки

| Веха | Недель от старта |
|------|-----------------|
| Фаза 0: Инфраструктура | 2 |
| Фаза 1: Ядро (клубы, люди) | 8 |
| Фаза 2: Тренировки | 13 |
| Фаза 3: Матчи и турниры | 19 |
| **MVP Web (все роли)** | **22** |
| MVP Mobile (App Store / Google Play) | 30 |
| Полная платформа v2.0 | ~38 |

### Принципы

- **Web-first:** веб-версия покрывает 100% функций
- **API-first:** Backend API обслуживает и веб, и мобильное приложение
- **Модульная архитектура:** nwidart/laravel-modules

---

## [1.0.0] - TBD

### Auth API

- `POST /auth/register`, `/auth/login`, `/auth/refresh`, `/auth/logout`
- `POST /auth/forgot-password`, `/auth/reset-password`
- `GET /me`

### Users API

- `GET /users`, `GET /users/{id}`, `PUT /users/{id}`
- Player/Coach profiles CRUD

### Clubs API

- `GET /clubs`, `POST /clubs`, `GET /clubs/{id}`, `PUT /clubs/{id}`, `DELETE /clubs/{id}`

### Teams API

- Teams CRUD, members management

### Trainings API

- Trainings CRUD + cancel + attendance

### Matches API

- Matches CRUD + start/end + events + lineup

### Tournaments API

- Tournaments CRUD + team registration

### References API (public)

- sport-types, club-types, user-roles, positions, dominant-feet, kinship-types, match-event-types, countries, cities

### Security

- Bearer Token аутентификация (Laravel Sanctum)
- RBAC (Role-Based Access Control)

---

## Legend

- **Added** — Новый функционал
- **Changed** — Изменения в существующем функционале
- **Deprecated** — Функционал, который будет удалён
- **Removed** — Удалённый функционал
- **Fixed** — Исправления ошибок
- **Security** — Изменения, связанные с безопасностью
