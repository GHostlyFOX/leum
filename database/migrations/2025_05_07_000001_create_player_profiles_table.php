<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('player_profiles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user')->comment('Пользователь');
            $table->foreign('user')->references('id')->on('users');

            $table->unsignedTinyInteger('leg')->default(0)->comment('Нога: 1 - Левая, 2 - Правая, 3 - Обе');

            $table->unsignedBigInteger('ref_position')->comment('Позиция');
            $table->foreign('ref_position')->references('id')->on('ref_position');

            $table->unsignedBigInteger('ref_type_sport')->comment('Вид спорта');
            $table->foreign('ref_type_sport')->references('id')->on('ref_type_sport');

            $table->string('address', 4000)->nullable()->comment('Адрес');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_profiles');
    }
};
