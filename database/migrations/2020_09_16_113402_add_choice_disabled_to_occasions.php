<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChoiceDisabledToOccasions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('occasions', function($table) {
			$table->boolean('choice_disabled');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('occasions', function($table) {
			$table->dropColumn('choice_disabled');
		});
	}

}
