<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ref_roles', function (Blueprint $table) {
            $table->id()->comment('Первичный ключ');
            $table->string('name', 255)->comment('Наименование');
            $table->unique('name');
        });

        DB::statement("COMMENT ON TABLE ref_roles IS 'Справочник: Роли'");
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_roles');
    }
};