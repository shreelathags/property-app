<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\PropertyController;
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

Route::post('/agents', [AgentController::class, 'create']);
Route::get('/agents/search', [AgentController::class, 'search']);
Route::get('/agents/summary', [AgentController::class, 'summary']);
Route::get('/agents/{agent}', [AgentController::class, 'get']);
Route::post('/agents/{agent}/properties/{property}', [AgentController::class, 'addProperty']);

Route::get('/properties/search', [PropertyController::class, 'search']);
