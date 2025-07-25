<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedisInfoController;


Route::get('/', function () {return view('welcome');});

Route::get('/redis-info', [RedisInfoController::class, 'index']);
