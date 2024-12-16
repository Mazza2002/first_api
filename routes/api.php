<?php

use App\Http\Controllers\PosteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::post('/users', [UserController::class,'store']);
Route::put('users/{user}',[UserController::class,'update']);
Route::delete('users/{user}',[UserController::class,'destroy']);
Route::get('/users/getnom',[UserController::class,'getNom']);
Route::post('/users/login',[UserController::class,'login']);


// poste
Route::get('/poste', [PosteController::class, 'index']);
Route::post('/poste',[PosteController::class,'store']);
Route::get('/poste/{userid}',[PosteController::class,'getPostByUserId']);
Route::delete('/poste/{poste}',[PosteController::class,'destroy']);
Route::put('/poste',[PosteController::class,'update']);
Route::get('/postes/today',[PosteController::class,'getTodayPostes']);
Route::post("poste/like",[PosteController::class,"likePost"]);

// product
Route::post("/product",[ProductController::class,'store']);
Route::post("/product/card",[ProductController::class,'addToCard']);
Route::get("/product/card",[ProductController::class,'getAddtocard']);
