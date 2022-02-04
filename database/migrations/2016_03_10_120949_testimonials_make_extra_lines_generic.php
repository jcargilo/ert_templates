<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TestimonialsMakeExtraLinesGeneric extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('testimonials', function($table)
		{
			$table->renameColumn('title', 'line2');
			$table->renameColumn('company', 'line3');
			$table->dropColumn(['details']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('testimonials', function($table)
		{
			$table->string('details')->after('line3');
			$table->renameColumn('line2', 'title');
			$table->renameColumn('line3', 'company');
		});
	}

}
