<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('parent_id')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('title');
			$table->string('plugin');
			$table->string('slug');
			$table->integer('rank');
			$table->boolean('separator');
			$table->boolean('enabled')->default(1);

			$table->foreign('parent_id')
				  ->references('id')->on('menu')
				  ->onUpdate('cascade')
				  ->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menu', function($table)
		{
			$table->dropForeign('menu_parent_id_foreign');
		});

		Schema::drop('menu');
	}

}
