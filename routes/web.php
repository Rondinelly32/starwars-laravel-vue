<?php

use App\Http\Controllers\SwApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/{type}', [SwApiController::class, 'list'])
    ->whereIn('type', ['people', 'films']);
Route::get('/{type}/{id}', [SwApiController::class, 'details'])
    ->whereIn('type', ['people', 'films']);
Route::get('/stats', [SwApiController::class, 'stats']);
