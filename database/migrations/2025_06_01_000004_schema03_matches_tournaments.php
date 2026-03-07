<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Схема 3: Матчи и турниры.
 *
 * Таблицы: ref_tournament_types, ref_match_event_types, opponents,
 *          tournaments, tournament_teams, matches,
 *          match_coaches, match_players, match_events
 *
 * Зависит от: схемы 1 (users, clubs, teams, files, ref_sport_types, ref_positions)
 *             схемы 2 (venues)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ENUM-типы ──────────────────────────────────────────────────────────
        DB::statement("DO $$ BEGIN CREATE TYPE match_type AS ENUM ('friendly', 'tournament_group', 'tournament_playoff'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");
        DB::statement("DO $$ BEGIN CREATE TYPE tournament_entry_status AS ENUM ('participating', 'disqualified'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");

        // ── Справочник видов турниров ──────────────────────────────────────────

        Schema::create('ref_tournament_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 255);
            $table->unsignedSmallInteger('sport_type_id');
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
            $table->unique(['name', 'sport_type_id']);
        });
        DB::statement("COMMENT ON TABLE ref_tournament_types IS 'Справочник видов турниров, привязанных к виду спорта (Чемпионат, Кубок, Товарищеский и т.д.)'");
        DB::statement("COMMENT ON COLUMN ref_tournament_types.name IS 'Название вида турнира'");
        DB::statement("COMMENT ON COLUMN ref_tournament_types.sport_type_id IS 'Вид спорта, к которому относится тип турнира'");

        // ── Справочник типов событий матча ─────────────────────────────────────

        Schema::create('ref_match_event_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 100)->unique();
        });
        DB::statement("COMMENT ON TABLE ref_match_event_types IS 'Справочник типов событий матча: гол, жёлтая карточка, сейв, ассист и т.д.'");

        // ── Внешние команды-соперники ──────────────────────────────────────────

        Schema::create('opponents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->unsignedInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->unsignedSmallInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->timestampTz('created_at')->useCurrent();
        });
        DB::statement("COMMENT ON TABLE opponents IS 'Внешние команды-соперники, не зарегистрированные в системе'");
        DB::statement("COMMENT ON COLUMN opponents.name IS 'Название внешней команды-соперника'");
        DB::statement("COMMENT ON COLUMN opponents.city_id IS 'Город команды-соперника (необязательно)'");
        DB::statement("COMMENT ON COLUMN opponents.country_id IS 'Страна команды-соперника (необязательно)'");

        // ── Турниры ────────────────────────────────────────────────────────────

        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tournament_type_id');
            $table->foreign('tournament_type_id')->references('id')->on('ref_tournament_types');
            $table->string('name', 255);
            $table->unsignedBigInteger('logo_file_id')->nullable();
            $table->foreign('logo_file_id')->references('id')->on('files')->nullOnDelete();
            $table->date('starts_at');
            $table->date('ends_at');
            $table->unsignedSmallInteger('half_duration_minutes');
            $table->unsignedSmallInteger('halves_count');
            $table->string('organizer', 255)->nullable();
            $table->timestampsTz();
        });
        DB::statement("COMMENT ON TABLE tournaments IS 'Турниры, в которых участвуют команды клуба'");
        DB::statement("COMMENT ON COLUMN tournaments.tournament_type_id IS 'Вид турнира из справочника ref_tournament_types'");
        DB::statement("COMMENT ON COLUMN tournaments.name IS 'Название турнира'");
        DB::statement("COMMENT ON COLUMN tournaments.logo_file_id IS 'Логотип турнира — ссылка на files (необязательно)'");
        DB::statement("COMMENT ON COLUMN tournaments.starts_at IS 'Дата начала турнира'");
        DB::statement("COMMENT ON COLUMN tournaments.ends_at IS 'Дата окончания турнира. Равна starts_at для однодневных турниров'");
        DB::statement("COMMENT ON COLUMN tournaments.half_duration_minutes IS 'Продолжительность одного тайма в минутах (по регламенту турнира)'");
        DB::statement("COMMENT ON COLUMN tournaments.halves_count IS 'Количество таймов по регламенту турнира'");
        DB::statement("COMMENT ON COLUMN tournaments.organizer IS 'Название организатора турнира (необязательно)'");

        // ── Команды на турнире ─────────────────────────────────────────────────

        Schema::create('tournament_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tournament_id');
            $table->foreign('tournament_id')->references('id')->on('tournaments')->cascadeOnDelete();
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams');
            $table->string('status', 20)->default('participating');  // tournament_entry_status ENUM
            $table->unique(['tournament_id', 'team_id']);
            $table->index('tournament_id', 'idx_tournament_teams_tour');
            $table->index('team_id',       'idx_tournament_teams_team');
        });
        DB::statement("ALTER TABLE tournament_teams ALTER COLUMN status TYPE tournament_entry_status USING status::tournament_entry_status");

        DB::statement("COMMENT ON TABLE tournament_teams IS 'Команды, заявленные для участия в конкретном турнире'");
        DB::statement("COMMENT ON COLUMN tournament_teams.tournament_id IS 'Турнир'");
        DB::statement("COMMENT ON COLUMN tournament_teams.club_id IS 'Клуб, за который выступает команда'");
        DB::statement("COMMENT ON COLUMN tournament_teams.team_id IS 'Заявленная команда'");
        DB::statement("COMMENT ON COLUMN tournament_teams.status IS 'Статус участия: participating = участвует, disqualified = дисквалифицирована'");

        // ── Матчи ──────────────────────────────────────────────────────────────

        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('match_type', 30);  // match_type ENUM
            $table->unsignedBigInteger('tournament_id')->nullable();
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->unsignedSmallInteger('sport_type_id');
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
            $table->unsignedInteger('venue_id');
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams');
            $table->unsignedBigInteger('opponent_team_id')->nullable();
            $table->foreign('opponent_team_id')->references('id')->on('teams');
            $table->unsignedInteger('opponent_id')->nullable();
            $table->foreign('opponent_id')->references('id')->on('opponents');
            $table->timestampTz('scheduled_at');
            $table->unsignedSmallInteger('half_duration_minutes');
            $table->unsignedSmallInteger('halves_count');
            $table->boolean('is_away')->default(false);
            $table->timestampTz('actual_start_at')->nullable();
            $table->timestampTz('actual_end_at')->nullable();
            $table->timestampsTz();
            $table->index('tournament_id', 'idx_matches_tournament');
            $table->index('club_id',       'idx_matches_club');
            $table->index('team_id',       'idx_matches_team');
            $table->index('scheduled_at',  'idx_matches_scheduled');
        });
        DB::statement("ALTER TABLE matches ALTER COLUMN match_type TYPE match_type USING match_type::match_type");
        // CHECK: ровно один соперник
        DB::statement("
            ALTER TABLE matches
            ADD CONSTRAINT chk_opponent
            CHECK ((opponent_team_id IS NOT NULL)::INT + (opponent_id IS NOT NULL)::INT = 1)
        ");

        DB::statement("COMMENT ON TABLE matches IS 'Матчи: товарищеские или в рамках турнира. Хранит нашу команду и соперника (внутреннего или внешнего)'");
        DB::statement("COMMENT ON COLUMN matches.match_type IS 'Тип матча: friendly = товарищеский, tournament_group = групповой этап, tournament_playoff = плей-офф'");
        DB::statement("COMMENT ON COLUMN matches.tournament_id IS 'Турнир, в рамках которого проводится матч. NULL для товарищеских матчей'");
        DB::statement("COMMENT ON COLUMN matches.sport_type_id IS 'Вид спорта матча'");
        DB::statement("COMMENT ON COLUMN matches.venue_id IS 'Место проведения из таблицы venues'");
        DB::statement("COMMENT ON COLUMN matches.name IS 'Название матча (например, «Финал Кубка города»)'");
        DB::statement("COMMENT ON COLUMN matches.description IS 'Дополнительное описание матча (необязательно)'");
        DB::statement("COMMENT ON COLUMN matches.club_id IS 'Наш клуб'");
        DB::statement("COMMENT ON COLUMN matches.team_id IS 'Наша команда'");
        DB::statement("COMMENT ON COLUMN matches.opponent_team_id IS 'Команда-соперник из нашей системы. Заполняется, если соперник зарегистрирован'");
        DB::statement("COMMENT ON COLUMN matches.opponent_id IS 'Внешняя команда-соперник из таблицы opponents. Заполняется, если соперник не в системе'");
        DB::statement("COMMENT ON COLUMN matches.scheduled_at IS 'Запланированные дата и время начала матча'");
        DB::statement("COMMENT ON COLUMN matches.half_duration_minutes IS 'Фактическая продолжительность тайма в минутах'");
        DB::statement("COMMENT ON COLUMN matches.halves_count IS 'Фактическое количество таймов'");
        DB::statement("COMMENT ON COLUMN matches.is_away IS 'Флаг: true = выездной матч, false = домашний'");
        DB::statement("COMMENT ON COLUMN matches.actual_start_at IS 'Фактическое время начала матча (заполняется после начала)'");
        DB::statement("COMMENT ON COLUMN matches.actual_end_at IS 'Фактическое время окончания матча (заполняется после финального свистка)'");

        // ── Тренерский штаб матча ──────────────────────────────────────────────

        Schema::create('match_coaches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->cascadeOnDelete();
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams');
            $table->unsignedBigInteger('coach_user_id');
            $table->foreign('coach_user_id')->references('id')->on('users');
            $table->unique(['match_id', 'coach_user_id']);
            $table->index('match_id', 'idx_match_coaches_match');
        });
        DB::statement("COMMENT ON TABLE match_coaches IS 'Тренерский штаб конкретного матча'");
        DB::statement("COMMENT ON COLUMN match_coaches.match_id IS 'Матч'");
        DB::statement("COMMENT ON COLUMN match_coaches.club_id IS 'Клуб (денормализовано)'");
        DB::statement("COMMENT ON COLUMN match_coaches.team_id IS 'Команда (денормализовано)'");
        DB::statement("COMMENT ON COLUMN match_coaches.coach_user_id IS 'Тренер из таблицы users'");

        // ── Состав игроков на матч ─────────────────────────────────────────────

        Schema::create('match_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->cascadeOnDelete();
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams');
            $table->unsignedBigInteger('player_user_id');
            $table->foreign('player_user_id')->references('id')->on('users');
            $table->unsignedSmallInteger('position_id');
            $table->foreign('position_id')->references('id')->on('ref_positions');
            $table->boolean('is_starter')->default(true);
            $table->text('absence_reason')->nullable();
            $table->unsignedBigInteger('parent_user_id')->nullable();
            $table->foreign('parent_user_id')->references('id')->on('users');
            $table->unique(['match_id', 'player_user_id']);
            $table->index('match_id',       'idx_match_players_match');
            $table->index('player_user_id', 'idx_match_players_player');
        });
        DB::statement("COMMENT ON TABLE match_players IS 'Заявка игроков на конкретный матч с указанием позиции и стартового/запасного статуса'");
        DB::statement("COMMENT ON COLUMN match_players.match_id IS 'Матч'");
        DB::statement("COMMENT ON COLUMN match_players.club_id IS 'Клуб (денормализовано)'");
        DB::statement("COMMENT ON COLUMN match_players.team_id IS 'Команда (денормализовано)'");
        DB::statement("COMMENT ON COLUMN match_players.player_user_id IS 'Игрок из таблицы users'");
        DB::statement("COMMENT ON COLUMN match_players.position_id IS 'Игровая позиция на данный матч'");
        DB::statement("COMMENT ON COLUMN match_players.is_starter IS 'Флаг: true = стартовый состав, false = запасной'");
        DB::statement("COMMENT ON COLUMN match_players.absence_reason IS 'Причина неявки игрока на матч (необязательно)'");
        DB::statement("COMMENT ON COLUMN match_players.parent_user_id IS 'Родитель/опекун игрока (необязательно; нужен для уведомлений)'");

        // ── События матча ──────────────────────────────────────────────────────

        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->cascadeOnDelete();
            $table->unsignedSmallInteger('event_type_id');
            $table->foreign('event_type_id')->references('id')->on('ref_match_event_types');
            $table->unsignedSmallInteger('match_minute');
            $table->unsignedBigInteger('player_user_id');
            $table->foreign('player_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('assistant_user_id')->nullable();
            $table->foreign('assistant_user_id')->references('id')->on('users');
            $table->timestampTz('event_at')->useCurrent();
            $table->timestampTz('created_at')->useCurrent();
            $table->index('match_id',       'idx_match_events_match');
            $table->index('player_user_id', 'idx_match_events_player');
        });
        DB::statement("COMMENT ON TABLE match_events IS 'События матча по минутам: голы, карточки, сейвы и т.д.'");
        DB::statement("COMMENT ON COLUMN match_events.match_id IS 'Матч, в котором произошло событие'");
        DB::statement("COMMENT ON COLUMN match_events.event_type_id IS 'Тип события из справочника ref_match_event_types'");
        DB::statement("COMMENT ON COLUMN match_events.match_minute IS 'Минута матча, на которой произошло событие'");
        DB::statement("COMMENT ON COLUMN match_events.player_user_id IS 'Основной игрок события (забил гол, получил карточку и т.д.)'");
        DB::statement("COMMENT ON COLUMN match_events.assistant_user_id IS 'Ассистент (заполняется только для голов и ассистированных передач)'");
        DB::statement("COMMENT ON COLUMN match_events.event_at IS 'Точное время события (timestamp)'");
        DB::statement("COMMENT ON COLUMN match_events.created_at IS 'Время создания записи в системе'");
    }

    public function down(): void
    {
        Schema::dropIfExists('match_events');
        Schema::dropIfExists('match_players');
        Schema::dropIfExists('match_coaches');
        Schema::dropIfExists('matches');
        Schema::dropIfExists('tournament_teams');
        Schema::dropIfExists('tournaments');
        Schema::dropIfExists('opponents');
        Schema::dropIfExists('ref_match_event_types');
        Schema::dropIfExists('ref_tournament_types');

        DB::statement('DROP TYPE IF EXISTS tournament_entry_status');
        DB::statement('DROP TYPE IF EXISTS match_type');
    }
};
