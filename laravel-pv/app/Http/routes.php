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

// GET
Route::get('/', 'PagesController@index');
Route::get('/songs', 'PagesController@songs');
Route::get('/show/{slug}', 'PagesController@show');
Route::get('/shows', 'PagesController@shows');
Route::get('/years', 'PagesController@years');
Route::get('/year/{year}', 'PagesController@year');
Route::get('/login', 'PagesController@login');
Route::get('/register', 'PagesController@register');
Route::get('/logout', 'PagesController@logout');
Route::get('/song/{slug}', 'PagesController@showSong');
Route::get('/song/{slug}/{submission_id}', 'PagesController@showComments');
Route::get('/show/{date}/{slug}', 'PagesController@showSongFromDate');
Route::get('/submit/{slug}', 'PagesController@submitSong');
Route::get('/submit', 'PagesController@submit');
Route::get('/about', 'PagesController@about');

//Route::get('/updateShows', 'PagesController@updateShows');

// POST
Route::post('/comment', 'PagesController@doComment');
Route::post('/login', 'PagesController@doLogin');
Route::post('/register', 'PagesController@doRegister');
Route::post('/submit', 'PagesController@doSubmit');
Route::post('/ajax/vote', 'PagesController@updateVotes');
Route::post('/song-search', 'PagesController@songSearch');
Route::post('/delete/{submission_id}/{user_id}/{slug}', 'PagesController@deleteSubmission');
