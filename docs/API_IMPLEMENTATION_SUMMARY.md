# Реализация REST API по OpenAPI контрактам

## Сводка

Реализованы все REST API endpoints согласно OpenAPI спецификациям в `docs/openapi/`.

---

## Модули и Endpoints

### 1. Auth API (`Modules/Auth`)

| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| POST | `/api/v1/auth/register` | Регистрация | `AuthController@register` |
| POST | `/api/v1/auth/login` | Авторизация | `AuthController@login` |
| POST | `/api/v1/auth/refresh` | Обновление токена | `AuthController@refresh` |
| POST | `/api/v1/auth/logout` | Выход (auth) | `AuthController@logout` |
| POST | `/api/v1/auth/forgot-password` | Забыли пароль | `AuthController@forgotPassword` |
| POST | `/api/v1/auth/reset-password` | Сброс пароля | `AuthController@resetPassword` |

---

### 2. User API (`Modules/User`)

| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/me` | Текущий пользователь | `UserController@me` |
| GET | `/api/v1/users` | Список пользователей | `UserController@index` |
| GET | `/api/v1/users/{id}` | Пользователь по ID | `UserController@show` |
| PUT | `/api/v1/users/{id}` | Обновить пользователя | `UserController@update` |
| POST | `/api/v1/users/{id}/player-profile` | Создать профиль игрока | `UserController@createPlayerProfile` |
| POST | `/api/v1/users/{id}/coach-profile` | Создать профиль тренера | `UserController@createCoachProfile` |

**Players:**
| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/players` | Список игроков | `PlayerController@index` |
| GET | `/api/v1/players/{id}` | Игрок по ID | `PlayerController@show` |
| PUT | `/api/v1/players/{id}` | Обновить игрока | `PlayerController@update` |
| DELETE | `/api/v1/players/{id}` | Удалить игрока | `PlayerController@destroy` |

**Coaches:**
| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/coaches` | Список тренеров | `CoachController@index` |
| GET | `/api/v1/coaches/{id}` | Тренер по ID | `CoachController@show` |
| PUT | `/api/v1/coaches/{id}` | Обновить тренера | `CoachController@update` |
| DELETE | `/api/v1/coaches/{id}` | Удалить тренера | `CoachController@destroy` |

---

### 3. Club API (`Modules/Club`)

| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/clubs` | Список клубов | `ClubController@index` |
| POST | `/api/v1/clubs` | Создать клуб | `ClubController@store` |
| GET | `/api/v1/clubs/{id}` | Клуб по ID | `ClubController@show` |
| PUT | `/api/v1/clubs/{id}` | Обновить клуб | `ClubController@update` |
| DELETE | `/api/v1/clubs/{id}` | Удалить клуб | `ClubController@destroy` |

---

### 4. Team API (`Modules/Team`)

| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/clubs/{clubId}/teams` | Команды клуба | `TeamController@index` |
| POST | `/api/v1/clubs/{clubId}/teams` | Создать команду | `TeamController@store` |
| GET | `/api/v1/teams/{id}` | Команда по ID | `TeamController@show` |
| PUT | `/api/v1/teams/{id}` | Обновить команду | `TeamController@update` |
| DELETE | `/api/v1/teams/{id}` | Удалить команду | `TeamController@destroy` |
| POST | `/api/v1/teams/{teamId}/members` | Добавить участника | `TeamController@addMember` |

**Seasons:**
| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/seasons` | Список сезонов | `SeasonController@index` |
| POST | `/api/v1/seasons` | Создать сезон | `SeasonController@store` |
| GET | `/api/v1/seasons/{id}` | Сезон по ID | `SeasonController@show` |
| PUT | `/api/v1/seasons/{id}` | Обновить сезон | `SeasonController@update` |
| DELETE | `/api/v1/seasons/{id}` | Удалить сезон | `SeasonController@destroy` |
| POST | `/api/v1/seasons/{id}/teams` | Добавить команду к сезону | `SeasonController@attachTeam` |
| DELETE | `/api/v1/seasons/{id}/teams` | Удалить команду из сезона | `SeasonController@detachTeam` |

**Invite Links:**
| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/invite-links` | Список инвайтов | `InviteController@index` |
| POST | `/api/v1/invite-links` | Создать инвайт | `InviteController@store` |
| GET | `/api/v1/invite-links/{id}` | Инвайт по ID | `InviteController@show` |
| DELETE | `/api/v1/invite-links/{id}` | Отозвать инвайт | `InviteController@destroy` |
| GET | `/api/v1/invite-links/{token}/validate` | Проверить токен (public) | `InviteController@validateToken` |
| POST | `/api/v1/invite-links/{token}/accept` | Принять приглашение | `InviteController@accept` |

---

### 5. Training API (`Modules/Training`)

| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/trainings` | Список тренировок | `TrainingController@index` |
| POST | `/api/v1/trainings` | Создать тренировку | `TrainingController@store` |
| GET | `/api/v1/trainings/{id}` | Тренировка по ID | `TrainingController@show` |
| PUT | `/api/v1/trainings/{id}` | Обновить тренировку | `TrainingController@update` |
| POST | `/api/v1/trainings/{id}/cancel` | Отменить тренировку | `TrainingController@cancel` |
| PATCH | `/api/v1/trainings/{trainingId}/attendance/{playerUserId}` | Отметить посещаемость | `TrainingController@markAttendance` |

**Venues (Места проведения):**
| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/venues` | Список площадок | `VenueController@index` |
| POST | `/api/v1/venues` | Создать площадку | `VenueController@store` |
| GET | `/api/v1/venues/{id}` | Площадка по ID | `VenueController@show` |
| PUT | `/api/v1/venues/{id}` | Обновить площадку | `VenueController@update` |
| DELETE | `/api/v1/venues/{id}` | Удалить площадку | `VenueController@destroy` |

**Announcements (Объявления):**
| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/announcements` | Список объявлений | `AnnouncementController@index` |
| POST | `/api/v1/announcements` | Создать объявление | `AnnouncementController@store` |
| GET | `/api/v1/announcements/{id}` | Объявление по ID | `AnnouncementController@show` |
| PUT | `/api/v1/announcements/{id}` | Обновить объявление | `AnnouncementController@update` |
| DELETE | `/api/v1/announcements/{id}` | Удалить объявление | `AnnouncementController@destroy` |
| POST | `/api/v1/announcements/{id}/publish` | Опубликовать | `AnnouncementController@publish` |

**Event Responses (RSVP):**
| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/events/{eventType}/{eventId}/responses` | Отклики на событие | `EventResponseController@index` |
| POST | `/api/v1/events/{eventType}/{eventId}/responses` | Создать/обновить отклик | `EventResponseController@store` |
| GET | `/api/v1/events/{eventType}/{eventId}/my-response` | Мой отклик | `EventResponseController@myResponse` |
| PUT | `/api/v1/events/{eventType}/{eventId}/responses/{userId}` | Обновить отклик | `EventResponseController@update` |
| DELETE | `/api/v1/events/{eventType}/{eventId}/responses/{userId}` | Удалить отклик | `EventResponseController@destroy` |
| POST | `/api/v1/events/bulk/responses` | Массовое обновление | `EventResponseController@bulkStore` |
| GET | `/api/v1/users/{userId}/events/upcoming` | Предстоящие события | `EventResponseController@upcoming` |

**Recurring Trainings (Шаблоны):**
| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/recurring-trainings` | Список шаблонов | `RecurringTrainingController@index` |
| POST | `/api/v1/recurring-trainings` | Создать шаблон | `RecurringTrainingController@store` |
| GET | `/api/v1/recurring-trainings/{id}` | Шаблон по ID | `RecurringTrainingController@show` |
| PUT | `/api/v1/recurring-trainings/{id}` | Обновить шаблон | `RecurringTrainingController@update` |
| DELETE | `/api/v1/recurring-trainings/{id}` | Удалить шаблон | `RecurringTrainingController@destroy` |
| POST | `/api/v1/recurring-trainings/{id}/generate` | Сгенерировать тренировки | `RecurringTrainingController@generate` |

---

### 6. Match API (`Modules/Match`)

| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/matches` | Список матчей | `MatchController@index` |
| POST | `/api/v1/matches` | Создать матч | `MatchController@store` |
| GET | `/api/v1/matches/{id}` | Матч по ID | `MatchController@show` |
| PUT | `/api/v1/matches/{id}` | Обновить матч | `MatchController@update` |
| POST | `/api/v1/matches/{id}/start` | Начать матч | `MatchController@start` |
| POST | `/api/v1/matches/{id}/end` | Завершить матч | `MatchController@end` |
| POST | `/api/v1/matches/{id}/events` | Добавить событие | `MatchController@addEvent` |
| PUT | `/api/v1/matches/{id}/lineup` | Установить состав | `MatchController@setLineup` |

---

### 7. Tournament API (`Modules/Tournament`)

| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/tournaments` | Список турниров | `TournamentController@index` |
| POST | `/api/v1/tournaments` | Создать турнир | `TournamentController@store` |
| GET | `/api/v1/tournaments/{id}` | Турнир по ID | `TournamentController@show` |
| PUT | `/api/v1/tournaments/{id}` | Обновить турнир | `TournamentController@update` |
| POST | `/api/v1/tournaments/{id}/teams` | Регистрация команды | `TournamentController@registerTeam` |

---

### 8. Reference API (`Modules/Reference`)

| Method | Endpoint | Description | Controller |
|--------|----------|-------------|------------|
| GET | `/api/v1/refs/sport-types` | Виды спорта | `ReferenceController@sportTypes` |
| GET | `/api/v1/refs/club-types` | Типы клубов | `ReferenceController@clubTypes` |
| GET | `/api/v1/refs/user-roles` | Роли пользователей | `ReferenceController@userRoles` |
| GET | `/api/v1/refs/positions` | Позиции | `ReferenceController@positions` |
| GET | `/api/v1/refs/dominant-feet` | Ведущие ноги | `ReferenceController@dominantFeet` |
| GET | `/api/v1/refs/kinship-types` | Типы родства | `ReferenceController@kinshipTypes` |
| GET | `/api/v1/refs/match-event-types` | Типы событий матча | `ReferenceController@matchEventTypes` |
| GET | `/api/v1/refs/countries` | Страны | `ReferenceController@countries` |
| GET | `/api/v1/refs/cities` | Города | `ReferenceController@cities` |

---

## Созданные файлы

### Модели
- `Modules/Team/Models/Season.php`
- `Modules/Team/Models/InviteLink.php`
- `Modules/Training/Models/Announcement.php`
- `Modules/Training/Models/EventResponse.php`

### API Resources
- `app/Http/Resources/SeasonResource.php`
- `app/Http/Resources/VenueResource.php`
- `app/Http/Resources/InviteLinkResource.php`
- `app/Http/Resources/AnnouncementResource.php`
- `app/Http/Resources/EventResponseResource.php`
- `app/Http/Resources/RecurringTrainingResource.php`

### Контроллеры
- `Modules/Team/Http/Controllers/V1/SeasonController.php`
- `Modules/Team/Http/Controllers/V1/InviteController.php`
- `Modules/Training/Http/Controllers/V1/VenueController.php`
- `Modules/Training/Http/Controllers/V1/AnnouncementController.php`
- `Modules/Training/Http/Controllers/V1/EventResponseController.php`
- `Modules/Training/Http/Controllers/V1/RecurringTrainingController.php`

### Трейты
- `app/Traits/ApiResponse.php`

### Обновленные Routes
- `Modules/Team/Routes/api_v1.php`
- `Modules/Training/Routes/api_v1.php`

---

## Защита API

Все endpoints (кроме явно указанных как public) защищены middleware:
- `auth:sanctum` — аутентификация через Sanctum
- `permission:*` — проверка разрешений через Spatie Permission

---

## Формат ответов

### Успешный ответ (200/201)
```json
{
  "data": { ... },
  "message": "Операция выполнена"
}
```

### Список с пагинацией
```json
{
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

### Ошибка валидации (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field": ["Error message"]
  }
}
```

### Не найдено (404)
```json
{
  "success": false,
  "message": "Resource not found"
}
```

---

## Итого реализовано

- **8 модулей** API
- **90+ endpoints**
- **6 новых моделей**
- **6 API Resources**
- **6 новых контроллеров**
- **Обновлены 2 route-файла**
