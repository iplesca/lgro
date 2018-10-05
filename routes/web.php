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
//Route::get('/', 'Clan@info')->name('homepage');

Route::get('/auth_wargaming/{wgCsrf}', 'Login@wargaming');
Route::get('/logout', 'Login@logout')->name('logout');
Route::get('/login', function () {
    return redirect('');
})->name('login');

function ___Get($route, $action, $name = '', $middleware = '') {
    ___('get', $route, $action, $name, $middleware);
}
function ___Post($route, $action, $name = '', $middleware = '') {
    ___('post', $route, $action, $name, $middleware);
}
function ___($verb, $route, $action, $name = '', $middleware = '') {
    $clanTag = '/clan:{clanTag}';
    $cr = Route::$verb($clanTag . $route, $action);
    $r = Route::$verb($route, $action);

    if (!empty($name)) {
        $r->name($name);
    }
    if (!empty($middleware)) {
        $r->middleware($middleware);
    }
}

___Get('/', 'Clan@info', 'homepage');

// AUTH
Route::middleware(['prepare', 'auth'])->group(function () {
    // CLAN
    ___Get('/welcome', 'InfoClan@welcome', 'infoWelcome');
    ___Get('/rules', 'InfoClan@rules', 'infoRules', 'can:access,');

    // DASHBOARD
    ___Get('/dashboard', 'Clan@dashboard', 'clanDashboard', 'can:access,');
    ___Get('/members', 'Clan@members', 'clanMembers');

    // PROFILE
    ___Get('/profile/{memberId}', 'Profile@index', 'profile');
    ___Get('/profile/{memberId}/garage', 'Profile@garage', 'profileGarage');
    ___Get('/profile/{memberId}/inbox', 'Profile@inbox', 'profileMessages');

    // OFFICER
    ___Get('/recruitment', 'Officer@recruitment', 'officerRecruitment', 'can:access-recruitment,');
    ___Get('/combat', 'Officer@combat', 'officerCombat', 'can:access-combat,');
    ___Get('/combat/clan-wars', 'Officer@clanWars', 'officerClanWars', 'can:access-clanwars,');
    ___Get('/command', 'Officer@command', 'officerCommand', 'can:access-command,');
});

