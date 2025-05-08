<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id()->comment('Первичный ключ');
            $table->string('name', 255)->comment('Наименование');
            $table->text('description')->nullable()->comment('Описание');
            $table->unsignedBigInteger('ref_sex')->comment('Пол');
            $table->unsignedBigInteger('logo')->nullable()->comment('Логотип');
            $table->integer('kids_year')->comment('Год учащихся');
            $table->unsignedBigInteger('club')->comment('Клуб');
            $table->unsignedBigInteger('ref_type_sport')->comment('Вид спорта');
            $table->unsignedBigInteger('country')->comment('Страна');
            $table->unsignedBigInteger('sity')->comment('Город');

            $table->foreign('ref_sex')->references('id')->on('ref_sex');
            $table->foreign('club')->references('id')->on('club');
            $table->foreign('ref_type_sport')->references('id')->on('ref_type_sport');
            $table->foreign('country')->references('id')->on('ref_regions');
            $table->foreign('sity')->references('id')->on('ref_regions');

            $table->index(['name', 'club', 'ref_type_sport', 'ref_sex'], 'teams_name_club_ref_type_sport_ref_sex_index');
        });

        DB::statement("COMMENT ON TABLE teams IS 'Команды'");
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
