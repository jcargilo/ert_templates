<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAltTextToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `posts` 
            MODIFY column created_at timestamp null default NULL,
            MODIFY column updated_at timestamp null default NULL,
            MODIFY column `date` timestamp null default NULL");

        Schema::table('posts', function(Blueprint $table)
        {
            $table->string('alt_text')->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function(Blueprint $table)
        {
            $table->dropColumn('alt_text');
        });
    }
}