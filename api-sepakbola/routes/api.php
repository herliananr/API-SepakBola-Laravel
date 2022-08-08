<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('kotamarkas', [Controllers\KotaMarkasController::class, 'index']);
Route::post('kotamarkas', [Controllers\KotaMarkasController::class, 'store']);
Route::get('kotamarkas/{param}', [Controllers\KotaMarkasController::class, 'show']);
Route::put('kotamarkas/{param}', [Controllers\KotaMarkasController::class, 'update']);
Route::delete('kotamarkas/{param}', [Controllers\KotaMarkasController::class, 'delete']);

Route::get('tim', [Controllers\TimController::class, 'index']);
Route::post('tim', [Controllers\TimController::class, 'store']);
Route::get('tim/{param}', [Controllers\TimController::class, 'show']);
Route::post('tim/{param}', [Controllers\TimController::class, 'update']); //menggunakan post agar bisa melakukan pengiriman gambar logo
Route::delete('tim/{param}', [Controllers\TimController::class, 'delete']);

Route::get('pemain', [Controllers\PemainController::class, 'index']);
Route::post('pemain', [Controllers\PemainController::class, 'store']);
Route::get('pemain/{param}', [Controllers\PemainController::class, 'show']);
Route::put('pemain/{param}', [Controllers\PemainController::class, 'update']);
Route::delete('pemain/{param}', [Controllers\PemainController::class, 'delete']);

Route::get('jadwalpertandingan', [Controllers\JadwalPertandinganController::class, 'index']);
Route::post('jadwalpertandingan', [Controllers\JadwalPertandinganController::class, 'store']);
Route::get('jadwalpertandingan/{param}', [Controllers\JadwalPertandinganController::class, 'show']);
Route::put('jadwalpertandingan/{param}', [Controllers\JadwalPertandinganController::class, 'update']);
Route::delete('jadwalpertandingan/{param}', [Controllers\JadwalPertandinganController::class, 'delete']);

Route::get('pencetakgolpertandingan', [Controllers\PencetakGolPertandinganController::class, 'index']);
Route::post('pencetakgolpertandingan', [Controllers\PencetakGolPertandinganController::class, 'store']);
Route::get('pencetakgolpertandingan/{param}', [Controllers\PencetakGolPertandinganController::class, 'show']);
Route::put('pencetakgolpertandingan/{param}', [Controllers\PencetakGolPertandinganController::class, 'update']);
Route::delete('pencetakgolpertandingan/{param}', [Controllers\PencetakGolPertandinganController::class, 'delete']);

Route::get('hasilpertandingan', [Controllers\HasilPertandinganController::class, 'index']);
Route::get('hasilpertandingan/{param}', [Controllers\HasilPertandinganController::class, 'show']);

Route::get('reporthasilpertandingan', [Controllers\ReportHasilPertandinganController::class, 'index']);
Route::get('reporthasilpertandingan/{param}', [Controllers\ReportHasilPertandinganController::class, 'show']);

