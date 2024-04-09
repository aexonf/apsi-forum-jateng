<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Front\DiscussionCommentController;
use App\Http\Controllers\Front\ForumController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->prefix("/v1/auth")->group(function () {
    Route::post("/login", "login");
    Route::put("/reset-password/{id}", "resetPassword");
});


Route::prefix("/v1")->group(function () {

    Route::controller(ForumController::class)->prefix("/forum")->group(function () {
        Route::get("/", "index");
        Route::post("/", "store");
        Route::get("/{id}", "detail");
        Route::put("/{id}", "update");
        Route::delete("/{id}", "delete");
        Route::post("/like/{id}", "like");
    });


    Route::controller(DiscussionCommentController::class)->prefix(("/comment"))->group(function () {
        Route::post("/{id}", "create"); // id dari discussion nya / blog nya
        Route::put("/{id}", "update"); // id dari comment nya
        Route::delete("/{id}", "destroy"); // id dari comment nya
        Route::post("/{id}", "like"); // id dari comment nya
    });
});
