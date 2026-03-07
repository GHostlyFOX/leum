-- =============================================================================
-- Схема 2: Тренировки
-- База данных: PostgreSQL 15
-- Зависит от: schema_01_users_clubs_teams.sql
--   (таблицы: users, clubs, teams, countries, cities, files)
-- =============================================================================
--
-- ИЗМЕНЕНИЯ ОТНОСИТЕЛЬНО ИСХОДНОЙ СХЕМЫ
-- ─────────────────────────────────────
-- [1]  Диалект: MySQL → PostgreSQL (SERIAL/BIGSERIAL, TIMESTAMPTZ, JSONB).
-- [2]  Именование: русские имена → snake_case.
-- [3]  training_status → ENUM ('scheduled','completed','cancelled')
--      вместо INT с inline-комментарием.
-- [4]  attendance_status → ENUM ('pending','present','absent')
--      вместо BOOLEAN «Присутствие». Булево не покрывает состояние
--      «ещё не ответил» — только confirmed/not confirmed.
-- [5]  «Кто отметил» INT (без FK!) → marked_by_user_id BIGINT REFERENCES users(id).
--      Исходная схема хранила «ИД тренера или родителя» без ограничения FK,
--      что не гарантирует целостность.
-- [6]  «Дать подтверждения» DATETIME NOT NULL → confirmed_at TIMESTAMPTZ NULL.
--      Поле означает момент подтверждения — до подтверждения оно NULL.
-- [7]  «Причина отсутствия» TEXT NOT NULL → TEXT NULL.
--      Причина релевантна только при отсутствии; пустая строка хуже NULL.
-- [8]  «Комментарий» TEXT NOT NULL → TEXT NULL. Комментарий необязателен.
-- [9]  Связь recurring_trainings → trainings:
--      В исходной схеме «Регулярные» и «Тренировка» никак не связаны.
--      Добавлен recurring_id FK в trainings, позволяющий определить,
--      из какого шаблона создана конкретная тренировка.
-- [10] «Расписание» и «Расписание создания новой» JSON → JSONB с задокументированной
--      структурой. Добавлено поле is_active (пауза/архив шаблона).
-- [11] «Ref: Регионы» дублирует схему 1 — таблица исключена;
--      venues ссылается на countries/cities из Schema 1.
-- [12] «Фото и видео» (путь TEXT) → training_media ссылается на files из Schema 1.
--      Единое хранилище файлов, mime-тип и размер берутся из files.
-- [13] Добавлено поле name в venues («Стадион Лужники» и т.п.).
-- [14] Добавлены явные FK на clubs, teams, users в trainings/recurring_trainings.
--      Добавлены UNIQUE-ограничения и индексы, created_at/updated_at.
-- =============================================================================

-- ── ENUM-типы ─────────────────────────────────────────────────────────────────

CREATE TYPE training_status   AS ENUM ('scheduled', 'completed', 'cancelled');
CREATE TYPE attendance_status AS ENUM ('pending', 'present', 'absent');

-- ── Справочники ───────────────────────────────────────────────────────────────

-- Виды тренировок (per-club: каждый клуб может задавать свои типы)
CREATE TABLE ref_training_types (
    id          SERIAL  PRIMARY KEY,
    club_id     BIGINT  NOT NULL REFERENCES clubs(id) ON DELETE CASCADE,
    name        VARCHAR(255) NOT NULL,
    description TEXT,
    UNIQUE (club_id, name)
);

-- Места проведения ([11] + [13])
-- Ссылается на countries/cities из Schema 1 вместо дублирующего Ref: Регионы
CREATE TABLE venues (
    id         SERIAL   PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,                          -- [13] добавлено
    country_id SMALLINT NOT NULL REFERENCES countries(id),
    city_id    INT      NOT NULL REFERENCES cities(id),
    address    TEXT     NOT NULL,
    club_id    BIGINT   REFERENCES clubs(id) ON DELETE SET NULL,
    -- NULL = общедоступное место; не NULL = площадка принадлежит клубу
    created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ── Шаблоны регулярных тренировок ([9], [10]) ─────────────────────────────────
-- Создаётся первым — на него ссылается trainings.recurring_id

CREATE TABLE recurring_trainings (
    id             BIGSERIAL PRIMARY KEY,
    club_id        BIGINT   NOT NULL REFERENCES clubs(id)  ON DELETE CASCADE,
    team_id        BIGINT   NOT NULL REFERENCES teams(id)  ON DELETE CASCADE,

    -- Недельное расписание: список сессий (день + время + место + тренер)
    -- Формат: [{"day_of_week":1,"start_time":"10:00","venue_id":5,
    --           "coach_id":42,"duration_minutes":90}, ...]
    schedule       JSONB    NOT NULL,

    -- Правила авто-генерации новых тренировок из шаблона
    -- Формат: {"advance_days":7,"until_date":"2025-08-31"}
    auto_create    JSONB    NOT NULL,

    notify_parents BOOLEAN  NOT NULL DEFAULT TRUE,
    require_rsvp   BOOLEAN  NOT NULL DEFAULT TRUE,
    is_active      BOOLEAN  NOT NULL DEFAULT TRUE,             -- [10] пауза шаблона
    created_at     TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at     TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ── Тренировки ────────────────────────────────────────────────────────────────

CREATE TABLE trainings (
    id               BIGSERIAL PRIMARY KEY,
    coach_id         BIGINT          NOT NULL REFERENCES users(id),   -- [14] FK
    club_id          BIGINT          NOT NULL REFERENCES clubs(id),   -- [14] FK
    team_id          BIGINT          NOT NULL REFERENCES teams(id),   -- [14] FK
    training_date    DATE            NOT NULL,
    start_time       TIME            NOT NULL,
    duration_minutes SMALLINT        NOT NULL,  -- минуты ([8]: в исходной INT без единиц)
    venue_id         INT             NOT NULL REFERENCES venues(id),
    training_type_id INT             NOT NULL REFERENCES ref_training_types(id),
    status           training_status NOT NULL DEFAULT 'scheduled',    -- [3] ENUM
    notify_parents   BOOLEAN         NOT NULL DEFAULT TRUE,
    require_rsvp     BOOLEAN         NOT NULL DEFAULT TRUE,
    comment          TEXT,                                            -- [8] NULL
    recurring_id     BIGINT          REFERENCES recurring_trainings(id) ON DELETE SET NULL,
    -- [9] ссылка на шаблон; NULL = разовая тренировка
    created_at       TIMESTAMPTZ     NOT NULL DEFAULT now(),
    updated_at       TIMESTAMPTZ     NOT NULL DEFAULT now()
);

-- ── Посещаемость тренировки ([4]–[7]) ────────────────────────────────────────

CREATE TABLE training_attendance (
    id                 BIGSERIAL         PRIMARY KEY,
    training_id        BIGINT            NOT NULL REFERENCES trainings(id) ON DELETE CASCADE,
    player_user_id     BIGINT            NOT NULL REFERENCES users(id),
    marked_by_user_id  BIGINT            NOT NULL REFERENCES users(id),   -- [5] FK
    attendance_status  attendance_status NOT NULL DEFAULT 'pending',       -- [4] ENUM
    confirmed_at       TIMESTAMPTZ,                                        -- [6] NULL
    absence_reason     TEXT,                                               -- [7] NULL
    created_at         TIMESTAMPTZ       NOT NULL DEFAULT now(),
    updated_at         TIMESTAMPTZ       NOT NULL DEFAULT now(),
    UNIQUE (training_id, player_user_id)                                   -- [14]
);

-- ── Медиа тренировки ([12]) ───────────────────────────────────────────────────
-- Заменяет таблицу «Фото и видео»: ссылается на files из Schema 1

CREATE TABLE training_media (
    id          BIGSERIAL PRIMARY KEY,
    training_id BIGINT    NOT NULL REFERENCES trainings(id) ON DELETE CASCADE,
    file_id     BIGINT    NOT NULL REFERENCES files(id) ON DELETE CASCADE,
    sort_order  SMALLINT  NOT NULL DEFAULT 0,
    created_at  TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ── Индексы ───────────────────────────────────────────────────────────────────

CREATE INDEX idx_trainings_coach      ON trainings(coach_id);
CREATE INDEX idx_trainings_club       ON trainings(club_id);
CREATE INDEX idx_trainings_team       ON trainings(team_id);
CREATE INDEX idx_trainings_date       ON trainings(training_date);
CREATE INDEX idx_trainings_recurring  ON trainings(recurring_id);
CREATE INDEX idx_attendance_training  ON training_attendance(training_id);
CREATE INDEX idx_attendance_player    ON training_attendance(player_user_id);
CREATE INDEX idx_recurring_club       ON recurring_trainings(club_id);
CREATE INDEX idx_recurring_team       ON recurring_trainings(team_id);
CREATE INDEX idx_venues_club          ON venues(club_id);
CREATE INDEX idx_training_media_train ON training_media(training_id);

-- ── Комментарии к таблицам ────────────────────────────────────────────────────

COMMENT ON TABLE ref_training_types  IS 'Справочник видов тренировок, задаётся каждым клубом индивидуально';
COMMENT ON TABLE venues              IS 'Места проведения тренировок и матчей (стадионы, залы, поля). Общедоступные площадки имеют club_id = NULL';
COMMENT ON TABLE recurring_trainings IS 'Шаблоны регулярных тренировок: еженедельное расписание с правилами авто-генерации';
COMMENT ON TABLE trainings           IS 'Конкретные тренировочные занятия. Могут быть разовыми или порождёнными из шаблона recurring_trainings';
COMMENT ON TABLE training_attendance IS 'Посещаемость тренировок: статус присутствия каждого игрока с возможностью причины отсутствия';
COMMENT ON TABLE training_media      IS 'Медиафайлы (фото/видео), прикреплённые к тренировке';

-- ── Комментарии к колонкам ────────────────────────────────────────────────────

-- ref_training_types
COMMENT ON COLUMN ref_training_types.club_id     IS 'Клуб, создавший этот вид тренировки';
COMMENT ON COLUMN ref_training_types.name        IS 'Название вида тренировки (например, «Физическая подготовка», «Тактика»)';
COMMENT ON COLUMN ref_training_types.description IS 'Подробное описание вида тренировки (необязательно)';

-- venues
COMMENT ON COLUMN venues.name       IS 'Название площадки (например, «Стадион Лужники», «Зал №3»)';
COMMENT ON COLUMN venues.country_id IS 'Страна расположения площадки';
COMMENT ON COLUMN venues.city_id    IS 'Город расположения площадки';
COMMENT ON COLUMN venues.address    IS 'Полный почтовый адрес';
COMMENT ON COLUMN venues.club_id    IS 'Клуб-владелец площадки. NULL = общедоступная площадка';

-- recurring_trainings
COMMENT ON COLUMN recurring_trainings.club_id        IS 'Клуб, которому принадлежит шаблон';
COMMENT ON COLUMN recurring_trainings.team_id        IS 'Команда, для которой создан шаблон';
COMMENT ON COLUMN recurring_trainings.schedule       IS 'Недельное расписание сессий в формате JSON: [{"day_of_week":1,"start_time":"10:00","venue_id":5,"coach_id":42,"duration_minutes":90}]';
COMMENT ON COLUMN recurring_trainings.auto_create    IS 'Правила авто-генерации тренировок: {"advance_days":7,"until_date":"2025-08-31"}';
COMMENT ON COLUMN recurring_trainings.notify_parents IS 'Флаг: отправлять ли уведомления родителям при создании тренировки из шаблона';
COMMENT ON COLUMN recurring_trainings.require_rsvp   IS 'Флаг: требовать ли подтверждение посещения (RSVP) от родителей';
COMMENT ON COLUMN recurring_trainings.is_active      IS 'Флаг активности шаблона. FALSE = шаблон приостановлен или в архиве';

-- trainings
COMMENT ON COLUMN trainings.coach_id         IS 'Тренер, проводящий занятие';
COMMENT ON COLUMN trainings.club_id          IS 'Клуб (денормализовано для быстрых запросов)';
COMMENT ON COLUMN trainings.team_id          IS 'Команда, для которой проводится тренировка';
COMMENT ON COLUMN trainings.training_date    IS 'Дата проведения тренировки';
COMMENT ON COLUMN trainings.start_time       IS 'Время начала тренировки';
COMMENT ON COLUMN trainings.duration_minutes IS 'Продолжительность тренировки в минутах';
COMMENT ON COLUMN trainings.venue_id         IS 'Место проведения из таблицы venues';
COMMENT ON COLUMN trainings.training_type_id IS 'Вид тренировки из справочника ref_training_types';
COMMENT ON COLUMN trainings.status           IS 'Статус тренировки: scheduled / completed / cancelled';
COMMENT ON COLUMN trainings.notify_parents   IS 'Флаг: уведомить родителей об этой тренировке';
COMMENT ON COLUMN trainings.require_rsvp     IS 'Флаг: требовать подтверждение посещения';
COMMENT ON COLUMN trainings.comment          IS 'Заметки тренера к тренировке (необязательно)';
COMMENT ON COLUMN trainings.recurring_id     IS 'Ссылка на шаблон recurring_trainings. NULL = разовая тренировка';

-- training_attendance
COMMENT ON COLUMN training_attendance.training_id       IS 'Тренировка, к которой относится запись';
COMMENT ON COLUMN training_attendance.player_user_id    IS 'Игрок';
COMMENT ON COLUMN training_attendance.marked_by_user_id IS 'Пользователь, отметивший посещаемость (тренер или родитель)';
COMMENT ON COLUMN training_attendance.attendance_status IS 'Статус: pending = ещё не ответил, present = присутствовал, absent = отсутствовал';
COMMENT ON COLUMN training_attendance.confirmed_at      IS 'Момент подтверждения присутствия. NULL = ещё не подтверждено';
COMMENT ON COLUMN training_attendance.absence_reason    IS 'Причина отсутствия (заполняется только при absent)';

-- training_media
COMMENT ON COLUMN training_media.training_id IS 'Тренировка, к которой прикреплён файл';
COMMENT ON COLUMN training_media.file_id     IS 'Файл из централизованного реестра files';
COMMENT ON COLUMN training_media.sort_order  IS 'Порядок отображения медиафайла в галерее';
