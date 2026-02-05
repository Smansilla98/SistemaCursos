<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AccessKeyController as AdminAccessKeyController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\PaymentController as StudentPaymentController;
use App\Http\Controllers\Student\AccessKeyController as StudentAccessKeyController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/curso/{slug}', [HomeController::class, 'showCourse'])->name('course.show');

// Rutas de autenticación
require __DIR__.'/auth.php';

// Redirigir dashboard según rol
Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->hasRole('profesor')) {
        return redirect()->route('teacher.courses.index');
    } else {
        return redirect()->route('student.courses.index');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas - Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('courses', AdminCourseController::class);
    Route::resource('users', AdminUserController::class);
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    Route::resource('access-keys', AdminAccessKeyController::class);
});

// Rutas protegidas - Alumno
Route::middleware(['auth', 'role:alumno'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/purchase', [StudentPaymentController::class, 'create'])->name('courses.purchase');
    Route::post('/access-keys/validate', [StudentAccessKeyController::class, 'validate'])->name('access-keys.validate');
    Route::get('/payments/success', [StudentPaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/failure', [StudentPaymentController::class, 'failure'])->name('payments.failure');
    Route::get('/payments/pending', [StudentPaymentController::class, 'pending'])->name('payments.pending');
});

// Rutas protegidas - Profesor
Route::middleware(['auth', 'role:profesor'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/courses', [\App\Http\Controllers\Teacher\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [\App\Http\Controllers\Teacher\CourseController::class, 'show'])->name('courses.show');
});
