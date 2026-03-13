# Changelog — Сбор (sbor.team)

Все значимые изменения проекта документируются в этом файле.
Формат основан на [Keep a Changelog](https://keepachangelog.com/ru/1.0.0/).

---

## [Unreleased]

### Added

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
