<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWidgetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('widgets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->integer('type');
			$table->text('content');
			$table->integer('rank');
		});

		Schema::create('widgets_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->integer('rank');
		});

		Schema::create('widgets_in_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('category_id');
        	$table->unsignedInteger('widget_id');
        	$table->integer('rank');

			$table->foreign('category_id')->references('id')->on('widgets_categories');
			$table->foreign('widget_id')->references('id')->on('widgets');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('widgets_in_categories');
		Schema::drop('widgets_categories');
		Schema::drop('widgets');
	}

}
