# Детская лига — платформа управления детским спортом

Веб и мобильная платформа для управления детскими спортивными клубами: команды, тренировки, матчи, турниры, посещаемость и уведомления.

---

## Стек технологий

| Слой              | Технологии                              |
|-------------------|-----------------------------------------|
| Backend           | PHP 8 / Laravel, REST API               |
| Веб-интерфейс     | Laravel + Vue.js (SPA/SSR)              |
| Мобильное приложение | React Native / Flutter (Android + iOS) |
| База данных       | PostgreSQL 15                           |
| Файловое хранилище | S3 / локальное (фото, логотипы)        |
| Очередь задач     | Laravel Queue / Redis                   |
| Планировщик       | Laravel Scheduler / Cron                |
| Push-уведомления  | FCM (Android) / APNs (iOS)              |
| Бот-уведомления   | Telegram Bot API                        |
| Авторизация OAuth | Google Identity / Sign in with Apple    |

---

## Структура проекта

```
/
├── app/                        — Laravel-приложение (контроллеры, модели, сервисы)
├── database/
│   └── migrations/             — Миграции PostgreSQL (4 файла, порядок описан ниже)
├── docs/
│   ├── c4/                     — Архитектурные диаграммы C4 Model (PlantUML)
│   │   ├── c1_context.puml     — Level 1: System Context
│   │   ├── c2_container.puml   — Level 2: Container
│   │   ├── c3_component_api.puml    — Level 3: Backend API
│   │   ├── c3_component_mobile.puml — Level 3: Mobile App
│   │   ├── er_01_users_clubs_teams.puml  — ER: пользователи, клубы, команды
│   │   ├── er_02_trainings.puml          — ER: тренировки
│   │   └── er_03_matches_tournaments.puml — ER: матчи и турниры
│   ├── sql/                    — Оптимизированные SQL-схемы PostgreSQL
│   │   ├── schema_01_users_clubs_teams.sql
│   │   ├── schema_02_trainings.sql
│   │   └── schema_03_matches_tournaments.sql
│   └── png/                    — PNG-рендеры архитектурных диаграмм
├── resources/                  — Шаблоны и фронтенд-ресурсы
├── routes/                     — Маршруты API и веб
└── ...
```

---

## Документация

### Архитектура (C4 Model)

Диаграммы описывают систему на четырёх уровнях детализации по [C4 Model](https://c4model.com) (Simon Brown). Файлы находятся в `docs/c4/`.

#### Level 1 — System Context [`docs/c4/c1_context.puml`](docs/c4/c1_context.puml)

Система в окружении пользователей и внешних сервисов.

**Роли пользователей:** Администратор, Тренер, Родитель, Игрок

**Внешние интеграции:** FCM/APNs, Google/Apple Calendar, Telegram, Google/Apple OAuth2, Excel/CSV

#### Level 2 — Container [`docs/c4/c2_container.puml`](docs/c4/c2_container.puml)

| Контейнер              | Технологии               | Описание                                       |
|------------------------|--------------------------|------------------------------------------------|
| Веб-приложение         | PHP 8 / Laravel + Vue.js | SPA/SSR интерфейс для администратора и тренера |
| Мобильное приложение   | React Native / Flutter   | Кроссплатформенное приложение (Android + iOS)  |
| Backend API            | PHP 8 / Laravel (REST)   | Бизнес-логика, поддержка всех ролей            |
| База данных            | PostgreSQL 15            | Основное хранилище данных                      |
| Файловое хранилище     | S3 / локальное           | Фотографии игроков и тренеров, логотипы команд |
| Очередь задач          | Laravel Queue / Redis    | Асинхронные уведомления, импорт данных         |
| Планировщик            | Laravel Scheduler / Cron | Напоминания, авто-создание тренировок          |

#### Level 3 — Backend API [`docs/c4/c3_component_api.puml`](docs/c4/c3_component_api.puml)

| Компонент            | Ответственность                                                   |
|----------------------|-------------------------------------------------------------------|
| Auth Module          | Регистрация, вход, OAuth2 (Google / Apple), RBAC                  |
| Club & Team Module   | CRUD клубов и команд                                              |
| People Module        | Игроки, тренеры, родители, фото, приглашения                      |
| Training Module      | Расписание, шаблоны, посещаемость, причины отсутствия             |
| Tournament & Match   | Турниры трёх типов, таймер матча, голы и ассисты в реальном времени |
| Statistics Module    | Агрегация посещаемости и результатов                              |
| Notification Module  | Push (FCM/APNs) и Telegram; настройки пользователя                |
| Calendar Module      | Экспорт в Google/Apple Calendar (iCal)                            |
| Import/Export Module | Импорт игроков из Excel, экспорт отчётов                          |
| File Storage Module  | Загрузка и хранение файлов                                        |
| Directory Module     | Справочники: виды спорта, позиции, типы тренировок                |

#### Level 3 — Mobile App [`docs/c4/c3_component_mobile.puml`](docs/c4/c3_component_mobile.puml)

| Компонент               | Ответственность                                          |
|-------------------------|----------------------------------------------------------|
| Navigation & Auth Guard | Роутинг, защита маршрутов по роли, global state          |
| Auth Screen             | Вход, OAuth2, Secure Storage токена                      |
| Home / Dashboard        | Сводка по ближайшим событиям                             |
| Team Screen             | Состав, карточки игроков и тренеров                      |
| Calendar Screen         | Расписание в режиме календаря и списка                   |
| Attendance Screen       | Подтверждение/отмена присутствия, причины отсутствия     |
| Training Detail Screen  | Детали тренировки, список участников                     |
| Match & Tournament      | Карточка матча, live-таймер, голы, результаты            |
| Statistics Screen       | Посещаемость, голы, ассисты, игровое время               |
| Notifications Screen    | Уведомления, настройки отключения                        |
| Offline Sync Layer      | SQLite/Hive кэш, очередь отложенных запросов             |
| API Client              | REST + WebSocket, interceptors, retry                    |
| Push Handler            | FCM/APNs SDK, deep-link навигация                        |

---

### Схемы базы данных

Все схемы оптимизированы под **PostgreSQL 15**: нативные ENUM-типы, JSONB, TIMESTAMPTZ, нет AUTO_INCREMENT. Файлы находятся в `docs/sql/`.

#### Схема 1 — Пользователи, клубы и команды [`docs/sql/schema_01_users_clubs_teams.sql`](docs/sql/schema_01_users_clubs_teams.sql)

Основа системы. Покрывает все сущности людей и организаций.

| Таблица               | Назначение                                                        |
|-----------------------|-------------------------------------------------------------------|
| `ref_sport_types`     | Справочник видов спорта                                           |
| `ref_club_types`      | Справочник типов клубов (частный, государственный, академия)      |
| `ref_user_roles`      | Справочник ролей пользователей (admin, coach, player, parent)     |
| `ref_positions`       | Игровые позиции, привязанные к виду спорта                        |
| `ref_dominant_feet`   | Рабочая нога игрока (left, right, both)                           |
| `ref_kinship_types`   | Виды родства (мать, отец, опекун)                                 |
| `countries`           | Справочник стран                                                  |
| `cities`              | Справочник городов с привязкой к стране                           |
| `files`               | Централизованный реестр файлов (S3-ключи, MIME, размер)           |
| `users`               | Аккаунты пользователей всех ролей                                 |
| `user_parent_player`  | Связь родитель ↔ ребёнок-игрок с типом родства                    |
| `clubs`               | Клубы с телефонами (JSONB), географией, логотипом                 |
| `teams`               | Команды внутри клуба по году рождения и полу                      |
| `team_members`        | Состав команды: пользователь + роль + команда                     |
| `player_profiles`     | Игровой профиль: нога, позиция, вид спорта                        |
| `coach_profiles`      | Тренерский профиль: специализация, лицензия, достижения (JSONB)   |

**ENUM-типы:** `user_gender` (male, female) · `team_gender` (boys, girls, mixed)

**ER-диаграмма:** [`docs/c4/er_01_users_clubs_teams.puml`](docs/c4/er_01_users_clubs_teams.puml)

---

#### Схема 2 — Тренировки [`docs/sql/schema_02_trainings.sql`](docs/sql/schema_02_trainings.sql)

Расписание, шаблоны регулярных тренировок, посещаемость и медиа. Зависит от схемы 1.

| Таблица               | Назначение                                                        |
|-----------------------|-------------------------------------------------------------------|
| `ref_training_types`  | Виды тренировок, задаются каждым клубом индивидуально             |
| `venues`              | Места проведения (стадионы, залы); общедоступные — без клуба      |
| `recurring_trainings` | Шаблоны регулярных тренировок с расписанием и правилами (JSONB)   |
| `trainings`           | Конкретные занятия (разовые или из шаблона)                       |
| `training_attendance` | Посещаемость: pending / present / absent, причина отсутствия      |
| `training_media`      | Медиафайлы (фото/видео), прикреплённые к тренировке               |

**ENUM-типы:** `training_status` (scheduled, completed, cancelled) · `attendance_status` (pending, present, absent)

**ER-диаграмма:** [`docs/c4/er_02_trainings.puml`](docs/c4/er_02_trainings.puml)

---

#### Схема 3 — Матчи и турниры [`docs/sql/schema_03_matches_tournaments.sql`](docs/sql/schema_03_matches_tournaments.sql)

Товарищеские матчи и турниры трёх типов с событиями в реальном времени. Зависит от схем 1 и 2.

| Таблица                | Назначение                                                       |
|------------------------|------------------------------------------------------------------|
| `ref_tournament_types` | Виды турниров (чемпионат, кубок и т.д.), привязанные к спорту    |
| `ref_match_event_types`| Типы событий матча (гол, ассист, карточка, сейв)                 |
| `opponents`            | Внешние команды-соперники, не зарегистрированные в системе       |
| `tournaments`          | Турниры с регламентом (таймы, продолжительность)                 |
| `tournament_teams`     | Команды, заявленные на турнир                                    |
| `matches`              | Матчи; соперник — или внутренняя команда, или внешний `opponent` |
| `match_coaches`        | Тренерский штаб на матч                                          |
| `match_players`        | Заявка игроков (стартовый/запасной, позиция)                     |
| `match_events`         | События по минутам: голы, карточки, сейвы                        |

**ENUM-типы:** `match_type` (friendly, tournament_group, tournament_playoff) · `tournament_entry_status` (participating, disqualified)

**ER-диаграмма:** [`docs/c4/er_03_matches_tournaments.puml`](docs/c4/er_03_matches_tournaments.puml)

---

### Миграции Laravel

Находятся в `database/migrations/`. Порядок запуска строго фиксирован зависимостями.

| Файл                                              | Содержимое                                               |
|---------------------------------------------------|----------------------------------------------------------|
| `2025_06_01_000001_schema01_enums_refs_geo_files.php` | ENUM-типы, справочники, geography, files            |
| `2025_06_01_000002_schema01_users_clubs_teams.php`    | users, clubs, teams, team_members, profiles         |
| `2025_06_01_000003_schema02_trainings.php`            | venues, recurring_trainings, trainings, attendance  |
| `2025_06_01_000004_schema03_matches_tournaments.php`  | tournaments, matches, events                        |

```bash
php artisan migrate
```

> Все ENUM-колонки создаются как `VARCHAR`, после чего приводятся к PostgreSQL-типу через `ALTER TABLE … ALTER COLUMN … TYPE … USING`.
> Каждая таблица и значимая колонка снабжены `COMMENT ON` для отображения в pgAdmin / DBeaver.

---

## Как открыть PlantUML-диаграммы

**VS Code** — установить расширение [PlantUML](https://marketplace.visualstudio.com/items?itemName=jebbs.plantuml), нажать `Alt+D` для превью.

**IntelliJ IDEA / PhpStorm** — установить плагин [PlantUML Integration](https://plugins.jetbrains.com/plugin/7017-plantuml-integration).

**Онлайн** — вставить содержимое файла на [plantuml.com](https://www.plantuml.com/plantuml/uml/) или [kroki.io](https://kroki.io).

**CLI:**
```bash
java -jar plantuml.jar docs/c4/c1_context.puml
```

---

## Backlog диаграмм

- `c3_component_web.puml` — компоненты веб-приложения (Vue/Blade)
- `sequence_match_live.puml` — Sequence: фиксация гола в реальном времени
- `sequence_invite.puml` — Sequence: приглашение тренера по email
- `sequence_rsvp.puml` — Sequence: подтверждение посещения тренировки

---

*Документация составлена по ТЗ «Детская лига» · [C4 Model](https://c4model.com) · PostgreSQL 15*
