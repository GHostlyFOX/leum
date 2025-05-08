<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coach_profiles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user')->comment('Пользователь');
            $table->foreign('user')->references('id')->on('users');

            $table->unsignedBigInteger('ref_position')->comment('Позиция');
            $table->foreign('ref_position')->references('id')->on('ref_position');

            $table->date('date_begin')->nullable()->comment('Дата начала карьеры');
            $table->string('license', 255)->nullable()->comment('Лицензия');
            $table->date('date_end_license')->nullable()->comment('Дата окончания лицензии');
            $table->json('awards')->nullable()->comment('Награды');

            $table->unsignedBigInteger('ref_type_sport')->comment('Вид спорта');
            $table->foreign('ref_type_sport')->references('id')->on('ref_type_sport');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coach_profiles');
    }
};
