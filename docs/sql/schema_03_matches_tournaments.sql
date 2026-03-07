-- =============================================================================
-- Схема 3: Матчи и турниры
-- База данных: PostgreSQL 15
-- Зависит от: schema_01_users_clubs_teams.sql
--               schema_02_trainings.sql (таблица venues)
--   (таблицы: users, clubs, teams, countries, cities, files,
--             ref_sport_types, ref_positions, venues)
-- =============================================================================
--
-- ИЗМЕНЕНИЯ ОТНОСИТЕЛЬНО ИСХОДНОЙ СХЕМЫ
-- ─────────────────────────────────────
-- [1]  Диалект: MySQL → PostgreSQL.
-- [2]  Именование: русские имена → snake_case.
-- [3]  КРИТИЧЕСКАЯ ОШИБКА: `Факт`.`время начала` и `Факт`.`время окончания` —
--      невалидный синтаксис (точка внутри имени колонки). Исправлено на
--      actual_start_at / actual_end_at TIMESTAMPTZ NULL.
-- [4]  match_type → ENUM ('friendly','tournament_group','tournament_playoff')
--      вместо INT с inline-комментарием (`Вид` в исходной схеме).
-- [5]  is_away → BOOLEAN вместо INT (1/2).
-- [6]  `Матч.Описание` NOT NULL → NULL. Описание необязательно.
-- [7]  `Ref: Место проведения` дублирует Schema 2 — удалена; матч
--      ссылается на venues из Schema 2 напрямую.
-- [8]  Добавлена таблица opponents для соперников. В исходной схеме
--      «Соперник» ссылался на «таблицу команд» без FK и без учёта
--      внешних команд, не зарегистрированных в системе.
-- [9]  `Состав команды: Тренера` и `Состав команды: Игроки` — удалён
--      redundant FK на `Турнир`: турнир однозначно определяется через
--      matches.tournament_id. Хранить tournament_id дополнительно означает
--      возможность рассинхронизации данных.
-- [10] `Состав команды: Игроки.Причина отсутствия` NOT NULL → NULL.
-- [11] `Состав команды: Игроки.Родитель` INT NOT NULL → parent_user_id BIGINT NULL
--      REFERENCES users(id). В исходной схеме поле обязательное без FK,
--      что блокирует запись если родитель не зарегистрирован.
-- [12] `Состав команды: Игроки.Участие` BOOLEAN → is_starter BOOLEAN для
--      ясности семантики (true = стартовый состав, false = запасной).
-- [13] `Результаты` переименована в match_events. Удалены redundant поля
--      `Клуб`, `Команда`, `Турнир` — все они однозначно выводятся через
--      match_id (матч знает клуб, команду и турнир). Дублирование создаёт
--      риск противоречивых данных.
-- [14] `Результаты.Ассистент` INT NOT NULL → assistant_user_id BIGINT NULL.
--      Ассистент есть только у голов/передач; у карточек, сейвов — нет.
-- [15] `Ref: Вид события.Наименование` INT → VARCHAR(100). Очевидная
--      ошибка типа: имя вида события не может быть целым числом.
-- [16] `Команды заявленные на турнир.Участие` INT → ENUM
--      ('participating','disqualified'). Добавлен UNIQUE(tournament_id, team_id).
-- [17] `Ref: Место проведения.Адрес` BIGINT → TEXT. Тип явно ошибочный.
-- [18] Добавлены FK на clubs, teams, users, ref_sport_types там, где они
--      отсутствовали. Добавлены created_at/updated_at и индексы.
-- =============================================================================

-- ── ENUM-типы ─────────────────────────────────────────────────────────────────

CREATE TYPE match_type             AS ENUM ('friendly', 'tournament_group', 'tournament_playoff');
CREATE TYPE tournament_entry_status AS ENUM ('participating', 'disqualified');

-- ── Справочники ───────────────────────────────────────────────────────────────

-- Виды турниров (привязаны к виду спорта)
CREATE TABLE ref_tournament_types (
    id            SMALLSERIAL PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    sport_type_id SMALLINT     NOT NULL REFERENCES ref_sport_types(id),
    UNIQUE (name, sport_type_id)
);

-- Виды событий матча ([15]: исправлен тип Наименование INT → VARCHAR)
CREATE TABLE ref_match_event_types (
    id   SMALLSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
    -- 'goal', 'penalty_scored', 'assist', 'yellow_card', 'red_card',
    -- 'goal_conceded', 'save', 'penalty_awarded', 'penalty_saved'
);

-- ── Соперники ([8]) ───────────────────────────────────────────────────────────
-- Внешние команды, не зарегистрированные в системе.
-- Если соперник — команда из нашей системы, используется поле opponent_team_id в matches.

CREATE TABLE opponents (
    id         SERIAL       PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    city_id    INT          REFERENCES cities(id),
    country_id SMALLINT     REFERENCES countries(id),
    created_at TIMESTAMPTZ  NOT NULL DEFAULT now()
);

-- ── Турниры ───────────────────────────────────────────────────────────────────

CREATE TABLE tournaments (
    id                   BIGSERIAL    PRIMARY KEY,
    tournament_type_id   SMALLINT     NOT NULL REFERENCES ref_tournament_types(id),
    name                 VARCHAR(255) NOT NULL,
    logo_file_id         BIGINT       REFERENCES files(id) ON DELETE SET NULL,
    starts_at            DATE         NOT NULL,
    ends_at              DATE         NOT NULL,   -- равен starts_at для однодневных
    half_duration_minutes SMALLINT    NOT NULL,
    halves_count         SMALLINT     NOT NULL,
    organizer            VARCHAR(255),            -- [6] nullable: не всегда известен
    created_at           TIMESTAMPTZ  NOT NULL DEFAULT now(),
    updated_at           TIMESTAMPTZ  NOT NULL DEFAULT now()
);

-- Команды, заявленные на турнир ([16])
CREATE TABLE tournament_teams (
    id            BIGSERIAL              PRIMARY KEY,
    tournament_id BIGINT                 NOT NULL REFERENCES tournaments(id) ON DELETE CASCADE,
    club_id       BIGINT                 NOT NULL REFERENCES clubs(id),       -- [18] FK
    team_id       BIGINT                 NOT NULL REFERENCES teams(id),       -- [18] FK
    status        tournament_entry_status NOT NULL DEFAULT 'participating',
    UNIQUE (tournament_id, team_id)                                           -- [16]
);

-- ── Матчи ─────────────────────────────────────────────────────────────────────

CREATE TABLE matches (
    id                    BIGSERIAL    PRIMARY KEY,
    match_type            match_type   NOT NULL,                              -- [4] ENUM
    tournament_id         BIGINT       REFERENCES tournaments(id),            -- NULL для товарищеских
    sport_type_id         SMALLINT     NOT NULL REFERENCES ref_sport_types(id), -- [18] FK
    venue_id              INT          NOT NULL REFERENCES venues(id),        -- [7] из Schema 2
    name                  VARCHAR(255) NOT NULL,
    description           TEXT,                                               -- [6] NULL
    club_id               BIGINT       NOT NULL REFERENCES clubs(id),         -- [18] FK
    team_id               BIGINT       NOT NULL REFERENCES teams(id),         -- [18] FK

    -- Соперник — либо команда из системы, либо внешняя ([8])
    -- Ровно одно из двух полей должно быть заполнено
    opponent_team_id      BIGINT       REFERENCES teams(id),
    opponent_id           INT          REFERENCES opponents(id),

    scheduled_at          TIMESTAMPTZ  NOT NULL,
    half_duration_minutes SMALLINT     NOT NULL,
    halves_count          SMALLINT     NOT NULL,
    is_away               BOOLEAN      NOT NULL DEFAULT FALSE,                -- [5] BOOLEAN
    actual_start_at       TIMESTAMPTZ,                                        -- [3] исправлено
    actual_end_at         TIMESTAMPTZ,                                        -- [3] исправлено
    created_at            TIMESTAMPTZ  NOT NULL DEFAULT now(),
    updated_at            TIMESTAMPTZ  NOT NULL DEFAULT now(),

    CONSTRAINT chk_opponent CHECK (
        (opponent_team_id IS NOT NULL)::INT + (opponent_id IS NOT NULL)::INT = 1
    )
);

-- ── Состав тренерского штаба на матч ([9]) ────────────────────────────────────
-- Убран redundant FK на турнир — выводится через matches.tournament_id

CREATE TABLE match_coaches (
    id            BIGSERIAL PRIMARY KEY,
    match_id      BIGINT    NOT NULL REFERENCES matches(id)  ON DELETE CASCADE,
    club_id       BIGINT    NOT NULL REFERENCES clubs(id),
    team_id       BIGINT    NOT NULL REFERENCES teams(id),
    coach_user_id BIGINT    NOT NULL REFERENCES users(id),                    -- [18] FK
    UNIQUE (match_id, coach_user_id)
);

-- ── Состав игроков на матч ([9]–[12]) ────────────────────────────────────────
-- Убран redundant FK на турнир

CREATE TABLE match_players (
    id               BIGSERIAL PRIMARY KEY,
    match_id         BIGINT    NOT NULL REFERENCES matches(id)  ON DELETE CASCADE,
    club_id          BIGINT    NOT NULL REFERENCES clubs(id),
    team_id          BIGINT    NOT NULL REFERENCES teams(id),
    player_user_id   BIGINT    NOT NULL REFERENCES users(id),                 -- [18] FK
    position_id      SMALLINT  NOT NULL REFERENCES ref_positions(id),
    is_starter       BOOLEAN   NOT NULL DEFAULT TRUE,                         -- [12]
    absence_reason   TEXT,                                                    -- [10] NULL
    parent_user_id   BIGINT    REFERENCES users(id),                          -- [11] NULL + FK
    UNIQUE (match_id, player_user_id)
);

-- ── События матча ([13]–[14]) ─────────────────────────────────────────────────
-- Удалены redundant поля club_id, team_id, tournament_id

CREATE TABLE match_events (
    id                  BIGSERIAL PRIMARY KEY,
    match_id            BIGINT    NOT NULL REFERENCES matches(id) ON DELETE CASCADE,
    event_type_id       SMALLINT  NOT NULL REFERENCES ref_match_event_types(id),
    match_minute        SMALLINT  NOT NULL,
    player_user_id      BIGINT    NOT NULL REFERENCES users(id),              -- [18] FK
    assistant_user_id   BIGINT    REFERENCES users(id),                       -- [14] NULL
    event_at            TIMESTAMPTZ NOT NULL DEFAULT now(),
    created_at          TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- ── Индексы ───────────────────────────────────────────────────────────────────

CREATE INDEX idx_matches_tournament    ON matches(tournament_id);
CREATE INDEX idx_matches_club          ON matches(club_id);
CREATE INDEX idx_matches_team          ON matches(team_id);
CREATE INDEX idx_matches_scheduled     ON matches(scheduled_at);
CREATE INDEX idx_tournament_teams_tour ON tournament_teams(tournament_id);
CREATE INDEX idx_tournament_teams_team ON tournament_teams(team_id);
CREATE INDEX idx_match_players_match   ON match_players(match_id);
CREATE INDEX idx_match_players_player  ON match_players(player_user_id);
CREATE INDEX idx_match_coaches_match   ON match_coaches(match_id);
CREATE INDEX idx_match_events_match    ON match_events(match_id);
CREATE INDEX idx_match_events_player   ON match_events(player_user_id);

-- ── Комментарии к таблицам ────────────────────────────────────────────────────

COMMENT ON TABLE ref_tournament_types   IS 'Справочник видов турниров, привязанных к виду спорта (Чемпионат, Кубок, Товарищеский и т.д.)';
COMMENT ON TABLE ref_match_event_types  IS 'Справочник типов событий матча: гол, жёлтая карточка, сейв, ассист и т.д.';
COMMENT ON TABLE opponents              IS 'Внешние команды-соперники, не зарегистрированные в системе';
COMMENT ON TABLE tournaments            IS 'Турниры, в которых участвуют команды клуба';
COMMENT ON TABLE tournament_teams       IS 'Команды, заявленные для участия в конкретном турнире';
COMMENT ON TABLE matches                IS 'Матчи: товарищеские или в рамках турнира. Хранит нашу команду и соперника (внутреннего или внешнего)';
COMMENT ON TABLE match_coaches          IS 'Тренерский штаб конкретного матча';
COMMENT ON TABLE match_players          IS 'Заявка игроков на конкретный матч с указанием позиции и стартового/запасного статуса';
COMMENT ON TABLE match_events           IS 'События матча по минутам: голы, карточки, сейвы и т.д.';

-- ── Комментарии к колонкам ────────────────────────────────────────────────────

-- ref_tournament_types
COMMENT ON COLUMN ref_tournament_types.name          IS 'Название вида турнира';
COMMENT ON COLUMN ref_tournament_types.sport_type_id IS 'Вид спорта, к которому относится тип турнира';

-- opponents
COMMENT ON COLUMN opponents.name       IS 'Название внешней команды-соперника';
COMMENT ON COLUMN opponents.city_id    IS 'Город команды-соперника (необязательно)';
COMMENT ON COLUMN opponents.country_id IS 'Страна команды-соперника (необязательно)';

-- tournaments
COMMENT ON COLUMN tournaments.tournament_type_id    IS 'Вид турнира из справочника ref_tournament_types';
COMMENT ON COLUMN tournaments.name                  IS 'Название турнира';
COMMENT ON COLUMN tournaments.logo_file_id          IS 'Логотип турнира — ссылка на files (необязательно)';
COMMENT ON COLUMN tournaments.starts_at             IS 'Дата начала турнира';
COMMENT ON COLUMN tournaments.ends_at               IS 'Дата окончания турнира. Равна starts_at для однодневных турниров';
COMMENT ON COLUMN tournaments.half_duration_minutes IS 'Продолжительность одного тайма в минутах (по регламенту турнира)';
COMMENT ON COLUMN tournaments.halves_count          IS 'Количество таймов по регламенту турнира';
COMMENT ON COLUMN tournaments.organizer             IS 'Название организатора турнира (необязательно)';

-- tournament_teams
COMMENT ON COLUMN tournament_teams.tournament_id IS 'Турнир';
COMMENT ON COLUMN tournament_teams.club_id       IS 'Клуб, за который выступает команда';
COMMENT ON COLUMN tournament_teams.team_id       IS 'Заявленная команда';
COMMENT ON COLUMN tournament_teams.status        IS 'Статус участия: participating = участвует, disqualified = дисквалифицирована';

-- matches
COMMENT ON COLUMN matches.match_type            IS 'Тип матча: friendly = товарищеский, tournament_group = групповой этап, tournament_playoff = плей-офф';
COMMENT ON COLUMN matches.tournament_id         IS 'Турнир, в рамках которого проводится матч. NULL для товарищеских матчей';
COMMENT ON COLUMN matches.sport_type_id         IS 'Вид спорта матча';
COMMENT ON COLUMN matches.venue_id              IS 'Место проведения из таблицы venues';
COMMENT ON COLUMN matches.name                  IS 'Название матча (например, «Финал Кубка города»)';
COMMENT ON COLUMN matches.description           IS 'Дополнительное описание матча (необязательно)';
COMMENT ON COLUMN matches.club_id               IS 'Наш клуб';
COMMENT ON COLUMN matches.team_id               IS 'Наша команда';
COMMENT ON COLUMN matches.opponent_team_id      IS 'Команда-соперник из нашей системы. Заполняется, если соперник зарегистрирован';
COMMENT ON COLUMN matches.opponent_id           IS 'Внешняя команда-соперник из таблицы opponents. Заполняется, если соперник не в системе';
COMMENT ON COLUMN matches.scheduled_at          IS 'Запланированные дата и время начала матча';
COMMENT ON COLUMN matches.half_duration_minutes IS 'Фактическая продолжительность тайма в минутах';
COMMENT ON COLUMN matches.halves_count          IS 'Фактическое количество таймов';
COMMENT ON COLUMN matches.is_away               IS 'Флаг: true = выездной матч, false = домашний';
COMMENT ON COLUMN matches.actual_start_at       IS 'Фактическое время начала матча (заполняется после начала)';
COMMENT ON COLUMN matches.actual_end_at         IS 'Фактическое время окончания матча (заполняется после финального свистка)';

-- match_coaches
COMMENT ON COLUMN match_coaches.match_id      IS 'Матч';
COMMENT ON COLUMN match_coaches.club_id       IS 'Клуб (денормализовано)';
COMMENT ON COLUMN match_coaches.team_id       IS 'Команда (денормализовано)';
COMMENT ON COLUMN match_coaches.coach_user_id IS 'Тренер из таблицы users';

-- match_players
COMMENT ON COLUMN match_players.match_id        IS 'Матч';
COMMENT ON COLUMN match_players.club_id         IS 'Клуб (денормализовано)';
COMMENT ON COLUMN match_players.team_id         IS 'Команда (денормализовано)';
COMMENT ON COLUMN match_players.player_user_id  IS 'Игрок из таблицы users';
COMMENT ON COLUMN match_players.position_id     IS 'Игровая позиция на данный матч';
COMMENT ON COLUMN match_players.is_starter      IS 'Флаг: true = стартовый состав, false = запасной';
COMMENT ON COLUMN match_players.absence_reason  IS 'Причина неявки игрока на матч (необязательно)';
COMMENT ON COLUMN match_players.parent_user_id  IS 'Родитель/опекун игрока (необязательно; нужен для уведомлений)';

-- match_events
COMMENT ON COLUMN match_events.match_id           IS 'Матч, в котором произошло событие';
COMMENT ON COLUMN match_events.event_type_id      IS 'Тип события из справочника ref_match_event_types';
COMMENT ON COLUMN match_events.match_minute       IS 'Минута матча, на которой произошло событие';
COMMENT ON COLUMN match_events.player_user_id     IS 'Основной игрок события (забил гол, получил карточку и т.д.)';
COMMENT ON COLUMN match_events.assistant_user_id  IS 'Ассистент (заполняется только для голов и ассистированных передач)';
COMMENT ON COLUMN match_events.event_at           IS 'Точное время события (timestamp)';
COMMENT ON COLUMN match_events.created_at         IS 'Время создания записи в системе';
