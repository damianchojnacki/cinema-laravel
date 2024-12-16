<?php

use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\ShowingController;

Route::apiResource('movies', MovieController::class)->only(['index', 'show']);
Route::apiResource('movies.showings', ShowingController::class)->only(['index', 'show']);
Route::apiResource('showings.reservations', ReservationController::class)->only(['store']);
Route::get('reservations/{reservation:token}', [ReservationController::class, 'show'])->name('reservations.show');
Route::get('reservations/{reservation:token}/qr', [ReservationController::class, 'qr'])->name('reservations.qr');
