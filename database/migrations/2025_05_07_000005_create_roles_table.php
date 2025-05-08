<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id()->comment('Первичный ключ');
            $table->unsignedBigInteger('user')->comment('Пользователь');
            $table->unsignedBigInteger('ref_roles')->comment('Роль');
            $table->unsignedBigInteger('club')->comment('Клуб');
            $table->unsignedBigInteger('teams')->comment('Команда');

            $table->foreign('user')->references('id')->on('users');
            $table->foreign('ref_roles')->references('id')->on('ref_roles');
            $table->foreign('club')->references('id')->on('club');
            $table->foreign('teams')->references('id')->on('teams');

            $table->index(['user', 'ref_roles', 'club', 'teams'], 'roles_user_ref_roles_club_teams_index');
        });

        DB::statement("COMMENT ON TABLE roles IS 'Роли'");
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
