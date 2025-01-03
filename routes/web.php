<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BuildController::class, 'index']);

Route::get('/create', [BuildController::class, 'create']);

Route::post('/create', [BuildController::class, 'rstore']);

Route::get('/start/{room}', [BuildController::class, 'start']);

Route::get('/enter', [BuildController::class, 'enter']);