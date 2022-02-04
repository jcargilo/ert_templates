<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('author_id');
			$table->string('title');
			$table->string('slug');
			$table->boolean('published');

			$table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('posts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('category_id');
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('author_id');
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('title');
			$table->string('slug');
			$table->string('excerpt', 750);
			$table->text('body');
			$table->string('image');
			$table->string('seo_title', 255);
			$table->string('seo_keywords', 500);
			$table->string('seo_description', 1000);
			$table->boolean('featured');
			$table->integer('views');
			$table->boolean('published');

			$table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
			$table->foreign('category_id')->references('id')->on('posts_categories')->onUpdate('cascade')->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
		Schema::drop('posts_categories');
	}

}
