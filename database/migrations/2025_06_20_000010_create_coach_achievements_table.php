<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coach_achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_profile_id');
            $table->foreign('coach_profile_id')
                ->references('id')
                ->on('coach_profiles')
                ->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->year('year');
            $table->string('category', 100)->nullable(); // championship, cup, personal, etc.
            $table->timestamps();
            
            $table->index('coach_profile_id');
            $table->index('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coach_achievements');
    }
};
