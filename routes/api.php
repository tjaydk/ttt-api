<?php

use App\Http\Controllers\GameController;
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

Route::middleware('api')->group(function () {

    Route::prefix('games')->group(function () {
        Route::get('', [GameController::class, 'index']);
        Route::post('', [GameController::class, 'create']);
        Route::prefix('{uuid}')->group(function () {
            Route::get('', [GameController::class, 'get']);
            Route::delete('', [GameController::class, 'delete']);
            Route::get('check', [GameController::class, 'checkForVictory']);
            Route::post('restart', [GameController::class, 'restart']);
            Route::post('{piece}', [GameController::class, 'setPiece']);
        });
    });
});
