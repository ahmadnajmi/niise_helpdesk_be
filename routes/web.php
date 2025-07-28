<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware('web')->group(function () {
    Route::post('login', [AuthController::class, 'loginweb'])->name('loginweb');

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logoutWeb'])->name('logoutweb');
        Route::get('niise/am', [AuthController::class, 'loginAssetManagement'])->name('niise.asset_management');
        Route::get('niise/hd', [AuthController::class, 'loginHelpDesk'])->name('niise.helpdesk');
        Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    });

});

    // Route::get('callback', [AuthController::class, 'dashboard'])->name('dashboard.testing');
    Route::get('redirect', [TestingController::class, 'redirect'])->name('netiq.redirect');
    Route::get('callback', [TestingController::class, 'callback'])->name('netiq.callback');

require __DIR__.'/auth.php';

