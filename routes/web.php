<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AgentRouteController;
use App\Http\Controllers\AgentUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PriceStrategyController;
use App\Http\Controllers\PriceStrategyLineController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\RouteScheduleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\SubRouteController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletsController;
use App\Http\Controllers\InformationTextController;
use App\Http\Controllers\MapTableController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SettingFeeController;
use App\Http\Controllers\TagController;
use App\Models\Section;
use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

*/

Route::controller(PrintController::class)->group(function () {
    Route::get('/p/ticket/{bookingno}', 'ticket')->name('print.ticket');
    Route::get('/p/detail/{bookingno}', 'detail')->name('print.detail');
});

Route::middleware('auth')->group(function () {

    Route::get('/', [BookingController::class, 'index']);


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(StationController::class)->group(function () {
        Route::post('/station/change-status/{station}', 'changeStatus')->name('station.changeStatus');
    });

    Route::controller(SectionController::class)->group(function () {
        Route::post('/section/change-status/{section}', 'changeStatus')->name('section.changeStatus');
    });

    Route::controller(RouteController::class)->group(function () {
        Route::post('/route/change-status/{route}', 'changeStatus')->name('route.changeStatus');
    });

    Route::controller(SubRouteController::class)->group(function () {
        Route::post('/sub-route/change-status/{subRoute}', 'changeStatus')->name('subRoute.changeStatus');

        Route::post('/sub-route/price-strategy/{subRoute}', 'priceStrategy')->name('subRoute.priceStrategy');
    });

    Route::controller(PriceStrategyController::class)->group(function () {
        Route::post('/price-strategy/change-status/{priceStrategy}', 'changeStatus')->name('priceStrategy.changeStatus');
    });

    Route::controller(RouteScheduleController::class)->group(function () {
        Route::get('/route-schedule/processing/{routeSchedule}', 'processing')->name('routeSchedule.processing');
        Route::get('/route-schedule/calendar/{subRoute}', 'calendar')->name('routeSchedule.calendar');

        Route::post('/route-schedule/daily-store/{subRoute}', 'dailyStore')->name('routeSchedule.dailyStore');
    });

    Route::controller(DemoController::class)->group(function () {
        Route::get('/demo', 'index')->name('demo.index');
        Route::get('/demo/route', 'route')->name('demo.route');
    });

    Route::controller(AgentController::class)->group(function () {
        Route::get('/agent/route/{agent}', 'route')->name('agent.route');
        Route::get('/agent/wallet/{agent}', 'wallet')->name('agent.wallet');
        Route::get('/agent/user/{agent}', 'user')->name('agent.user');
    });

    Route::controller(WalletsController::class)->group(function () {
        Route::post('/wallet/add-balance/{wallet}', 'addBalance')->name('wallet.addBalance');
    });

    Route::controller(ReportController::class)->group(function () {
        Route::get('/report', 'index')->name('report.index');
    });

    Route::controller(BookingController::class)->group(function () {
        Route::get('/booking/flight', 'flight')->name('booking.flight');
        Route::get('/booking/payment/{invoiceno}', 'payment')->name('booking.payment');
    });

    Route::controller(FinancialController::class)->group(function () {
        Route::get('/financial/fee', 'fee')->name('financial.fee');
        Route::get('/financial/fare', 'fare')->name('financial.fare');
        Route::get('/financial/promotion', 'promotion')->name('financial.promotion');
    });




    Route::resources([
        'booking' => BookingController::class,
        'station' => StationController::class,
        'section' => SectionController::class,
        'route' => RouteController::class,
        'subRoute' => SubRouteController::class,
        'user' => UserController::class,
        'priceStrategy' => PriceStrategyController::class,
        'priceStrategyLine' => PriceStrategyLineController::class,
        'routeSchedule' => RouteScheduleController::class,
        'agent' => AgentController::class,
        'wallet' => WalletsController::class,
        'agentRoute' => AgentRouteController::class,
        'payment' => PaymentController::class,
        'agentUser' => AgentUserController::class,
        'template' => TemplateController::class,
        'news' => NewsController::class,
        'mapTable' => MapTableController::class,
        'informationText' => InformationTextController::class,
        'financial' => FinancialController::class,
        'promotion' => PromotionController::class,
        'settingFee' => SettingFeeController::class,
        'tag' => TagController::class

    ]);
});

require __DIR__ . '/auth.php';
