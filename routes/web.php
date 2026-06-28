<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\WorkShiftController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DepartmentController;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});
Route::get('/track-order', [OrderController::class, 'trackPublic'])->name('orders.track-public');

// Custom Order Form (Landing Page Integration)
Route::get('/custom-order', [CustomOrderController::class, 'showForm'])->name('custom-order.form');
Route::post('/custom-order', [CustomOrderController::class, 'submitForm'])->name('custom-order.submit');
Route::get('/custom-order/success', [CustomOrderController::class, 'success'])->name('custom-order.success');

// Authentication Routes (MVC)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Administrative Dashboard (MVC, RBAC Guarded)
Route::middleware(['auth', 'role:Super Admin,Owner,Sales,Production,HRD,Finance'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('dashboard.role');
    Route::resource('packages', PackageController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('offices', OfficeController::class);
    Route::resource('work-shifts', WorkShiftController::class);
    Route::resource('employees', EmployeeController::class)->except(['show']);
    Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/players', [OrderController::class, 'updatePlayers'])->name('orders.update-players');
    Route::post('orders/{order}/designs', [OrderController::class, 'uploadDesign'])->name('orders.upload-design');
    Route::post('orders/{order}/transition', [OrderController::class, 'transitionStage'])->name('orders.transition-stage');
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('settings', [ProfileController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [ProfileController::class, 'update'])->name('settings.update');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('positions', PositionController::class)->except(['show']);
        Route::resource('departments', DepartmentController::class)->except(['show']);
    });
});

// Employee Dashboard (accessible to all authenticated staff)
Route::middleware(['auth'])->group(function () {
    Route::get('employee/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
});

Route::post('/attendance', [AttendanceController::class, 'clock'])->name('attendance.clock');

