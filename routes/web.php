<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Back\AdminManagementController;
use App\Http\Controllers\Back\ForumController;
use App\Http\Controllers\Back\PublicationController;
use App\Http\Controllers\Back\SupervisorController;
use App\Http\Middleware\SuperAdminMiddleware;
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
Route::get('/post/{id}', function ($id) {
    return Inertia::render('detail', [
        'id' => $id
    ]);
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


// router untuk login
Route::controller(AuthController::class)->prefix("/login")->group(function() {
    Route::post("/", "login");
});


Route::prefix("/dashboard")->group(function () {

    Route::get("/", function (Request $request) {
        return view("pages.index", [
            "request" => $request
        ]);
    })->name("admin.dashboard");

    Route::controller(ForumController::class)->prefix("/forum")->group(function () {
        Route::get("/", "index")->name("admin.forum.index");
        Route::get("/detail/{id}", "detail")->name("admin.forum.detail");
        Route::put("/{id}/approved", "approved")->name("admin.forum.approved");
        Route::put("/{id}/rejected", "rejected")->name("admin.forum.rejected");
        // Delete Comment
        Route::delete("/comment/delete/{id}", "destroyComment")->name("admin.forum.comment.delete");
    });


    Route::controller(SupervisorController::class)->prefix("/supervisor")->group(function () {
        Route::get("/", "index")->name("admin.supervisor.index");
        Route::post("/", "create")->name("admin.supervisor.create");
        Route::put("/{id}/update", "update")->name("admin.supervisor.update");
        Route::delete("/{id}/delete", "delete")->name("admin.supervisor.delete");
        Route::get("/download/format", "downloadFormat")->name("admin.supervisor.download.format");
        Route::post("/import", "import")->name("admin.supervisor.import");
        Route::get("/export", "export")->name("admin.supervisor.export");
    });

    Route::controller(PublicationController::class)->prefix("/publication")->group(function() {
        Route::get("/", "index")->name("admin.publication.index");
        Route::post("/", "create")->name("admin.publication.create");
        Route::put("/{id}/update", "update")->name("admin.publication.update");
        Route::delete("/{id}/delete", "delete")->name("admin.publication.delete");
    });

    Route::controller(AdminManagementController::class)->prefix("/admin-management")->group(function() {
        Route::get("/", "index")->name("admin.admin-management.index");
        Route::post("/", "create")->name("admin.admin-management.create");
        Route::put("/{id}/update", "update")->name("admin.admin-management.update");
        Route::delete("/{id}/delete", "delete")->name("admin.admin-management.delete");
        Route::get("/download/format", "downloadFormat")->name("admin..admin-management.download.format");
        Route::post("/import", "import")->name("admin..admin-management.import");
        Route::get("/export", "export")->name("admin..admin-management.export");
    });


})->middleware("auth");