<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Rosary Prayer
Route::match(['get', 'post'], '/categories', [App\Http\Controllers\Api\RosaryPrayerController::class, 'categories']);
Route::post('/sub-categories', [App\Http\Controllers\Api\RosaryPrayerController::class, 'subCategories']);
Route::post('/categories/prayers', [App\Http\Controllers\Api\RosaryPrayerController::class, 'categoryPrayers']);
Route::post('/prayers/detail', [App\Http\Controllers\Api\RosaryPrayerController::class, 'prayerDetail']);




?>