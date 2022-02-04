<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplayInFooter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
    {
        Schema::table('pages', function($table)
        {
            $table->string('display_in_footer')->after('display_in_menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function($table) 
        {
            $table->dropColumn('display_in_footer');
        });
    }
}
