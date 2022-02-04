<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MultisiteIntegration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::rename('settings', 'sites');

		Schema::table('sites', function($table)
		{
			$table->timestamp('deleted_at')->nullable()->after('updated_at');
			$table->text('meta_tags')->after('youtube');
			$table->string('favicon')->after('scripts');
			$table->string('domain')->after('site_title');
		    $table->renameColumn('site_title', 'title');
		});

		// Modify existing row.
		DB::table('menu')
			->where('plugin', 'settings')
			->update([
				'title' => 'Sites',
				'plugin' => 'sites',
				'slug' => 'sites'
			]);

		// Remove "/admin" from slug
		DB::table('menu')
			->update(['slug' => DB::raw('replace(slug, \'admin/\', \'\')')]);

		Schema::table('pages', function($table)
		{
			$table->unsignedInteger('site_id')->after('deleted_at')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});

		Schema::drop('widgets_in_categories');
		Schema::table('widgets_categories', function($table)
		{
			$table->unsignedInteger('site_id')->after('id')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});
		Schema::table('widgets', function($table)
		{
			$table->unsignedInteger('category_id')->after('id')->default(1);
			$table->foreign('category_id')
			  ->references('id')->on('widgets_categories')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});

		Schema::table('templates', function($table)
		{
			$table->unsignedInteger('site_id')->after('id')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});

		Schema::table('menu', function($table)
		{
			$table->unsignedInteger('site_id')->after('id')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});

		Schema::table('email_logs', function($table)
		{
			$table->unsignedInteger('site_id')->after('id')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});

		Schema::table('slideshows', function($table)
		{
			$table->unsignedInteger('site_id')->after('id')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});

		Schema::table('posts_categories', function($table)
		{
			if (Schema::hasColumn('post_categories', 'author'))
			{
				$table->dropColumn(['author']);
				$table->unsignedInteger('author_id')->after('deleted_at')->default(3);

				$table->foreign('author_id')
				  ->references('id')->on('users')
				  ->onUpdate('cascade')
				  ->onDelete('restrict');
			}
	
			$table->unsignedInteger('site_id')->after('id')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});

		Schema::table('galleries', function($table)
		{
			$table->unsignedInteger('site_id')->after('id')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});

		Schema::table('events', function($table)
		{
			$table->unsignedInteger('site_id')->after('id')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
			  ->onUpdate('cascade')
			  ->onDelete('restrict');
		});

		Schema::table('testimonials', function($table)
		{
			$table->unsignedInteger('site_id')->after('id')->default(1);
			$table->foreign('site_id')
			  ->references('id')->on('sites')
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
		Schema::rename('sites', 'settings');

		Schema::table('settings', function($table)
		{
		    $table->renameColumn('title', 'site_title');
		    $table->dropColumn([
		    	'domain',
		    	'favicon', 
		    	'meta_tags',
		    	'deleted_at'
		    ]);
		});

		// Modify existing row.
		DB::table('menu')
			->where('plugin', 'settings')
			->update([
				'title' => 'Settings',
				'plugin' => 'settings',
				'slug' => 'admin/settings'
			]);

		Schema::table('pages', function($table)
		{
			$table->dropForeign('pages_site_id_foreign');
			$table->dropColumn(['site_id']);
		});

		Schema::create('widgets_in_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('category_id');
        	$table->unsignedInteger('widget_id');
        	$table->integer('rank');

			$table->foreign('category_id')->references('id')->on('widgets_categories');
			$table->foreign('widget_id')->references('id')->on('widgets');
		});
		Schema::table('widgets_categories', function($table)
		{
			$table->dropForeign('widgets_categories_site_id_foreign');
			$table->dropColumn(['site_id']);
		});
		Schema::table('widgets', function($table)
		{
			$table->dropForeign('widgets_category_id_foreign');
			$table->dropColumn(['category_id']);
		});

		Schema::table('templates', function($table)
		{
			$table->dropForeign('templates_site_id_foreign');
			$table->dropColumn(['site_id']);
		});

		Schema::table('menu', function($table)
		{
			$table->dropForeign('menu_site_id_foreign');
			$table->dropColumn(['site_id']);
		});

		Schema::table('email_logs', function($table)
		{
			$table->dropForeign('email_logs_site_id_foreign');
			$table->dropColumn(['site_id']);
		});

		Schema::table('slideshows', function($table)
		{
			$table->dropForeign('slideshows_site_id_foreign');
			$table->dropColumn(['site_id']);
		});

		Schema::table('posts_categories', function($table)
		{
			$table->dropForeign('posts_categories_author_id_foreign');
			$table->dropForeign('posts_categories_site_id_foreign');
			$table->dropColumn(['author_id']);
			$table->dropColumn(['site_id']);
			$table->string('author')->after('deleted_at')->default('401 Consulting');
		});

		Schema::table('galleries', function($table)
		{
			$table->dropForeign('galleries_site_id_foreign');
			$table->dropColumn(['site_id']);
		});

		Schema::table('events', function($table)
		{
			$table->dropForeign('events_site_id_foreign');
			$table->dropColumn(['site_id']);
		});

		Schema::table('testimonials', function($table)
		{
			$table->dropForeign('testimonials_site_id_foreign');
			$table->dropColumn(['site_id']);
		});
	}
}