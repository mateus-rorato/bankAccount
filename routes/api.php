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

use App\Http\Controllers\ContaBancariaController;

Route::post('/contas', [ContaBancariaController::class, 'store']);
Route::post('/transfer', [ContaBancariaController::class, 'transfer']);
Route::get('/conta', [ContaBancariaController::class, 'index']);
Route::post('/agendar', [ContaBancariaController::class, 'scheduled']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
