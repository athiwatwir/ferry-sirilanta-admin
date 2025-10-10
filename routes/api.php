<?php

use App\Http\Controllers\AgentRouteController;

use App\Http\Controllers\Api\V1\Back\AgentRouteApiController;
use App\Http\Controllers\Api\V1\BookingApiController;
use App\Http\Controllers\Api\V1\InformationTextApiController;
use App\Http\Controllers\Api\V1\PromotionApiController;
use App\Http\Controllers\Api\V1\RouteApiController;
use App\Http\Controllers\Api\V1\StationApiController;
use App\Http\Controllers\RouteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



/**
 * Internal api
 */
Route::post('/agent-route/save', [AgentRouteController::class, 'saveAgentRoute']);

Route::get('/route', [RouteController::class, 'routes']);



/**
 * Agent api
 */

Route::prefix('v1/back')->middleware('verify.api_key')->group(function () {

    Route::prefix('agent-route')->group(function () {
        Route::get('/', [AgentRouteApiController::class, 'index']);
        Route::post('/added-route', [AgentRouteApiController::class, 'addedRoute']);


        Route::post('/{id}', [AgentRouteApiController::class, 'update']);
    });
});


/**
 * Front end api
 */

Route::prefix('v1')->middleware('verify.api_key')->group(function () {

    Route::prefix('route')->group(function () {
        Route::get('/', [RouteApiController::class, 'index']);

        Route::get('/{id}', [RouteApiController::class, 'getRoute']);
    });

    Route::prefix('booking')->group(function () {
        Route::get('/', [BookingApiController::class, 'index']);
        Route::post('/create', [BookingApiController::class, 'create']);
        Route::post('/complete', [BookingApiController::class, 'complete']);
        Route::post('/cancel', [BookingApiController::class, 'cancel']);

        Route::get('/{invoiceno}', [BookingApiController::class, 'show']);
    });

    Route::prefix('station')->group(function () {
        Route::get('/', [StationApiController::class, 'index']);
        Route::get('departure', [StationApiController::class, 'departure']);
        Route::get('destination', [StationApiController::class, 'destination']);

        Route::get('/{id}', [StationApiController::class, 'getStation']);
    });

    Route::prefix('information')->group(function () {
        Route::get('/', [InformationTextApiController::class, 'index']);
    });

    Route::prefix('promotion')->group(function () {
        Route::get('/', [PromotionApiController::class, 'index']);

        Route::get('/{id}', [PromotionApiController::class, 'detail']);
    });
});
