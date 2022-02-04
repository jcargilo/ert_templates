<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoGalleryTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('galleries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('author_id');
			$table->string('title');
			$table->boolean('show_albums');
			$table->boolean('published');

			$table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('galleries_albums', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('gallery_id');
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('author_id');
			$table->string('title');
			$table->string('image');
			$table->text('comments');
			$table->string('slug');
			$table->integer('rank');
			$table->boolean('published');

			$table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
			$table->foreign('gallery_id')->references('id')->on('galleries')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('galleries_photos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('gallery_id')->nullable();
			$table->unsignedInteger('album_id')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('author_id');
			$table->string('title');
			$table->text('caption');
			$table->string('thumb');
			$table->string('image');
			$table->integer('rank');
			$table->boolean('published');

			$table->foreign('gallery_id')->references('id')->on('galleries')->onUpdate('cascade')->onDelete('restrict');
			$table->foreign('album_id')->references('id')->on('galleries_albums')->onUpdate('cascade')->onDelete('restrict');
			$table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('galleries_photos');
		Schema::drop('galleries_albums');
		Schema::drop('galleries');
	}

}
