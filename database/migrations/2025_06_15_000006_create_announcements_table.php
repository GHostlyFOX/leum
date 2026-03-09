<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Создаёт таблицу announcements — объявления клуба/команды.
 *
 * Администратор или тренер создаёт объявление с приоритетом,
 * привязывает к команде (или ко всему клубу), может сохранить
 * как черновик или опубликовать сразу.
 *
 * Соответствует обновлённой ER-диаграмме er_02_trainings.puml
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── ENUM для приоритета ──────────────────────────────────────────────
        DB::statement("DO $$ BEGIN CREATE TYPE announcement_priority AS ENUM ('normal', 'important', 'urgent'); EXCEPTION WHEN duplicate_object THEN NULL; END $$;");

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('message');
            $table->string('priority', 15)->default('normal');  // announcement_priority ENUM
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
            $table->unsignedBigInteger('club_id');
            $table->foreign('club_id')->references('id')->on('clubs')->cascadeOnDelete();
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users');
            $table->timestampTz('published_at')->nullable();
            $table->timestampTz('expires_at')->nullable();
            $table->boolean('is_draft')->default(true);
            $table->timestampsTz();

            $table->index('club_id', 'idx_announcements_club');
            $table->index('team_id', 'idx_announcements_team');
            $table->index('published_at', 'idx_announcements_published');
            $table->index('is_draft', 'idx_announcements_draft');
        });
        DB::statement("ALTER TABLE announcements ALTER COLUMN priority DROP DEFAULT");
        DB::statement("ALTER TABLE announcements ALTER COLUMN priority TYPE announcement_priority USING priority::announcement_priority");
        DB::statement("ALTER TABLE announcements ALTER COLUMN priority SET DEFAULT 'normal'");

        DB::statement("COMMENT ON TABLE announcements IS 'Объявления клуба или команды. Поддерживают черновики, приоритет и срок действия'");
        DB::statement("COMMENT ON COLUMN announcements.title IS 'Заголовок объявления'");
        DB::statement("COMMENT ON COLUMN announcements.message IS 'Текст объявления'");
        DB::statement("COMMENT ON COLUMN announcements.priority IS 'Приоритет: normal, important, urgent. Влияет на отображение в UI и push-уведомления'");
        DB::statement("COMMENT ON COLUMN announcements.team_id IS 'Команда-адресат. NULL = объявление для всего клуба'");
        DB::statement("COMMENT ON COLUMN announcements.club_id IS 'Клуб-владелец объявления'");
        DB::statement("COMMENT ON COLUMN announcements.author_id IS 'Автор объявления (администратор или тренер)'");
        DB::statement("COMMENT ON COLUMN announcements.published_at IS 'Дата/время публикации. NULL = ещё не опубликовано (черновик)'");
        DB::statement("COMMENT ON COLUMN announcements.expires_at IS 'Дата/время истечения. NULL = бессрочное объявление'");
        DB::statement("COMMENT ON COLUMN announcements.is_draft IS 'Флаг черновика: true = не опубликовано'");
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
        DB::statement('DROP TYPE IF EXISTS announcement_priority');
    }
};
