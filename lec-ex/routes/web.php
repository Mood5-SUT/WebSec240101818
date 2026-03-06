<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); //welcome.blade.php
});

Route::get('/multable', function () {
    $j = 6;
    return view('multable', compact('j')); //multable.blade.php
});


Route::get('/even', function () {
    return view('even'); //even.blade.php
});

Route::get('/prime', function () {
    return view('prime'); //prime.blade.php
});
