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
Route::get('/', 'Landing@index')->name('homepage');

Route::get('/auth_wargaming/{wgCsrf}', 'Login@wargaming');
Route::get('/logout', 'Login@logout')->name('logout');
Route::get('/login', function () {
    return redirect('');
})->name('login');

// AUTH
Route::middleware(['auth'])->group(function () {
    // CLAN
    Route::get('/welcome', 'Clan@welcome')
        ->name('infoWelcome')
        ->middleware('can:access,');

    Route::get('/rules', 'Clan@rules')
        ->name('infoRules')
        ->middleware('can:access,');

    // DASHBOARD
    Route::get('/dashboard', 'Clan@dashboard')
        ->name('clanDashboard')
        ->middleware('can:access,');

    Route::get('/members', 'Clan@members')
        ->name('clanMembers');

    // PROFILE
    Route::get('/profile/{memberId}', 'Profile@index')
        ->name('profile');

    Route::get('/profile/{memberId}/garage', 'Profile@garage')
        ->name('profileGarage');

    Route::get('/profile/{memberId}/inbox', 'Profile@inbox')
        ->name('profileMessages');

    // OFFICER
    Route::get('/recruitment', 'Officer@recruitment')
        ->name('officerRecruitment')
        ->middleware('can:access-recruitment,');

    Route::get('/combat', 'Officer@combat')
        ->name('officerCombat')
        ->middleware('can:access-combat,');

    Route::get('/combat/clan-wars', 'Officer@clanWars')
        ->name('officerClanWars')
        ->middleware('can:access-clanwars,');

    Route::get('/command', 'Officer@command')
        ->name('officerCommand')
        ->middleware('can:access-command,');
});

