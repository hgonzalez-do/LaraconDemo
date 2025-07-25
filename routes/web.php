<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedisInfoController;
use App\Http\Controllers\PGInfoController;
use App\Http\Controllers\SpacesController;


Route::get('/', function () {return view('welcome');});
Route::get('/gradient', function () {return view('gradient');});
Route::get('/redis', [RedisInfoController::class, 'index']);
Route::get('/postgres', [PGInfoController::class, 'index']);
Route::get('/spaces', [SpacesController::class, 'index'])->name('spaces.index');
Route::get('/spaces', [SpacesController::class, 'index'])->name('s3.browser');
Route::get('/spaces/download', [SpacesController::class, 'download'])->name('s3.download');