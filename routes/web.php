<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\AlarmController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public route - redirect to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// All authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Device Management
    Route::prefix('devices')->name('devices.')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('index');
        Route::get('/create', [DeviceController::class, 'create'])->name('create');
        Route::post('/', [DeviceController::class, 'store'])->name('store');
        Route::get('/{device}', [DeviceController::class, 'show'])->name('show');
        Route::get('/{device}/edit', [DeviceController::class, 'edit'])->name('edit');
        Route::put('/{device}', [DeviceController::class, 'update'])->name('update');
        Route::delete('/{device}', [DeviceController::class, 'destroy'])->name('destroy');

        // SSH Routes
        Route::post('/{device}/ssh/test', [DeviceController::class, 'testSshConnection'])->name('ssh.test');
        Route::post('/{device}/ssh/push-config', [DeviceController::class, 'pushNetFlowConfig'])->name('ssh.push');
        Route::get('/{device}/ssh/config', [DeviceController::class, 'getNetFlowConfig'])->name('ssh.config');

        // SNMP Routes
        Route::post('/{device}/snmp/test', [DeviceController::class, 'testSnmpConnection'])->name('snmp.test');
        Route::post('/{device}/snmp/poll', [DeviceController::class, 'pollSnmpDevice'])->name('snmp.poll');
        Route::post('/{device}/snmp/poll-system', [DeviceController::class, 'pollSnmpSystemInfo'])->name('snmp.poll-system');
        Route::post('/{device}/snmp/poll-interfaces', [DeviceController::class, 'pollSnmpInterfaces'])->name('snmp.poll-interfaces');
        Route::get('/{device}/snmp/status', [DeviceController::class, 'getSnmpStatus'])->name('snmp.status');
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
        Route::get('/talkers', [ReportController::class, 'talkersReport'])->name('talkers');
        Route::get('/export', [ReportController::class, 'exportReport'])->name('export');
        // PDF exports
        Route::get('/traffic/pdf', [ReportController::class, 'trafficReportPdf'])->name('traffic.pdf');
        Route::get('/devices/pdf', [ReportController::class, 'deviceReportPdf'])->name('devices.pdf');
        Route::get('/talkers/pdf', [ReportController::class, 'talkersReportPdf'])->name('talkers.pdf');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes
require __DIR__.'/auth.php';
