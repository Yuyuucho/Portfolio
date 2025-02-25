<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\KickOrBanController;


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



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [BuildController::class, 'index'])->name('index');

Route::controller(BuildController::class)->middleware(['auth'])->group(function(){
    Route::get('/create', 'create')->name('create');
    Route::post('/create', 'storeRoom')->name('storeRoom');
    Route::get('/start/{room}', 'start')->name('start');
    Route::post('/start/{room}', 'startRoomPost')->name('startRoomPost');
    Route::get('/enter', 'enter')->name('enter');
    Route::post('/enter', 'joinRoom')->name('joinRoom');
    Route::get('/wait/{room}/{user}', 'wait')->name('wait');
    Route::get('/lottery/{room}', 'lottery')->name('lottery');
    Route::post('/lottery/again/{room}', 'nextLottery')->name('nextLottery');
    Route::post('/lottery/add/{room}', 'addLottery')->name('addLottery');
    Route::delete('/lottery/{room}', 'destroyRoom')->name('destroyRoom');
    Route::post('/wait/{room}/{user}', 'leaveRoom')->name('leaveRoom');
});

Route::controller(KickOrBanController::class)->middleware(['auth'])->group(function(){
    Route::post('/lottery/{room}/kick/{user}', 'kick')->name('kick');
    Route::post('/lottery/{room}/ban/{user}', 'ban')->name('ban');
});

require __DIR__.'/auth.php';
