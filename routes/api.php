<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Front\DiscussionCommentController;
use App\Http\Controllers\Front\ForumController;
use App\Http\Controllers\Front\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    $user = $request->user()->with('supervisor')->first();
    return response()->json([
        'data' => $user
    ]);
})->middleware("auth:sanctum");


Route::controller(AuthController::class)->prefix("/v1/auth")->group(function () {
    Route::post("/login", "login");
    Route::put("/reset-password", "resetPassword")->middleware("auth:sanctum");
    Route::post("/logout", "logout")->middleware("auth:sanctum");
});


Route::get("/v1/forum", [ForumController::class, "index"]);

Route::prefix("/v1")->middleware("auth:sanctum")->group(function () {

    Route::controller(ForumController::class)->prefix("/forum")->group(function () {
        Route::post("/", "store");
        Route::get("/me", "myForum");
        Route::get("/{id}", "detail");
        Route::put("/{id}", "update");
        Route::delete("/{id}", "delete");
        Route::post("/like/{id}", "like");
    });


    Route::controller(DiscussionCommentController::class)->prefix("/comment")->group(function () {
        Route::get("/{id}", "detail"); // id  blog nya
        Route::post("/{id}", "create"); // id dari  blog nya
        Route::put("/{id}", "update"); // id dari comment nya
        Route::delete("/{id}", "destroy"); // id dari comment nya
        Route::post("/like/{id}", "like"); // id dari comment nya
    });

    Route::controller(ProfileController::class)->prefix("/user")->group(function () {
        Route::get("/", "index");
        Route::put("/", "update");
    });

});
