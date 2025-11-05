<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\AlarmController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Device Management
Route::get('/devices/create', [App\Http\Controllers\DeviceController::class, 'create'])->name('devices.create');
Route::post('/devices', [App\Http\Controllers\DeviceController::class, 'store'])->name('devices.store');
Route::resource('devices', App\Http\Controllers\DeviceController::class)->except(['create', 'store']);

// Devices
Route::prefix('devices')->name('devices.')->group(function () {
    Route::get('/', [DeviceController::class, 'index'])->name('index');
    Route::get('/{device}', [DeviceController::class, 'show'])->name('show');
});

// Traffic
Route::prefix('traffic')->name('traffic.')->group(function () {
    Route::get('/', [TrafficController::class, 'index'])->name('index');
    Route::get('/{device}', [TrafficController::class, 'show'])->name('show');
});

// Alarms
Route::prefix('alarms')->name('alarms.')->group(function () {
    Route::get('/', [AlarmController::class, 'index'])->name('index');
    Route::get('/{alarm}', [AlarmController::class, 'show'])->name('show');
    Route::post('/{alarm}/acknowledge', [AlarmController::class, 'acknowledge'])->name('acknowledge');
    Route::post('/{alarm}/resolve', [AlarmController::class, 'resolve'])->name('resolve');
});

// Reports
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/traffic', [ReportController::class, 'trafficReport'])->name('traffic');
    Route::get('/devices', [ReportController::class, 'deviceReport'])->name('devices');
    Route::get('/export', [ReportController::class, 'exportReport'])->name('export');
});

// Settings
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('index');
    Route::put('/', [App\Http\Controllers\SettingsController::class, 'update'])->name('update');
});