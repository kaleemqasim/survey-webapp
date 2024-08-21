<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SurveyController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'users', 'namespace' => 'Admin', 'as' => 'users.'], function(){
    Route::get('/', [UserController::class, 'index'])->name('index');
});

Route::group(['prefix' => 'surveys', 'namespace' => 'Admin', 'as' => 'surveys.'], function(){
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    Route::get('/create', [SurveyController::class, 'create'])->name('create');
    Route::post('/store', [SurveyController::class, 'store'])->name('store');
    Route::get('/{survey}/edit', [SurveyController::class, 'edit'])->name('edit');
    Route::put('/{survey}', [SurveyController::class, 'update'])->name('update');
    Route::delete('/{survey}', [SurveyController::class, 'destroy'])->name('destroy');
});
Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile.edit');
Route::post('/profile/update', [App\Http\Controllers\HomeController::class, 'update_profile'])->name('profile.update');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
