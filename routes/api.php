<?php

use Illuminate\Support\Facades\Route;
use Laravolt\Indonesia\Http\Controllers\ApiController;

Route::get('cities/{province}', [ApiController::class, 'cities']);
Route::get('districts/{city}', [ApiController::class, 'districts']);
Route::get('villages/{district}', [ApiController::class, 'villages']);
