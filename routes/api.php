<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthenticationController::class)->group(function(){
    Route::post('/register','register');
    Route::post('/login','login');
});

Route::controller(ProjectController::class)->group(function () {
    Route::post('/register','register');
    Route::post('/login','login');
});

//----------------------------------------------------------------------------
Route::controller(ProjectController::class)->group(function () {
    Route::post('/projects', 'store');
    Route::put('/projects', 'update');
    Route::get('/projects', 'index');
    Route::post('/projects/pinned', 'pinnedProject');
    Route::get('/count/projects', 'countProject');

});

//----------------------------------------------------------------------------
Route::controller(MemberController::class)->group(function () {
    Route::post('/members', 'store');
    Route::put('/members', 'update');
    Route::get('/members', 'index');
});

Route::controller(TaskController::class)->group(function(){
    Route::post('/tasks','createTask'); //finish
    Route::post('tasks/not_started_to_pending','TaskToNotStartedToPending'); //work on
    Route::post('tasks/pending_to_completed','TaskToPendingToCompleted'); //work on
    Route::post('tasks/completed_to_pending','TaskToCompletedToPending'); //work on

    Route::post('tasks/completed_to_not_started','TaskToCompletedToNotStarted'); //home work
    Route::post('tasks/not_started_to_completed','TaskToNotStartedToCompleted');  //home work
    Route::post('tasks/pending_to_not_started','TaskToPendingToNotStarted');  //home work

});

