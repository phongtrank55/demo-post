<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestSocialController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\WatermarkController;
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

Route::get('reports', [ReportController::class, 'index'])->name('report.index');
Route::get('reports/export-word', [ReportController::class, 'exportWord'])->name('report.export-word');
Route::get('reports/export-word-2', [ReportController::class, 'exportWord2'])->name('report.export-word-2');

Route::get('stories', [StoryController::class, 'index']);
Route::get('stories/{id}', [StoryController::class, 'show'])->name('stories.show');

Route::get('watermark', [WatermarkController::class, 'index']);
Route::get('watermark/run', [WatermarkController::class, 'run']);

//route cho media
Route::name('media.')->prefix('media')->group(function() {
    Route::get('library', [MediaController::class, 'library'])->name('library');
    Route::post('store', [MediaController::class, 'store'])->name('store');
    Route::post('update', [MediaController::class, 'update'])->name('update');
    Route::post('search', [MediaController::class, 'search'])->name('search');
});
