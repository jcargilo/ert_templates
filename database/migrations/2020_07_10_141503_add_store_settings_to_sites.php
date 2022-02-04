<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoreSettingsToSites extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sites', function($table) {
			$table->boolean('choice_disabled');
			$table->datetime('choice_disabled_start_at')->nullable();
			$table->datetime('choice_disabled_end_at')->nullable();
			$table->text('choice_disabled_message');
			$table->boolean('tins_disabled');
			$table->datetime('tins_disabled_start_at')->nullable();
			$table->datetime('tins_disabled_end_at')->nullable();
			$table->boolean('ribbons_disabled');
			$table->datetime('ribbons_disabled_start_at')->nullable();
			$table->datetime('ribbons_disabled_end_at')->nullable();
			$table->boolean('gluten_free_disabled');
			$table->datetime('gluten_free_disabled_start_at')->nullable();
			$table->datetime('gluten_free_disabled_end_at')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('settings', function($table) {
		   	$table->dropColumn([
				'choice_disabled',
				'choice_disabled_start_at',
				'choice_disabled_end_at',
				'choice_disabled_message',
				'tins_disabled',
				'tins_disabled_start_at',
				'tins_disabled_end_at',
				'ribbons_disabled',
				'ribbons_disabled_start_at',
				'ribbons_disabled_end_at',
				'gluten_free_disabled',
				'gluten_free_disabled_start_at',
				'gluten_free_disabled_end_at',
			]);
		});
	}
}