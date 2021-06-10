<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestSocialController;
use App\Http\Controllers\ReportController;
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

Route::get('/', [HomeController::class, 'index']);

Route::get('test-social', [TestSocialController::class, 'index'])->name('test_social');

Route::get('/social/{provider}', [LoginController::class, 'redirect'])->name('auth.social');
Route::get('/callback/{provider}', [LoginController::class, 'callback'])->name('auth.social.callback');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('reports', [ReportController::class, 'index']);
