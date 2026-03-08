<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Таблица refresh-токенов.
 *
 * Access-токен (Sanctum) живёт 60 минут.
 * Refresh-токен живёт 30 дней и позволяет получить новую пару access+refresh
 * без повторного ввода пароля.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refresh_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('created_at')->useCurrent();
        });

        DB::statement("COMMENT ON TABLE refresh_tokens IS 'Refresh-токены для обновления access-токенов Sanctum'");
    }

    public function down(): void
    {
        Schema::dropIfExists('refresh_tokens');
    }
};
