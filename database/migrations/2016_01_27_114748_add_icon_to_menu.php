<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIconToMenu extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add icon field to menu table
		Schema::table('menu', function($table) {
			$table->string('icon')->after('title');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menu', function($table) {
           	$table->dropColumn('icon');
        });
	}

}
