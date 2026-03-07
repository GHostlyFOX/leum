<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Схема 1, часть 1: ENUM-типы, справочники, география, файлы.
 *
 * Таблицы: ref_sport_types, ref_club_types, ref_user_roles, ref_positions,
 *          ref_dominant_feet, ref_kinship_types, ref_document_types,
 *          ref_admission_statuses, countries, cities, files
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ENUM-типы ──────────────────────────────────────────────────────────
        DB::statement("DO $$ BEGIN CREATE TYPE user_gender AS ENUM ('male', 'female'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");
        DB::statement("DO $$ BEGIN CREATE TYPE team_gender AS ENUM ('boys', 'girls', 'mixed'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");

        // ── Справочники ────────────────────────────────────────────────────────

        Schema::create('ref_sport_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 100)->unique();
        });
        DB::statement("COMMENT ON TABLE ref_sport_types IS 'Справочник: виды спорта (Футбол, Хоккей, Баскетбол и т.д.)'");

        Schema::create('ref_club_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 100)->unique();
        });
        DB::statement("COMMENT ON TABLE ref_club_types IS 'Справочник: организационно-правовые типы клубов (Частный, Государственный, Академия)'");

        Schema::create('ref_user_roles', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50)->unique();
        });
        DB::statement("COMMENT ON TABLE ref_user_roles IS 'Справочник: роли пользователей в системе (admin, coach, player, parent и т.д.)'");

        Schema::create('ref_positions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 100);
            $table->unsignedSmallInteger('sport_type_id');
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
            $table->unique(['name', 'sport_type_id']);
        });
        DB::statement("COMMENT ON TABLE ref_positions IS 'Справочник: игровые позиции, привязанные к виду спорта'");
        DB::statement("COMMENT ON COLUMN ref_positions.sport_type_id IS 'Вид спорта, к которому относится позиция'");

        Schema::create('ref_dominant_feet', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 20)->unique();
        });
        DB::statement("COMMENT ON TABLE ref_dominant_feet IS 'Справочник: рабочая нога игрока (left, right, both)'");

        Schema::create('ref_kinship_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 50)->unique();
        });
        DB::statement("COMMENT ON TABLE ref_kinship_types IS 'Справочник: виды родства между родителем и игроком (Мать, Отец, Опекун, Другое)'");

        // ── География ──────────────────────────────────────────────────────────

        Schema::create('countries', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 100)->unique();
        });
        DB::statement("COMMENT ON TABLE countries IS 'Справочник стран. Используется в географии клубов, команд, площадок'");

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedSmallInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unique(['name', 'country_id']);
        });
        DB::statement("COMMENT ON TABLE cities IS 'Справочник городов с привязкой к стране'");
        DB::statement("COMMENT ON COLUMN cities.country_id IS 'Страна, к которой относится город'");

        // ── Файлы ──────────────────────────────────────────────────────────────

        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->text('path')->unique();
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('size_bytes')->unsigned()->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });
        DB::statement("COMMENT ON TABLE files IS 'Централизованный реестр загруженных файлов. Хранит путь/ключ S3, MIME-тип и размер'");
        DB::statement("COMMENT ON COLUMN files.path IS 'Относительный путь к файлу или ключ объекта в S3'");
        DB::statement("COMMENT ON COLUMN files.mime_type IS 'MIME-тип файла (image/jpeg, application/pdf и т.д.)'");
        DB::statement("COMMENT ON COLUMN files.size_bytes IS 'Размер файла в байтах'");
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('ref_kinship_types');
        Schema::dropIfExists('ref_dominant_feet');
        Schema::dropIfExists('ref_positions');
        Schema::dropIfExists('ref_user_roles');
        Schema::dropIfExists('ref_club_types');
        Schema::dropIfExists('ref_sport_types');

        DB::statement('DROP TYPE IF EXISTS team_gender');
        DB::statement('DROP TYPE IF EXISTS user_gender');
    }
};
