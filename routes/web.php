<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/surgery-tools', function () { return view('layouts.surgery.msurg-packs'); })->name('surgery-tools');
Route::get('/autoclave', function () { return view('layouts.surgery.autoclave'); })->name('autoclave');
Route::get('/gala-tools', function () { return view('layouts.galactic.gala-tools'); })->name('gala-tools');
Route::get('/fishin-pack', function () { return view('layouts.fishing.fishin-pack'); })->name('fishin-pack');
Route::get('/firefighter-pack', function () { return view('layouts.firefighter.firefighter-pack'); })->name('firefighter-pack');
Route::get('/city-pack', function () { return view('layouts.city.city-pack'); })->name('city-pack');
Route::get('/farm-pack', function () { return view('layouts.farm.farm-pack'); })->name('farm-pack');
Route::get('/paleontologist-kit', function () { return view('layouts.paleontologist.paleontologist-kit'); })->name('paleontologist-kit');