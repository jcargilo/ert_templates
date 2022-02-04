<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScrollNextToPages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add scroll next field to pages_sections table
		Schema::table('pages_sections', function($table) {
			$table->boolean('scroll_next')->after('same_height');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pages_sections', function($table) {
           	$table->dropColumn('scroll_next');
        });
	}

}
