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

        // Существующие пользователи считаются уже прошедшими онбординг
        DB::table('users')->whereNull('onboarded_at')->update([
            'onboarded_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('onboarded_at');
        });
    }
};
