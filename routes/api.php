<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubModuleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RefTableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'login']);

Route::middleware(['api','auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('dashboard', DashboardController::class);

    Route::apiResource('module', ModuleController::class);
    Route::apiResource('permission', PermissionController::class);
    Route::apiResource('role', RoleController::class);
    Route::apiResource('ref_table', RefTableController::class);
    Route::apiResource('user', UserController::class)->only('index','show');

    Route::post('role_permission', [RoleController::class,'updateRolePermission'])->name('role.role_permission');
    Route::get('navigation', [ModuleController::class,'index'])->name('navigation.index');
    Route::get('auth/details', [AuthController::class, 'authDetails'])->name('auth.details');

});

Route::get('testing', [UserController::class,'testingJasper']);

