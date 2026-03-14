<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Добавляет колонку club_id в таблицы tournaments и opponents.
 *
 * Турниры ранее связывались с клубами только через tournament_teams,
 * но для удобства фильтрации (например, в MatchCreate) добавляем
 * прямую связь club_id — клуб-создатель турнира / соперника.
 */
return new class extends Migration
{
    public function up(): void
    {
        // tournaments: добавляем club_id (nullable, т.к. могут быть общие турниры)
        Schema::table('tournaments', function (Blueprint $table) {
            $table->unsignedBigInteger('club_id')->nullable()->after('name');
            $table->foreign('club_id')->references('id')->on('clubs')->nullOnDelete();
            $table->index('club_id', 'idx_tournaments_club');
        });

        // opponents: добавляем club_id (nullable, соперник может быть общим)
        Schema::table('opponents', function (Blueprint $table) {
            $table->unsignedBigInteger('club_id')->nullable()->after('name');
            $table->foreign('club_id')->references('id')->on('clubs')->nullOnDelete();
            $table->index('club_id', 'idx_opponents_club');
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropForeign(['club_id']);
            $table->dropIndex('idx_tournaments_club');
            $table->dropColumn('club_id');
        });

        Schema::table('opponents', function (Blueprint $table) {
            $table->dropForeign(['club_id']);
            $table->dropIndex('idx_opponents_club');
            $table->dropColumn('club_id');
        });
    }
};
