<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Добавляет в таблицу matches:
 *   - game_location  — ENUM(home, away) вместо булевого is_away
 *   - score_home     — счёт хозяев
 *   - score_away     — счёт гостей
 *   - score_mode     — ENUM(auto, manual) — режим подсчёта счёта
 *
 * Удаляет:
 *   - is_away (заменено на game_location)
 *
 * Соответствует обновлённой ER-диаграмме er_03_matches_tournaments.puml
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ENUM-типы ────────────────────────────────────────────────────────
        DB::statement("DO $$ BEGIN CREATE TYPE game_location AS ENUM ('home', 'away'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");
        DB::statement("DO $$ BEGIN CREATE TYPE score_mode AS ENUM ('auto', 'manual'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");

        // ── Новые колонки ────────────────────────────────────────────────────
        Schema::table('matches', function (Blueprint $table) {
            $table->smallInteger('score_home')->unsigned()->nullable()->after('halves_count');
            $table->smallInteger('score_away')->unsigned()->nullable()->after('score_home');
            $table->string('score_mode', 10)->default('auto')->after('score_away');
            $table->string('game_location', 10)->default('home')->after('score_mode');
        });

        // Конвертируем is_away → game_location
        DB::statement("UPDATE matches SET game_location = CASE WHEN is_away THEN 'away' ELSE 'home' END");

        // Применяем ENUM-типы
        DB::statement("ALTER TABLE matches ALTER COLUMN score_mode DROP DEFAULT");
        DB::statement("ALTER TABLE matches ALTER COLUMN score_mode TYPE score_mode USING score_mode::score_mode");
        DB::statement("ALTER TABLE matches ALTER COLUMN score_mode SET DEFAULT 'auto'");

        DB::statement("ALTER TABLE matches ALTER COLUMN game_location DROP DEFAULT");
        DB::statement("ALTER TABLE matches ALTER COLUMN game_location TYPE game_location USING game_location::game_location");
        DB::statement("ALTER TABLE matches ALTER COLUMN game_location SET DEFAULT 'home'");

        // Удаляем старую колонку is_away
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('is_away');
        });

        // ── Комментарии ──────────────────────────────────────────────────────
        DB::statement("COMMENT ON COLUMN matches.score_home IS 'Счёт хозяев. NULL до окончания матча'");
        DB::statement("COMMENT ON COLUMN matches.score_away IS 'Счёт гостей. NULL до окончания матча'");
        DB::statement("COMMENT ON COLUMN matches.score_mode IS 'Режим подсчёта: auto = автосумма из match_events, manual = ручной ввод'");
        DB::statement("COMMENT ON COLUMN matches.game_location IS 'Место проведения: home = домашний, away = выездной'");
    }

    public function down(): void
    {
        // Восстанавливаем is_away
        Schema::table('matches', function (Blueprint $table) {
            $table->boolean('is_away')->default(false)->after('halves_count');
        });

        // Конвертируем game_location → is_away
        DB::statement("UPDATE matches SET is_away = (game_location = 'away')");

        // Удаляем новые колонки
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['game_location', 'score_mode', 'score_away', 'score_home']);
        });

        DB::statement('DROP TYPE IF EXISTS score_mode');
        DB::statement('DROP TYPE IF EXISTS game_location');
    }
};
