<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('site_title');
			$table->string('email', 1000)->default('jeremy@takeoffdesigngroup.com');;
			$table->string('facebook', 50);			
			$table->string('twitter', 50);
			$table->string('googleplus', 50);
			$table->string('instagram', 50);
			$table->string('instagram_feed', 50);
			$table->string('flickr', 50);
			$table->string('pinterest', 50);
			$table->string('linkedin', 50);
			$table->string('youtube', 50);
			$table->text('scripts');
			$table->string('logo');
			$table->string('seo_title', 255);
			$table->string('seo_keywords', 500);
			$table->string('seo_description', 1000);
			$table->boolean('show_coming_soon');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
