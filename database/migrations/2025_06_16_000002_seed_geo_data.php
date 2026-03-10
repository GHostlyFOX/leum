<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Заполнение географических справочников
 *
 * Справочники: страны, города (Россия и СНГ)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─────────────────────────────────────────────────────────────────────
        // 1. СПРАВОЧНИК СТРАН
        // ─────────────────────────────────────────────────────────────────────
        $countries = [
            ['id' => 1, 'name' => 'Россия'],
            ['id' => 2, 'name' => 'Азербайджан'],
            ['id' => 3, 'name' => 'Армения'],
            ['id' => 4, 'name' => 'Беларусь'],
            ['id' => 5, 'name' => 'Казахстан'],
            ['id' => 6, 'name' => 'Кыргызстан'],
            ['id' => 7, 'name' => 'Молдова'],
            ['id' => 8, 'name' => 'Таджикистан'],
            ['id' => 9, 'name' => 'Туркменистан'],
            ['id' => 10, 'name' => 'Узбекистан'],
            ['id' => 11, 'name' => 'Украина'],
            ['id' => 12, 'name' => 'Абхазия'],
            ['id' => 13, 'name' => 'Южная Осетия'],
            ['id' => 14, 'name' => 'Латвия'],
            ['id' => 15, 'name' => 'Литва'],
            ['id' => 16, 'name' => 'Эстония'],
            ['id' => 17, 'name' => 'Грузия'],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->insertOrIgnore($country);
        }

        // ─────────────────────────────────────────────────────────────────────
        // 2. СПРАВОЧНИК ГОРОДОВ (Россия - country_id = 1)
        // ─────────────────────────────────────────────────────────────────────
        $russianCities = [
            // Москва и МО
            ['id' => 1, 'name' => 'Москва', 'country_id' => 1],
            ['id' => 2, 'name' => 'Химки', 'country_id' => 1],
            ['id' => 3, 'name' => 'Красногорск', 'country_id' => 1],
            ['id' => 4, 'name' => 'Одинцово', 'country_id' => 1],
            ['id' => 5, 'name' => 'Подольск', 'country_id' => 1],
            ['id' => 6, 'name' => 'Люберцы', 'country_id' => 1],
            ['id' => 7, 'name' => 'Мытищи', 'country_id' => 1],
            ['id' => 8, 'name' => 'Балашиха', 'country_id' => 1],
            ['id' => 9, 'name' => 'Королёв', 'country_id' => 1],
            ['id' => 10, 'name' => 'Химки', 'country_id' => 1],
            
            // Санкт-Петербург и ЛО
            ['id' => 11, 'name' => 'Санкт-Петербург', 'country_id' => 1],
            ['id' => 12, 'name' => 'Гатчина', 'country_id' => 1],
            ['id' => 13, 'name' => 'Всеволожск', 'country_id' => 1],
            ['id' => 14, 'name' => 'Сосновый Бор', 'country_id' => 1],
            ['id' => 15, 'name' => 'Пушкин', 'country_id' => 1],
            ['id' => 16, 'name' => 'Кронштадт', 'country_id' => 1],
            
            // Крупные города
            ['id' => 17, 'name' => 'Новосибирск', 'country_id' => 1],
            ['id' => 18, 'name' => 'Екатеринбург', 'country_id' => 1],
            ['id' => 19, 'name' => 'Казань', 'country_id' => 1],
            ['id' => 20, 'name' => 'Нижний Новгород', 'country_id' => 1],
            ['id' => 21, 'name' => 'Челябинск', 'country_id' => 1],
            ['id' => 22, 'name' => 'Самара', 'country_id' => 1],
            ['id' => 23, 'name' => 'Омск', 'country_id' => 1],
            ['id' => 24, 'name' => 'Ростов-на-Дону', 'country_id' => 1],
            ['id' => 25, 'name' => 'Уфа', 'country_id' => 1],
            ['id' => 26, 'name' => 'Красноярск', 'country_id' => 1],
            ['id' => 27, 'name' => 'Пермь', 'country_id' => 1],
            ['id' => 28, 'name' => 'Воронеж', 'country_id' => 1],
            ['id' => 29, 'name' => 'Волгоград', 'country_id' => 1],
            ['id' => 30, 'name' => 'Краснодар', 'country_id' => 1],
            
            // Дополнительные города-миллионники и крупные центры
            ['id' => 31, 'name' => 'Саратов', 'country_id' => 1],
            ['id' => 32, 'name' => 'Тюмень', 'country_id' => 1],
            ['id' => 33, 'name' => 'Тольятти', 'country_id' => 1],
            ['id' => 34, 'name' => 'Ижевск', 'country_id' => 1],
            ['id' => 35, 'name' => 'Барнаул', 'country_id' => 1],
            ['id' => 36, 'name' => 'Ульяновск', 'country_id' => 1],
            ['id' => 37, 'name' => 'Иркутск', 'country_id' => 1],
            ['id' => 38, 'name' => 'Хабаровск', 'country_id' => 1],
            ['id' => 39, 'name' => 'Ярославль', 'country_id' => 1],
            ['id' => 40, 'name' => 'Владивосток', 'country_id' => 1],
            ['id' => 41, 'name' => 'Махачкала', 'country_id' => 1],
            ['id' => 42, 'name' => 'Томск', 'country_id' => 1],
            ['id' => 43, 'name' => 'Оренбург', 'country_id' => 1],
            ['id' => 44, 'name' => 'Кемерово', 'country_id' => 1],
            ['id' => 45, 'name' => 'Новокузнецк', 'country_id' => 1],
            ['id' => 46, 'name' => 'Рязань', 'country_id' => 1],
            ['id' => 47, 'name' => 'Астрахань', 'country_id' => 1],
            ['id' => 48, 'name' => 'Набережные Челны', 'country_id' => 1],
            ['id' => 49, 'name' => 'Пенза', 'country_id' => 1],
            ['id' => 50, 'name' => 'Липецк', 'country_id' => 1],
            ['id' => 51, 'name' => 'Киров', 'country_id' => 1],
            ['id' => 52, 'name' => 'Чебоксары', 'country_id' => 1],
            ['id' => 53, 'name' => 'Тула', 'country_id' => 1],
            ['id' => 54, 'name' => 'Калининград', 'country_id' => 1],
            ['id' => 55, 'name' => 'Балашиха', 'country_id' => 1],
            ['id' => 56, 'name' => 'Курск', 'country_id' => 1],
            ['id' => 57, 'name' => 'Ставрополь', 'country_id' => 1],
            ['id' => 58, 'name' => 'Сочи', 'country_id' => 1],
            ['id' => 59, 'name' => 'Улан-Удэ', 'country_id' => 1],
            ['id' => 60, 'name' => 'Тверь', 'country_id' => 1],
            
            // Города СНГ
            ['id' => 61, 'name' => 'Минск', 'country_id' => 4],
            ['id' => 62, 'name' => 'Гомель', 'country_id' => 4],
            ['id' => 63, 'name' => 'Могилёв', 'country_id' => 4],
            ['id' => 64, 'name' => 'Витебск', 'country_id' => 4],
            ['id' => 65, 'name' => 'Гродно', 'country_id' => 4],
            ['id' => 66, 'name' => 'Брест', 'country_id' => 4],
            
            ['id' => 67, 'name' => 'Алматы', 'country_id' => 5],
            ['id' => 68, 'name' => 'Астана', 'country_id' => 5],
            ['id' => 69, 'name' => 'Шымкент', 'country_id' => 5],
            ['id' => 70, 'name' => 'Караганда', 'country_id' => 5],
            
            ['id' => 71, 'name' => 'Киев', 'country_id' => 11],
            ['id' => 72, 'name' => 'Харьков', 'country_id' => 11],
            ['id' => 73, 'name' => 'Одесса', 'country_id' => 11],
            ['id' => 74, 'name' => 'Днепр', 'country_id' => 11],
            
            ['id' => 75, 'name' => 'Ташкент', 'country_id' => 10],
            ['id' => 76, 'name' => 'Самарканд', 'country_id' => 10],
            
            ['id' => 77, 'name' => 'Баку', 'country_id' => 2],
            ['id' => 78, 'name' => 'Гянджа', 'country_id' => 2],
            
            ['id' => 79, 'name' => 'Ереван', 'country_id' => 3],
            ['id' => 80, 'name' => 'Гюмри', 'country_id' => 3],
            
            ['id' => 81, 'name' => 'Бишкек', 'country_id' => 6],
            ['id' => 82, 'name' => 'Ош', 'country_id' => 6],
            
            ['id' => 83, 'name' => 'Душанбе', 'country_id' => 8],
            ['id' => 84, 'name' => 'Худжанд', 'country_id' => 8],
            
            ['id' => 85, 'name' => 'Кишинёв', 'country_id' => 7],
            
            ['id' => 86, 'name' => 'Ашхабад', 'country_id' => 9],
            
            ['id' => 87, 'name' => 'Тбилиси', 'country_id' => 17],
            ['id' => 88, 'name' => 'Батуми', 'country_id' => 17],
            
            ['id' => 89, 'name' => 'Рига', 'country_id' => 14],
            ['id' => 90, 'name' => 'Вильнюс', 'country_id' => 15],
            ['id' => 91, 'name' => 'Таллин', 'country_id' => 16],
            ['id' => 92, 'name' => 'Сухум', 'country_id' => 12],
            ['id' => 93, 'name' => 'Цхинвал', 'country_id' => 13],
        ];

        foreach ($russianCities as $city) {
            DB::table('cities')->insertOrIgnore($city);
        }
    }

    public function down(): void
    {
        // Очистка таблиц в обратном порядке
        DB::table('cities')->delete();
        DB::table('countries')->delete();
    }
};
