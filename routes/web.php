<?php

use App\Http\Controllers\HousingController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\AdminDashboardController;
use RealRashid\SweetAlert\Facades\Alert;

Auth::routes();

Route::get('/auth/login', function () {
    toast('Login Successful!', 'success', 'bottom-end')->autoClose(5000);
    return view('auth.login');
});
Route::get('/', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard')->middleware('auth');
Route::any('/webhook', [WebhookController::class, 'handle'])->name('webhook');
Route::any('/send-message', [DashboardController::class, 'sendTemplateMessage']);

Route::any('/send-receipt', [WebhookController::class, 'sendOnlineReceipt']);
Route::any('/resend-receipt', [WebhookController::class, 'resendReceipt']);
Route::post('/update-status', [WebhookController::class, 'updateStatus']);
Route::any('/add-amount', [HousingController::class, 'addAmount']);

Route::any('/update-utility', [DashboardController::class, 'saveData']);
Route::get('/get-housing-data', [HousingController::class, 'getHousingData']);
Route::get('/get-housing-bills', [HousingController::class, 'getHousingBills']);
Route::any('/update-housing-report', [HousingController::class, 'updateHousingReport']);

Route::get('/manage-housing/{housingId}', [AdminDashboardController::class, 'manageHousing'])->name('admin.manageHousing');
Route::prefix('superadmin')->middleware(['auth', 'role:0'])->group(function () { 
    Route::get('/create', [AdminController::class, 'showCreateForm'])->name('admin.create');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admins', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/import', [AdminController::class, 'import'])->name('admin.import');
    Route::get('/add-resident/{housingId}', [AdminController::class, 'addResident'])->name('admin.add');
    Route::post('/add-resident/{housingId}', [AdminController::class, 'saveResident'])->name('admin.saveResident');
    Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admins/{id}/update', [AdminController::class, 'update'])->name('admin.update');
    Route::any('/admins/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::any('/housing/{housingId}/{rowId}', [AdminController::class, 'deleteResident'])->name('admin.deleteResident');
    Route::get('/edit/{housingId}/{rowId}', [AdminController::class, 'editResident'])->name('admin.editResident');
    Route::post('/update/{housingId}/{rowId}', [AdminController::class, 'updateResident'])->name('admin.updateResident');
    Route::any('/housing/{housingId}', [AdminController::class, 'deleteHousing'])->name('admin.deleteHousing');
    Route::any('/convertjson', [AdminController::class, 'convertExcelToJson'])->name('convertjson');
    
    Route::get('/report', [AdminController::class, 'report'])->name('admin.report');

});


