<?php

use App\Http\Controllers\RfidTapController;
use App\Http\Controllers\MonitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/rfid-tap', [RfidTapController::class, 'tap'])->middleware('throttle:60,1');
Route::get('/monitor-terakhir', [MonitorController::class, 'terakhir'])->middleware('throttle:120,1');
