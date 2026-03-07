<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Схема 4: RBAC — глобальная роль пользователя, таблица разрешений,
 * связь «роль → разрешения».
 *
 * Глобальные роли (global_role в users): super_admin, admin, coach, parent, player.
 * ref_user_roles — роли внутри команды (team_members.role_id).
 * permissions     — гранулярные разрешения (clubs.create, trainings.update, …).
 * role_permissions — привязка разрешений к глобальным ролям.
 *
 * Зависит от: миграции 2025_06_01_000002 (users)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ENUM для глобальных ролей ────────────────────────────────────────
        DB::statement("DO $$ BEGIN CREATE TYPE user_global_role AS ENUM (
            'super_admin', 'admin', 'coach', 'parent', 'player'
        ); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");

        // ── Колонка global_role в users ──────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->string('global_role', 20)->default('player')->after('gender');
        });
        DB::statement("ALTER TABLE users ALTER COLUMN global_role DROP DEFAULT");
        DB::statement("ALTER TABLE users ALTER COLUMN global_role TYPE user_global_role USING global_role::user_global_role");
        DB::statement("ALTER TABLE users ALTER COLUMN global_role SET DEFAULT 'player'");
        DB::statement("CREATE INDEX idx_users_global_role ON users (global_role)");
        DB::statement("COMMENT ON COLUMN users.global_role IS 'Глобальная роль пользователя в системе: super_admin, admin, coach, parent, player'");

        // ── Таблица разрешений ───────────────────────────────────────────────
        Schema::create('permissions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug', 80)->unique();   // clubs.create, trainings.update
            $table->string('name', 150);             // Человекочитаемое описание
            $table->string('group', 50)->index();    // clubs, teams, trainings, …
        });
        DB::statement("COMMENT ON TABLE permissions IS 'Гранулярные разрешения системы, сгруппированные по доменам (clubs, teams, trainings и т.д.)'");
        DB::statement("COMMENT ON COLUMN permissions.slug IS 'Уникальный код разрешения в формате domain.action (clubs.create, trainings.update)'");
        DB::statement("COMMENT ON COLUMN permissions.name IS 'Человекочитаемое описание разрешения'");
        DB::statement("COMMENT ON COLUMN permissions.\"group\" IS 'Группа (домен) разрешения: clubs, teams, trainings, matches, tournaments, users, files'");

        // ── Связь «глобальная роль → разрешения» ─────────────────────────────
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->string('role', 20);         // значение user_global_role
            $table->unsignedSmallInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
            $table->primary(['role', 'permission_id']);
        });
        DB::statement("COMMENT ON TABLE role_permissions IS 'Связь глобальных ролей с разрешениями. Определяет, какие действия доступны каждой роли'");
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('global_role');
        });

        DB::statement('DROP TYPE IF EXISTS user_global_role');
    }
};
