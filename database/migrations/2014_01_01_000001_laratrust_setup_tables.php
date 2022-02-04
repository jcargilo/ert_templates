<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class LaratrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Determine if Confide was installed and, if so, upgrade to Laratrust.
        if (Schema::hasTable('assigned_roles')) {
            $this->cleanupDatabase();
            $this->updateTimestamps();
            $this->convertConfideToLaratrust();
            $this->cleanupPermissions();
        } else {
            $this->setupLaratrust();
        }
    }

    protected function setupLaratrust()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->string('user_type');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        // Create table for associating permissions to users (Many-to-Many)
        Schema::create('permission_user', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('user_type');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'user_id']);
        });
    }

    protected function cleanupDatabase()
    {
        Schema::drop('authorizations');
        Schema::drop('biographies');
        Schema::drop('notes');
        Schema::drop('phones');
        Schema::drop('persons_emails');
        Schema::drop('persons');
        Schema::drop('organizations');
    }

    protected function updateTimestamps()
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

        DB::statement("ALTER TABLE `posts` 
            MODIFY column created_at timestamp null default NULL,
            MODIFY column updated_at timestamp null default NULL,
            MODIFY column `date` timestamp null default NULL");
    }

    protected function convertConfideToLaratrust()
    {
        DB::statement("ALTER TABLE `roles` 
            MODIFY column created_at timestamp null default NULL,
            MODIFY column updated_at timestamp null default NULL");

        Schema::table('roles', function (Blueprint $table) {
            $table->string('display_name')->after('name');
            $table->string('description')->after('display_name');
        });

        DB::statement("ALTER TABLE `permissions` 
            MODIFY column created_at timestamp null default NULL,
            MODIFY column updated_at timestamp null default NULL");

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('display_name')->nullable()->change();
            $table->string('description')->after('display_name');
        });

        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
            $table->string('user_type');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id', 'user_type']);
        });

        // Create table for associating permissions to users (Many To Many Polymorphic)
        Schema::create('permission_user', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('user_id');
            $table->string('user_type');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'permission_id', 'user_type']);
        });

        DB::statement("ALTER TABLE `permission_role` 
            drop column id,
            drop foreign key permission_role_permission_id_foreign,
            drop foreign key permission_role_role_id_foreign,
            drop key permission_role_role_id_foreign,
            drop key permission_role_permission_id_foreign");

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::table('permission_role', function (Blueprint $table) {
            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        // Move unique role assignments from assigned_roles to role_user// Moved unique role assignments from assigned_roles to role_user
        DB::statement("insert into role_user(role_id, user_id, user_type)
            select role_id, user_id, 'TakeoffDesignGroup\\\\CMS\\\\Models\\\\Auth\\\\User' from assigned_roles
            where user_id not in (
                select user_id
                from assigned_roles
                group by user_id
                having count(user_id) > 1
            )");

        // Move first apperance of any non-unique role assignments from assigned_roles to role_user
        DB::statement("INSERT INTO `role_user`(`role_id`, `user_id`, `user_type`)
            SELECT DISTINCT `role_id`, `user_id`, 'TakeoffDesignGroup\\\\CMS\\\\Models\\\\Auth\\\\User'
            FROM `assigned_roles`
            WHERE `user_id` IN (
                SELECT `user_id`
                FROM `assigned_roles`
                GROUP BY `user_id`
                HAVING COUNT(`user_id`) > 1
            )
            ORDER BY `user_id`");

        Schema::dropIfExists('assigned_roles');        
    }

    private function cleanupPermissions() 
    {
        DB::statement("DELETE FROM `permission_role`");
        DB::statement("DELETE FROM `permissions`");
        DB::statement("ALTER TABLE `permissions` AUTO_INCREMENT 1");

        DB::statement("
            INSERT INTO `permissions` (`name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES 
            ('access_admin', 'Access Admin', 'Access Admin', NOW(), NOW()),
            ('manage_sites', 'Manage Sites', 'Manage Sites', NOW(), NOW()),
            ('manage_logs', 'Manage Logs', 'Manage Logs', NOW(), NOW()),
            ('manage_admins', 'Manage Admins', 'Manage Admins', NOW(), NOW()),
            ('manage_users', 'Manage Users', 'Manage Users', NOW(), NOW()),
            ('manage_events', 'Manage Events', 'Manage Events', NOW(), NOW()),
            ('create_events', 'Create Events', 'Create Events', NOW(), NOW()),
            ('read_events', 'Read Events', 'Read Events', NOW(), NOW()),
            ('update_events', 'Update Events', 'Update Events', NOW(), NOW()),
            ('delete_events', 'Delete Events', 'Delete Events', NOW(), NOW()),
            ('manage_galleries', 'Manage Galleries', 'Manage Galleries', NOW(), NOW()),
            ('create_galleries', 'Create Galleries', 'Create Galleries', NOW(), NOW()),
            ('read_galleries', 'Read Galleries', 'Read Galleries', NOW(), NOW()),
            ('update_galleries', 'Update Galleries', 'Update Galleries', NOW(), NOW()),
            ('delete_galleries', 'Delete Galleries', 'Delete Galleries', NOW(), NOW()),
            ('manage_pages', 'Manage Pages', 'Manage Pages', NOW(), NOW()),
            ('create_pages', 'Create Pages', 'Create Pages', NOW(), NOW()),
            ('read_pages', 'Read Pages', 'Read Pages', NOW(), NOW()),
            ('update_pages', 'Update Pages', 'Update Pages', NOW(), NOW()),
            ('delete_pages', 'Delete Pages', 'Delete Pages', NOW(), NOW()),
            ('manage_posts', 'Manage Posts', 'Manage Posts', NOW(), NOW()),
            ('create_posts', 'Create Posts', 'Create Posts', NOW(), NOW()),
            ('read_posts', 'Read Posts', 'Read Posts', NOW(), NOW()),
            ('update_posts', 'Update Posts', 'Update Posts', NOW(), NOW()),
            ('delete_posts', 'Delete Posts', 'Delete Posts', NOW(), NOW()),
            ('manage_slideshows', 'Manage Slideshows', 'Manage Slideshows', NOW(), NOW()),
            ('create_slideshows', 'Create Slideshows', 'Create Slideshows', NOW(), NOW()),
            ('read_slideshows', 'Read Slideshows', 'Read Slideshows', NOW(), NOW()),
            ('update_slideshows', 'Update Slideshows', 'Update Slideshows', NOW(), NOW()),
            ('delete_slideshows', 'Delete Slideshows', 'Delete Slideshows', NOW(), NOW()),
            ('manage_testimonials', 'Manage Testimonials', 'Manage Testimonials', NOW(), NOW()),
            ('create_testimonials', 'Create Testimonials', 'Create Testimonials', NOW(), NOW()),
            ('read_testimonials', 'Read Testimonials', 'Read Testimonials', NOW(), NOW()),
            ('update_testimonials', 'Update Testimonials', 'Update Testimonials', NOW(), NOW()),
            ('delete_testimonials', 'Delete Testimonials', 'Delete Testimonials', NOW(), NOW()),
            ('manage_widgets', 'Manage Widgets', 'Manage Widgets', NOW(), NOW()),
            ('create_widgets', 'Create Widgets', 'Create Widgets', NOW(), NOW()),
            ('read_widgets', 'Read Widgets', 'Read Widgets', NOW(), NOW()),
            ('update_widgets', 'Update Widgets', 'Update Widgets', NOW(), NOW()),
            ('delete_widgets', 'Delete Widgets', 'Delete Widgets', NOW(), NOW()),            
            ('manage_uploads', 'Manage Uploads', 'Manage Uploads', NOW(), NOW()),
            ('create_uploads', 'Create Uploads', 'Create Uploads', NOW(), NOW()),
            ('read_uploads', 'Read Uploads', 'Read Uploads', NOW(), NOW()),
            ('update_uploads', 'Update Uploads', 'Update Uploads', NOW(), NOW()),
            ('delete_uploads', 'Delete Uploads', 'Delete Uploads', NOW(), NOW())");

        DB::statement("
            UPDATE `roles`
            set `name` = 'developer',
                `display_name` = 'Developer',
                `description` = 'Developer'
            WHERE id = 1
        ");
        DB::statement("
            UPDATE `roles`
            set `name` = 'administrator',
                `display_name` = 'Administrator',
                `description` = 'Administrator'
            WHERE id = 2            
        ");
        DB::statement("
            UPDATE `roles`
            set `name` = 'editor',
                `display_name` = 'Editor',
                `description` = 'Editor'
            WHERE id = 3
        ");
        DB::statement("
            INSERT INTO `roles` (`name`, `display_name`, `description`, `created_at`, `updated_at`)
            VALUES ('author', 'Author', 'Author', NOW(), NOW())
        ");

        // Move admins to admin role
        DB::statement("UPDATE `role_user` SET `role_id` = 4 WHERE `role_id` = 3");
        DB::statement("UPDATE `role_user` SET `role_id` = 3 WHERE `role_id` = 2");
        DB::statement("UPDATE `role_user` SET `role_id` = 2 WHERE `role_id` = 1");
        
        // Add developer to admin role
        DB::statement("
            INSERT INTO `role_user` (`role_id`, `user_id`, `user_type`)
            VALUES (1, 1, 'TakeoffDesignGroup\\\\CMS\\\\Models\\\\Auth\\\\User')
        ");

        // Add admin permissions
        DB::statement("
            INSERT INTO `permission_role` (`permission_id`, `role_id`)
            SELECT `id`, 2
            FROM `permissions`
            WHERE `name` LIKE '%manage%' OR `id` = 1
        ");

        // Add editor permissions
        DB::statement("
            INSERT INTO `permission_role` (`permission_id`, `role_id`)
            SELECT `id`, 3
            FROM `permissions`
            WHERE `name` NOT LIKE '%manage%' OR `id` = 1
        ");

        // Add author permissions
        DB::statement("
            INSERT INTO `permission_role` (`permission_id`, `role_id`)
            SELECT `id`, 4
            FROM `permissions`
            WHERE (`name` NOT LIKE '%manage%' AND `name` LIKE '%post%') OR `id` = 1
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        //! TODO - If necessary
        // Schema::drop('permission_user');
        // Schema::drop('permission_role');
        // Schema::drop('permissions');
        // Schema::drop('role_user');
        // Schema::drop('roles');
    }
}