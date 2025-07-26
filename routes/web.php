<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedisInfoController;
use App\Http\Controllers\PGInfoController;
use App\Http\Controllers\SpacesController;
use App\Http\Controllers\DatabaseController;

Route::get('/', function () {return view('welcome');});
Route::get('/gradient', function () {return view('gradient');});
Route::get('/redis', [RedisInfoController::class, 'index']);
Route::get('/spaces', [SpacesController::class, 'index'])->name('spaces.index');
Route::get('/spaces', [SpacesController::class, 'index'])->name('s3.browser');
Route::get('/spaces/download', [SpacesController::class, 'download'])->name('s3.download');
Route::post('/spaces/upload', [SpacesController::class, 'upload'])->name('s3.upload');
Route::post('/spaces/create-folder', [SpacesController::class, 'createFolder'])->name('s3.create-folder');
Route::delete('/spaces/delete', [SpacesController::class, 'delete'])->name('s3.delete');
Route::get('/database/tables', [DatabaseController::class, 'tables'])->name('database.tables');
Route::get('/database/table/{table}', [DatabaseController::class, 'showTable'])->name('database.table.show');
Route::get('/database/table/{table}/data', [DatabaseController::class, 'browseTableData'])->name('database.table.data');
Route::get('/database/stats', [DatabaseController::class, 'stats'])->name('database.stats');