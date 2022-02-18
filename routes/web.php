<?php

Route::group(['namespace' => 'App\Http\Controllers'], function() 
{
    Route::post('contact', 'SiteController@contact');

    Route::get('{slug}', 'SiteController@index')->where('slug', '^((?!admin).)*$');
});