<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\Web\IncidentController;
use App\Http\Controllers\Web\QueueController;

Route::get('/', function () {
    return redirect()->route('welcome');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['web','web.token'])->name('web.')->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('incident', [IncidentController::class, 'index'])->name('incident.index');
    Route::get('queue', [QueueController::class, 'index'])->name('queue.index');

    Route::get('generate_due_date', [IncidentController::class, 'generateDueDateIncident'])->name('incident.generate_duedate');

    Route::get('/logs', function () { return redirect('/log-viewer');})->name('logs');
});

Route::get('redirect', [TestingController::class, 'redirect'])->name('netiq.redirect');
Route::get('callback', [TestingController::class, 'callback'])->name('netiq.callback');


