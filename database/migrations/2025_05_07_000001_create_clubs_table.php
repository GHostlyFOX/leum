<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique()->comment('Наименование');
            $table->unsignedBigInteger('logo')->nullable()->comment('Логотип');
            $table->text('description')->nullable()->comment('Описание');

            $table->unsignedBigInteger('ref_type_sport')->comment('Вид спорта');
            $table->foreign('ref_type_sport')->references('id')->on('ref_type_sport');

            $table->unsignedBigInteger('coutry')->comment('Страна');
            $table->foreign('coutry')->references('id')->on('ref_regions');

            $table->unsignedBigInteger('sity')->comment('Город');
            $table->foreign('sity')->references('id')->on('ref_regions');

            $table->string('address', 4000)->comment('Адрес');
            $table->string('email', 255)->nullable()->unique()->comment('E-Mail');
            $table->json('phones')->comment('Телефоны');

            $table->unsignedBigInteger('ref_type_club')->comment('Вид клуба');
            $table->foreign('ref_type_club')->references('id')->on('ref_type_club');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
