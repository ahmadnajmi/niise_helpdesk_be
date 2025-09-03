<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\RefTableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubModuleController;
use App\Http\Controllers\ActionCodeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\WorkbasketController;
use App\Http\Controllers\SlaTemplateController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\OperatingTimeController;
use App\Http\Controllers\CompanyContractController;
use App\Http\Controllers\IncidentResolutionController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['api','auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::resource('dashboard', DashboardController::class);

    Route::get('/workbasket', [WorkbasketController::class, 'index'])->name('workbasket.index');

    Route::apiResource('module', ModuleController::class);
    Route::apiResource('permission', PermissionController::class);
    Route::apiResource('role', RoleController::class);
    Route::apiResource('action_code', ActionCodeController::class);
    Route::apiResource('user', UserController::class);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('group_management', GroupController::class);
    Route::apiResource('calendar', CalendarController::class);
    Route::apiResource('email_template', EmailTemplateController::class);
    Route::apiResource('operating_time', OperatingTimeController::class);
    Route::apiResource('audit', AuditController::class)->only('index','show');
    Route::apiResource('knowledge_base', KnowledgeBaseController::class);
    Route::apiResource('sla', SlaController::class);
    Route::apiResource('sla_template', SlaTemplateController::class);
    Route::apiResource('company', CompanyController::class);
    Route::apiResource('incident_solution', IncidentResolutionController::class);
    Route::apiResource('company_contract', CompanyContractController::class);
    Route::apiResource('incident', IncidentController::class);
    Route::apiResource('branch', BranchController::class)->only('index','show');
    Route::apiResource('ref_table', RefTableController::class);

    Route::get('incidents/download/{filename}', [IncidentController::class, 'downloadFile'])->name('incidents.download');

    Route::get('dynamic_option', [GeneralController::class, 'dynamicOption'])->name('general.dynamic_option');

    Route::post('role_permission', [RoleController::class,'updateRolePermission'])->name('role.role_permission');
    Route::get('navigation', [ModuleController::class,'index'])->name('navigation.index');
    Route::get('auth/details', [AuthController::class, 'authDetails'])->name('auth.details');
    Route::get('ref_table_dropdown', [RefTableController::class, 'dropdownIndex'])->name('ref_table.dropdown');
    Route::get('ref_table_dropdown_value', [RefTableController::class, 'dropdownValueIndex'])->name('ref_table.dropdown_value');
    Route::get('user_search', [UserController::class, 'searchIcNo'])->name('user.search');
    Route::get('user_search_group', [UserController::class, 'searchIcNoContractor'])->name('user.search.group');

    Route::post('role_permission', [RoleController::class,'updateRolePermission'])->name('role.role_permission');

    Route::get('operating_time/{branch_id}/operating_branch', [OperatingTimeController::class, 'operantingTimeBranch']);
    Route::delete('operating_time/{branch_id}/operating_branch', [OperatingTimeController::class, 'operantingTimeBranchDelete']);
});

Route::get('testing', [UserController::class,'testingJasper']);

Route::prefix('iasset')->middleware('client.passport')->name('iasset.')->group(function () {
    Route::get('incidents/download_asset/{incident_no}', [IncidentController::class, 'downloadAssetFile'])->name('incidents.download_asset');
    Route::apiResource('branch', BranchController::class)->only('index','show');
    Route::apiResource('ref_table', RefTableController::class);
});

// Route::get('workbasket', [WorkbasketController::class, 'index'])->name('workbasket.index');
// Route::middleware('auth')->get('workbasket', [WorkbasketController::class, 'index'])->name('workbasket.index');
