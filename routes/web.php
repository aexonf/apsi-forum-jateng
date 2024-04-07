<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('home');
});
Route::get('/me', function () {
    return Inertia::render('my-forum');
});
Route::get('/new', function () {
    return Inertia::render('new');
});
Route::get('/edit', function () {
    return Inertia::render('edit');
});
Route::get('/post', function () {
    return Inertia::render('detail');
});
Route::get('/publikasi', function () {
    return Inertia::render('publication');
});
Route::get('/profile', function () {
    return Inertia::render('profile');
});

Route::get('/login', function () {
    return Inertia::render('login');
})->name('login');
