<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Создание таблици phrases
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('phrases', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->string('name');
			$table->boolean('active');
			$table->integer('category');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('phrases');
	}
};
