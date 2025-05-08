<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('composition_teams', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user')->comment('Пользователь');
            $table->foreign('user')->references('id')->on('users');

            $table->unsignedBigInteger('club')->comment('Клуб');
            $table->foreign('club')->references('id')->on('club');

            $table->unsignedBigInteger('teams')->comment('Команда');
            $table->foreign('teams')->references('id')->on('teams');

            $table->timestamps();

            $table->index(['user', 'teams', 'club'], 'composition_team_user_teams_club_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composition_teams');
    }
};
