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
Route::get('/test', "Page@test");

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', 'Page@profile')->name('profile');
    Route::get('/concurs', 'Page@concurs')->name('concurs');
    Route::get('/concurs/echipe', 'Page@concursEchipe')->name('concurs-echipe');
    Route::get('/concurs/rezultate', 'Page@concursRezultate')->name('concurs-rezultate');
//    Route::get('/ofiter', 'Page@ofiter')->name('ofiter')->middleware('can:isOfficer');
});

//Route::get('/home', 'HomeController@index')->name('home');

$data = [
    'teams' => [
        't1' => [
            'name' => 'Echipa 1',
            'players' => ['AlecsandruCorhan', 'marioseer', 'broscoi1']
        ],
        't2' => [
            'name' => 'Echipa 2',
            'players' => ['SirLucasIV', '_Syu_', 'zaman95']
        ],
        't3' => [
            'name' => 'Echipa 3',
            'players' => ['1Alexandrw', 'ligrivis', 'gabycarutasoiu']
        ],
        't4' => [
            'name' => 'Echipa 4',
            'players' => ['xxWOLVERINExxx', 'deniyz', 'stefy2014']
        ],
        't5' => [
            'name' => 'Echipa 5',
            'players' => ['uslaro', 'Panzerwaffe', 'ciukash']
        ],
        't6' => [
            'name' => 'Echipa 6',
            'players' => ['Deputy_Thunder', 'tenebras', 'robert_adrian2013']
        ],
        't7' => [
            'name' => 'Echipa 7',
            'players' => ['DemonSMV', 'dmmoisi', 'Sulla_Felix']
        ],
        't8' => [
            'name' => 'Echipa 8',
            'players' => ['acid8urn', 'GX5570', 'KinezGL']
        ],
        't9' => [
            'name' => 'Echipa 9',
            'players' => ['Blue_Banana', 'aurel19747777', 'zugamihai']
        ],
        't10' => [
            'name' => 'Echipa 10',
            'players' => ['Marius6354 ', 'UrSu77', 'iuga_mari78']
        ],
    ],
    'groups' => [
        'g1' => ['t3', 't4', 't7', 't9', 't10'],
        'g2' => ['t1', 't2', 't5', 't6', 't8'],
    ],
    'matches' => [
        'qualify' => [
            ['p1', 'p2'], ['p3', 'p4'], ['p1', 'p3'], ['p2', 'p5'], ['p2', 'p4'], ['p1', 'p5'], ['p1', 'p4'],
            ['p3', 'p5'], ['p2', 'p3'], ['p4', 'p5']
        ]
    ]
];
/*
1-2
3-4

1-3
2-5

2-4
1-5

1-4
3-5

2-3
4-5
*/