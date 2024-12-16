<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/storage/{path?}', ImageController::class)->where([
    'path' => ".*\..+",
])->name('images');
