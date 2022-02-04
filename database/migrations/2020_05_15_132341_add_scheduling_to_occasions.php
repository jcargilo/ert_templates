<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchedulingToOccasions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('occasions', function(Blueprint $table)
		{
			$table->datetime('start_date')->nullable()->after('deleted_at');
			$table->datetime('end_date')->nullable()->after('start_date');
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
			$table->dropColumn('start_date');
			$table->dropColumn('end_date');
		});
	}
}