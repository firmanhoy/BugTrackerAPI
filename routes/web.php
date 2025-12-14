<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});
// routes/web.php
Route::get('/', function () {
    return view('landing.landing');
})->name('landing');
