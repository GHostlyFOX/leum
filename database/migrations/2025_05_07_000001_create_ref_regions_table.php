<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ref_regions', function (Blueprint $table) {
            $table->id()->comment('Первичный ключ');
            $table->string('name', 255)->comment('Наименование');
            $table->integer('type')->default(0)->comment('Вид региона: 1 - Страна, 2 - Город');
            $table->unique(['name', 'type']);
        });

        // Комментарий к таблице (Laravel не поддерживает напрямую, используем raw)
        DB::statement("COMMENT ON TABLE ref_regions IS 'Справочник: Регионы'");
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_regions');
    }
};
