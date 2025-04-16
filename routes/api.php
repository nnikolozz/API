<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;



Route::post('/login',[AuthController::class, 'login']);
Route::post('/register',[AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout',[AuthController::class, 'logout']);
    Route::get('/profile',[AuthController::class, 'profile']);

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']); 
    Route::get('/tasks', [TaskController::class, 'index']);

    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    });



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
