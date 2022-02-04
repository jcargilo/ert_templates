<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBackgroundFillToPagesSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'users', 
            'galleries',
            'galleries_albums',
            'galleries_photos',
            'events',
            'pages',
            'pages_sections',
            'pages_sections_columns',
            'posts_categories',
        ];

        foreach ($tables as $table)
            DB::statement("ALTER TABLE `{$table}` 
                MODIFY column created_at timestamp null default NULL,
                MODIFY column updated_at timestamp null default NULL");

        if (!Schema::hasColumn('pages_sections', 'background_fill'))
        {
            Schema::table('pages_sections', function($table)
            {
                $table->string('background_fill')->after('background_parallax');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages_sections', function($table) 
        {
            $table->dropColumn('background_fill');
        });
    }
}
