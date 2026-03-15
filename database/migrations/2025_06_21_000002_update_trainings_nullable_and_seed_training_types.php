<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 1. Делает venue_id и training_type_id nullable в таблице trainings
 *    (тренировку можно создать без указания места и типа).
 * 2. Добавляет sport_type_id в ref_training_types для глобальных типов тренировок.
 * 3. Делает club_id nullable в ref_training_types (NULL = глобальный тип).
 * 4. Сеет справочник типов тренировок по видам спорта.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. trainings: venue_id и training_type_id → nullable ──────────────

        // Убираем FK, меняем на nullable, возвращаем FK
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropForeign(['venue_id']);
            $table->dropForeign(['training_type_id']);
        });

        DB::statement('ALTER TABLE trainings ALTER COLUMN venue_id DROP NOT NULL');
        DB::statement('ALTER TABLE trainings ALTER COLUMN training_type_id DROP NOT NULL');

        Schema::table('trainings', function (Blueprint $table) {
            $table->foreign('venue_id')->references('id')->on('venues')->nullOnDelete();
            $table->foreign('training_type_id')->references('id')->on('ref_training_types')->nullOnDelete();
        });

        DB::statement("COMMENT ON COLUMN trainings.venue_id IS 'Место проведения (nullable — можно указать позже)'");
        DB::statement("COMMENT ON COLUMN trainings.training_type_id IS 'Тип тренировки (nullable — можно указать позже)'");

        // ── 2. ref_training_types: sport_type_id + nullable club_id ──────────

        Schema::table('ref_training_types', function (Blueprint $table) {
            $table->dropForeign(['club_id']);
        });

        // Удаляем unique constraint (club_id, name) — будем заменять на (sport_type_id, club_id, name)
        DB::statement('ALTER TABLE ref_training_types DROP CONSTRAINT IF EXISTS ref_training_types_club_id_name_unique');

        DB::statement('ALTER TABLE ref_training_types ALTER COLUMN club_id DROP NOT NULL');

        Schema::table('ref_training_types', function (Blueprint $table) {
            $table->foreign('club_id')->references('id')->on('clubs')->nullOnDelete();
            $table->unsignedSmallInteger('sport_type_id')->nullable()->after('club_id');
            $table->foreign('sport_type_id')->references('id')->on('ref_sport_types');
        });

        // Новый unique constraint: в рамках вида спорта и клуба не должно быть дублей
        DB::statement('CREATE UNIQUE INDEX idx_ref_training_types_unique ON ref_training_types (COALESCE(club_id, 0), COALESCE(sport_type_id, 0), name)');

        DB::statement("COMMENT ON COLUMN ref_training_types.club_id IS 'Клуб-владелец типа. NULL = глобальный тип, доступный всем клубам данного вида спорта'");
        DB::statement("COMMENT ON COLUMN ref_training_types.sport_type_id IS 'Вид спорта, к которому относится тип тренировки'");

        // ── 3. Сеем глобальные типы тренировок по видам спорта ────────────────

        $this->seedTrainingTypes();
    }

    public function down(): void
    {
        // Удаляем сеяные данные
        DB::table('ref_training_types')->whereNull('club_id')->delete();

        // Убираем sport_type_id
        DB::statement('DROP INDEX IF EXISTS idx_ref_training_types_unique');

        Schema::table('ref_training_types', function (Blueprint $table) {
            $table->dropForeign(['sport_type_id']);
            $table->dropColumn('sport_type_id');
            $table->dropForeign(['club_id']);
        });

        DB::statement('ALTER TABLE ref_training_types ALTER COLUMN club_id SET NOT NULL');

        Schema::table('ref_training_types', function (Blueprint $table) {
            $table->foreign('club_id')->references('id')->on('clubs')->cascadeOnDelete();
            $table->unique(['club_id', 'name']);
        });

        // Возвращаем NOT NULL для trainings
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropForeign(['venue_id']);
            $table->dropForeign(['training_type_id']);
        });

        // Ставить NOT NULL назад опасно если есть NULL-записи, пропускаем
        Schema::table('trainings', function (Blueprint $table) {
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->foreign('training_type_id')->references('id')->on('ref_training_types');
        });
    }

    /**
     * Справочник глобальных типов тренировок, разделённых по видам спорта.
     */
    private function seedTrainingTypes(): void
    {
        $types = [
            // Общие типы (для всех видов спорта, sport_type_id = NULL)
            ['club_id' => null, 'sport_type_id' => null, 'name' => 'Общая физическая подготовка (ОФП)', 'description' => 'Развитие общих физических качеств: выносливость, сила, гибкость, координация'],
            ['club_id' => null, 'sport_type_id' => null, 'name' => 'Специальная физическая подготовка (СФП)', 'description' => 'Развитие специфических для вида спорта физических качеств'],
            ['club_id' => null, 'sport_type_id' => null, 'name' => 'Разминка / Восстановление', 'description' => 'Лёгкая тренировка для разминки или восстановления после нагрузок'],
            ['club_id' => null, 'sport_type_id' => null, 'name' => 'Теоретическое занятие', 'description' => 'Изучение тактики, правил, просмотр видео'],
            ['club_id' => null, 'sport_type_id' => null, 'name' => 'Контрольная тренировка', 'description' => 'Тестирование и оценка уровня подготовленности'],

            // Футбол (id=1)
            ['club_id' => null, 'sport_type_id' => 1, 'name' => 'Техническая тренировка', 'description' => 'Работа с мячом: пас, удар, дриблинг, приём мяча'],
            ['club_id' => null, 'sport_type_id' => 1, 'name' => 'Тактическая тренировка', 'description' => 'Отработка командных схем, расстановок, взаимодействий'],
            ['club_id' => null, 'sport_type_id' => 1, 'name' => 'Тренировка вратарей', 'description' => 'Специализированная работа для вратарей'],
            ['club_id' => null, 'sport_type_id' => 1, 'name' => 'Двусторонняя игра', 'description' => 'Тренировочный матч внутри команды'],
            ['club_id' => null, 'sport_type_id' => 1, 'name' => 'Стандартные положения', 'description' => 'Отработка угловых, штрафных, ауты'],

            // Хоккей (id=2)
            ['club_id' => null, 'sport_type_id' => 2, 'name' => 'Ледовая тренировка', 'description' => 'Тренировка на льду: катание, владение шайбой'],
            ['club_id' => null, 'sport_type_id' => 2, 'name' => 'Тактическая тренировка', 'description' => 'Отработка командных взаимодействий на льду'],
            ['club_id' => null, 'sport_type_id' => 2, 'name' => 'Броски и добивания', 'description' => 'Работа над точностью и силой бросков'],
            ['club_id' => null, 'sport_type_id' => 2, 'name' => 'Тренировка вратарей', 'description' => 'Специализированные упражнения для вратарей'],
            ['club_id' => null, 'sport_type_id' => 2, 'name' => 'Силовая подготовка (зал)', 'description' => 'Тренировка в тренажёрном зале'],

            // Баскетбол (id=3)
            ['club_id' => null, 'sport_type_id' => 3, 'name' => 'Техническая тренировка', 'description' => 'Дриблинг, передачи, броски, работа с мячом'],
            ['club_id' => null, 'sport_type_id' => 3, 'name' => 'Тактическая тренировка', 'description' => 'Командные комбинации, защита, нападение'],
            ['club_id' => null, 'sport_type_id' => 3, 'name' => 'Бросковая тренировка', 'description' => 'Работа над точностью бросков с разных дистанций'],
            ['club_id' => null, 'sport_type_id' => 3, 'name' => 'Двусторонняя игра', 'description' => 'Тренировочный матч внутри команды'],

            // Волейбол (id=4)
            ['club_id' => null, 'sport_type_id' => 4, 'name' => 'Техническая тренировка', 'description' => 'Приём, подача, передача, блок, нападающий удар'],
            ['club_id' => null, 'sport_type_id' => 4, 'name' => 'Тактическая тренировка', 'description' => 'Командные взаимодействия, расстановки'],
            ['club_id' => null, 'sport_type_id' => 4, 'name' => 'Подачи и приём', 'description' => 'Специализированная работа над подачей и приёмом'],

            // Теннис (id=5)
            ['club_id' => null, 'sport_type_id' => 5, 'name' => 'Техническая тренировка', 'description' => 'Удары с отскока, подача, воллей, смэш'],
            ['club_id' => null, 'sport_type_id' => 5, 'name' => 'Тактическая тренировка', 'description' => 'Игровые комбинации, работа в корте'],
            ['club_id' => null, 'sport_type_id' => 5, 'name' => 'Спарринг', 'description' => 'Тренировочная игра с партнёром'],

            // Плавание (id=6)
            ['club_id' => null, 'sport_type_id' => 6, 'name' => 'Техническая тренировка', 'description' => 'Работа над техникой плавания разными стилями'],
            ['club_id' => null, 'sport_type_id' => 6, 'name' => 'Скоростная тренировка', 'description' => 'Работа на скорость и выносливость в воде'],
            ['club_id' => null, 'sport_type_id' => 6, 'name' => 'Тренировка старта и поворотов', 'description' => 'Специализированная работа над стартом и поворотами'],

            // Лёгкая атлетика (id=7)
            ['club_id' => null, 'sport_type_id' => 7, 'name' => 'Беговая тренировка', 'description' => 'Спринт, средние и длинные дистанции'],
            ['club_id' => null, 'sport_type_id' => 7, 'name' => 'Техническая тренировка', 'description' => 'Работа над техникой прыжков или метаний'],
            ['club_id' => null, 'sport_type_id' => 7, 'name' => 'Кроссовая подготовка', 'description' => 'Бег по пересечённой местности'],

            // Гимнастика (id=8)
            ['club_id' => null, 'sport_type_id' => 8, 'name' => 'Тренировка на снарядах', 'description' => 'Работа на гимнастических снарядах'],
            ['club_id' => null, 'sport_type_id' => 8, 'name' => 'Хореография', 'description' => 'Хореографическая подготовка для вольных упражнений'],
            ['club_id' => null, 'sport_type_id' => 8, 'name' => 'Акробатическая подготовка', 'description' => 'Отработка акробатических элементов'],

            // Единоборства (id=9)
            ['club_id' => null, 'sport_type_id' => 9, 'name' => 'Техническая тренировка', 'description' => 'Отработка приёмов и техники'],
            ['club_id' => null, 'sport_type_id' => 9, 'name' => 'Спарринг', 'description' => 'Тренировочный бой с партнёром'],
            ['club_id' => null, 'sport_type_id' => 9, 'name' => 'Работа на снарядах', 'description' => 'Тренировка с использованием мешков, лап, макивар'],
        ];

        foreach ($types as $type) {
            DB::table('ref_training_types')->insertOrIgnore($type);
        }
    }
};
