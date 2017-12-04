<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Page@index')->name('homepage');
Route::get('/auth_wargaming/{wgCsrf}', 'Login@wargaming');
Route::get('/logout', 'Login@logout')->name('logout');
Route::get('/login', function () {
    return redirect('');
})->name('login');

// WoT API test
Route::get('/test', "Test@test");

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', 'Page@profile')->name('profile');
});

//Route::get('/home', 'HomeController@index')->name('home');
