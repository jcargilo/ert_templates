<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropColumn("created_at");
            $table->dropColumn("updated_at");
        });

        // Add scroll next field to pages_sections table
        Schema::table('users', function($table) {
            $table->timestamp('created_at')->nullable()->after('id');
            $table->timestamp('updated_at')->nullable()->after('created_at');
            $table->timestamp('deleted_at')->nullable()->after('updated_at');
            $table->renameColumn('name', 'username');
            $table->string('first_name', 255)->after('password');
            $table->string('last_name', 255)->after('first_name');
            $table->string('confirmation_code', 255)->after('last_name');
            $table->boolean('confirmed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropColumn("created_at");
            $table->dropColumn("updated_at");
        });

        Schema::table('users', function($table) {
            $table->dropColumn('confirmed');
            $table->dropColumn('confirmation_code');
            $table->dropColumn('last_name');
            $table->dropColumn('first_name');
            $table->renameColumn('username', 'name');
            $table->dropColumn('deleted_at');
            $table->timestamp('created_at')->nullable()->after('remember_token');
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
    }

}
