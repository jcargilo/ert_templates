<?php

Route::group(['namespace' => 'App\Http\Controllers'], function() 
{
    Route::get('team/{slug}', 'TeamsController@index');

    Route::post('contact', 'SiteController@contact');

    Route::get('{slug}', 'SiteController@index')->where('slug', '^((?!admin).)*$');
});