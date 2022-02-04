<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('templates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('title');
			$table->string('url', 100);
			$table->integer('rank');
			$table->boolean('published');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('templates');
	}

}
