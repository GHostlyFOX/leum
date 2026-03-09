<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Создаёт таблицы:
 *   - seasons       — спортивные сезоны клуба (Осень 2025, Весна 2026)
 *   - season_teams  — привязка команд к сезонам (M:N)
 *
 * Соответствует обновлённой ER-диаграмме er_01_users_clubs_teams.puml
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ENUM-тип для статуса сезона ──────────────────────────────────────
        DB::statement("DO $$ BEGIN CREATE TYPE season_status AS ENUM ('planned', 'active', 'archived'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");

        // ── Сезоны ───────────────────────────────────────────────────────────
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->cascadeOnDelete();
            $table->unsignedSmallInteger('sport_type_id');
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
            $table->string('status', 20)->default('planned');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestampsTz();

            $table->index('club_id', 'idx_seasons_club');
            $table->index('status', 'idx_seasons_status');
        });
        DB::statement("ALTER TABLE seasons ALTER COLUMN status DROP DEFAULT");
        DB::statement("ALTER TABLE seasons ALTER COLUMN status TYPE season_status USING status::season_status");
        DB::statement("ALTER TABLE seasons ALTER COLUMN status SET DEFAULT 'planned'");

        DB::statement("COMMENT ON TABLE seasons IS 'Спортивные сезоны клуба. Каждый сезон имеет дату начала/окончания и привязку к командам'");
        DB::statement("COMMENT ON COLUMN seasons.name IS 'Название сезона (напр. «Осень 2025», «Первенство города 2026»)'");
        DB::statement("COMMENT ON COLUMN seasons.club_id IS 'Клуб, которому принадлежит сезон'");
        DB::statement("COMMENT ON COLUMN seasons.sport_type_id IS 'Вид спорта сезона'");
        DB::statement("COMMENT ON COLUMN seasons.status IS 'Статус: planned = запланирован, active = текущий, archived = завершён'");
        DB::statement("COMMENT ON COLUMN seasons.start_date IS 'Дата начала сезона'");
        DB::statement("COMMENT ON COLUMN seasons.end_date IS 'Дата окончания сезона'");

        // ── Привязка команд к сезонам ────────────────────────────────────────
        Schema::create('season_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('season_id');
            $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnDelete();
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
            $table->unique(['season_id', 'team_id']);
            $table->index('season_id', 'idx_season_teams_season');
            $table->index('team_id', 'idx_season_teams_team');
        });
        DB::statement("COMMENT ON TABLE season_teams IS 'Связь M:N — какие команды участвуют в каком сезоне'");
        DB::statement("COMMENT ON COLUMN season_teams.season_id IS 'Сезон'");
        DB::statement("COMMENT ON COLUMN season_teams.team_id IS 'Команда, участвующая в сезоне'");
    }

    public function down(): void
    {
        Schema::dropIfExists('season_teams');
        Schema::dropIfExists('seasons');
        DB::statement('DROP TYPE IF EXISTS season_status');
    }
};
