<?php

use App\Http\Controllers\Api\CompressionController;
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

Route::prefix('files')
    ->name('files.')
    ->group(function () {
        Route::post('compress', [CompressionController::class, 'compress'])->name('compress');
        Route::get('download/{fileName}', [CompressionController::class, 'download'])->name('download');;
    });

