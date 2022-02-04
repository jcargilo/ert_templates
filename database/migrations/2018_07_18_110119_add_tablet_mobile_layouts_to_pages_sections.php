<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTabletMobileLayoutsToPagesSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages_sections', function($table) {
            $table->string('layout_mobile')->after('author_id')->default('1');
            $table->string('layout_tablet')->after('layout_mobile')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages_sections', function($table) {
            $table->dropColumn('layout_mobile');
            $table->dropColumn('layout_tablet');
        });
    }
}