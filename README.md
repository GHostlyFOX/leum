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
│   │   ├── lib/                — C4-PlantUML библиотека (локальная)
│   │   ├── c1_context.puml     — Level 1: System Context
│   │   ├── c2_container.puml   — Level 2: Container
│   │   ├── c3_component_api.puml    — Level 3: Backend API
│   │   ├── c3_component_web.puml    — Level 3: Web Application
│   │   ├── c3_component_mobile.puml — Level 3: Mobile App
│   │   ├── er_01_users_clubs_teams.puml  — ER: пользователи, клубы, команды
│   │   ├── er_02_trainings.puml          — ER: тренировки
│   │   ├── er_03_matches_tournaments.puml — ER: матчи и турниры
│   │   ├── data_flow.puml      — Карта движения данных
│   │   ├── journey_*.puml      — User Journeys для всех ролей
│   │   ├── sequence_*.puml     — Sequence диаграммы
│   │   └── roadmap.puml        — Дорожная карта разработки
│   ├── sql/                    — Оптимизированные SQL-схемы PostgreSQL
│   │   ├── schema_01_users_clubs_teams.sql
│   │   ├── schema_02_trainings.sql
│   │   └── schema_03_matches_tournaments.sql
│   ├── openapi/                — OpenAPI 3.0.3 спецификация
│   │   ├── auth.yaml              — Аутентификация
│   │   ├── user.yaml              — Пользователи и профили
│   │   ├── club.yaml              — Клубы
│   │   ├── team.yaml              — Команды
│   │   ├── training.yaml          — Тренировки
│   │   ├── match.yaml             — Матчи
│   │   ├── tournament.yaml        — Турниры
│   │   ├── venue.yaml             — Места проведения (площадки)
│   │   ├── season.yaml            — Сезоны
│   │   ├── invite.yaml            — Пригласительные ссылки
│   │   ├── announcement.yaml      — Объявления
│   │   ├── event-response.yaml    — RSVP/отклики на события
│   │   ├── recurring-training.yaml — Шаблоны регулярных тренировок
│   │   ├── reference.yaml         — Справочники
│   │   └── file.yaml              — Файловый сервис
│   ├── examples/               — Примеры использования API
│   │   ├── curl/               — curl команды
│   │   └── postman/            — Postman коллекция
│   ├── CHANGELOG.md            — История изменений API
│   ├── TZ.md                   — Техническое задание
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
| Веб-приложение         | PHP 8 / Laravel + Vue.js | SPA/SSR интерфейс для всех ролей (админ, тренер, родитель, игрок) |
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

#### Level 3 — Web Application [`docs/c4/c3_component_web.puml`](docs/c4/c3_component_web.puml)

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

---

## Карта движения данных

[`docs/c4/data_flow.puml`](docs/c4/data_flow.puml) — полная карта всех потоков данных в системе.

Показывает 5 слоёв: **Акторы → Фронтенды → Backend API-модули → Хранилища (PostgreSQL, S3, Redis) → Внешние сервисы** (FCM/APNs, Telegram, OAuth, Calendar). Каждая стрелка подписана типом передаваемых данных или вызовом.

Ключевые потоки:

- **Авторизация**: Web/Mobile → AuthModule → PostgreSQL / Google OAuth / Apple Sign In
- **Файлы**: Upload → FileModule → S3 → `files` table
- **Уведомления**: любое событие → Redis Queue → NotifyModule → FCM/APNs + Telegram
- **RSVP**: Parent Mobile → API → `training_attendance` / `match_players`
- **Live-матч**: Coach Mobile → MatchModule → `match_events` → Queue → Push родителям
- **Авто-расписание**: Cron/Scheduler → TrainingModule → генерация `trainings` из `recurring_trainings`

---

## Пользовательские пути

### Администратор — веб [`docs/c4/journey_admin_web.puml`](docs/c4/journey_admin_web.puml)

Activity-диаграмма полного пути администратора с разбивкой на 8 блоков:

- **Вход**: email/password или OAuth (первичная регистрация → онбординг)
- **Онбординг (4 шага)**: клуб → команда → тренер + приглашение → игроки (форма или Excel-импорт)
- **Ежедневная работа**: управление тренировками, матчами/турнирами, составом, статистикой, настройками

### Тренер — веб + мобильный [`docs/c4/journey_coach.puml`](docs/c4/journey_coach.puml)

6 параллельных потоков в двух каналах (веб и мобильный):

- **[Веб]** Создание разовой / регулярной тренировки с уведомлениями
- **[Мобильный]** День тренировки: просмотр RSVP-статусов → отметка посещаемости → завершение
- **[Веб]** Создание матча (товарищеский / в турнире) + формирование состава
- **[Мобильный]** Day-of-match: запуск таймера → фиксация голов/карточек → завершение → пуш-итог
- **[Веб]** Статистика команды и игроков
- **[Веб]** Управление карточками игроков

### Родитель — мобильный + веб [`docs/c4/journey_parent.puml`](docs/c4/journey_parent.puml)

7 потоков доступных через мобильное приложение **и** веб-сайт:

- **Установка / вход**: получение ссылки-приглашения → активация аккаунта → вход через приложение или браузер
- **RSVP на тренировку**: подтверждение / причина отсутствия (с авто-продлением при травме)
- **RSVP на матч**: подтверждение / причина (активная травма подставляется автоматически)
- **Календарь**: переключение месяц/список, переход к тренировке / матчу / турниру
- **Статистика ребёнка**: посещаемость + матчевая статистика по периодам
- **Live-уведомления**: гол → итог матча → переход на live-экран
- **Настройки**: управление push/Telegram-уведомлениями по типам

### Игрок — мобильный + веб [`docs/c4/journey_player.puml`](docs/c4/journey_player.puml)

Доступно через мобильное приложение **и** веб-сайт:

- Расписание (read-only, RSVP делает родитель или тренер)
- Live-экран матча с real-time лентой событий
- Личная статистика: тренировки + матчи по сезонам
- Состав команды + контакты тренеров
- Push-уведомления с deep-link навигацией (мобильный) / уведомления в браузере (веб)

---

## Sequence-диаграммы

### Онбординг клуба [`docs/c4/sequence_onboarding.puml`](docs/c4/sequence_onboarding.puml)

Полный sequence от регистрации администратора до укомплектованной команды:
регистрация (email / OAuth) → создание клуба (+ логотип в S3) → создание команды → добавление тренера + dispatch invite email → добавление игроков через форму или пакетный Excel-импорт (transaction + bulk invites).

### Жизненный цикл тренировки [`docs/c4/sequence_training.puml`](docs/c4/sequence_training.puml)

Шесть фаз:
1. Создание регулярного шаблона → Cron генерирует тренировки + `training_attendance` (status=pending)
2. Push/Telegram уведомление родителям
3. RSVP: present / absent + авто-продление при травме на N тренировок
4. День тренировки: тренер видит статусы RSVP → проставляет фактическую посещаемость
5. Альтернатива: изменение / отмена + повторная рассылка
6. Статистика посещаемости по игрокам за период

### Live-матч [`docs/c4/sequence_match_live.puml`](docs/c4/sequence_match_live.puml)

Семь фаз полного матчевого дня:
1. Создание матча (тип, соперник, место, регламент)
2. Состав: RSVP от родителей или авто-выбор допущенных игроков
3. Напоминание за 24 часа до матча
4. Запуск таймера → Push «Матч начался»
5. Live-события: гол → `match_events` → счёт → Push «Гол! 1:0, 23 мин» в реальном времени
6. Завершение → пересчёт итогового счёта → Push «Победа 2:1»
7. Агрегация статистики: голы, ассисты, минуты на поле

---

## Дорожная карта

[`docs/c4/roadmap.puml`](docs/c4/roadmap.puml) — Gantt-диаграмма разработки от первого коммита до полной платформы.

| Фаза | Длительность | Результат |
|------|-------------|-----------|
| 0 — Инфраструктура | 2 нед. | Сервер, БД, CI/CD, Laravel-скелет |
| 1 — MVP Ядро | 6 нед. | Клуб + команды + игроки + Excel-импорт + приглашения |
| 2 — Тренировки | 5 нед. | Расписание + Cron + RSVP (веб + API) + авто-травма + Push |
| 3 — Матчи и турниры | 6 нед. | Все типы матчей + live-таймер + push + веб-RSVP/live для родителей |
| 4 — Статистика и экспорт | 3 нед. | Отчёты + Excel-экспорт + iCal + веб-уведомления для всех ролей |
| **MVP Web** | **~22 нед.** | **Веб-платформа для всех ролей готова к пилоту** |
| 5 — Мобильное приложение | 8 нед. | React Native/Flutter: RSVP + live + push + offline |
| **MVP Mobile** | **~30 нед.** | **App Store + Google Play** |
| 6 — Расширенный функционал | backlog | Галерея, чат, авто-сетка playoff, мультиклуб |

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

#### Level 3 — Web Application [`docs/c4/c3_component_web.puml`](docs/c4/c3_component_web.puml)

| Компонент               | Ответственность                                          |
|-------------------------|----------------------------------------------------------|
| Auth Pages              | Логин, OAuth2, регистрация, активация по приглашению     |
| Dashboard               | Главная страница с адаптацией по роли                    |
| Onboarding Wizard       | Пошаговое создание клуба и команды (админ)               |
| Club & Team Management  | CRUD клубов и команд, управление составом                |
| People Management       | Карточки игроков/тренеров, Excel-импорт                  |
| Training Pages          | Расписание, посещаемость, RSVP (все роли)                |
| Calendar                | Расписание в виде календаря/списка (все роли)            |
| Match & Tournament      | Матчи, турниры, live-таймер (все роли)                   |
| RSVP / Attendance       | Подтверждение присутствия через веб (родители)           |
| Statistics Page         | Статистика, экспорт Excel (все роли)                     |
| Notifications           | Список уведомлений, настройки push/Telegram (все роли)   |
| API Client (Axios)      | REST + WebSocket клиент                                  |

---

## Backlog диаграмм

- `sequence_invite.puml` — Sequence: приглашение тренера по email
- `sequence_rsvp.puml` — Sequence: подтверждение посещения тренировки

---

---

## API Documentation

### OpenAPI 3.0.3

Полная спецификация API находится в `docs/openapi/`:

| Файл | Описание |
|------|----------|
| [`auth.yaml`](docs/openapi/auth.yaml) | Аутентификация: регистрация, вход, refresh, сброс пароля |
| [`user.yaml`](docs/openapi/user.yaml) | Пользователи, профили игроков и тренеров |
| [`club.yaml`](docs/openapi/club.yaml) | Управление клубами |
| [`team.yaml`](docs/openapi/team.yaml) | Управление командами и составом |
| [`training.yaml`](docs/openapi/training.yaml) | Тренировки и посещаемость |
| [`match.yaml`](docs/openapi/match.yaml) | Матчи, события, составы |
| [`tournament.yaml`](docs/openapi/tournament.yaml) | Турниры и регистрация команд |
| [`venue.yaml`](docs/openapi/venue.yaml) | Места проведения (площадки) |
| [`season.yaml`](docs/openapi/season.yaml) | Спортивные сезоны |
| [`invite.yaml`](docs/openapi/invite.yaml) | Пригласительные ссылки |
| [`announcement.yaml`](docs/openapi/announcement.yaml) | Объявления клуба/команды |
| [`event-response.yaml`](docs/openapi/event-response.yaml) | RSVP/отклики на события |
| [`recurring-training.yaml`](docs/openapi/recurring-training.yaml) | Шаблоны регулярных тренировок |
| [`reference.yaml`](docs/openapi/reference.yaml) | Справочники (публичные endpoint) |
| [`file.yaml`](docs/openapi/file.yaml) | Файловый сервис (внутренний) |

### История изменений и аудит

- [`CHANGELOG.md`](docs/CHANGELOG.md) — История изменений API (Keep a Changelog)
- [`API_MIGRATION_AUDIT.md`](docs/API_MIGRATION_AUDIT.md) — Аудит соответствия OpenAPI и миграций БД (100% покрытие)

### Примеры использования

Примеры запросов доступны в `docs/examples/`:
- [`curl/`](docs/examples/curl/) — команды curl для всех основных операций
- [`postman/`](docs/examples/postman/) — Postman коллекция

---

## Техническое задание

Исходное ТЗ в формате Markdown: [`docs/TZ.md`](docs/TZ.md)

---

*Документация составлена по ТЗ «Детская лига» · [C4 Model](https://c4model.com) · PostgreSQL 15*
