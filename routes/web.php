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

// PUBLIC
Route::get('/', 'Clan@info')->name('homepage');

Route::get('/auth_wargaming/{wgCsrf}', 'Login@wargaming');
Route::get('/logout', 'Login@logout')->name('logout');
Route::get('/login', function () {
    return redirect('');
})->name('login');

// AUTH
Route::middleware(['auth'])->group(function () {
//    Route::get('/members', 'Clan@members')->name('clanMembers');
//    Route::get('/dashboard', 'Profile@dashboard')->name('clanDashboard');
//    Route::get('/profile/{memberId}', 'Profile@index')->name('profile');
//    Route::get('/profile/{memberId}/garage', 'Profile@tanks')->name('profile-tanks');


    // DASHBOARD
    Route::get('/dashboard', 'Clan@dashboard')
        ->name('clanDashboard');

    Route::get('/members', 'Clan@members')
        ->name('clanMembers');

    // PROFILE
    Route::get('/profile/{memberId}', 'Profile@index')
        ->name('profile');

    Route::get('/profile/{memberId}/garage', 'Profile@garage')
        ->name('profileGarage');

    Route::get('/profile/{memberId}/inbox', 'Profile@inbox')
        ->name('profileMessages');
});

