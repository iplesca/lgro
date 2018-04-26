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
use Illuminate\Support\Facades\Route;

Route::get('/', 'Page@index')->name('homepage');
Route::get('/auth_wargaming/{wgCsrf}', 'Login@wargaming');
Route::get('/logout', 'Login@logout')->name('logout');
Route::get('/login', function () {
    return redirect('');
})->name('login');

// WoT API test
Route::get('/test', "Page@test");

Route::middleware(['auth'])->group(function () {
    Route::get('/members', 'Clan@members')->name('clanMembers');
    Route::get('/dashboard', 'Profile@dashboard')->name('clanDashboard');
//    Route::get('/profile', 'Page@profile')->name('profile');
    Route::get('/profile/{memberId}', 'Profile@index')->name('profile');
    Route::get('/profile/{memberId}/garage', 'Profile@tanks')->name('profile-tanks');

    Route::get('/concurs', 'Page@concurs')->name('concurs');
    Route::get('/concurs/echipe', 'Page@concursEchipe')->name('concurs-echipe');
    Route::post('/concurs/save', 'Page@concursSave')->name('concurs-save');
    Route::post('/concurs/rezultate', 'Page@concursRezultate')->name('concurs-rezultate');
    Route::get('/concurs/rezultate', 'Page@concursRezultate')->name('concurs-rezultate');
//    Route::get('/ofiter', 'Page@ofiter')->name('ofiter')->middleware('can:isOfficer');
});

