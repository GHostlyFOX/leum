# Changelog

Все значимые изменения API документируются в этом файле.

Формат основан на [Keep a Changelog](https://keepachangelog.com/ru/1.0.0/).

## [Unreleased]

### Added
- Добавлены C4 диаграммы компонентов для Web и Mobile приложений
- Добавлено техническое задание в формате Markdown (`TZ.md`)
- Добавлены примеры использования API (curl и Postman коллекция)
- **Новые OpenAPI спецификации:**
  - `venue.yaml` — CRUD для мест проведения (площадки)
  - `season.yaml` — Управление спортивными сезонами
  - `invite.yaml` — Пригласительные ссылки и вступление в команду
  - `announcement.yaml` — Объявления клуба/команды
  - `event-response.yaml` — RSVP/отклики на тренировки и матчи
  - `recurring-training.yaml` — Шаблоны регулярных тренировок
- **Полная реализация REST API по OpenAPI контрактам:**
  - Созданы модели: `Season`, `InviteLink`, `Announcement`, `EventResponse`
  - Созданы API Resources для форматирования ответов
  - Созданы контроллеры: `SeasonController`, `VenueController`, `InviteController`, `AnnouncementController`, `EventResponseController`, `RecurringTrainingController`
  - Реализовано 90+ API endpoints согласно OpenAPI спецификациям
  - Документация: [`API_IMPLEMENTATION_SUMMARY.md`](docs/API_IMPLEMENTATION_SUMMARY.md)

### Changed
- Реструктуризация документации: удалена папка `docs/api/`, оставлена только `docs/openapi/`
- **Roadmap:** `roadmap.puml` заменён на `roadmap.md` — теперь используется Mermaid Gantt + Markdown таблицы вместо PlantUML (лучше поддержка в различных рендерерах)

### Fixed
- **C4 Diagrams:** Исправлены includes для офлайн-работы — C4-PlantUML библиотека скачана локально в `docs/c4/lib/`, все диаграммы используют `!include lib/C4_*.puml`
- **match.yaml:** Добавлены поля `game_location` (enum: home/away), `score_home`, `score_away`, `score_mode` (auto/manual). Поле `is_away` помечено как deprecated.
- **user.yaml:** Добавлены поля `onboarded_at` и `timezone`.
- **team.yaml:** Добавлено поле `team_color` (HEX цвет).
- **reference.yaml:** Добавлены endpoints `/refs/training-types` и `/refs/tournament-types`.

### API Completeness
- ✅ Достигнуто полное соответствие между OpenAPI спецификациями и миграциями БД (16 миграций = 100% покрытие API)

## [1.0.0] - 2024-XX-XX

### Added

#### Auth API
- `POST /auth/register` — Регистрация нового пользователя
- `POST /auth/login` — Вход в систему
- `POST /auth/refresh` — Обновление пары токенов
- `POST /auth/logout` — Выход из системы
- `POST /auth/forgot-password` — Запрос сброса пароля
- `POST /auth/reset-password` — Сброс пароля по токену
- `GET /me` — Получение текущего пользователя

#### Users API
- `GET /users` — Список пользователей
- `GET /users/{id}` — Получение пользователя по ID
- `PUT /users/{id}` — Обновление пользователя
- `POST /users/{id}/player-profile` — Создание профиля игрока
- `POST /users/{id}/coach-profile` — Создание профиля тренера
- `GET /players` — Список игроков
- `GET /players/{id}` — Профиль игрока
- `PUT /players/{id}` — Обновление профиля игрока
- `DELETE /players/{id}` — Удаление профиля игрока
- `GET /coaches` — Список тренеров
- `GET /coaches/{id}` — Профиль тренера
- `PUT /coaches/{id}` — Обновление профиля тренера
- `DELETE /coaches/{id}` — Удаление профиля тренера

#### Clubs API
- `GET /clubs` — Список клубов с фильтрацией
- `POST /clubs` — Создание клуба (multipart с логотипом)
- `GET /clubs/{id}` — Получение клуба по ID
- `PUT /clubs/{id}` — Обновление клуба
- `DELETE /clubs/{id}` — Удаление клуба

#### Teams API
- `GET /clubs/{clubId}/teams` — Команды клуба
- `POST /clubs/{clubId}/teams` — Создание команды
- `GET /teams/{id}` — Получение команды по ID
- `PUT /teams/{id}` — Обновление команды
- `DELETE /teams/{id}` — Удаление команды
- `POST /teams/{teamId}/members` — Добавление участника в команду

#### Trainings API
- `GET /trainings` — Список тренировок с фильтрацией
- `POST /trainings` — Создание тренировки
- `GET /trainings/{id}` — Получение тренировки по ID
- `PUT /trainings/{id}` — Обновление тренировки
- `POST /trainings/{id}/cancel` — Отмена тренировки
- `PATCH /trainings/{trainingId}/attendance/{playerUserId}` — Отметка посещаемости

#### Matches API
- `GET /matches` — Список матчей с фильтрацией
- `POST /matches` — Создание матча
- `GET /matches/{id}` — Получение матча по ID
- `PUT /matches/{id}` — Обновление матча
- `POST /matches/{id}/start` — Начало матча
- `POST /matches/{id}/end` — Завершение матча
- `POST /matches/{id}/events` — Добавление события матча
- `PUT /matches/{id}/lineup` — Установка состава

#### Tournaments API
- `GET /tournaments` — Список турниров
- `POST /tournaments` — Создание турнира
- `GET /tournaments/{id}` — Получение турнира по ID
- `PUT /tournaments/{id}` — Обновление турнира
- `DELETE /tournaments/{id}` — Удаление турнира
- `POST /tournaments/{id}/teams` — Регистрация команды в турнире

#### References API (публичные)
- `GET /refs/sport-types` — Виды спорта
- `GET /refs/club-types` — Типы клубов
- `GET /refs/user-roles` — Роли пользователей
- `GET /refs/positions` — Позиции игроков
- `GET /refs/dominant-feet` — Варианты ведущей ноги
- `GET /refs/kinship-types` — Типы родства
- `GET /refs/match-event-types` — Типы событий матча
- `GET /refs/countries` — Страны
- `GET /refs/cities` — Города

### Security
- Bearer Token аутентификация (Laravel Sanctum)
- RBAC (Role-Based Access Control) для разграничения прав

---

## API Versions

| Версия | Дата | Статус |
|--------|------|--------|
| 1.0.0 | 2024-XX-XX | Активная |

## Legend

- **Added** — Новый функционал
- **Changed** — Изменения в существующем функционале
- **Deprecated** — Функционал, который будет удалён
- **Removed** — Удалённый функционал
- **Fixed** — Исправления ошибок
- **Security** — Изменения, связанные с безопасностью
