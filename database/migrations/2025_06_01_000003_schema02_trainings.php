<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Схема 2: Тренировки.
 *
 * Таблицы: ref_training_types, venues, recurring_trainings, trainings,
 *          training_attendance, training_media
 *
 * Зависит от: схемы 1 (users, clubs, teams, countries, cities, files)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ENUM-типы ──────────────────────────────────────────────────────────
        DB::statement("CREATE TYPE training_status AS ENUM ('scheduled', 'completed', 'cancelled')");
        DB::statement("CREATE TYPE attendance_status AS ENUM ('pending', 'present', 'absent')");

        // ── Виды тренировок (per-club) ─────────────────────────────────────────

        Schema::create('ref_training_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->cascadeOnDelete();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->unique(['club_id', 'name']);
        });
        DB::statement("COMMENT ON TABLE ref_training_types IS 'Справочник видов тренировок, задаётся каждым клубом индивидуально'");
        DB::statement("COMMENT ON COLUMN ref_training_types.club_id IS 'Клуб, создавший этот вид тренировки'");
        DB::statement("COMMENT ON COLUMN ref_training_types.name IS 'Название вида тренировки (например, «Физическая подготовка», «Тактика»)'");
        DB::statement("COMMENT ON COLUMN ref_training_types.description IS 'Подробное описание вида тренировки (необязательно)'");

        // ── Места проведения ───────────────────────────────────────────────────

        Schema::create('venues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->unsignedSmallInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->text('address');
            $table->unsignedBigInteger('club_id')->nullable();
            $table->foreign('club_id')->references('id')->on('clubs')->nullOnDelete();
            $table->timestampTz('created_at')->useCurrent();
            $table->index('club_id', 'idx_venues_club');
        });
        DB::statement("COMMENT ON TABLE venues IS 'Места проведения тренировок и матчей (стадионы, залы, поля). Общедоступные площадки имеют club_id = NULL'");
        DB::statement("COMMENT ON COLUMN venues.name IS 'Название площадки (например, «Стадион Лужники», «Зал №3»)'");
        DB::statement("COMMENT ON COLUMN venues.country_id IS 'Страна расположения площадки'");
        DB::statement("COMMENT ON COLUMN venues.city_id IS 'Город расположения площадки'");
        DB::statement("COMMENT ON COLUMN venues.address IS 'Полный почтовый адрес'");
        DB::statement("COMMENT ON COLUMN venues.club_id IS 'Клуб-владелец площадки. NULL = общедоступная площадка'");

        // ── Шаблоны регулярных тренировок ──────────────────────────────────────

        Schema::create('recurring_trainings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->cascadeOnDelete();
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
            $table->jsonb('schedule');
            $table->jsonb('auto_create');
            $table->boolean('notify_parents')->default(true);
            $table->boolean('require_rsvp')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
            $table->index('club_id', 'idx_recurring_club');
            $table->index('team_id', 'idx_recurring_team');
        });
        DB::statement("COMMENT ON TABLE recurring_trainings IS 'Шаблоны регулярных тренировок: еженедельное расписание с правилами авто-генерации'");
        DB::statement("COMMENT ON COLUMN recurring_trainings.club_id IS 'Клуб, которому принадлежит шаблон'");
        DB::statement("COMMENT ON COLUMN recurring_trainings.team_id IS 'Команда, для которой создан шаблон'");
        DB::statement("COMMENT ON COLUMN recurring_trainings.schedule IS 'Недельное расписание сессий в формате JSON: [{\"day_of_week\":1,\"start_time\":\"10:00\",\"venue_id\":5,\"coach_id\":42,\"duration_minutes\":90}]'");
        DB::statement("COMMENT ON COLUMN recurring_trainings.auto_create IS 'Правила авто-генерации тренировок: {\"advance_days\":7,\"until_date\":\"2025-08-31\"}'");
        DB::statement("COMMENT ON COLUMN recurring_trainings.notify_parents IS 'Флаг: отправлять ли уведомления родителям при создании тренировки из шаблона'");
        DB::statement("COMMENT ON COLUMN recurring_trainings.require_rsvp IS 'Флаг: требовать ли подтверждение посещения (RSVP) от родителей'");
        DB::statement("COMMENT ON COLUMN recurring_trainings.is_active IS 'Флаг активности шаблона. FALSE = шаблон приостановлен или в архиве'");

        // ── Тренировки ─────────────────────────────────────────────────────────

        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_id');
            $table->foreign('coach_id')->references('id')->on('users');
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams');
            $table->date('training_date');
            $table->time('start_time');
            $table->unsignedSmallInteger('duration_minutes');
            $table->unsignedInteger('venue_id');
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->unsignedInteger('training_type_id');
            $table->foreign('training_type_id')->references('id')->on('ref_training_types');
            $table->string('status', 20)->default('scheduled');  // training_status ENUM
            $table->boolean('notify_parents')->default(true);
            $table->boolean('require_rsvp')->default(true);
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('recurring_id')->nullable();
            $table->foreign('recurring_id')->references('id')->on('recurring_trainings')->nullOnDelete();
            $table->timestampsTz();
            $table->index('coach_id',       'idx_trainings_coach');
            $table->index('club_id',        'idx_trainings_club');
            $table->index('team_id',        'idx_trainings_team');
            $table->index('training_date',  'idx_trainings_date');
            $table->index('recurring_id',   'idx_trainings_recurring');
        });
        DB::statement("ALTER TABLE trainings ALTER COLUMN status TYPE training_status USING status::training_status");

        DB::statement("COMMENT ON TABLE trainings IS 'Конкретные тренировочные занятия. Могут быть разовыми или порождёнными из шаблона recurring_trainings'");
        DB::statement("COMMENT ON COLUMN trainings.coach_id IS 'Тренер, проводящий занятие'");
        DB::statement("COMMENT ON COLUMN trainings.club_id IS 'Клуб (денормализовано для быстрых запросов)'");
        DB::statement("COMMENT ON COLUMN trainings.team_id IS 'Команда, для которой проводится тренировка'");
        DB::statement("COMMENT ON COLUMN trainings.training_date IS 'Дата проведения тренировки'");
        DB::statement("COMMENT ON COLUMN trainings.start_time IS 'Время начала тренировки'");
        DB::statement("COMMENT ON COLUMN trainings.duration_minutes IS 'Продолжительность тренировки в минутах'");
        DB::statement("COMMENT ON COLUMN trainings.venue_id IS 'Место проведения из таблицы venues'");
        DB::statement("COMMENT ON COLUMN trainings.training_type_id IS 'Вид тренировки из справочника ref_training_types'");
        DB::statement("COMMENT ON COLUMN trainings.status IS 'Статус тренировки: scheduled / completed / cancelled'");
        DB::statement("COMMENT ON COLUMN trainings.notify_parents IS 'Флаг: уведомить родителей об этой тренировке'");
        DB::statement("COMMENT ON COLUMN trainings.require_rsvp IS 'Флаг: требовать подтверждение посещения'");
        DB::statement("COMMENT ON COLUMN trainings.comment IS 'Заметки тренера к тренировке (необязательно)'");
        DB::statement("COMMENT ON COLUMN trainings.recurring_id IS 'Ссылка на шаблон recurring_trainings. NULL = разовая тренировка'");

        // ── Посещаемость ───────────────────────────────────────────────────────

        Schema::create('training_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_id');
            $table->foreign('training_id')->references('id')->on('trainings')->cascadeOnDelete();
            $table->unsignedBigInteger('player_user_id');
            $table->foreign('player_user_id')->references('id')->on('users');
            $table->unsignedBigInteger('marked_by_user_id');
            $table->foreign('marked_by_user_id')->references('id')->on('users');
            $table->string('attendance_status', 20)->default('pending');  // attendance_status ENUM
            $table->timestampTz('confirmed_at')->nullable();
            $table->text('absence_reason')->nullable();
            $table->timestampsTz();
            $table->unique(['training_id', 'player_user_id']);
            $table->index('training_id',    'idx_attendance_training');
            $table->index('player_user_id', 'idx_attendance_player');
        });
        DB::statement("ALTER TABLE training_attendance ALTER COLUMN attendance_status TYPE attendance_status USING attendance_status::attendance_status");

        DB::statement("COMMENT ON TABLE training_attendance IS 'Посещаемость тренировок: статус присутствия каждого игрока с возможностью причины отсутствия'");
        DB::statement("COMMENT ON COLUMN training_attendance.training_id IS 'Тренировка, к которой относится запись'");
        DB::statement("COMMENT ON COLUMN training_attendance.player_user_id IS 'Игрок'");
        DB::statement("COMMENT ON COLUMN training_attendance.marked_by_user_id IS 'Пользователь, отметивший посещаемость (тренер или родитель)'");
        DB::statement("COMMENT ON COLUMN training_attendance.attendance_status IS 'Статус: pending = ещё не ответил, present = присутствовал, absent = отсутствовал'");
        DB::statement("COMMENT ON COLUMN training_attendance.confirmed_at IS 'Момент подтверждения присутствия. NULL = ещё не подтверждено'");
        DB::statement("COMMENT ON COLUMN training_attendance.absence_reason IS 'Причина отсутствия (заполняется только при absent)'");

        // ── Медиа тренировки ───────────────────────────────────────────────────

        Schema::create('training_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_id');
            $table->foreign('training_id')->references('id')->on('trainings')->cascadeOnDelete();
            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')->references('id')->on('files')->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestampTz('created_at')->useCurrent();
            $table->index('training_id', 'idx_training_media_train');
        });
        DB::statement("COMMENT ON TABLE training_media IS 'Медиафайлы (фото/видео), прикреплённые к тренировке'");
        DB::statement("COMMENT ON COLUMN training_media.training_id IS 'Тренировка, к которой прикреплён файл'");
        DB::statement("COMMENT ON COLUMN training_media.file_id IS 'Файл из централизованного реестра files'");
        DB::statement("COMMENT ON COLUMN training_media.sort_order IS 'Порядок отображения медиафайла в галерее'");
    }

    public function down(): void
    {
        Schema::dropIfExists('training_media');
        Schema::dropIfExists('training_attendance');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('recurring_trainings');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('ref_training_types');

        DB::statement('DROP TYPE IF EXISTS attendance_status');
        DB::statement('DROP TYPE IF EXISTS training_status');
    }
};
