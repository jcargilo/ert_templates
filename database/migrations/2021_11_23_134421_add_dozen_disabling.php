<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDozenDisabling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function($table) {
            $table->boolean('two_dozen_disabled');
            $table->datetime('two_dozen_disabled_start_at')->nullable();
            $table->datetime('two_dozen_disabled_end_at')->nullable();
            $table->boolean('three_dozen_disabled');
            $table->datetime('three_dozen_disabled_start_at')->nullable();
            $table->datetime('three_dozen_disabled_end_at')->nullable();
        });
    }
}
