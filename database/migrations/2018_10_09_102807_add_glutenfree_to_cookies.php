<?php

use Illuminate\Database\Migrations\Migration;

class AddGlutenfreeToCookies extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cookies', function($table) {
			$table->boolean('gluten_free')->after('featured');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cookies', function($table) {
		   	$table->dropColumn('gluten_free');
		});
	}
}