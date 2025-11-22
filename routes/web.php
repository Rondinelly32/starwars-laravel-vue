<?php

use App\Http\Controllers\SWAPIController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/list/people', [SWAPIController::class, 'getPeopleList']);
//Route::get('/list/films', [SWAPIController::class, 'getFilmsList']);
//Route::get('/details/people/{$url}', [SWAPIController::class, 'getDetailsPeople']);
//Route::get('/details/films/{$url}', [SWAPIController::class, 'getDetailsFilm']);
Route::get('/{type}', [SWAPIController::class, 'list'])
    ->whereIn('type', ['people', 'films']);
Route::get('/{type}/{id}', [SWAPIController::class, 'details'])
    ->whereIn('type', ['people', 'films']);
Route::get('/stats', [SWAPIController::class, 'stats']);
