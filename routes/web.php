<?php

use App\Http\Controllers\Auth\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect('/app/login');
});

Route::get('/app/{any}', function () {
    return view('welcome');
});

Route::get('/check_email/{token}', [AuthenticationController::class, 'validEmail']);
