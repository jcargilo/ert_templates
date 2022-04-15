<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEventsTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('templates')->truncate();
        
        DB::statement("
            INSERT INTO `templates` (`id`, `site_id`, `created_at`, `updated_at`, `title`, `url`, `rank`, `published`) VALUES
            (1, 1, '2022-02-07 11:36:20', '2022-02-07 11:36:20', 'Hero', 'hero', 1, 1),
            (2, 1, '2022-02-07 12:46:44', '2022-02-07 12:46:44', 'Events (Upcoming)', 'events', 2, 1),
            (3, 1, '2022-02-07 13:16:45', '2022-02-07 13:16:45', 'Contact Banner', 'contact_banner', 4, 1),
            (4, 1, '2022-02-18 11:23:07', '2022-02-18 11:23:07', 'Advisory Services Team', 'team_laa', 5, 1),
            (5, 1, '2022-02-18 11:23:30', '2022-02-18 11:23:30', 'Proactive Planning Team', 'team_ppt', 6, 1),
            (6, 1, '2022-02-18 11:23:43', '2022-02-18 11:23:43', 'Virtual Family Office', 'team_vfo', 7, 1),
            (7, 1, '2022-02-18 12:25:49', '2022-02-18 12:25:49', 'Contact Form', 'forms.contact', 8, 1),
            (8, 1, '2022-04-15 15:07:13', '2022-04-15 15:07:13', 'Events (Past)', 'past_events', 3, 1);
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
