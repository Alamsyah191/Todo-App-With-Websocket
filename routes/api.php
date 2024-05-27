<?php

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


Route::get('ajax_todo',[TodoController::class,'ajax_todo'])->name('ajax_todo');
Route::post('todo_create', [TodoController::class, 'store'])->name('todo_create');
Route::put('todo_update/{id}', [TodoController::class, 'update'])->name('todo_update');
Route::delete('todo_destroy/{id}', [TodoController::class, 'destroy'])->name('todo_destroy');
