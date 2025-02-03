<?php

// use App\Http\Controllers\ActionCodeController;
// use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\BranchController;
// use App\Http\Controllers\BusinessHourController;
// use App\Http\Controllers\CallerController;
// use App\Http\Controllers\CaseStatusController;
// use App\Http\Controllers\ConfigCodeController;
// use App\Http\Controllers\ContactPersonController;
// use App\Http\Controllers\CustomerController;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\DocumentTypeController;
// use App\Http\Controllers\GlobalStatusController;
// use App\Http\Controllers\GroupController;
// use App\Http\Controllers\HolidayController;
// use App\Http\Controllers\IncidentController;
// use App\Http\Controllers\KnownErrorController;
// use App\Http\Controllers\MailTemplateController;
// use App\Http\Controllers\ReportController;
// use App\Http\Controllers\RoleController;
// use App\Http\Controllers\ServiceCategoriesController;
// use App\Http\Controllers\ServiceLevelController;
// use App\Http\Controllers\ServiceLevelTemplateController;
// use App\Http\Controllers\SupplierController;
// use App\Http\Controllers\UserController;
// use App\Http\Controllers\VendorController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubModuleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::middleware(["api"])->group(function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);

    // // # 1. Dashboard
    // Route::resource('dashboard', DashboardController::class);

    // // # 2. Administration
    // // --- # People
    // Route::resource('caller', CallerController::class);
    // Route::resource('contact-person', ContactPersonController::class);
    // Route::resource('group', GroupController::class);

    // // --- # Organizations
    // Route::resource('customer', CustomerController::class);
    // Route::resource('branch', BranchController::class);
    // Route::resource('supplier', SupplierController::class);
    // Route::resource('vendor', VendorController::class);

    // // --- # Business Ops
    // Route::resource('holiday', HolidayController::class);
    // Route::resource('business-hour', BusinessHourController::class);
    // Route::resource('service-category', ServiceCategoriesController::class);
    // Route::get('read-service-category/{id}', [ServiceCategoriesController::class,'show']);
    // Route::get('edit-service-category', [ServiceCategoriesController::class,'edit']);
    // Route::delete('delete-service-category/{id}', [ServiceCategoriesController::class,'destroy']);
    // Route::resource('case-status', CaseStatusController::class);
    // Route::get('read-action-code/{id}', [ActionCodeController::class,'show']);
    // Route::resource('action-code', ActionCodeController::class);
    // Route::resource('document-type', DocumentTypeController::class);

    // // # 3. Incidents
    // // --- # Listings
    // Route::resource('incident', IncidentController::class);

    // // --- # Known Errors
    // Route::resource('known-error', KnownErrorController::class);
    // Route::get('read-known-error/{id}', [KnownErrorController::class,'show']);
    // Route::get('edit-known-error', [KnownErrorController::class,'edit']);
    // Route::delete('delete-known-error/{id}', [KnownErrorController::class,'destroy']);
    // // # 4. Service Levels
    // // --- # Listings
    // Route::resource('service-level', ServiceLevelController::class);
    // Route::post('getSlaIndex', [ServiceLevelController::class,'index']);

    // // --- # Templates
    // Route::resource('service-level-template', ServiceLevelTemplateController::class);
    // Route::get('read-service-level-template/{id}', [ServiceLevelTemplateController::class,'show']);

    // // # 5. Reporting
    // Route::resource('report', ReportController::class);

    // // # 6. Configuration
    // // --- # Audit Trails
    // Route::resource('audit-trail', AuditTrailController::class);

    // // --- # Roles & Users
    // Route::resource('user', UserController::class);
    // Route::resource('role', RoleController::class);

    // // --- # Others
    // Route::resource('config-code', ConfigCodeController::class);
    // Route::resource('global-status', GlobalStatusController::class);
    // Route::resource('mail-template', MailTemplateController::class);

    Route::apiResource('module', ModuleController::class);
    Route::apiResource('sub_module', SubModuleController::class);
    Route::apiResource('permission', PermissionController::class);
    Route::apiResource('role', RoleController::class);

});

