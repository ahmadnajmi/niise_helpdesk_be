<?php

use App\Http\Controllers\MiniReportController;
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
use App\Http\Controllers\Admin\TestingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\IncidentDocumentController;
use App\Http\Controllers\LogViewerController;
use App\Http\Controllers\AdhocReportController;

Route::post('login', [AuthController::class, 'login']);
Route::get('logout-callback', [AuthController::class, 'logoutCallback']);
Route::post('verify_2fa', [AuthController::class, 'verifyToken']);
Route::post('auth/reset_password', [AuthController::class, 'resetPassword'])->name('auth.reset_password');

Route::middleware(['api','auth.check','auth:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('auth')->group(function () {
        Route::post('generate_qrcode', [AuthController::class, 'generateQrCode'])->name('generate_qrcode');
        Route::post('verify_code/{code}', [AuthController::class, 'verifyCode'])->name('verify_code');
        Route::get('token', [AuthController::class, 'authToken'])->name('token');
        Route::post('update_password', [AuthController::class, 'updatePassword'])->name('update_password');
        Route::get('details', [AuthController::class, 'getAuthDetails'])->name('details');
        Route::post('disable_two_factor', [AuthController::class, 'disableTwoFactor'])->name('disable_two_factor');
    });

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
    Route::apiResource('knowledge_base', KnowledgeBaseController::class);
    Route::apiResource('sla', SlaController::class);
    Route::apiResource('sla_template', SlaTemplateController::class);
    Route::apiResource('company', CompanyController::class);
    Route::apiResource('incident_solution', IncidentResolutionController::class);
    Route::apiResource('company_contract', CompanyContractController::class);
    Route::apiResource('incident', IncidentController::class);
    Route::apiResource('ref_table', RefTableController::class);
    Route::apiResource('dashboard', DashboardController::class)->only('index');
    Route::apiResource('branch', BranchController::class)->only('index','show');
    Route::apiResource('audit', AuditController::class)->only('index','show');
    Route::apiResource('workbasket', WorkbasketController::class)->only('index');
    Route::apiResource('incident_document', IncidentDocumentController::class)->only('destroy','show');
    Route::apiResource('adhoc_report', AdhocReportController::class);

    Route::get('incidents/download/{filename}', [IncidentController::class, 'downloadFile'])->name('incidents.download');
    Route::get('adhoc_report/download/{filename}', [AdhocReportController::class, 'downloadFile'])->name('adhoc_report.download');

    Route::get('dynamic_option', [GeneralController::class, 'dynamicOption'])->name('general.dynamic_option');
    Route::get('dashboard_graph', [DashboardController::class, 'dashboardGraph'])->name('dashboard.graph');

    Route::post('role_permission', [RoleController::class,'updateRolePermission'])->name('role.role_permission');
    Route::get('navigation', [ModuleController::class,'index'])->name('navigation.index');

    Route::get('ref_table_dropdown', [RefTableController::class, 'dropdownIndex'])->name('ref_table.dropdown');
    Route::get('ref_table_dropdown_value', [RefTableController::class, 'dropdownValueIndex'])->name('ref_table.dropdown_value');
    Route::get('user_search', [UserController::class, 'searchIcNo'])->name('user.search');
    Route::get('user_search_group', [UserController::class, 'searchIcNoContractor'])->name('user.search.group');

    Route::get('operating_time/{branch_id}/operating_branch', [OperatingTimeController::class, 'operantingTimeBranch']);
    Route::delete('operating_time/{branch_id}/operating_branch', [OperatingTimeController::class, 'operantingTimeBranchDelete']);

    Route::post('report/generate', [ReportController::class, 'generateReport'])->name('report.generate');
    Route::get('report', [ReportController::class, 'index'])->name('report.index');

    // Route::post('mini_report/generate', [MiniReportController::class, 'generate'])->name('mini_report.generate');

    Route::middleware(['admin.access'])->prefix('admin')->group(function () {
        Route::get('log-viewer', [LogViewerController::class, 'index'])->name('log-viewer.url');
        Route::get('incident/{incident}', [IncidentController::class, 'incidentInternal'])->name('incident.internal');
        Route::post('incident/{incident}/generate_end_date', [IncidentController::class, 'generateEndDate'])->name('incident.generate_end_date');
        Route::post('incident/{incident}/generate_penalty', [IncidentController::class, 'generatePenalty'])->name('incident.generate_penalty');
        Route::post('test_smtp', [TestingController::class, 'testEmail'])->name('testing.test_smtp');
        Route::post('test_imap', [TestingController::class, 'testImap'])->name('testing.test_imap');

    });
});

Route::prefix('iasset')->middleware('client.passport')->name('iasset.')->group(function () {
    Route::get('incidents/download_asset/{incident_no}', [IncidentController::class, 'downloadAssetFile'])->name('incidents.download_asset');
    Route::apiResource('branch', BranchController::class)->only('index','show');
    Route::apiResource('ref_table', RefTableController::class);
});

Route::prefix('idm')->middleware('client.passport')->name('idm.')->group(function () {
    Route::post('branch', [BranchController::class, 'idmCreateUpdate'])->name('branch.create');
});

Route::apiResource('dashboard-all', DashboardController::class)->only('index');
Route::get('dashboard_graph-all', [DashboardController::class, 'dashboardGraph'])->name('dashboard.graph-all');
Route::get('dynamic_option-all', [GeneralController::class, 'dynamicOption'])->name('general.dynamic_option-all');


Broadcast::routes(['middleware' => ['auth:api']]);
