<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\submitController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/reportResult/{testId}', [submitController::class, 'report']);
Route::post('/reportCompiler/{submissionId}', [submitController::class, 'reportCompile']);
Route::post('/reportCompiler/{submissionId}', [submitController::class, 'reportLinter']);
