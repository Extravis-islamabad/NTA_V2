<?php

use App\Http\Controllers\Api\DeviceApiController;
use App\Http\Controllers\Api\FlowApiController;
use App\Http\Controllers\Api\AlarmApiController;
use App\Http\Controllers\Api\GeoApiController;
use App\Http\Controllers\Api\RealtimeApiController;
use App\Http\Controllers\Api\ApplicationApiController;
use Illuminate\Support\Facades\Route;

// All API routes require authentication and have rate limiting
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Devices API
    Route::prefix('devices')->group(function () {
        Route::get('/', [DeviceApiController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\DeviceController::class, 'store']);
        Route::get('/{device}', [DeviceApiController::class, 'show']);
        Route::put('/{device}', [\App\Http\Controllers\DeviceController::class, 'update']);
        Route::delete('/{device}', [\App\Http\Controllers\DeviceController::class, 'destroy']);
        Route::get('/{device}/traffic', [DeviceApiController::class, 'traffic']);
        Route::get('/{device}/interfaces', [DeviceApiController::class, 'interfaces']);
    });

    // Flows API
    Route::prefix('flows')->group(function () {
        Route::get('/', [FlowApiController::class, 'index']);
        Route::get('/statistics', [FlowApiController::class, 'statistics']);
        Route::get('/device/{device}', [FlowApiController::class, 'byDevice']);

        // Device-specific traffic analytics
        Route::get('/device/{device}/summary', [FlowApiController::class, 'deviceSummary']);
        Route::get('/device/{device}/distribution', [FlowApiController::class, 'trafficDistribution']);
        Route::get('/device/{device}/timeseries', [FlowApiController::class, 'trafficTimeSeries']);
        Route::get('/device/{device}/applications', [FlowApiController::class, 'trafficByApplication']);
        Route::get('/device/{device}/protocols', [FlowApiController::class, 'trafficByProtocol']);
        Route::get('/device/{device}/sources', [FlowApiController::class, 'topSources']);
        Route::get('/device/{device}/destinations', [FlowApiController::class, 'topDestinations']);
        Route::get('/device/{device}/qos', [FlowApiController::class, 'qosDistribution']);
        Route::get('/device/{device}/conversations', [FlowApiController::class, 'topConversations']);
        Route::get('/device/{device}/cloud', [FlowApiController::class, 'cloudTraffic']);
        Route::get('/device/{device}/as', [FlowApiController::class, 'asTraffic']);
    });

    // Alarms API
    Route::prefix('alarms')->group(function () {
        Route::get('/', [AlarmApiController::class, 'index']);
        Route::get('/stats', [AlarmApiController::class, 'stats']);
        Route::get('/{alarm}', [AlarmApiController::class, 'show']);
        Route::post('/{alarm}/acknowledge', [AlarmApiController::class, 'acknowledge']);
        Route::post('/{alarm}/resolve', [AlarmApiController::class, 'resolve']);
    });

    // Geolocation API
    Route::prefix('geo')->group(function () {
        Route::get('/traffic', [GeoApiController::class, 'trafficByCountry']);
        Route::get('/traffic/{countryCode}', [GeoApiController::class, 'trafficByCity']);
        Route::get('/map-data', [GeoApiController::class, 'mapData']);
        Route::get('/flows', [GeoApiController::class, 'trafficFlows']);
        Route::get('/device/{device}', [GeoApiController::class, 'deviceGeoTraffic']);
        Route::get('/status', [GeoApiController::class, 'databaseStatus']);
    });

    // Real-time Bandwidth API
    Route::prefix('realtime')->group(function () {
        Route::get('/summary', [RealtimeApiController::class, 'dashboardSummary']);
        Route::get('/devices', [RealtimeApiController::class, 'allDevicesBandwidth']);
        Route::get('/bandwidth/{device}', [RealtimeApiController::class, 'deviceBandwidth']);
        Route::get('/sparkline/{device}', [RealtimeApiController::class, 'sparklineData']);
        Route::get('/interfaces/{device}', [RealtimeApiController::class, 'interfaceBandwidth']);
        Route::get('/talkers', [RealtimeApiController::class, 'topTalkers']);
    });

    // Applications API
    Route::prefix('applications')->group(function () {
        Route::get('/categories', [ApplicationApiController::class, 'categories']);
        Route::get('/by-category', [ApplicationApiController::class, 'byCategory']);
        Route::get('/all', [ApplicationApiController::class, 'all']);
        Route::get('/top', [ApplicationApiController::class, 'top']);
        Route::get('/category-traffic', [ApplicationApiController::class, 'categoryTraffic']);
        Route::get('/trends/{application}', [ApplicationApiController::class, 'trends']);
        Route::get('/device/{device}', [ApplicationApiController::class, 'deviceApplications']);
    });
});