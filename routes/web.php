<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\User\SurveyController as UserSurveyController;
use App\Http\Controllers\EarningsController;

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

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function(){
    Route::group(['prefix' => 'surveys', 'as' => 'surveys.'], function(){
        Route::get('/', [SurveyController::class, 'index'])->name('index');
        Route::get('/create', [SurveyController::class, 'create'])->name('create');
        Route::post('/store', [SurveyController::class, 'store'])->name('store');
        Route::get('/{survey}/edit', [SurveyController::class, 'edit'])->name('edit');
        Route::put('/{survey}', [SurveyController::class, 'update'])->name('update');
        Route::delete('/{survey}', [SurveyController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'withdrawals', 'as' => 'withdrawals.'], function(){
        Route::get('/withdrawal-requests', [UserController::class, 'withdraw_requests'])->name('withdrawal_requests');
        Route::get('/{userId}/bank-details', [UserController::class, 'getBankDetails']);
        Route::put('/{withdrawal}', [UserController::class, 'updateWithdrawalStatus'])->name('update');
    });

    Route::get('users', [UserController::class, 'index'])->name('users.index');
});

Route::group(['prefix' => 'user', 'namespace' => 'User', 'as' => 'user.'], function(){
    Route::get('/list_surveys', [UserSurveyController::class, 'index'])->name('available_surveys');
    Route::get('/{id}/take_survey', [UserSurveyController::class, 'take_survey'])->name('take_survey');
    Route::post('/{id}/submit_survey', [UserSurveyController::class, 'submit_survey'])->name('submit_survey');
    Route::get('/earnings', [EarningsController::class, 'index'])->name('earnings');
    Route::post('/withdraw', [EarningsController::class, 'requestWithdrawal'])->name('withdraw');
});

Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile.edit');
Route::post('/profile/update', [App\Http\Controllers\HomeController::class, 'update_profile'])->name('profile.update');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
