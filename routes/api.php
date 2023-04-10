<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\TripController;
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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


//////////////////////////////////////////////////////////////////////////
/// Mock Endpoints To Be Replaced With RESTful API.
/// - API implementation needs to return data in the format seen below.
/// - Post data will be in the format seen below.
/// - /resource/assets/traxAPI.js will have to be updated to align with
///   the API implementation
//////////////////////////////////////////////////////////////////////////

Route::middleware('auth:api')->group(function () {
    Route::controller(CarController::class)->group(function () {
        Route::get('/cars', 'index');
        Route::get('/cars/{car}', 'show');
        Route::post('/cars', 'store');
        Route::delete('/cars/{car}', 'destroy');
    });
    Route::controller(TripController::class)->group(function () {
        Route::get('/trips', 'index');
        Route::post('/trips', 'store');
    });
});
