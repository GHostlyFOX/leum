<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ref_doc_type')->comment('Вид документа');
            // Укажите внешнюю связь, если есть таблица doc_types
            // $table->foreign('ref_doc_type')->references('id')->on('doc_types');

            $table->unsignedBigInteger('user')->comment('Пользователь');
            $table->foreign('user')->references('id')->on('users');

            $table->string('organization_name', 4000)->nullable()->comment('Наименование учреждения');

            $table->date('date_begin')->comment('Дата выдачи');
            $table->date('date_end')->nullable()->comment('Дата окончания действия');

            $table->string('seria_number', 255)->nullable()->comment('Серия и номер');

            $table->unsignedBigInteger('citizenship')->nullable()->comment('Гражданство');
            $table->foreign('citizenship')->references('id')->on('ref_regions');

            $table->unsignedBigInteger('photo_docs')->nullable()->comment('Фото документа');

            $table->unsignedTinyInteger('is_allowed')->nullable()->comment('Допущен: 1 - да, 2 - частично, 3 - нет');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
