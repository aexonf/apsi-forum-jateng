<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Front\ForumController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->prefix("/v1/auth")->group(function() {
    Route::post("/login", "login");
    Route::put("/reset-password/{id}", "resetPassword");
});


Route::prefix("/v1")->group(function() {

    Route::controller(ForumController::class)->prefix("/forum")->group(function() {
        Route::post("/", "store");
        Route::get("/{id}", "detail");
        Route::put("/{id}", "update");
        Route::delete("/{id}", "delete");
        Route::post("/like/{id}", "like");
    });




});


