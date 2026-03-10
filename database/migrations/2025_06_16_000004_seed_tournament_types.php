<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Заполнение справочника типов турниров
 * 
 * Типы турниров привязаны к виду спорта
 * Только региональные и детско-юношеские соревнования
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─────────────────────────────────────────────────────────────────────
        // СПРАВОЧНИК ТИПОВ ТУРНИРОВ (только региональные и детско-юношеские)
        // ─────────────────────────────────────────────────────────────────────
        $tournamentTypes = [
            // Общие типы (подходят для большинства видов спорта)
            ['id' => 1, 'name' => 'Чемпионат', 'sport_type_id' => null],
            ['id' => 2, 'name' => 'Кубок', 'sport_type_id' => null],
            ['id' => 3, 'name' => 'Первенство', 'sport_type_id' => null],
            ['id' => 4, 'name' => 'Товарищеский турнир', 'sport_type_id' => null],
            ['id' => 5, 'name' => 'Открытый турнир', 'sport_type_id' => null],
            ['id' => 6, 'name' => 'Кубок главы региона', 'sport_type_id' => null],
            ['id' => 7, 'name' => 'Кубок города', 'sport_type_id' => null],
            ['id' => 8, 'name' => 'Кубок района', 'sport_type_id' => null],
            ['id' => 9, 'name' => 'Спартакиада', 'sport_type_id' => null],
            ['id' => 10, 'name' => 'Олимпионик', 'sport_type_id' => null],
            ['id' => 11, 'name' => 'Предварительный этап', 'sport_type_id' => null],
            ['id' => 12, 'name' => 'Финальный этап', 'sport_type_id' => null],
            ['id' => 13, 'name' => 'Зональный этап', 'sport_type_id' => null],
            
            // Региональные уровни
            ['id' => 14, 'name' => 'Первенство России', 'sport_type_id' => null],
            ['id' => 15, 'name' => 'Первенство округа', 'sport_type_id' => null],
            ['id' => 16, 'name' => 'Первенство области', 'sport_type_id' => null],
            ['id' => 17, 'name' => 'Первенство края', 'sport_type_id' => null],
            ['id' => 18, 'name' => 'Первенство республики', 'sport_type_id' => null],
            ['id' => 19, 'name' => 'Первенство города', 'sport_type_id' => null],
            ['id' => 20, 'name' => 'Первенство района', 'sport_type_id' => null],
            
            // Кубковые соревнования
            ['id' => 21, 'name' => 'Кубок России', 'sport_type_id' => null],
            ['id' => 22, 'name' => 'Кубок округа', 'sport_type_id' => null],
            ['id' => 23, 'name' => 'Кубок области', 'sport_type_id' => null],
            ['id' => 24, 'name' => 'Кубок края', 'sport_type_id' => null],
            ['id' => 25, 'name' => 'Кубок республики', 'sport_type_id' => null],
            
            // Всероссийские
            ['id' => 26, 'name' => 'Всероссийские соревнования', 'sport_type_id' => null],
            ['id' => 27, 'name' => 'Всероссийская Спартакиада', 'sport_type_id' => null],
            
            // Возрастные категории
            ['id' => 28, 'name' => 'Юношеский турнир (до 21 года)', 'sport_type_id' => null],
            ['id' => 29, 'name' => 'Юниорский турнир (до 19 лет)', 'sport_type_id' => null],
            ['id' => 30, 'name' => 'Кадетский турнир (до 17 лет)', 'sport_type_id' => null],
            ['id' => 31, 'name' => 'Детский турнир (до 15 лет)', 'sport_type_id' => null],
            ['id' => 32, 'name' => 'Младший возраст (до 13 лет)', 'sport_type_id' => null],
            ['id' => 33, 'name' => 'Старшая группа (2007-2008 г.р.)', 'sport_type_id' => null],
            ['id' => 34, 'name' => 'Средняя группа (2009-2010 г.р.)', 'sport_type_id' => null],
            ['id' => 35, 'name' => 'Младшая группа (2011-2012 г.р.)', 'sport_type_id' => null],
            ['id' => 36, 'name' => 'Самые младшие (2013 г.р. и младше)', 'sport_type_id' => null],
            
            // Сезонные
            ['id' => 37, 'name' => 'Летний кубок', 'sport_type_id' => null],
            ['id' => 38, 'name' => 'Зимний кубок', 'sport_type_id' => null],
            ['id' => 39, 'name' => 'Весенний чемпионат', 'sport_type_id' => null],
            ['id' => 40, 'name' => 'Осенний чемпионат', 'sport_type_id' => null],
            ['id' => 41, 'name' => 'Новогодний турнир', 'sport_type_id' => null],
            
            // Межрегиональные
            ['id' => 42, 'name' => 'Межрегиональный турнир', 'sport_type_id' => null],
            ['id' => 43, 'name' => 'Межобластные соревнования', 'sport_type_id' => null],
            
            // Школьные и студенческие
            ['id' => 44, 'name' => 'Школьная лига', 'sport_type_id' => null],
            ['id' => 45, 'name' => 'Универсиада', 'sport_type_id' => null],
            ['id' => 46, 'name' => 'Студенческие игры', 'sport_type_id' => null],
            
            // Корпоративные
            ['id' => 47, 'name' => 'Корпоративный турнир', 'sport_type_id' => null],
            ['id' => 48, 'name' => 'Турнир среди работодателей', 'sport_type_id' => null],
        ];

        foreach ($tournamentTypes as $type) {
            DB::table('ref_tournament_types')->insertOrIgnore($type);
        }
    }

    public function down(): void
    {
        DB::table('ref_tournament_types')->delete();
    }
};
