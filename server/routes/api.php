<?php

use App\Http\Controllers\API\TodoController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserController::class, 'register'])
    ->middleware('guest');

Route::post('login', [UserController::class, 'login'])->name('login')
    ->middleware('guest');

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('profile', [UserController::class, 'profile']);
    Route::post('logout', [UserController::class, 'logout']);
    //todos
    Route::resource('todos', TodoController::class);
    Route::get('todos-report', [TodoController::class, 'todosReport']);
});
