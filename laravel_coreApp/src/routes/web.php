<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\indexController;
use App\Http\Controllers\submitController;
use App\Http\Controllers\submitListController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [indexController::class, 'viewIndex']);
Route::get('/assignment/{assignmentId}', [submitController::class, 'view']);
Route::post('/assignment/{assignmentId}', [submitController::class, 'submit']);
Route::get('/submissions', [submitListController::class, 'viewPage']);
Route::get('/submissions/{submissionId}', [submitListController::class, 'viewDetail'])->name('submissionDetail');
