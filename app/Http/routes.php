<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'VideoController@index');

Route::post('/', [
    'as' => 'getVideo',
    'uses' => 'VideoController@getVideo'
]);

Route::post('/likeVideo', [
    'as' => 'likeVideo',
    'uses' => 'VideoController@like'
]);

Route::post('/dislikeVideo', [
    'as' => 'dislikeVideo',
    'uses' => 'VideoController@dislike'
]);
