<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Создаёт таблицу invite_links — пригласительные ссылки для вступления
 * в команду по токену (squadup.ru/join/{token}).
 *
 * Администратор генерирует ссылку с указанием:
 *   - команды
 *   - роли (player / coach / parent)
 *   - макс. числа использований
 *   - срока действия
 *
 * Соответствует обновлённой ER-диаграмме er_01_users_clubs_teams.puml
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ENUM для роли в инвайте ──────────────────────────────────────────
        DB::statement("DO $$ BEGIN CREATE TYPE invite_role AS ENUM ('player', 'coach', 'parent'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");

        Schema::create('invite_links', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
            $table->string('role', 10);  // invite_role ENUM — тип меняется ниже
            $table->unsignedBigInteger('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestampTz('expires_at');
            $table->timestampTz('created_at')->useCurrent();

            $table->index('team_id', 'idx_invite_links_team');
            $table->index('expires_at', 'idx_invite_links_expires');
        });
        DB::statement("ALTER TABLE invite_links ALTER COLUMN role DROP DEFAULT");
        DB::statement("ALTER TABLE invite_links ALTER COLUMN role TYPE invite_role USING role::invite_role");

        DB::statement("COMMENT ON TABLE invite_links IS 'Пригласительные ссылки для вступления в команду. Переход по ссылке squadup.ru/join/{token} → регистрация/логин → автовступление'");
        DB::statement("COMMENT ON COLUMN invite_links.token IS 'Уникальный случайный токен (64 символа). Используется в URL приглашения'");
        DB::statement("COMMENT ON COLUMN invite_links.team_id IS 'Команда, в которую приглашают'");
        DB::statement("COMMENT ON COLUMN invite_links.role IS 'Роль, с которой пользователь вступит в команду: player / coach / parent'");
        DB::statement("COMMENT ON COLUMN invite_links.created_by_id IS 'Администратор или тренер, создавший приглашение'");
        DB::statement("COMMENT ON COLUMN invite_links.max_uses IS 'Максимальное число активаций. NULL = неограниченно'");
        DB::statement("COMMENT ON COLUMN invite_links.used_count IS 'Текущее число использований'");
        DB::statement("COMMENT ON COLUMN invite_links.expires_at IS 'Дата и время истечения ссылки'");
    }

    public function down(): void
    {
        Schema::dropIfExists('invite_links');
        DB::statement('DROP TYPE IF EXISTS invite_role');
    }
};
