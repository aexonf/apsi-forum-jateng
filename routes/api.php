<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->prefix("/v1/auth")->group(function() {
    Route::post("/login", "login");
    Route::put("/reset-password/{id}", "resetPassword");
});


