# Changelog — Сбор (sbor.team)

Все значимые изменения проекта документируются в этом файле.
Формат основан на [Keep a Changelog](https://keepachangelog.com/ru/1.0.0/).

---

## [Unreleased]

### Added

#### Управление командами и составом
- **Карточки игроков + состав команды:** отображение количества игроков в команде на главной странице администратора
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

### Changed

- Добавлена колонка `members_count` в карточки команд на дашборде администратора
- Улучшен UI страницы команды с вкладками для разных разделов

### Fixed

- Исправлено отображение заявок на вступление - теперь показываются только pending-заявки
- Исправлена проверка прав доступа для принятия/отклонения заявок

---

## Предыдущие версии

[Остальное содержимое без изменений...]
