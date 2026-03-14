# Changelog — Сбор (sbor.team)

Все значимые изменения проекта документируются в этом файле.
Формат основан на [Keep a Changelog](https://keepachangelog.com/ru/1.0.0/).

---

## [Unreleased]

### Added

**Этап 1 — Критические исправления:**
- `Modules/Club/Routes/web.php` — добавлены маршруты `club.team.edit`, `club.team.update`, `club.team.delete`
- `Modules/Club/Http/Controllers/ClubController.php` — методы `teamEdit()`, `teamUpdate()`, `teamDelete()`
- `Modules/Club/Resources/views/team/edit.blade.php` — форма редактирования команды
  - Маршрут: `/club/team/{id}/edit`
- `Modules/Team/Models/InviteLink.php` — добавлен алиас `creator()` для relationship `createdBy()`
- `database/migrations/2025_06_21_000001_add_club_id_to_tournaments_and_opponents.php` — добавлена колонка `club_id` в таблицы `tournaments` и `opponents`
- `Modules/Tournament/Models/Tournament.php` — добавлены `club_id` в fillable и relationship `club()`
- `Modules/Match/Models/Opponent.php` — добавлен `club_id` в fillable
- `app/Livewire/MatchCreate.php` — исправлен запрос: `end_date` → `ends_at`, добавлен fallback через `tournament_teams`

**Этап 2 — Модуль «Тренировки» (Modules/Training/):**
- `Modules/Training/Livewire/TrainingList.php` — список тренировок с фильтрами
  - Маршрут: `/trainings`
- `Modules/Training/Livewire/TrainingCalendar.php` — календарь тренировок
  - Маршрут: `/trainings/calendar`
- `Modules/Training/Livewire/RecurringTrainings.php` — шаблоны расписания
  - Маршрут: `/trainings/recurring`
- `Modules/Training/Livewire/VenueList.php` — управление площадками (CRUD)
  - Маршрут: `/venues`
- `Modules/Training/Resources/views/livewire/` — blade-шаблоны для всех компонентов
- `Modules/Training/Routes/web.php` — web-маршруты модуля
- `Modules/Training/Providers/TrainingServiceProvider.php` — регистрация Livewire-компонентов

**Этап 2 — Модуль «Матчи» (Modules/Match/):**
- `Modules/Match/Livewire/MatchList.php` — список матчей с фильтрами
  - Маршрут: `/matches`
- `Modules/Match/Resources/views/livewire/match-list.blade.php` — blade-шаблон
- `Modules/Match/Routes/web.php` — web-маршруты модуля
- `Modules/Match/Providers/MatchServiceProvider.php` — регистрация Livewire-компонентов

**Этап 2 — Модуль «Турниры» (Modules/Tournament/):**
- `Modules/Tournament/Livewire/TournamentList.php` — список турниров с фильтром по году
  - Маршрут: `/tournaments`
- `Modules/Tournament/Livewire/TournamentCreate.php` — создание турнира
  - Маршрут: `/tournaments/create`
- `Modules/Tournament/Livewire/TournamentDetail.php` — детали турнира
  - Маршрут: `/tournament/{id}`
- `Modules/Tournament/Resources/views/livewire/` — blade-шаблоны
- `Modules/Tournament/Routes/web.php` — web-маршруты модуля
- `Modules/Tournament/Providers/TournamentServiceProvider.php` — регистрация Livewire-компонентов

### Fixed

- Исправлены дублирующиеся пункты меню в сайдбаре для роли admin (убраны 3 дубля «Приглашения», 2 дубля «Заявки», дубль «Команды»/«Мои команды»)
- `resources/views/layouts/components/app-sidebar.blade.php` — секция «Управление командой» отображается только для coach, admin видит всё в «Управление клубом»
- `Modules/Club/Resources/views/team/show.blade.php` — исправлена переменная `$trainingsThisWeek` → `$weekTrainings->count()`
- `.env.example` — добавлен комментарий о необходимости `APP_DEBUG=false` на продакшене

**Модальное окно "Создать событие" на дашборде:**
- `app/Livewire/Dashboard.php` — добавлены методы для работы с модальным окном
- `resources/views/livewire/index.blade.php` — добавлено модальное окно выбора типа события
- Выбор между: Объявление, Тренировка, Матч
- Маршруты: `/announcements/create`, `/trainings/create`, `/matches/create`

**Управление командой на странице профиля:**
- Кнопки редактирования и удаления команды
- Подтверждение удаления через JavaScript confirm
- Форма удаления с CSRF токеном

**Приглашение участников:**
- Кнопка "Пригласить игрока" во вкладке "Состав"
- Кнопка "Пригласить тренера" во вкладке "Тренеры"
- Исправлена работа модального окна приглашений
- Поддержка параметра роли (player/coach)

**Улучшения списка команд:**
- Кликабельные названия команд — переход на страницу профиля
- Логотип команды в форме редактирования
- Загрузка нового логотипа через FileService
- Отображение текущего логотипа при редактировании
- Цвет команды — исправлена подсветка выбранного цвета

**Реорганизация страницы профиля команды:**
- Объявления вынесены в отдельный блок над вкладками
- Карусель объявлений при наличии нескольких
- Компактный вид при отсутствии объявлений
- Новый порядок вкладок: Расписание, Турниры, Тренеры, Состав

**Git Commit:** `10bc555` — feat: Фаза 2 и Фаза 3 - UI/UX, уведомления и журнал активности  
**Git Commit:** `a5ae557` — feat: импорт/экспорт данных и PDF документы (Фаза 1)

### Added

#### Фаза 3 - Уведомления и интеграции

**Email-напоминания:**
- `app/Console/Commands/SendEventReminders.php` — команда отправки напоминаний
- `resources/views/email_templates/training_reminder.blade.php` — шаблон письма
- Команда: `php artisan send:event-reminders --hours=24`
- Напоминания о тренировках за 24 часа

**Telegram бот:**
- `app/Services/Telegram/TelegramService.php` — сервис для отправки сообщений
- `Modules/Auth/Http/Controllers/TelegramController.php` — API для подключения
- `app/Console/Commands/TelegramSetWebhook.php` — команда установки webhook
- Поддержка команд: /start, /schedule, /next
- Webhook для получения сообщений: `/telegram/webhook`
- API: `POST /telegram/connect`, `/telegram/disconnect`
- Команда: `php artisan telegram:set-webhook --url=https://sbor.team/telegram/webhook`

**Журнал активности (Audit Log):**
- `app/Models/ActivityLog.php` — модель журнала
- `app/Livewire/ActivityLog.php` — UI компонент
- `resources/views/livewire/activity-log.blade.php` — шаблон с фильтрами
- Таблица: `activity_log` с полями: user_id, action, entity_type, old_values, new_values
- Маршрут: `/admin/activity-log`
- Поддержка действий: create, update, delete, login, export, import

#### Фаза 2 - Расширение профилей и UI

**Управление достижениями тренера:**
- Миграция: `coach_achievements` таблица
- `Modules/User/Models/CoachAchievement.php` — модель
- `Modules/User/Http/Controllers/V1/CoachAchievementController.php` — API
- `app/Livewire/CoachAchievements.php` — UI компонент
- API: `GET/POST/PUT/DELETE /coaches/{id}/achievements`
- Маршрут: `/coach/{coachId}/achievements`
- Таймлайн достижений по годам

**Дашборд родителя:**
- `app/Livewire/ParentDashboard.php` — компонент
- `resources/views/livewire/parent-dashboard.blade.php` — шаблон
- Маршрут: `/parent/dashboard`
- Список детей с переключением
- Статистика посещаемости за 30 дней (круговая диаграмма)
- Предстоящие тренировки с RSVP статусом

**Детальные страницы:**
- Тренировка: `/training/{id}` — информация, посещаемость с возможностью отметки
- Матч: `/match/{id}` — счет, события (голы, карточки), состав команды
- Календарь: `/team/calendar` — месячный вид с тренировками и матчами

**Миграции Фазы 2-3:**
- `2025_06_20_000010_create_coach_achievements_table.php`
- `2025_06_20_000011_add_telegram_to_users_table.php`
- `2025_06_20_000012_create_activity_log_table.php`

#### Фаза 1 - Импорт/Экспорт данных

#### Импорт/Экспорт данных (Фаза 1)
- **Импорт игроков из CSV:**
  - `app/Services/Import/PlayerImportService.php` — сервис импорта с валидацией
  - `Modules/Team/Http/Controllers/V1/ImportExportController.php` — API контроллер
  - `app/Livewire/PlayerImport.php` — Livewire компонент
  - `resources/views/livewire/player-import.blade.php` — шаблон с drag-n-drop
  - Маршрут: `/players/import` — страница импорта
  - API: `POST /teams/{teamId}/players/import` — загрузка файла
  - API: `GET /teams/players/import/template` — скачивание шаблона
  - Поддержка позиций: Вратарь, Защитник, Полузащитник, Нападающий
  - Поддержка рабочей ноги: левая, правая, обе
  - Автоматическое создание родителей при указании в файле
  
- **Экспорт данных:**
  - `app/Services/Export/PlayerExportService.php` — экспорт списка игроков
  - `app/Services/Export/AttendanceExportService.php` — экспорт посещаемости
  - API: `GET /teams/{teamId}/players/export` — CSV со списком игроков
  - API: `GET /teams/{teamId}/attendance/export` — CSV с посещаемостью
  
- **PDF документы:**
  - `app/Services/PDF/TournamentApplicationPdfGenerator.php` — генератор заявочных листов
  - `Modules/Team/Http/Controllers/V1/PdfController.php` — PDF контроллер
  - API: `GET /tournaments/{tournamentId}/teams/{teamId}/application.pdf` — заявочный лист
  - API: `GET /teams/{teamId}/roster.pdf` — состав команды
  - HTML шаблоны с фирменным стилем (цвета #8fbd56)

#### Роуты Import/Export
- Обновлен `Modules/Team/Routes/api_v1.php` — добавлены endpoints для импорта/экспорта и PDF

### Changed

#### Исключен функционал
- **Управление документами игроков** исключено из проекта по бизнес-решению
  - Удалена вкладка "Документы" из ТЗ
  - Паспорта, медицинские справки, страховки — не реализуются
  - Основной профиль игрока: ФИО, дата рождения, фото, пол, позиция, контакты
  - Обновлено `docs/TZ.md`

#### План разработки v1.0 (обновлен)
- **Фаза 1** (текущая): Импорт/экспорт данных + PDF (1.5-2 недели)
- **Фаза 2**: UI/UX профили и страницы событий (1.5-2 недели)  
- **Фаза 3**: Уведомления и интеграции (1-1.5 недели)
- Исключена фаза "Документы игрока" (экономия 2-3.5 недели)

---

## [2026-03-13]

### Added

#### Дашборд для тренера
- **Дашборд тренера** (`/dashboard` для роли `coach`):
  - Список тренировок на текущую неделю с деталями (время, место)
  - Список игр и соревнований на неделю
  - Заявки на вступление в команду с быстрыми кнопками "Принять"
  - Плитки команд с количеством игроков
  - Объявления от клуба и тренеров
  - Быстрые действия: добавить тренировку, матч, объявление, пригласить игрока
  - `app/Livewire/Dashboard.php` — метод `getCoachDashboardData()`

#### Управление командами и составом
- **Карточки игроков + состав команды:** отображение количества игроков в команде на главной странице администратора и тренера
- **Управление командами (CRUD):** список команд с добавлением, редактированием и удалением (с подтверждением)
  - `app/Livewire/TeamManagement.php` — Livewire компонент
  - `resources/views/livewire/team-management.blade.php` — шаблон
  - Маршрут: `/club/teams`
- **Страница команды:**
  - Список игроков с возможностью пригласить/исключить (без удаления профиля)
  - Назначение главного тренера и помощников
  - Расписание тренировок на текущую неделю
  - Объявления команды
  - Предстоящие турниры, игры и мероприятия
  - `Modules/Club/Resources/views/team/show.blade.php` — шаблон
  - Маршрут: `/club/team/{id}`

#### Сотрудники и приглашения
- **Страница сотрудников:** список администраторов и тренеров клуба
  - `app/Livewire/ClubStaff.php` — Livewire компонент
  - `resources/views/livewire/club-staff.blade.php` — шаблон
  - Маршрут: `/club/staff`
- **Страница приглашений:** список всех приглашений с отображением статуса (активно/истекло/лимит)
  - `app/Livewire/InviteManagement.php` — Livewire компонент
  - `resources/views/livewire/invite-management.blade.php` — шаблон
  - Маршрут: `/club/invites`

#### Email-функционал
- **Шаблоны писем:**
  - `resources/views/email_templates/layout.blade.php` — базовый layout
  - `resources/views/email_templates/welcome.blade.php` — приветственное письмо
  - `resources/views/email_templates/invite.blade.php` — приглашение в команду
  - `resources/views/email_templates/password-reset.blade.php` — восстановление пароля
- **Инструкции по настройке:** `docs/EMAIL_SETUP.md`
- Поддержка SMTP: Gmail, Yandex, Mail.ru, SendGrid, Mailgun

#### Документация для AI-агентов
- **Создан `docs/AGENTS.md`** — правила работы с проектом:
  - Все изменения только в `docs/CHANGELOG.md`
  - Структура проекта: `docs/`, `database/`, `Modules/`
  - UI Guidelines обязательны (`docs/ui-guidelines.md`)
  - Форматирование кода

### Changed

- Добавлена колонка `members_count` в карточки команд на дашборде администратора
- Улучшен UI страницы команды с вкладками для разных разделов
- Обновлено ТЗ (`docs/TZ.md`) — исключен раздел "Документы"
- Обновлена дорожная карта (`docs/c4/roadmap.md`) — добавлена Фаза 4a (Импорт/Экспорт), указаны не реализованные функции

### Fixed

- Исправлено отображение заявок на вступление - теперь показываются только pending-заявки
- Исправлена проверка прав доступа для принятия/отклонения заявок

---

## Предыдущие версии

[Остальное содержимое без изменений...]
