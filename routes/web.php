<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SummerNoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;

use Illuminate\Http\Request;

Auth::routes();

// الصفحة الرئيسية
Route::get('/', [FrontendController::class, 'index'])->name('home');

// Routes بعد تسجيل الدخول
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('user', UserController::class)->middleware('permission:users.view|users.create|users.edit|users.delete');

    // Profile page & updates
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('profile-update/{user}', [ProfileController::class, 'profileUpdate'])->name('user.profile.update');
    Route::patch('user/pasword-update/{user}', [UserController::class, 'password_update'])->name('user.password.update');
    Route::put('user/profile-pic/{user}', [UserController::class, 'updateProfileImage'])->name('user.profile.image.update');
    Route::patch('delete-profile-image/{user}', [UserController::class, 'deleteProfileImage'])->name('delete.profile.image');
    
    // Trash & restore for users
    Route::get('user-trash', [UserController::class, 'trashView'])->name('user.trash');
    Route::get('user-restore/{id}', [UserController::class, 'restore'])->name('user.restore');
    Route::delete('user-delete/{id}', [UserController::class, 'force_delete'])->name('user.force.delete');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('setting')->middleware('permission:setting.update');
    Route::post('settings/{setting}', [SettingController::class, 'update'])->name('setting.update');

    // Categories
    Route::resource('category', CategoryController::class)->middleware('permission:categories.view|categories.create|categories.edit|categories.delete');

    // Services
    Route::resource('service', ServiceController::class)->middleware('permission:services.view|services.create|services.edit|services.delete');
    Route::get('service-trash', [ServiceController::class, 'trashView'])->name('service.trash');
    Route::get('service-restore/{id}', [ServiceController::class, 'restore'])->name('service.restore');
    Route::delete('service-delete/{id}', [ServiceController::class, 'force_delete'])->name('service.force.delete');

    // Summernote
    Route::post('summernote', [SummerNoteController::class, 'summerUpload'])->name('summer.upload.image');
    Route::post('summernote/delete', [SummerNoteController::class, 'summerDelete'])->name('summer.delete.image');

    // Employee
    Route::get('employee-booking', [UserController::class, 'EmployeeBookings'])->name('employee.bookings');
    Route::get('my-booking/{id}', [UserController::class, 'show'])->name('employee.booking.detail');
    Route::patch('employe-profile-update/{employee}', [ProfileController::class, 'employeeProfileUpdate'])->name('employee.profile.update');
    Route::put('employee-bio/{employee}', [EmployeeController::class, 'updateBio'])->name('employee.bio.update');
});

// ==== Frontend routes ====
// جلب الخدمات حسب الكاتيجوري (AJAX)
Route::get('/categories/{category}/services', [FrontendController::class, 'getServices']);

// جلب الموظفين حسب الخدمة
Route::get('/services/{service}/employees', [FrontendController::class, 'getEmployees'])->name('get.employees');

// جلب توافر الموظف حسب التاريخ
Route::get('/employees/{employee}/availability/{date?}', [FrontendController::class, 'getEmployeeAvailability'])->name('employee.availability');

// Appointments
Route::post('/bookings', [AppointmentController::class, 'store'])->name('bookings.store');
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments')->middleware('permission:appointments.view|appointments.create|services.appointments|appointments.delete');
Route::post('/appointments/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.update.status');

// Dashboard update status
Route::post('/update-status', [DashboardController::class, 'updateStatus'])->name('dashboard.update.status');

// ==== API مؤقت للحصول على الخدمات ====
Route::get('/api/services', [ServiceController::class, 'apiIndex']);
