<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Создаёт таблицу event_responses — полиморфные RSVP-ответы пользователей
 * на любые типы событий (тренировки, матчи, турниры и т.д.).
 *
 * Полиморфная связь через event_type + event_id:
 *   - event_type = 'training'   → trainings.id
 *   - event_type = 'match'      → matches.id
 *   - event_type = 'tournament' → tournaments.id
 *
 * Соответствует обновлённой ER-диаграмме er_02_trainings.puml
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ENUM для статуса RSVP ────────────────────────────────────────────
        DB::statement("DO $$ BEGIN CREATE TYPE rsvp_status AS ENUM ('yes', 'no', 'maybe', 'pending'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");

        Schema::create('event_responses', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 50);       // training, match, tournament
            $table->unsignedBigInteger('event_id');  // FK на соответствующую таблицу
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('status', 10)->default('pending');  // rsvp_status ENUM
            $table->text('comment')->nullable();
            $table->timestampTz('responded_at')->nullable();
            $table->timestampsTz();

            // Один ответ от одного пользователя на одно событие
            $table->unique(['event_type', 'event_id', 'user_id'], 'uniq_event_response');

            $table->index(['event_type', 'event_id'], 'idx_event_responses_event');
            $table->index('user_id', 'idx_event_responses_user');
        });
        DB::statement("ALTER TABLE event_responses ALTER COLUMN status DROP DEFAULT");
        DB::statement("ALTER TABLE event_responses ALTER COLUMN status TYPE rsvp_status USING status::rsvp_status");
        DB::statement("ALTER TABLE event_responses ALTER COLUMN status SET DEFAULT 'pending'");

        DB::statement("COMMENT ON TABLE event_responses IS 'Полиморфные RSVP-ответы. Один пользователь — один ответ на одно событие любого типа'");
        DB::statement("COMMENT ON COLUMN event_responses.event_type IS 'Тип события: training, match, tournament. Определяет таблицу для event_id'");
        DB::statement("COMMENT ON COLUMN event_responses.event_id IS 'ID записи в таблице, определяемой event_type (trainings.id, matches.id и т.д.)'");
        DB::statement("COMMENT ON COLUMN event_responses.user_id IS 'Пользователь, давший ответ (родитель, игрок, тренер)'");
        DB::statement("COMMENT ON COLUMN event_responses.status IS 'Статус ответа: yes = подтверждено, no = отклонено, maybe = под вопросом, pending = ожидает ответа'");
        DB::statement("COMMENT ON COLUMN event_responses.comment IS 'Комментарий к ответу (необязательно, напр. «на тренировке будет с 18:00»)'");
        DB::statement("COMMENT ON COLUMN event_responses.responded_at IS 'Дата/время последнего ответа. NULL = ещё не ответил'");
    }

    public function down(): void
    {
        Schema::dropIfExists('event_responses');
        DB::statement('DROP TYPE IF EXISTS rsvp_status');
    }
};
