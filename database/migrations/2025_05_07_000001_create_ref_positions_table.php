<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ref_position', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255)->comment('Наименование');

            $table->unsignedBigInteger('ref_type_sport')->comment('Вид спорта');
            $table->foreign('ref_type_sport')->references('id')->on('ref_type_sport');

            $table->unsignedBigInteger('club')->nullable()->comment('Клуб');
            // Если есть таблица `club`, можно раскомментировать строку ниже:
            // $table->foreign('club')->references('id')->on('club');

            $table->timestamps();

            $table->unique(['name', 'ref_type_sport', 'club'], 'ref_position_name_ref_type_sport_club_uindex');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_position');
    }
};
