<?php

use App\Http\Controllers\Api\DeviceApiController;
use App\Http\Controllers\Api\FlowApiController;
use App\Http\Controllers\Api\AlarmApiController;
use Illuminate\Support\Facades\Route;

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
});

// Alarms API
Route::prefix('alarms')->group(function () {
    Route::get('/', [AlarmApiController::class, 'index']);
    Route::get('/stats', [AlarmApiController::class, 'stats']);
    Route::get('/{alarm}', [AlarmApiController::class, 'show']);
    Route::post('/{alarm}/acknowledge', [AlarmApiController::class, 'acknowledge']);
    Route::post('/{alarm}/resolve', [AlarmApiController::class, 'resolve']);
});