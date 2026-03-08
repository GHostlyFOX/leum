<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Добавляет колонку onboarded_at в таблицу users.
 *
 * Если NULL — пользователь ещё не прошёл онбординг и будет
 * автоматически перенаправлен на /onboarding.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('onboarded_at')->nullable()->after('global_role');
        });

        // Все пользователи (включая существующих) должны пройти онбординг.
        // onboarded_at остаётся NULL — middleware перенаправит на /onboarding.
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('onboarded_at');
        });
    }
};
