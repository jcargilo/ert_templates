<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAssortmentToJson extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('occasions', function($table) {
			$table->dropColumn('assortment');
		});
		
		Schema::table('occasions', function($table) {
			DB::statement('ALTER TABLE `occasions` ADD `assortment` json AFTER `limited`');
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
			$table->dropColumn('assortment');
		});
		
		Schema::table('occasions', function($table) {
			DB::statement('ALTER TABLE `occasions` ADD `assortment` varchar(255) AFTER `limited`');
        });
	}

}
