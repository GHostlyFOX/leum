<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Изменение поля sport_type_id в ref_tournament_types на nullable
 * чтобы поддерживать общие типы турниров (не привязанные к виду спорта)
 */
return new class extends Migration
{
    public function up(): void
    {
        // Удаляем внешний ключ перед изменением поля
        Schema::table('ref_tournament_types', function (Blueprint $table) {
            $table->dropForeign(['sport_type_id']);
            $table->dropUnique(['name', 'sport_type_id']);
        });

        // Делаем поле nullable
        DB::statement('ALTER TABLE ref_tournament_types ALTER COLUMN sport_type_id DROP NOT NULL');

        // Восстанавливаем внешний ключ и уникальный индекс с учётом NULL
        Schema::table('ref_tournament_types', function (Blueprint $table) {
            $table->foreign('sport_type_id')
                  ->references('id')
                  ->on('ref_sport_types')
                  ->nullOnDelete();
            
            // Уникальный индекс только для не-NULL значений
            // В PostgreSQL: CREATE UNIQUE INDEX idx ON table (COALESCE(sport_type_id, 0), name)
            // Но Laravel не поддерживает такой синтаксис напрямую
            // Поэтому создаём частичный уникальный индекс
        });

        // Создаём частичный уникальный индекс для не-NULL записей
        DB::statement('
            CREATE UNIQUE INDEX ref_tournament_types_name_sport_unique 
            ON ref_tournament_types (name, sport_type_id) 
            WHERE sport_type_id IS NOT NULL
        ');

        // Обновляем комментарий
        DB::statement("COMMENT ON COLUMN ref_tournament_types.sport_type_id IS 'Вид спорта, к которому относится тип турнира (NULL = общий тип для всех видов спорта)'");
    }

    public function down(): void
    {
        // Удаляем индекс
        DB::statement('DROP INDEX IF EXISTS ref_tournament_types_name_sport_unique');

        // Удаляем записи с NULL sport_type_id (иначе не сможем сделать NOT NULL)
        DB::table('ref_tournament_types')->whereNull('sport_type_id')->delete();

        // Удаляем внешний ключ
        Schema::table('ref_tournament_types', function (Blueprint $table) {
            $table->dropForeign(['sport_type_id']);
        });

        // Делаем поле NOT NULL
        DB::statement('ALTER TABLE ref_tournament_types ALTER COLUMN sport_type_id SET NOT NULL');

        // Восстанавливаем внешний ключ и уникальный индекс
        Schema::table('ref_tournament_types', function (Blueprint $table) {
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
            $table->unique(['name', 'sport_type_id']);
        });
    }
};
