<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\LoginUserController;
use App\Http\Controllers\ChangePassController;
use App\Http\Controllers\ShowUserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/verification', [RegisteredUserController::class, 'verify']);
Route::post('/register', [RegisteredUserController::class, 'register']);
Route::post('/login', [LoginUserController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('change-password', [ChangePassController::class, 'changePassword']);
});
Route::middleware('auth:sanctum')->get('/user', ShowUserController::class);
