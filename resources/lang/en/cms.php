<?php

return [
	'badges' => [
		'custom' 		=> '<span class="label :background-color">:label</span>',
		'default' 		=> '<span class="label label-default">:label</span>',
		'success' 		=> '<span class="label label-success">:label</span>',
		'warning' 		=> '<span class="label label-warning">:label</span>',
		'danger' 		=> '<span class="label label-danger">:label</span>',
		
		'public' 		=> '<span class="label label-success">Public</span>',
		'active' 		=> '<span class="label label-success">Active</span>',
		'private'		=> '<span class="label label-warning">Private</span>',
		'disabled'		=> '<span class="label label-danger">Disabled</span>',
		'featured' 		=> '<span class="label label-success">Featured</span>',
		'deleted'		=> '<span class="label label-danger">Deleted</span>',
	],

	'alerts' => [
		'permission_denied' 	=> 'Your account does not have the permission to access that page.',
		'general_error'			=> 'An unexpected error occurred.  Please contact us if this problem continues.',

		'password_forgot'		=> 'Instructions for resetting your password was sent by email.',
		'wrong_password_forgot'	=> 'There is no account registered with that email address.',

		'download' => [
        	'general_error'		=> 'An unexpected error occurred downloading that file.  Please contact us if this problem continues.',
        	'not_found' 		=> 'Download not found. It may have been removed or is no longer available.',
        ],
	],

	'auth' => [
		'deleted'				=> 'That account has been deactivated. Please <a href="/contact">contact us</a> to inquire about the status of your account.',
		'not_approved'			=> 'Your account has not yet been approved yet. Please <a href="/contact">contact us</a> to inquire about the status of your account.',
		'forgot' 				=> "Please enter the email address for your account. An email will be sent to you containing a link to create a new password.",
		'reset'					=> "Please specify the new password you wish to use for your account.",
		'resetpassword_subject' => 'Recent changes to your :site account',
	],

	'oauth'	=> [
		'general_error' 		=> "There was an issue signing in using :service.  Please try again later.",
		'refusal'				=> "Unable to sign in using :service.  Please choose a different method.",
		'connected'				=> "Your <b>:service</b> account has been successfully connected.  You can now login using the <b>:service</b> option.",
		'already_connected'		=> "Your <b>:service</b> account is already connected with another account.  To access your connected account, logout of this account and choose the <b><i>Connect with :service</i></b> signin option.",
		'not_connected'			=> "There isn't an account associated with your <b>:service</b> login.  To connect an existing account, sign in normally and connect your <b>:service</b> login on the My Profile page.",
	],

	'email' => [

		'system_messages' => [
			'subject' => 'You have received a message from :site',
		],

        'reason' 	=> '',
    ],
];