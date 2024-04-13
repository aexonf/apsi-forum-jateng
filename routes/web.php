<?php

use App\Http\Controllers\Back\ForumController;
use Illuminate\Http\Request;
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


Route::get("/dashboard", function (Request $request) {
    return view("pages.index", [
        "request" => $request
    ]);
});


Route::prefix("/dashboard")->group(function () {

    Route::controller(ForumController::class)->prefix("/forum")->group(function () {
        Route::get("/", "index")->name("admin.forum.index");
        Route::get("/detail/{id}", "detail")->name("admin.forum.detail");
        Route::put("/{id}/approved", "approved")->name("admin.forum.approved");
        Route::put("/{id}/rejected", "rejected")->name("admin.forum.rejected");
        // Delete Comment
        Route::delete("/comment/delete/{id}", "destroyComment")->name("admin.forum.comment.delete");
    });

});
