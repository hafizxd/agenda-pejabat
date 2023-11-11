<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\AgendaController as ApiAgendaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [ApiAuthController::class, 'login'])->name('login');
Route::post('register', [ApiAuthController::class, 'register'])->name('register');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [ApiAuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [ApiAuthController::class, 'refresh'])->name('refresh');

    Route::get('profile', [ApiAuthController::class, 'getMe']);
    Route::put('profile', [ApiAuthController::class, 'update']);

    Route::prefix('agendas')->name('agendas.')->controller(ApiAgendaController::class)->group(function () {
        Route::get('/', 'list')->name('list');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'show')->name('show');
        Route::put('{id}/update', 'update')->name('update');
        Route::delete('{id}/delete', 'delete')->name('delete');
    });

    Route::prefix('rooms')->name('rooms.')->controller(ApiAgendaController::class)->group(function () {
        Route::get('/', 'listRoom')->name('listRoom');
        Route::get('{id}', 'detailRoom')->name('detailRoom');
        Route::post('{id}/book', 'storeBooking')->name('storeBooking');
    });
});
