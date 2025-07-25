<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedisInfoController;
use App\Http\Controllers\PGInfoController;
use App\Http\Controllers\SpacesController;


Route::get('/', function () {return view('welcome');});

Route::get('/redis', [RedisInfoController::class, 'index']);
Route::get('/postgres', [PGInfoController::class, 'index']);
Route::get('/spaces', [SpacesController::class, 'index']);
