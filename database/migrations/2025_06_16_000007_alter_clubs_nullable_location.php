<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Делаем country_id, city_id и address nullable,
        // т.к. при регистрации эти данные не обязательны
        DB::statement('ALTER TABLE clubs ALTER COLUMN country_id DROP NOT NULL');
        DB::statement('ALTER TABLE clubs ALTER COLUMN city_id DROP NOT NULL');
        DB::statement('ALTER TABLE clubs ALTER COLUMN address DROP NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE clubs ALTER COLUMN country_id SET NOT NULL');
        DB::statement('ALTER TABLE clubs ALTER COLUMN city_id SET NOT NULL');
        DB::statement('ALTER TABLE clubs ALTER COLUMN address SET NOT NULL');
    }
};
