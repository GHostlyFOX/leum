<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Добавляет:
 *   - users.timezone      — часовой пояс пользователя (IANA, напр. Europe/Moscow)
 *   - teams.team_color    — HEX-цвет команды для UI (#RRGGBB)
 *
 * Соответствует обновлённым ER-диаграммам:
 *   - er_01_users_clubs_teams.puml (users.timezone, teams.team_color)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── users.timezone ────────────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->string('timezone', 50)->nullable()->after('onboarded_at');
        });
        DB::statement("COMMENT ON COLUMN users.timezone IS 'Часовой пояс пользователя в формате IANA (Europe/Moscow, Asia/Yekaterinburg). NULL = пояс сервера'");

        // ── teams.team_color ──────────────────────────────────────────────────
        Schema::table('teams', function (Blueprint $table) {
            $table->string('team_color', 7)->nullable()->after('logo_file_id');
        });
        DB::statement("COMMENT ON COLUMN teams.team_color IS 'HEX-цвет команды для отображения в UI (#RRGGBB)'");
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('team_color');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });
    }
};
