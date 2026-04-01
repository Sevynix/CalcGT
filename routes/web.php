<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/surgery-tools', function () { return view('layouts.surgery.msurg-packs'); })->name('surgery-tools');
Route::get('/autoclave', function () { return view('layouts.surgery.autoclave'); })->name('autoclave');
