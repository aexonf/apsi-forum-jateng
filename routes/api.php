<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Front\DiscussionCommentController;
use App\Http\Controllers\Front\ForumController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\PublicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    $user = $request->user()->with('supervisor:id,name,email,label,level')->first();
    return response()->json([
        'data' => $user
    ], 200);
})->middleware("auth:sanctum");


Route::controller(AuthController::class)->prefix("/v1/auth")->group(function () {
    Route::post("/login", "login");
    Route::put("/reset-password", "resetPassword")->middleware("auth:sanctum");
    Route::post("/logout", "logout")->middleware("auth:sanctum");
});

Route::prefix("/v1")->group(function () {
    Route::get("/forum", [ForumController::class, "index"]);
    Route::get("/forum/detail/{id}", [ForumController::class, "detail"]);
    Route::get("/comment/{id}", [DiscussionCommentController::class, "detail"]); // id  blog nya
});

Route::prefix("/v1")->middleware("auth:sanctum")->group(function () {

    Route::controller(ForumController::class)->prefix("/forum")->group(function () {
        Route::post("/", "store");
        Route::get("/me", "myForum");
        Route::put("/{id}", "update");
        Route::delete("/delete/{id}", "destroy");
        Route::post("/like/{id}", "like");
    });

    Route::controller(DiscussionCommentController::class)->prefix("/comment")->group(function () {
        Route::post("/{id}", "create"); // id dari  blog nya
        Route::put("/{id}", "update"); // id dari comment nya
        Route::delete("/{id}", "destroy"); // id dari comment nya
        Route::post("/like/{id}", "like"); // id dari comment nya
    });

    Route::controller(ProfileController::class)->prefix("/user")->group(function () {
        Route::get("/", "index");
        Route::put("/", "update");
    });

    Route::controller(PublicationController::class)->prefix("/publication")->group(function () {
        Route::get("/", "index");
        Route::put("/{id}", "download"); // counter download
    });
});
