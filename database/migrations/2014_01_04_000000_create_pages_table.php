<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('author_id');
			$table->integer('parent_category_id');
			$table->string('title');
			$table->string('slug');
			$table->boolean('display_in_menu');
			$table->boolean('redirect');
			$table->integer('redirect_page_id')->nullable();
			$table->string('redirect_page_other');
			$table->boolean('redirect_new_window');
			$table->string('seo_title', 255);
			$table->string('seo_keywords', 500);
			$table->string('seo_description', 1000);
			$table->integer('rank');
			$table->string('published', 20);
			$table->string('password', 255);

			$table->foreign('author_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
		});

		Schema::create('pages_sections', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('page_id');
			$table->unsignedInteger('author_id');
			$table->string('layout');
			$table->string('title');
			$table->text('spacing_above');
			$table->text('spacing_below');
			$table->string('vertical_alignment');
			$table->boolean('full_width');
			$table->boolean('same_height');
			$table->string('border_color');
			$table->string('border_style');
			$table->string('padding_top', 50);
			$table->string('padding_right', 50);
			$table->string('padding_bottom', 50);
			$table->string('padding_left', 50);
			$table->string('border_top_width', 50);
			$table->string('border_right_width', 50);
			$table->string('border_bottom_width', 50);
			$table->string('border_left_width', 50);
			$table->string('section_background_color');
			$table->string('page_background_color');
			$table->boolean('background_fixed');
			$table->boolean('background_parallax');
			$table->boolean('background_stretch');
			$table->integer('background_repeat_x');
			$table->integer('background_repeat_y');
			$table->string('background_size');
			$table->integer('background_position_x');
			$table->integer('background_position_y');
			$table->string('background_image');
			$table->boolean('overlay');
			$table->string('overlay_class', 255);
			$table->string('overlay_position', 6);
			$table->integer('overlay_distance');
			$table->integer('rank');

			$table->foreign('page_id')
				  ->references('id')->on('pages')
				  ->onUpdate('cascade')
				  ->onDelete('cascade');

			$table->foreign('author_id')
				  ->references('id')->on('users')
				  ->onUpdate('cascade')
				  ->onDelete('restrict');
		});

		Schema::create('pages_sections_columns', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('section_id');
			$table->unsignedInteger('author_id');
			$table->integer('column');
			$table->string('layout')->default('');
			$table->text('content');
			$table->unsignedInteger('slideshow_id')->nullable();
			$table->unsignedInteger('template_id')->nullable();
			
			$table->foreign('section_id')
				  ->references('id')->on('pages_sections')
				  ->onUpdate('cascade')
				  ->onDelete('cascade');

			$table->foreign('author_id')
				  ->references('id')->on('users')
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
		Schema::drop('pages_sections_columns');
		Schema::drop('pages_sections');
		Schema::drop('pages');
	}

}
