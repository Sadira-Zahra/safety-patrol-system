<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\DeptPicController;
use App\Http\Controllers\FindingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\DeptManagerController;
use App\Http\Controllers\SafetyAdminController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\LaporanFindingController;
use App\Http\Controllers\SafetyPatrollerController;

// Route Welcome (Public)
Route::get('/', [AuthController::class, 'welcome'])->name('welcome');

// Route Login (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Change Password
Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password.index');
Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');


// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // ==========================================
    // FINDINGS ROUTES (TEMUAN)
    // ==========================================
    Route::prefix('findings')->group(function () {
        Route::get('/', [FindingController::class, 'index'])->name('findings.index');
        Route::post('/', [FindingController::class, 'store'])->name('findings.store');
        Route::get('/{finding}', [FindingController::class, 'show'])->name('findings.show');
        Route::delete('/{finding}', [FindingController::class, 'destroy'])->name('findings.destroy');
        
        // Safety Admin actions
        Route::put('/{finding}/verify', [FindingController::class, 'verify'])->name('findings.verify');
        Route::post('/{finding}/close', [FindingController::class, 'close'])->name('findings.close');
        
        // PIC Departemen actions
        Route::post('/{finding}/action', [FindingController::class, 'updateActionPlan'])->name('findings.action');
    });

    // API Helper untuk AJAX
    Route::get('/api/users-by-department/{departmentId}', function($departmentId) {
        $role = request()->query('role');
        return \App\Models\User::where('department_id', $departmentId)
            ->where('role', $role)
            ->select('id', 'name', 'nik')
            ->get();
    });

    // Master Data Routes (Master Master)
    Route::prefix('master_master')->group(function () {
        Route::get('departemen', [DepartemenController::class, 'index'])->name('departemens.index');
        Route::post('departemen', [DepartemenController::class, 'store'])->name('departemens.store');
        Route::get('departemen/{departemen}', [DepartemenController::class, 'show'])->name('departemens.show');
        Route::put('departemen/{departemen}', [DepartemenController::class, 'update'])->name('departemens.update');
        Route::delete('departemen/{departemen}', [DepartemenController::class, 'destroy'])->name('departemens.destroy');

        Route::get('kategori', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('kategori', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('kategori/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::put('kategori/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('kategori/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('grade', [GradeController::class, 'index'])->name('grades.index');
        Route::post('grade', [GradeController::class, 'store'])->name('grades.store');
        Route::get('grade/{grade}', [GradeController::class, 'show'])->name('grades.show');
        Route::put('grade/{grade}', [GradeController::class, 'update'])->name('grades.update');
        Route::delete('grade/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');

        
    });

    // User Management Routes (Master User)
    Route::prefix('master_user')->group(function () {
        Route::get('administrator', [AdministratorController::class, 'index'])->name('administrators.index');
        Route::post('administrator', [AdministratorController::class, 'store'])->name('administrators.store');
        Route::get('administrator/{administrator}', [AdministratorController::class, 'show'])->name('administrators.show');
        Route::put('administrator/{administrator}', [AdministratorController::class, 'update'])->name('administrators.update');
        Route::delete('administrator/{administrator}', [AdministratorController::class, 'destroy'])->name('administrators.destroy');

        Route::get('safety_patroller', [SafetyPatrollerController::class, 'index'])->name('safety-patrollers.index');
        Route::post('safety_patroller', [SafetyPatrollerController::class, 'store'])->name('safety-patrollers.store');
        Route::get('safety_patroller/{safetyPatroller}', [SafetyPatrollerController::class, 'show'])->name('safety-patrollers.show');
        Route::put('safety_patroller/{safetyPatroller}', [SafetyPatrollerController::class, 'update'])->name('safety-patrollers.update');
        Route::delete('safety_patroller/{safetyPatroller}', [SafetyPatrollerController::class, 'destroy'])->name('safety-patrollers.destroy');

        Route::get('safety_admin', [SafetyAdminController::class, 'index'])->name('safety-admins.index');
        Route::post('safety_admin', [SafetyAdminController::class, 'store'])->name('safety-admins.store');
        Route::get('safety_admin/{safetyAdmin}', [SafetyAdminController::class, 'show'])->name('safety-admins.show');
        Route::put('safety_admin/{safetyAdmin}', [SafetyAdminController::class, 'update'])->name('safety-admins.update');
        Route::delete('safety_admin/{safetyAdmin}', [SafetyAdminController::class, 'destroy'])->name('safety-admins.destroy');

        Route::get('pic_departemen', [DeptPicController::class, 'index'])->name('dept-pics.index');
        Route::post('pic_departemen', [DeptPicController::class, 'store'])->name('dept-pics.store');
        Route::get('pic_departemen/{deptPic}', [DeptPicController::class, 'show'])->name('dept-pics.show');
        Route::put('pic_departemen/{deptPic}', [DeptPicController::class, 'update'])->name('dept-pics.update');
        Route::delete('pic_departemen/{deptPic}', [DeptPicController::class, 'destroy'])->name('dept-pics.destroy');

        Route::get('manager', [DeptManagerController::class, 'index'])->name('dept-managers.index');
        Route::post('manager', [DeptManagerController::class, 'store'])->name('dept-managers.store');
        Route::get('manager/{deptManager}', [DeptManagerController::class, 'show'])->name('dept-managers.show');
        Route::put('manager/{deptManager}', [DeptManagerController::class, 'update'])->name('dept-managers.update');
        Route::delete('manager/{deptManager}', [DeptManagerController::class, 'destroy'])->name('dept-managers.destroy');
    });
});

// ==========================================
    // LAPORAN ROUTES (HARUS DI ATAS FINDINGS DETAIL!)
    // ==========================================
    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanFindingController::class, 'index'])->name('laporan.index');
        Route::get('/export', [LaporanFindingController::class, 'exportExcel'])->name('laporan.export'); // Export HARUS di atas {finding}
        Route::get('/{finding}', [LaporanFindingController::class, 'show'])->name('laporan.show');
    });
    
// Finding Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('findings', FindingController::class);
    Route::put('findings/{finding}/verify', [FindingController::class, 'verify'])->name('findings.verify');
    Route::post('findings/{finding}/action', [FindingController::class, 'updateActionPlan'])->name('findings.action');
    Route::post('findings/{finding}/close', [FindingController::class, 'close'])->name('findings.close');
    
    
    // API untuk get users by department
    Route::get('api/users-by-department/{departemen_id}', function($departemen_id) {
        $role = request('role');
        return \App\Models\User::where('departemen_id', $departemen_id)
            ->where('role', $role)
            ->get(['id', 'name', 'nik']);
    });
});

// routes/web.php
Route::post('/findings/{finding}/action-plan', [FindingController::class, 'updateActionPlan'])
  ->name('findings.actionPlan');

  // routes/web.php
Route::post('/findings/{finding}/action-plan', [FindingController::class, 'updateActionPlan'])
  ->name('findings.actionPlan'); // PIC kirim/revisi

Route::post('/findings/{finding}/close', [FindingController::class, 'close'])
  ->name('findings.close'); // Admin approve/return

  Route::middleware(['auth'])->group(function () {
    Route::get('/findings', [FindingController::class, 'index'])->name('findings.index');
    Route::post('/findings', [FindingController::class, 'store'])->name('findings.store');
    Route::get('/findings/{finding}', [FindingController::class, 'show'])->name('findings.show');
    Route::put('/findings/{finding}', [FindingController::class, 'update'])->name('findings.update');
    Route::put('/findings/{finding}/verify', [FindingController::class, 'verify'])->name('findings.verify');
    Route::post('/findings/{finding}/action', [FindingController::class, 'updateAction'])->name('findings.action');
    Route::post('/findings/{finding}/close', [FindingController::class, 'close'])->name('findings.close');
    Route::delete('/findings/{finding}', [FindingController::class, 'destroy'])->name('findings.destroy');
});




