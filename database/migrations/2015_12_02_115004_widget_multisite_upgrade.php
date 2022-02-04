<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WidgetMultisiteUpgrade extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('widgets_categories', function($table) {
           	$table->dropForeign('widgets_categories_site_id_foreign');
           	$table->dropColumn('site_id');
           	$table->dropColumn('rank');
        });

        Schema::create('widgets_categories_sites', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('site_id');
			$table->unsignedInteger('category_id');
			$table->integer('rank');

			$table->foreign('site_id')
				  ->references('id')->on('sites')
				  ->onUpdate('cascade')
				  ->onDelete('restrict');

			$table->foreign('category_id')
				  ->references('id')->on('widgets_categories')
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
		Schema::table('widgets_categories', function($table) {
			$table->unsignedInteger('site_id');
			$table->integer('rank');

			$table->foreign('site_id')
				  ->references('id')->on('sites')
				  ->onUpdate('cascade')
				  ->onDelete('restrict');
        });

        Schema::drop('widgets_categories_sites');
	}

}
