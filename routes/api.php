<?php

use App\Http\Controllers\ColorController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/todos')->controller(TodoController::class)->name('todos.')->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::get('/mark-completed', 'markCompleted');
    Route::get('/clear-completed', 'clearCompleted');
    Route::prefix('/{id}')->group(function() {
        Route::get('/', 'show')->name('show');
        Route::put('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

Route::prefix('/colors')->controller(ColorController::class)->name('colors.')->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');    
    Route::prefix('/{id}')->group(function() {
        Route::get('/', 'show')->name('show');
        Route::put('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});