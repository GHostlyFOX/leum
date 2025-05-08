<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('Первичный ключ');

            $table->string('name', 255)->comment('Имя');
            $table->string('lastname', 255)->comment('Фамилия');
            $table->string('middlename', 255)->nullable()->comment('Отчество');

            $table->string('email', 255)->nullable()->comment('E-Mail');
            $table->string('phone', 12)->comment('Телефон');

            $table->unsignedBigInteger('photo')->nullable()->comment('Фото');
            $table->boolean('is_send_notifications')->default(true)->comment('Отправлять уведомления');
            $table->date('birthdate')->nullable()->comment('Дата рождения');

            $table->unsignedBigInteger('ref_sex')->nullable()->comment('Пол');
            $table->unsignedBigInteger('kindred_spirit')->nullable()->comment('Вид родственика');

            $table->string('password', 255)->nullable();
            $table->string('remember_token', 255)->nullable();

            $table->date('email_verified_at')->nullable();
            $table->date('phone_verified_at')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->date('deleted_at')->nullable();

            $table->foreign('ref_sex')->references('id')->on('ref_sex');

            $table->unique('email');
            $table->unique('phone');
        });

        DB::statement("COMMENT ON TABLE users IS 'Пользователи'");
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
