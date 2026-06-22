<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DekanController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PMBController;
use App\Http\Controllers\RektorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile & General Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile/password', [AuthController::class, 'showChangePassword'])->name('profile.password');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/conversions/{conversion}/pdf', [KaprodiController::class, 'downloadPdf'])->name('conversions.pdf');
});

// PMB Hub
Route::middleware(['auth', 'pmb'])->prefix('pmb')->name('pmb.')->group(function () {
    Route::get('/', [PMBController::class, 'index'])->name('dashboard');
    Route::get('/students', [PMBController::class, 'index'])->name('students.index');
    Route::get('/students/create', [PMBController::class, 'createStudent'])->name('students.create');
    Route::post('/students', [PMBController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/{student}/edit', [PMBController::class, 'editStudent'])->name('students.edit');
    Route::put('/students/{student}', [PMBController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{student}', [PMBController::class, 'destroyStudent'])->name('students.destroy');

    // Universities
    Route::get('/universities', [PMBController::class, 'universitiesIndex'])->name('universities.index');
    Route::get('/universities/create', [PMBController::class, 'universitiesCreate'])->name('universities.create');
    Route::post('/universities', [PMBController::class, 'universitiesStore'])->name('universities.store');
    Route::get('/universities/{university}/edit', [PMBController::class, 'universitiesEdit'])->name('universities.edit');
    Route::put('/universities/{university}', [PMBController::class, 'universitiesUpdate'])->name('universities.update');
    Route::delete('/universities/{university}', [PMBController::class, 'universitiesDestroy'])->name('universities.destroy');

    // Reviews
    Route::get('/conversions', [PMBController::class, 'conversionsIndex'])->name('conversions.index');
    Route::put('/conversions/{conversion}', [PMBController::class, 'conversionsUpdate'])->name('conversions.update');
});

// Kaprodi Hub
Route::middleware(['auth', 'kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
    Route::get('/', [KaprodiController::class, 'index'])->name('dashboard');

    // Subjects
    Route::get('/subjects', [KaprodiController::class, 'subjectsIndex'])->name('subjects.index');
    Route::get('/subjects/create', [KaprodiController::class, 'subjectsCreate'])->name('subjects.create');
    Route::post('/subjects', [KaprodiController::class, 'subjectsStore'])->name('subjects.store');
    Route::get('/subjects/{subject}/edit', [KaprodiController::class, 'subjectsEdit'])->name('subjects.edit');
    Route::put('/subjects/{subject}', [KaprodiController::class, 'subjectsUpdate'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [KaprodiController::class, 'subjectsDestroy'])->name('subjects.destroy');

    // Mappings
    Route::get('/mappings', [KaprodiController::class, 'mappingsIndex'])->name('mappings.index');
    Route::get('/mappings/create', [KaprodiController::class, 'mappingsCreate'])->name('mappings.create');
    Route::post('/mappings', [KaprodiController::class, 'mappingsStore'])->name('mappings.store');
    Route::get('/mappings/{source}/edit', [KaprodiController::class, 'mappingsEdit'])->name('mappings.edit');
    Route::put('/mappings/{source}', [KaprodiController::class, 'mappingsUpdate'])->name('mappings.update');
    Route::delete('/mappings/source/{source}', [KaprodiController::class, 'mappingsDestroyBySource'])->name('mappings.destroy-by-source');
    Route::delete('/mappings/{mapping}', [KaprodiController::class, 'mappingsDestroy'])->name('mappings.destroy');

    // Reviews & Workbench
    Route::get('/conversions', [KaprodiController::class, 'conversionsIndex'])->name('conversions.index');
    Route::get('/conversions/{conversion}', [KaprodiController::class, 'conversionsShow'])->name('conversions.show');
    Route::put('/conversions/{conversion}', [KaprodiController::class, 'conversionsUpdate'])->name('conversions.update');
    Route::post('/conversions/{conversion}/results', [KaprodiController::class, 'conversionsResultStore'])->name('conversions.results.store');
    Route::post('/conversions/{conversion}/results/bulk', [KaprodiController::class, 'conversionsResultBulkStore'])->name('conversions.results.bulk-store');
    Route::post('/conversions/{conversion}/results/sync', [KaprodiController::class, 'conversionsResultSync'])->name('conversions.results.sync');
    Route::put('/conversions/results/bulk', [KaprodiController::class, 'conversionsResultBulkUpdate'])->name('conversions.results.bulk-update');
    Route::put('/conversions/results/{result}', [KaprodiController::class, 'conversionsResultUpdate'])->name('conversions.results.update');
    Route::delete('/conversions/results/bulk', [KaprodiController::class, 'conversionsResultBulkDestroy'])->name('conversions.results.bulk-destroy');
    Route::delete('/conversions/results/{result}', [KaprodiController::class, 'conversionsResultDestroy'])->name('conversions.results.destroy');

    // Grade Conversions
    Route::get('/grade-conversions', [KaprodiController::class, 'gradeConversionsIndex'])->name('grade-conversions.index');
    Route::post('/grade-conversions', [KaprodiController::class, 'gradeConversionsStore'])->name('grade-conversions.store');
    Route::put('/grade-conversions/{gradeConversion}', [KaprodiController::class, 'gradeConversionsUpdate'])->name('grade-conversions.update');
    Route::delete('/grade-conversions/{gradeConversion}', [KaprodiController::class, 'gradeConversionsDestroy'])->name('grade-conversions.destroy');

    // User Management (Moved from Admin)
    Route::get('/users', [KaprodiController::class, 'usersIndex'])->name('users.index');
    Route::get('/users/create', [KaprodiController::class, 'usersCreate'])->name('users.create');
    Route::post('/users', [KaprodiController::class, 'usersStore'])->name('users.store');
    Route::get('/users/{user}/edit', [KaprodiController::class, 'usersEdit'])->name('users.edit');
    Route::put('/users/{user}', [KaprodiController::class, 'usersUpdate'])->name('users.update');
    Route::delete('/users/{user}', [KaprodiController::class, 'usersDestroy'])->name('users.destroy');
});

// Mahasiswa Hub
Route::middleware(['auth', 'mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('dashboard');
    Route::get('/profile', [MahasiswaController::class, 'profileEdit'])->name('profile.edit');
    Route::put('/profile', [MahasiswaController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/conversions/create', [MahasiswaController::class, 'conversionsCreate'])->name('conversions.create');
    Route::post('/conversions', [MahasiswaController::class, 'conversionsStore'])->name('conversions.store');
});

// Dekan Hub
Route::middleware(['auth', 'dekan'])->prefix('dekan')->name('dekan.')->group(function () {
    Route::get('/', [DekanController::class, 'index'])->name('dashboard');
    Route::get('/conversions', [DekanController::class, 'conversionsIndex'])->name('conversions.index');
    Route::get('/conversions/{conversion}', [DekanController::class, 'conversionsShow'])->name('conversions.show');
    Route::put('/conversions/{conversion}', [DekanController::class, 'conversionsUpdate'])->name('conversions.update');
});

// Rektor Hub
Route::middleware(['auth', 'rektor'])->prefix('rektor')->name('rektor.')->group(function () {
    Route::get('/', [RektorController::class, 'index'])->name('dashboard');
    Route::get('/conversions', [RektorController::class, 'conversionsIndex'])->name('conversions.index');
    Route::get('/conversions/{conversion}', [RektorController::class, 'conversionsShow'])->name('conversions.show');
    Route::put('/conversions/{conversion}', [RektorController::class, 'conversionsUpdate'])->name('conversions.update');
});
