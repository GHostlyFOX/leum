<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Схема 1, часть 2: пользователи, клубы, команды, состав, профили, документы.
 *
 * Таблицы: users, user_parent_player, clubs, teams, team_members,
 *          player_profiles, coach_profiles
 *
 * Зависит от: миграции 2025_06_01_000001 (справочники, география, files)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Пользователи ───────────────────────────────────────────────────────

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('email', 255)->unique();
            $table->string('phone', 30)->nullable();
            $table->string('password_hash', 255);
            $table->unsignedBigInteger('photo_file_id')->nullable();
            $table->foreign('photo_file_id')->references('id')->on('files')->nullOnDelete();
            $table->boolean('notifications_on')->default(true);
            $table->date('birth_date');
            $table->string('gender', 10);  // user_gender ENUM
            $table->timestampsTz();
        });
        // Применяем ENUM-тип PostgreSQL
        DB::statement("ALTER TABLE users ALTER COLUMN gender TYPE user_gender USING gender::user_gender");

        DB::statement("COMMENT ON TABLE users IS 'Аккаунты всех пользователей системы: игроки, тренеры, родители, администраторы'");
        DB::statement("COMMENT ON COLUMN users.first_name IS 'Имя пользователя'");
        DB::statement("COMMENT ON COLUMN users.last_name IS 'Фамилия пользователя'");
        DB::statement("COMMENT ON COLUMN users.middle_name IS 'Отчество (необязательно)'");
        DB::statement("COMMENT ON COLUMN users.email IS 'Уникальный e-mail — используется для входа и уведомлений'");
        DB::statement("COMMENT ON COLUMN users.phone IS 'Контактный телефон в произвольном формате'");
        DB::statement("COMMENT ON COLUMN users.password_hash IS 'Хэш пароля (bcrypt/argon2)'");
        DB::statement("COMMENT ON COLUMN users.photo_file_id IS 'Ссылка на фото профиля в таблице files'");
        DB::statement("COMMENT ON COLUMN users.notifications_on IS 'Флаг: пользователь согласен получать push-уведомления'");
        DB::statement("COMMENT ON COLUMN users.birth_date IS 'Дата рождения'");
        DB::statement("COMMENT ON COLUMN users.gender IS 'Пол пользователя: male / female'");

        // Индекс на email (UNIQUE уже создал индекс, добавляем именованный для явности)
        Schema::table('users', function (Blueprint $table) {
            $table->index('email', 'idx_users_email');
        });

        // ── Родитель ↔ игрок ───────────────────────────────────────────────────

        Schema::create('user_parent_player', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_user_id');
            $table->unsignedBigInteger('player_user_id');
            $table->unsignedSmallInteger('kinship_type_id');
            $table->foreign('parent_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('player_user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('kinship_type_id')->references('id')->on('ref_kinship_types');
            $table->unique(['parent_user_id', 'player_user_id']);
        });
        DB::statement("COMMENT ON TABLE user_parent_player IS 'Связь «родитель → ребёнок-игрок» с указанием типа родства. Один родитель может быть связан с несколькими детьми'");
        DB::statement("COMMENT ON COLUMN user_parent_player.parent_user_id IS 'Пользователь-родитель (или опекун)'");
        DB::statement("COMMENT ON COLUMN user_parent_player.player_user_id IS 'Пользователь-игрок (ребёнок)'");
        DB::statement("COMMENT ON COLUMN user_parent_player.kinship_type_id IS 'Тип родства из справочника ref_kinship_types'");

        // ── Клубы ──────────────────────────────────────────────────────────────

        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->unsignedBigInteger('logo_file_id')->nullable();
            $table->foreign('logo_file_id')->references('id')->on('files')->nullOnDelete();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('club_type_id');
            $table->foreign('club_type_id')->references('id')->on('ref_club_types');
            $table->unsignedSmallInteger('sport_type_id');
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
            $table->unsignedSmallInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('address', 255);
            $table->string('email', 255)->nullable();
            $table->jsonb('phones')->nullable();
            $table->timestampsTz();
        });
        DB::statement("COMMENT ON TABLE clubs IS 'Спортивные клубы — основная организационная единица системы'");
        DB::statement("COMMENT ON COLUMN clubs.name IS 'Официальное название клуба'");
        DB::statement("COMMENT ON COLUMN clubs.logo_file_id IS 'Логотип клуба — ссылка на files'");
        DB::statement("COMMENT ON COLUMN clubs.description IS 'Краткое описание клуба (необязательно)'");
        DB::statement("COMMENT ON COLUMN clubs.club_type_id IS 'Тип клуба из справочника ref_club_types'");
        DB::statement("COMMENT ON COLUMN clubs.sport_type_id IS 'Основной вид спорта клуба'");
        DB::statement("COMMENT ON COLUMN clubs.country_id IS 'Страна расположения клуба'");
        DB::statement("COMMENT ON COLUMN clubs.city_id IS 'Город расположения клуба'");
        DB::statement("COMMENT ON COLUMN clubs.address IS 'Почтовый адрес клуба'");
        DB::statement("COMMENT ON COLUMN clubs.email IS 'Контактный e-mail клуба (необязательно)'");
        DB::statement("COMMENT ON COLUMN clubs.phones IS 'Контактные телефоны клуба в формате JSON: [{\"type\":\"mobile\",\"number\":\"+7...\"}]'");

        // ── Команды ────────────────────────────────────────────────────────────

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('gender', 10);  // team_gender ENUM
            $table->unsignedBigInteger('logo_file_id')->nullable();
            $table->foreign('logo_file_id')->references('id')->on('files')->nullOnDelete();
            $table->unsignedSmallInteger('birth_year');
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->cascadeOnDelete();
            $table->unsignedSmallInteger('sport_type_id');
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->timestampsTz();
            $table->index('club_id', 'idx_teams_club');
        });
        DB::statement("ALTER TABLE teams ALTER COLUMN gender TYPE team_gender USING gender::team_gender");

        DB::statement("COMMENT ON TABLE teams IS 'Команды внутри клуба, сгруппированные по году рождения и полу'");
        DB::statement("COMMENT ON COLUMN teams.name IS 'Название команды'");
        DB::statement("COMMENT ON COLUMN teams.description IS 'Описание команды (необязательно)'");
        DB::statement("COMMENT ON COLUMN teams.gender IS 'Половой состав команды: boys / girls / mixed'");
        DB::statement("COMMENT ON COLUMN teams.logo_file_id IS 'Логотип команды — ссылка на files'");
        DB::statement("COMMENT ON COLUMN teams.birth_year IS 'Год рождения игроков команды'");
        DB::statement("COMMENT ON COLUMN teams.club_id IS 'Клуб, которому принадлежит команда'");
        DB::statement("COMMENT ON COLUMN teams.sport_type_id IS 'Вид спорта команды'");
        DB::statement("COMMENT ON COLUMN teams.country_id IS 'Страна команды (если отличается от клуба)'");
        DB::statement("COMMENT ON COLUMN teams.city_id IS 'Город команды (если отличается от клуба)'");

        // ── Состав команды ─────────────────────────────────────────────────────

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->cascadeOnDelete();
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
            $table->unsignedSmallInteger('role_id');
            $table->foreign('role_id')->references('id')->on('ref_user_roles');
            $table->date('joined_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unique(['user_id', 'team_id', 'role_id']);
            $table->index('user_id',  'idx_team_members_user');
            $table->index('team_id',  'idx_team_members_team');
            $table->index('club_id',  'idx_team_members_club');
        });
        DB::statement("COMMENT ON TABLE team_members IS 'Состав команды: привязка пользователя к команде с конкретной ролью (тренер, игрок и т.д.)'");
        DB::statement("COMMENT ON COLUMN team_members.user_id IS 'Пользователь — участник команды'");
        DB::statement("COMMENT ON COLUMN team_members.club_id IS 'Клуб (денормализовано для быстрых запросов)'");
        DB::statement("COMMENT ON COLUMN team_members.team_id IS 'Команда'");
        DB::statement("COMMENT ON COLUMN team_members.role_id IS 'Роль пользователя в команде (тренер, игрок и т.д.)'");
        DB::statement("COMMENT ON COLUMN team_members.joined_at IS 'Дата вступления в команду'");
        DB::statement("COMMENT ON COLUMN team_members.is_active IS 'Флаг активного участника (false = покинул команду)'");

        // ── Профиль игрока ─────────────────────────────────────────────────────

        Schema::create('player_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('dominant_foot_id');
            $table->foreign('dominant_foot_id')->references('id')->on('ref_dominant_feet');
            $table->unsignedSmallInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('ref_positions');
            $table->unsignedSmallInteger('sport_type_id');
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
            $table->index('user_id', 'idx_player_profiles_user');
        });
        DB::statement("COMMENT ON TABLE player_profiles IS 'Игровой профиль пользователя: рабочая нога, позиция, вид спорта'");
        DB::statement("COMMENT ON COLUMN player_profiles.user_id IS 'Пользователь (один профиль на одного игрока)'");
        DB::statement("COMMENT ON COLUMN player_profiles.dominant_foot_id IS 'Рабочая нога из справочника ref_dominant_feet'");
        DB::statement("COMMENT ON COLUMN player_profiles.position_id IS 'Предпочтительная игровая позиция (необязательно)'");
        DB::statement("COMMENT ON COLUMN player_profiles.sport_type_id IS 'Вид спорта профиля'");

        // ── Профиль тренера ────────────────────────────────────────────────────

        Schema::create('coach_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('sport_type_id');
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
            $table->unsignedSmallInteger('specialty_id')->nullable();
            $table->foreign('specialty_id')->references('id')->on('ref_positions');
            $table->date('career_start')->nullable();
            $table->string('license_number', 100)->nullable();
            $table->date('license_expires')->nullable();
            $table->jsonb('achievements')->nullable();
            $table->index('user_id', 'idx_coach_profiles_user');
        });
        DB::statement("COMMENT ON TABLE coach_profiles IS 'Тренерский профиль: специализация, лицензия, дата начала карьеры, достижения'");
        DB::statement("COMMENT ON COLUMN coach_profiles.user_id IS 'Пользователь (один профиль на одного тренера)'");
        DB::statement("COMMENT ON COLUMN coach_profiles.sport_type_id IS 'Вид спорта тренера'");
        DB::statement("COMMENT ON COLUMN coach_profiles.specialty_id IS 'Специализация тренера (ссылается на ref_positions как роль, а не игровую позицию)'");
        DB::statement("COMMENT ON COLUMN coach_profiles.career_start IS 'Дата начала тренерской карьеры'");
        DB::statement("COMMENT ON COLUMN coach_profiles.license_number IS 'Номер тренерской лицензии'");
        DB::statement("COMMENT ON COLUMN coach_profiles.license_expires IS 'Дата окончания действия лицензии'");
        DB::statement("COMMENT ON COLUMN coach_profiles.achievements IS 'Достижения тренера в формате JSON: [{\"year\":2022,\"title\":\"Лучший тренер региона\"}]'");

    }

    public function down(): void
    {
        Schema::dropIfExists('coach_profiles');
        Schema::dropIfExists('player_profiles');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('clubs');
        Schema::dropIfExists('user_parent_player');
        Schema::dropIfExists('users');
    }
};
