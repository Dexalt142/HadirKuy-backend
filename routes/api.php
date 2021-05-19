<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PertemuanController;
use App\Http\Controllers\PresensiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function() {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware(['api.auth']);
    Route::get('user', [AuthController::class, 'getUser'])->middleware(['api.auth']);
});

Route::get('pertemuan/{id}', [PertemuanController::class, 'getPertemuan']);
Route::post('presensi', [PresensiController::class, 'createPresensi']);

Route::group(['prefix' => 'guru'], function() {

});

Route::any('{any}', function () {
    return response()->json([
        'status' => 400,
        'message' => 'Bad request'
    ], 400);
})->where('any', '.*');