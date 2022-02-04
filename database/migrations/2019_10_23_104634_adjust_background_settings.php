<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustBackgroundSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages_sections', function($table) 
        {
            $table->dropColumn('background_fill');
        });

        Schema::table('pages_sections', function($table)
        {
            $table->integer('background_max_height')->after('background_size');
            $table->renameColumn('background_size', 'background_min_height');
        });

        DB::statement('ALTER TABLE `pages_sections` MODIFY `background_min_height` int(11) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('pages_sections', 'background_fill'))
        {
            Schema::table('pages_sections', function($table)
            {
                $table->string('background_fill')->after('background_parallax');
            });
        }
    }
}
