<?php

Route::group(['namespace' => 'App\Http\Controllers'], function() 
{
    Route::post('contact', 'SiteController@contact');

    Route::any('{slug}', 'SiteController@index')->where('slug', '^((?!admin).)*$');
});