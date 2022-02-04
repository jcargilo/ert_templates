<?php

use Illuminate\Database\Migrations\Migration;

class AddAltTextToSlideOverlayImages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('slideshows_slides', function($table) {
			$table->string('overlay_alt_text')->after('overlay');
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
		   	$table->dropColumn('overlay_alt_text');
		});
	}
}