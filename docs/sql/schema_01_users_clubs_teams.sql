-- =============================================================================
-- Схема 1: Пользователи, клубы и команды
-- База данных: PostgreSQL 15
-- =============================================================================
--
-- ИЗМЕНЕНИЯ ОТНОСИТЕЛЬНО ИСХОДНОЙ СХЕМЫ
-- ─────────────────────────────────────
-- [1]  Диалект: MySQL → PostgreSQL (SERIAL/BIGSERIAL, TIMESTAMPTZ, JSONB,
--      кавычки вместо обратных кавычек, нет AUTO_INCREMENT).
-- [2]  Именование: русские имена → snake_case, пробелы убраны.
-- [3]  Добавлена таблица files — она была неявно нужна (несколько полей
--      ссылались на «таблицу с файлами», но сама таблица отсутствовала).
-- [4]  Ref: Регионы (тип 1/2) → две отдельные таблицы countries + cities
--      с явной иерархией (city.country_id). Исходная схема смешивала страны
--      и города в одну таблицу с type-дискриминатором — это затрудняет
--      целостность и запросы.
-- [5]  Ref: Пол → два ENUM-типа: user_gender (male/female) и
--      team_gender (boys/girls/mixed). «Смешанная команда» — атрибут
--      команды, но не биологический пол пользователя.
-- [6]  Пользователи.Вид родства → убран из users; создана отдельная таблица
--      user_parent_player (parent_user_id, player_user_id, kinship_type_id).
--      Один пользователь может быть родителем нескольких детей.
-- [7]  Роли + Состав команды → объединены в одну таблицу team_members.
--      Обе исходные таблицы хранили одно и то же: user + club + team + role.
-- [8]  Ref: Позиция.Клуб → убран FK на клуб. Позиции — общие для вида
--      спорта, а не специфичны для конкретного клуба.
-- [9]  Профиль игрока.Адрес → убран (адрес относится к пользователю,
--      а не к игровому профилю).
-- [10] Профиль тренера: поле «Позиция» переименовано в specialty_id
--      (тренер не имеет игровой позиции; это его специализация/роль).
-- [11] Добавлены created_at / updated_at к основным таблицам.
--      Добавлен UNIQUE на users.email.
-- =============================================================================

-- ── ENUM-типы ─────────────────────────────────────────────────────────────────

CREATE TYPE user_gender  AS ENUM ('male', 'female');
CREATE TYPE team_gender  AS ENUM ('boys', 'girls', 'mixed');

-- ── Справочники ───────────────────────────────────────────────────────────────

-- [1] Виды спорта
CREATE TABLE ref_sport_types (
    id   SMALLSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
    -- 'Футбол', 'Хоккей', ...
);

-- [2] Виды клубов
CREATE TABLE ref_club_types (
    id   SMALLSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
    -- 'Частный', 'Государственный', 'Академия', ...
);

-- [3] Роли пользователей в системе
CREATE TABLE ref_user_roles (
    id   SMALLSERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
    -- 'admin', 'coach', 'player', 'parent', 'medic', 'assistant_admin'
);

-- [4] Позиции игроков — привязаны к виду спорта (не к клубу — [8])
CREATE TABLE ref_positions (
    id            SMALLSERIAL PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    sport_type_id SMALLINT     NOT NULL REFERENCES ref_sport_types(id),
    UNIQUE (name, sport_type_id)
    -- 'Вратарь', 'Нападающий', 'Полузащитник', 'Защитник' — для футбола
);

-- [5] Рабочая нога (вынесено из inline-комментария)
CREATE TABLE ref_dominant_feet (
    id   SMALLSERIAL PRIMARY KEY,
    name VARCHAR(20) NOT NULL UNIQUE
    -- 'left', 'right', 'both'
);

-- [6] Типы родства (вынесено из поля users.Вид родства — [6])
CREATE TABLE ref_kinship_types (
    id   SMALLSERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
    -- 'Мать', 'Отец', 'Опекун', 'Другое'
);

-- ── География ([4]) ───────────────────────────────────────────────────────────

CREATE TABLE countries (
    id   SMALLSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE cities (
    id         SERIAL   PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    country_id SMALLINT     NOT NULL REFERENCES countries(id),
    UNIQUE (name, country_id)
);

-- ── Файлы ([3]) ───────────────────────────────────────────────────────────────
-- Централизованное хранилище мета-данных файлов (S3 / local).
-- path — относительный путь или S3-ключ.

CREATE TABLE files (
    id         BIGSERIAL PRIMARY KEY,
    path       TEXT         NOT NULL UNIQUE,
    mime_type  VARCHAR(100),
    size_bytes BIGINT,
    created_at TIMESTAMPTZ  NOT NULL DEFAULT now()
);

-- ── Пользователи ──────────────────────────────────────────────────────────────

CREATE TABLE users (
    id               BIGSERIAL   PRIMARY KEY,
    first_name       VARCHAR(100) NOT NULL,
    last_name        VARCHAR(100) NOT NULL,
    middle_name      VARCHAR(100),
    email            VARCHAR(255) NOT NULL UNIQUE,       -- [12] UNIQUE
    phone            VARCHAR(30),
    password_hash    VARCHAR(255) NOT NULL,
    photo_file_id    BIGINT       REFERENCES files(id) ON DELETE SET NULL,
    notifications_on BOOLEAN      NOT NULL DEFAULT TRUE,
    birth_date       DATE         NOT NULL,
    gender           user_gender  NOT NULL,              -- [5] ENUM
    created_at       TIMESTAMPTZ  NOT NULL DEFAULT now(),
    updated_at       TIMESTAMPTZ  NOT NULL DEFAULT now()
);

-- Связь родитель ↔ ребёнок-игрок ([6])
CREATE TABLE user_parent_player (
    id              BIGSERIAL PRIMARY KEY,
    parent_user_id  BIGINT   NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    player_user_id  BIGINT   NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    kinship_type_id SMALLINT NOT NULL REFERENCES ref_kinship_types(id),
    UNIQUE (parent_user_id, player_user_id)
);

-- ── Клубы ─────────────────────────────────────────────────────────────────────

CREATE TABLE clubs (
    id            BIGSERIAL    PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    logo_file_id  BIGINT       REFERENCES files(id) ON DELETE SET NULL,
    description   TEXT,
    club_type_id  SMALLINT     NOT NULL REFERENCES ref_club_types(id),
    sport_type_id SMALLINT     NOT NULL REFERENCES ref_sport_types(id),
    country_id    SMALLINT     NOT NULL REFERENCES countries(id),
    city_id       INT          NOT NULL REFERENCES cities(id),
    address       VARCHAR(255) NOT NULL,
    email         VARCHAR(255),
    phones        JSONB,
    -- формат: [{"type": "mobile", "number": "+7..."}]
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT now(),
    updated_at    TIMESTAMPTZ  NOT NULL DEFAULT now()
);

-- ── Команды ───────────────────────────────────────────────────────────────────

CREATE TABLE teams (
    id            BIGSERIAL   PRIMARY KEY,
    name          VARCHAR(255) NOT NULL,
    description   TEXT,
    gender        team_gender  NOT NULL,                 -- [5] ENUM
    logo_file_id  BIGINT       REFERENCES files(id) ON DELETE SET NULL,
    birth_year    SMALLINT     NOT NULL,
    club_id       BIGINT       NOT NULL REFERENCES clubs(id) ON DELETE CASCADE,
    sport_type_id SMALLINT     NOT NULL REFERENCES ref_sport_types(id),
    country_id    SMALLINT     REFERENCES countries(id), -- необязательно: наследуется от клуба
    city_id       INT          REFERENCES cities(id),    -- необязательно: наследуется от клуба
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT now(),
    updated_at    TIMESTAMPTZ  NOT NULL DEFAULT now()
);

-- ── Состав команды ([7]) ──────────────────────────────────────────────────────
-- Заменяет таблицы «Роли» + «Состав команды» из исходной схемы:
-- обе хранили одинаковый набор (пользователь, клуб, команда, роль).

CREATE TABLE team_members (
    id        BIGSERIAL PRIMARY KEY,
    user_id   BIGINT    NOT NULL REFERENCES users(id)  ON DELETE CASCADE,
    club_id   BIGINT    NOT NULL REFERENCES clubs(id)  ON DELETE CASCADE,
    team_id   BIGINT    NOT NULL REFERENCES teams(id)  ON DELETE CASCADE,
    role_id   SMALLINT  NOT NULL REFERENCES ref_user_roles(id),
    joined_at DATE,
    is_active BOOLEAN   NOT NULL DEFAULT TRUE,
    UNIQUE (user_id, team_id, role_id)
);

-- ── Профиль игрока ────────────────────────────────────────────────────────────
-- Адрес убран ([9]): он относится к пользователю, не к игровому профилю.

CREATE TABLE player_profiles (
    id               BIGSERIAL PRIMARY KEY,
    user_id          BIGINT    NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    dominant_foot_id SMALLINT  NOT NULL REFERENCES ref_dominant_feet(id),
    position_id      SMALLINT  REFERENCES ref_positions(id),
    sport_type_id    SMALLINT  NOT NULL REFERENCES ref_sport_types(id)
);

-- ── Профиль тренера ───────────────────────────────────────────────────────────
-- «Позиция» переименована в specialty_id ([10]):
-- тренер не занимает игровую позицию, это его специализация.

CREATE TABLE coach_profiles (
    id              BIGSERIAL    PRIMARY KEY,
    user_id         BIGINT       NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    sport_type_id   SMALLINT     NOT NULL REFERENCES ref_sport_types(id),
    specialty_id    SMALLINT     REFERENCES ref_positions(id),  -- специализация тренера
    career_start    DATE,
    license_number  VARCHAR(100),
    license_expires DATE,
    achievements    JSONB
    -- формат: [{"year": 2022, "title": "Лучший тренер региона"}]
);

-- ── Индексы ───────────────────────────────────────────────────────────────────

CREATE INDEX idx_users_email          ON users(email);
CREATE INDEX idx_team_members_user    ON team_members(user_id);
CREATE INDEX idx_team_members_team    ON team_members(team_id);
CREATE INDEX idx_team_members_club    ON team_members(club_id);
CREATE INDEX idx_teams_club           ON teams(club_id);
CREATE INDEX idx_player_profiles_user ON player_profiles(user_id);
CREATE INDEX idx_coach_profiles_user  ON coach_profiles(user_id);
CREATE INDEX idx_cities_country       ON cities(country_id);

-- ── Комментарии к таблицам ────────────────────────────────────────────────────

COMMENT ON TABLE ref_sport_types        IS 'Справочник: виды спорта (Футбол, Хоккей, Баскетбол и т.д.)';
COMMENT ON TABLE ref_club_types         IS 'Справочник: организационно-правовые типы клубов (Частный, Государственный, Академия)';
COMMENT ON TABLE ref_user_roles         IS 'Справочник: роли пользователей в системе (admin, coach, player, parent и т.д.)';
COMMENT ON TABLE ref_positions          IS 'Справочник: игровые позиции, привязанные к виду спорта';
COMMENT ON TABLE ref_dominant_feet      IS 'Справочник: рабочая нога игрока (left, right, both)';
COMMENT ON TABLE ref_kinship_types      IS 'Справочник: виды родства между родителем и игроком (Мать, Отец, Опекун, Другое)';
COMMENT ON TABLE countries              IS 'Справочник стран. Используется в географии клубов, команд, площадок';
COMMENT ON TABLE cities                 IS 'Справочник городов с привязкой к стране';
COMMENT ON TABLE files                  IS 'Централизованный реестр загруженных файлов. Хранит путь/ключ S3, MIME-тип и размер';
COMMENT ON TABLE users                  IS 'Аккаунты всех пользователей системы: игроки, тренеры, родители, администраторы';
COMMENT ON TABLE user_parent_player     IS 'Связь «родитель → ребёнок-игрок» с указанием типа родства. Один родитель может быть связан с несколькими детьми';
COMMENT ON TABLE clubs                  IS 'Спортивные клубы — основная организационная единица системы';
COMMENT ON TABLE teams                  IS 'Команды внутри клуба, сгруппированные по году рождения и полу';
COMMENT ON TABLE team_members           IS 'Состав команды: привязка пользователя к команде с конкретной ролью (тренер, игрок и т.д.)';
COMMENT ON TABLE player_profiles        IS 'Игровой профиль пользователя: рабочая нога, позиция, вид спорта';
COMMENT ON TABLE coach_profiles         IS 'Тренерский профиль: специализация, лицензия, дата начала карьеры, достижения';
-- ── Комментарии к колонкам ────────────────────────────────────────────────────

-- ref_positions
COMMENT ON COLUMN ref_positions.sport_type_id IS 'Вид спорта, к которому относится позиция';

-- countries / cities
COMMENT ON COLUMN cities.country_id IS 'Страна, к которой относится город';

-- files
COMMENT ON COLUMN files.path       IS 'Относительный путь к файлу или ключ объекта в S3';
COMMENT ON COLUMN files.mime_type  IS 'MIME-тип файла (image/jpeg, application/pdf и т.д.)';
COMMENT ON COLUMN files.size_bytes IS 'Размер файла в байтах';

-- users
COMMENT ON COLUMN users.first_name        IS 'Имя пользователя';
COMMENT ON COLUMN users.last_name         IS 'Фамилия пользователя';
COMMENT ON COLUMN users.middle_name       IS 'Отчество (необязательно)';
COMMENT ON COLUMN users.email             IS 'Уникальный e-mail — используется для входа и уведомлений';
COMMENT ON COLUMN users.phone             IS 'Контактный телефон в произвольном формате';
COMMENT ON COLUMN users.password_hash     IS 'Хэш пароля (bcrypt/argon2)';
COMMENT ON COLUMN users.photo_file_id     IS 'Ссылка на фото профиля в таблице files';
COMMENT ON COLUMN users.notifications_on  IS 'Флаг: пользователь согласен получать push-уведомления';
COMMENT ON COLUMN users.birth_date        IS 'Дата рождения';
COMMENT ON COLUMN users.gender            IS 'Пол пользователя: male / female';

-- user_parent_player
COMMENT ON COLUMN user_parent_player.parent_user_id  IS 'Пользователь-родитель (или опекун)';
COMMENT ON COLUMN user_parent_player.player_user_id  IS 'Пользователь-игрок (ребёнок)';
COMMENT ON COLUMN user_parent_player.kinship_type_id IS 'Тип родства из справочника ref_kinship_types';

-- clubs
COMMENT ON COLUMN clubs.name          IS 'Официальное название клуба';
COMMENT ON COLUMN clubs.logo_file_id  IS 'Логотип клуба — ссылка на files';
COMMENT ON COLUMN clubs.description   IS 'Краткое описание клуба (необязательно)';
COMMENT ON COLUMN clubs.club_type_id  IS 'Тип клуба из справочника ref_club_types';
COMMENT ON COLUMN clubs.sport_type_id IS 'Основной вид спорта клуба';
COMMENT ON COLUMN clubs.country_id    IS 'Страна расположения клуба';
COMMENT ON COLUMN clubs.city_id       IS 'Город расположения клуба';
COMMENT ON COLUMN clubs.address       IS 'Почтовый адрес клуба';
COMMENT ON COLUMN clubs.email         IS 'Контактный e-mail клуба (необязательно)';
COMMENT ON COLUMN clubs.phones        IS 'Контактные телефоны клуба в формате JSON: [{"type":"mobile","number":"+7..."}]';

-- teams
COMMENT ON COLUMN teams.name          IS 'Название команды';
COMMENT ON COLUMN teams.description   IS 'Описание команды (необязательно)';
COMMENT ON COLUMN teams.gender        IS 'Половой состав команды: boys / girls / mixed';
COMMENT ON COLUMN teams.logo_file_id  IS 'Логотип команды — ссылка на files';
COMMENT ON COLUMN teams.birth_year    IS 'Год рождения игроков команды';
COMMENT ON COLUMN teams.club_id       IS 'Клуб, которому принадлежит команда';
COMMENT ON COLUMN teams.sport_type_id IS 'Вид спорта команды';
COMMENT ON COLUMN teams.country_id    IS 'Страна команды (если отличается от клуба)';
COMMENT ON COLUMN teams.city_id       IS 'Город команды (если отличается от клуба)';

-- team_members
COMMENT ON COLUMN team_members.user_id   IS 'Пользователь — участник команды';
COMMENT ON COLUMN team_members.club_id   IS 'Клуб (денормализовано для быстрых запросов)';
COMMENT ON COLUMN team_members.team_id   IS 'Команда';
COMMENT ON COLUMN team_members.role_id   IS 'Роль пользователя в команде (тренер, игрок и т.д.)';
COMMENT ON COLUMN team_members.joined_at IS 'Дата вступления в команду';
COMMENT ON COLUMN team_members.is_active IS 'Флаг активного участника (false = покинул команду)';

-- player_profiles
COMMENT ON COLUMN player_profiles.user_id          IS 'Пользователь (один профиль на одного игрока)';
COMMENT ON COLUMN player_profiles.dominant_foot_id IS 'Рабочая нога из справочника ref_dominant_feet';
COMMENT ON COLUMN player_profiles.position_id      IS 'Предпочтительная игровая позиция (необязательно)';
COMMENT ON COLUMN player_profiles.sport_type_id    IS 'Вид спорта профиля';

-- coach_profiles
COMMENT ON COLUMN coach_profiles.user_id         IS 'Пользователь (один профиль на одного тренера)';
COMMENT ON COLUMN coach_profiles.sport_type_id   IS 'Вид спорта тренера';
COMMENT ON COLUMN coach_profiles.specialty_id    IS 'Специализация тренера (ссылается на ref_positions как роль, а не игровую позицию)';
COMMENT ON COLUMN coach_profiles.career_start    IS 'Дата начала тренерской карьеры';
COMMENT ON COLUMN coach_profiles.license_number  IS 'Номер тренерской лицензии';
COMMENT ON COLUMN coach_profiles.license_expires IS 'Дата окончания действия лицензии';
COMMENT ON COLUMN coach_profiles.achievements    IS 'Достижения тренера в формате JSON: [{"year":2022,"title":"Лучший тренер региона"}]';

