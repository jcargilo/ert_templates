<?php

use Illuminate\Database\Migrations\Migration;

class AddCookiesOccasion extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('occasions')->insert([
			'created_at' => \Carbon\Carbon::now(),
			'updated_at' => \Carbon\Carbon::now(),
			'deleted_at' => NULL,
			'author' => 'support@gmediastudios.com',
			'title' => 'Cookies',
			'description' => '',
			'special_message' => '',
			'slug' => 'cookies',
			'featured' => 0,
			'seo_title' => '',
			'seo_keywords' => '',
			'seo_description' => '',
			'rank' => 100,
			'seasonal' => 0,
			'assortment' => '',
			'published' => 1
		]);

		$occasion_id = DB::table('occasions')->orderBy('id', 'desc')->first()->id;
		DB::table('products_in_occasions')->insert(['occasion_id' => $occasion_id, 'product_id' => 154, 'rank' => 1]);
		DB::table('products_in_occasions')->insert(['occasion_id' => $occasion_id, 'product_id' => 153, 'rank' => 2]);
		DB::table('products_in_occasions')->insert(['occasion_id' => $occasion_id, 'product_id' => 102, 'rank' => 3]);
		DB::table('products_in_occasions')->insert(['occasion_id' => $occasion_id, 'product_id' => 98, 'rank' => 4]);
		DB::table('products_in_occasions')->insert(['occasion_id' => $occasion_id, 'product_id' => 72, 'rank' => 5]);
		DB::table('products_in_occasions')->insert(['occasion_id' => $occasion_id, 'product_id' => 71, 'rank' => 6]);
		DB::table('products_in_occasions')->insert(['occasion_id' => $occasion_id, 'product_id' => 21, 'rank' => 7]);
		DB::table('products_in_occasions')->insert(['occasion_id' => $occasion_id, 'product_id' => 18, 'rank' => 8]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$occasion_id = DB::table('occasions')->orderBy('id', 'desc')->first()->id;
		DB::table('products_in_occasions')->where('occasion_id', '=', $occasion_id)->delete();
		DB::table('occasions')->where('id', '=', $occasion_id)->delete();
	}

}