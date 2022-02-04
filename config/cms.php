<?php

return [
	/**
	 * Determines whether the CMS routes are enabled or not
	 *
	 * @type boolean
	 */
	'routes_enabled' => true,

	/**
	 * Package URI
	 *
	 * @type string
	 */
	'uri' => 'admin',

	/**
	* Login redirect
	*
	* @type string
	*/
	'login_redirect' => '/admin/pages',

	/**
	 * HTML title tag
	 *
	 * @type string
	 */
	'title' => env('APP_NAME', 'Laravel') . ' | CMS',

	/**
	 * Package URI
	 *
	 * @type string
	 */
	'support_uri' => 'https://www.takeoffdesigngroup.com/',

	/**
	 * Default adminbar icon
	 *
	 * @type string
	 */
	'cms_logo' => '/vendor/takeoffdesigngroup/cms/images/TakeoffDesignGroup_CMSLogo.svg',

	/**
	 * Default adminbar icon
	 *
	 * @type string
	 */
	'login_logo' => '/assets/images/logo.png',

	/**
	 * Default adminbar icon
	 *
	 * @type string
	 */
	'icon' => '',

	/**
	 * Custom loading graphic (animated gif or otherwise) to replace the standard four-box animation.
	 *
	 * @type string
	 */
	'loading_graphic' => '',

	/**
	 * Set the access key to load the file manager.
	 * NOTE: The File Manager will NOT work unless it contains the key provided here in the access_keys attribute in its config file.
	 *
	 * @type string
	 */
	'file_manager_access_key' => 'f2f519004af58510c5d9ab950dc7708326d04410773f5409980f2527dfe3cf4e',

	/**
	 * Global default rows per page
	 *
	 * @type NULL|int
	 */
	'global_rows_per_page' => 20,

	/**
	 * Determines whether or not to expose the multi-site functionality for controlling more than a single site with the current 
	 * database/admin instances.
	 *
	 * @type boolean
	 */
	'multiple_sites_enabled' => false,

    /**
	 * Define whether or not to add a specified prefix to all tables used by the CMS.
	 *
	 * @type string
	 */
    'table_prefix' => NULL,

	/**
	 * Specify a list of domains for the admin routing to be active on.  Shows on all sites by default.
	 *
	 * @type array
	 */
	'admin_domains' => [],

	/**
	 * Settings for the blog engine
	 */
	'blog' => [
		'name' => 'blog',
		'comments_enabled' => false,
	],

	'role_structure' => [
        'administrator' => [
            'admin' => 'a,m',
            'acl' => 'm',
            'logs' => 'm',
            'events' => 'm',
            'galleries' => 'm',
            'pages' => 'm',
            'posts' => 'm',
            'sites' => 'm',
            'slideshows' => 'm',
            'testimonials' => 'm',
            'users' => 'm',
            'widgets' => 'm',
            'uploads' => 'm',
        ],
        'developer' => [],
        'editor' => [
        	'admin' => 'a',
        	'events' => 'c,r,u,d',
        	'galleries' => 'c,r,u,d',
        	'pages' => 'c,r,u,d',
        	'posts' => 'c,r,u,d',
        	'slideshows' => 'c,r,u,d',
        	'testimonials' => 'c,r,u,d',
        	'widgets' => 'c,r,u,d',
        	'uploads' => 'c,r,u,d',
        ],
    ],
    'permission_structure' => [
        // users put within this structure override permissions set at their role level  
    ],
    'permissions_map' => [
        'a' => 'access',
        'm' => 'manage', // FULL CRUD
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];