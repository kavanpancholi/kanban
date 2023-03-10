<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\ColumnController;
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

Route::resource('columns', ColumnController::class);
Route::post('columns/{card}/reorder', [ColumnController::class, 'reorder']);
Route::delete('columns/{column}/card', [ColumnController::class, 'destroyCard']);
