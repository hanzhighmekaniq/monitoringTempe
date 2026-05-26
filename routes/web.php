<?php

use App\Http\Controllers\Dht22Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\AktuatorController;
use App\Models\SensorData;
use App\Models\Control;

Route::get('/navbar', function () {
    return view('layouts.navbar');
});

Route::get('/dashboard', function () {

    $latest = SensorData::latest()->first();

    $datas = SensorData::latest()
    ->take(10)
    ->get()
    ->reverse();

    return view('dashboard', compact(
        'latest',
        'datas'
    ));

    

});

Route::get('/riwayat', function () {

    $datas = SensorData::latest()
                ->take(50)
                ->get();

    return view('riwayat', compact('datas'));

});

Route::get('/grafik', function () {

    $datas = SensorData::latest()
                ->take(20)
                ->get()
                ->reverse();

    return view('grafik', compact('datas'));

});

Route::get('/update-data/{tmp}/{hmd}', [Dht22Controller::class, 'updateData']);

// Route::get('/get-data', [Dht22Controller::class, 'getData']);

Route::post('/sensor', [SensorController::class, 'store']);

Route::get('/get-data', function () {

    return \App\Models\SensorData::latest()
        ->take(20)
        ->get()
        ->reverse()
        ->values();

});

// ======================
// FAN MODE
// ======================

Route::post('/fan/{mode}',
    [AktuatorController::class,
    'updateFanMode']
);

Route::get('/fan-mode',
    [AktuatorController::class,
    'getFanMode']
);

// ======================
// HEATER + SPREAD MODE
// ======================

Route::post('/heater-spread/{mode}',
    [AktuatorController::class,
    'updateHeaterSpreadMode']
);

Route::get('/heater-spread-mode',
    [AktuatorController::class,
    'getHeaterSpreadMode']
);