<?php

use Illuminate\Database\Migrations\Migration;

class AddLimitedToOccasions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('occasions', function($table) {
			$table->boolean('limited')->after('seasonal');
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
		   	$table->dropColumn('limited');
		});
	}
}