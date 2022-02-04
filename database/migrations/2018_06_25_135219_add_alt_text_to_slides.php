<?php

use Illuminate\Database\Migrations\Migration;

class AddAltTextToSlides extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE `slideshows_slides` CHANGE COLUMN `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;');

		Schema::table('slideshows_slides', function($table) {
			$table->string('alt_text')->after('image');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('slideshows_slides', function($table) {
		   	$table->dropColumn('alt_text');
		});
	}

}