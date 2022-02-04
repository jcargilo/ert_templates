<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlideshowsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('slideshows', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('author_id');
			$table->string('title');
			$table->integer('width');
			$table->integer('margin')->nullable();
			$table->integer('pause')->nullable();
			$table->integer('speed')->nullable();
			$table->boolean('auto');
			$table->boolean('loop');
			$table->integer('min_slides');
			$table->integer('max_slides');
			$table->integer('move_slides');
			$table->boolean('show_captions');
			$table->boolean('show_controls');
			$table->boolean('show_pager');
			$table->boolean('published');
		});

		Schema::create('slideshows_slides', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('slideshow_id');
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('author_id');
			$table->string('title');
			$table->text('caption');
			$table->string('link');
			$table->string('image');
			$table->string('overlay', 255);
			$table->integer('rank');
			$table->boolean('published');

			$table->foreign('slideshow_id')
				  ->references('id')->on('slideshows')
				  ->onUpdate('cascade')
				  ->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('slideshows_slides');
		Schema::drop('slideshows');
	}

}
