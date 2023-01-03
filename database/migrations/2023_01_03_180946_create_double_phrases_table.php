<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Создание таблицы double_phrases
     *
     * @return void
     */
    public function up()
    {
        Schema::create('double_phrases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('active');
            $table->string('name');
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('double_phrases');
    }
};
