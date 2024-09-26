<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::put('/projects', [ProjectController::class, 'update']);
Route::get('/projects', [ProjectController::class, 'index']);
