<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformationTextController;
use App\Http\Controllers\MapTableController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SubRouteController;
use App\Http\Controllers\TimeTableController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home.index');
});


Route::controller(BookingController::class)->group(function () {
    Route::get('/booking/route-fillter', 'routeFillter')->name('booking.routeFillter');
});

Route::controller(FinancialController::class)->group(function () {
    Route::get('/financial/fee', 'fee')->name('financial.fee');
    Route::get('/financial/fare', 'fare')->name('financial.fare');
    Route::get('/financial/promotion', 'promotion')->name('financial.promotion');
});



Route::resources([
    'booking' => BookingController::class,
    'route' => RouteController::class,
    'payment' => PaymentController::class,
    'user' => UserController::class,
    'subRoute' => SubRouteController::class,
    'news' => NewsController::class,
    'mapTable' => MapTableController::class,
    'informationText' => InformationTextController::class,
    'financial' => FinancialController::class,
    'promotion' => PromotionController::class
]);
