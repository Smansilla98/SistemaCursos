<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/curso/{course}', [HomeController::class, 'showCourse'])->name('course.show');

// Rutas de autenticación
require __DIR__.'/auth.php';

// Redirigir dashboard según rol
Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
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
    Route::post('/courses/{course}/lessons', [AdminCourseController::class, 'addLesson'])->name('courses.lessons.add');
    Route::delete('/lessons/{lesson}', [AdminCourseController::class, 'deleteLesson'])->name('lessons.delete');
    Route::resource('users', AdminUserController::class);
    Route::get('/purchases', [\App\Http\Controllers\Admin\PurchaseController::class, 'index'])->name('purchases.index');
    Route::post('/purchases/{purchase}/approve', [\App\Http\Controllers\Admin\PurchaseController::class, 'approve'])->name('purchases.approve');
    Route::post('/purchases/{purchase}/reject', [\App\Http\Controllers\Admin\PurchaseController::class, 'reject'])->name('purchases.reject');
});

// Rutas protegidas - Student
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/purchase', [\App\Http\Controllers\Student\PurchaseController::class, 'create'])->name('courses.purchase');
    Route::get('/purchases/success', [\App\Http\Controllers\Student\PurchaseController::class, 'success'])->name('purchases.success');
    Route::get('/purchases/failure', [\App\Http\Controllers\Student\PurchaseController::class, 'failure'])->name('purchases.failure');
    Route::get('/purchases/pending', [\App\Http\Controllers\Student\PurchaseController::class, 'pending'])->name('purchases.pending');
});

// Rutas de API para webhooks (sin autenticación)
Route::prefix('api')->group(function () {
    Route::post('/mercadopago/webhook', [\App\Http\Controllers\Api\MercadoPagoWebhookController::class, 'handle'])
        ->name('api.mercadopago.webhook');
});
