<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE events MODIFY COLUMN end_date datetime null');
			$table->string('image')->after('location');
			$table->string('url')->after('image');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('events', function(Blueprint $table)
		{
			$table->dropColumn('url');
			$table->dropColumn('image');
			DB::statement('ALTER TABLE events MODIFY COLUMN end_date datetime');
		});
	}

}
