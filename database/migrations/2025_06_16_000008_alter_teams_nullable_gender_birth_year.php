<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Делаем gender и birth_year nullable,
        // т.к. при регистрации эти данные не обязательны
        DB::statement('ALTER TABLE teams ALTER COLUMN gender DROP NOT NULL');
        DB::statement('ALTER TABLE teams ALTER COLUMN birth_year DROP NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE teams ALTER COLUMN gender SET NOT NULL');
        DB::statement('ALTER TABLE teams ALTER COLUMN birth_year SET NOT NULL');
    }
};
