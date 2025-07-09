<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public jobs listing
Route::view('/jobs', 'jobs.index')->name('jobs.index');

// Auth pages
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
