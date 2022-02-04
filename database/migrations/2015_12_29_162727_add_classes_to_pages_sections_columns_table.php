<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassesToPagesSectionsColumnsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add scroll next field to pages_sections table
		Schema::table('pages_sections_columns', function($table) {
			$table->string('classes')->after('content');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pages_sections_columns', function($table) {
           	$table->dropColumn('classes');
        });
	}

}
