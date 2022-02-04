<?php

use Illuminate\Database\Migrations\Migration;

class AddAltTextToImages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE `cookies` CHANGE COLUMN `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;');
		DB::statement('ALTER TABLE `products` CHANGE COLUMN `created_at` `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;');

		Schema::table('cookies', function($table) {
			$table->string('alt_text')->after('image');
        });

        Schema::table('products', function($table) {
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
		Schema::table('cookies', function($table) {
           	$table->dropColumn('thumb_alt_text');
           	$table->dropColumn('image_alt_text');
        });

        Schema::table('products', function($table) {
           	$table->dropColumn('alt_text');
        });
	}

}