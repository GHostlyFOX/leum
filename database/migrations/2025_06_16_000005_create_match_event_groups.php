<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Создание таблицы групп типов событий матча
 * и обновление таблицы ref_match_event_types
 * 
 * Теперь типы событий группируются по категориям (голы, карточки, замены)
 * и могут быть привязаны к конкретному виду спорта
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Создание таблицы групп типов событий ─────────────────────────────
        Schema::create('ref_match_event_groups', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->string('icon', 50)->nullable();
            $table->string('color', 20)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->unsignedSmallInteger('sport_type_id')->nullable();
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types')->nullOnDelete();
        });
        DB::statement("COMMENT ON TABLE ref_match_event_groups IS 'Группы типов событий матча (голы, карточки, замены и т.д.)'");
        DB::statement("COMMENT ON COLUMN ref_match_event_groups.code IS 'Код группы для использования в API (goals, cards, subs)'");
        DB::statement("COMMENT ON COLUMN ref_match_event_groups.icon IS 'CSS-класс или имя иконки'");
        DB::statement("COMMENT ON COLUMN ref_match_event_groups.color IS 'Цвет группы в HEX (#ff0000) или название'");
        DB::statement("COMMENT ON COLUMN ref_match_event_groups.sort_order IS 'Порядок сортировки групп'");
        DB::statement("COMMENT ON COLUMN ref_match_event_groups.sport_type_id IS 'Привязка к виду спорта (NULL = общая группа)'");

        // ── Обновление таблицы типов событий ─────────────────────────────────
        // Сначала удаляем внешний ключ из match_events
        Schema::table('match_events', function (Blueprint $table) {
            $table->dropForeign(['event_type_id']);
        });

        // Теперь можно удалить старую таблицу
        Schema::dropIfExists('ref_match_event_types');
        
        // Создаём новую таблицу с дополнительными полями
        Schema::create('ref_match_event_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->unsignedSmallInteger('group_id');
            $table->foreign('group_id')->references('id')->on('ref_match_event_groups');
            $table->unsignedSmallInteger('sport_type_id')->nullable();
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types')->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_statistical')->default(true);
            $table->boolean('affects_score')->default(false);
            $table->string('icon', 50)->nullable();
            $table->string('color', 20)->nullable();
        });
        DB::statement("COMMENT ON TABLE ref_match_event_types IS 'Типы событий матча (гол, карточка, замена и т.д.)'");
        DB::statement("COMMENT ON COLUMN ref_match_event_types.group_id IS 'Группа события (голы, карточки и т.д.)'");
        DB::statement("COMMENT ON COLUMN ref_match_event_types.sport_type_id IS 'Привязка к виду спорта (NULL = для всех видов)'");
        DB::statement("COMMENT ON COLUMN ref_match_event_types.is_statistical IS 'Учитывается в статистике игрока'");
        DB::statement("COMMENT ON COLUMN ref_match_event_types.affects_score IS 'Влияет на счёт матча'");
        DB::statement("COMMENT ON COLUMN ref_match_event_types.code IS 'Код события для API (goal, yellow_card и т.д.)'");

        // Восстанавливаем внешний ключ в match_events
        Schema::table('match_events', function (Blueprint $table) {
            $table->foreign('event_type_id')->references('id')->on('ref_match_event_types');
        });

        // ── Обновление таблицы match_events ──────────────────────────────────
        // Добавляем поле для хранения дополнительных данных события (JSON)
        Schema::table('match_events', function (Blueprint $table) {
            $table->jsonb('metadata')->nullable()->after('assistant_user_id');
            $table->string('description', 255)->nullable()->after('metadata');
        });
        DB::statement("COMMENT ON COLUMN match_events.metadata IS 'Дополнительные данные события в формате JSON'");
        DB::statement("COMMENT ON COLUMN match_events.description IS 'Описание/комментарий к событию'");
    }

    public function down(): void
    {
        Schema::table('match_events', function (Blueprint $table) {
            $table->dropColumn(['metadata', 'description']);
            $table->dropForeign(['event_type_id']);
        });
        
        Schema::dropIfExists('ref_match_event_types');
        Schema::dropIfExists('ref_match_event_groups');
        
        // Восстановление старой структуры
        Schema::create('ref_match_event_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 100)->unique();
        });

        // Восстанавливаем внешний ключ
        Schema::table('match_events', function (Blueprint $table) {
            $table->foreign('event_type_id')->references('id')->on('ref_match_event_types');
        });
    }
};
