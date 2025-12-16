<?php

use App\Http\Controllers\Api\DeviceApiController;
use App\Http\Controllers\Api\FlowApiController;
use App\Http\Controllers\Api\AlarmApiController;
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
});