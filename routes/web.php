<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\InterviewScheduleController;
use App\Http\Controllers\InterviewResultController;
use App\Http\Controllers\AhpController;
use App\Http\Controllers\ProfileMatchingController;
use App\Http\Controllers\CriteriaStatusController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileMatchingHistoryController;
use App\Http\Controllers\ProfileController;

// Authentication Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.password.update');
});

// Email Verification Routes
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Logout Route
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Vacancies
    Route::resource('vacancies', VacancyController::class);

    // CriteriaStatus
    Route::resource('criteria_statuses', CriteriaStatusController::class);

    // Criteria
    Route::resource('criterias', CriteriaController::class);

    // Candidates
    Route::resource('candidates', CandidateController::class);

    // AHP
    Route::get('ahp', [AhpController::class, 'index'])->name('ahp.index');
    Route::post('ahp', [AhpController::class, 'store'])->name('ahp.store');
    Route::delete('ahp/{comparison}', [AhpController::class, 'destroy'])->name('ahp.destroy');

    // Profile Matching
    Route::get('profile-matching', [ProfileMatchingController::class, 'index'])->name('profile-matching.index');
    Route::post('profile-matching/process', [ProfileMatchingController::class, 'process'])->name('profile-matching.process');

    Route::get('profile-matching/results/{vacancy_id}', [ProfileMatchingController::class, 'results'])->name('profile-matching.results');
    Route::post('profile-matching/save-ideal-values', [ProfileMatchingController::class, 'saveIdealValues'])
        ->name('profile-matching.save-ideal-values');

    Route::post('profile-matching/save-results', [ProfileMatchingController::class, 'saveResults'])
        ->name('profile-matching.save-results');
    Route::get('profile-matching/view-results/{vacancy_id}', [ProfileMatchingController::class, 'viewResults'])->name('profile-matching.view-results');


    // Profile Matching History
    Route::prefix('profile-matching-history')->group(function () {
        Route::get('/', [ProfileMatchingHistoryController::class, 'index'])
            ->name('profile-matching.history.index');
        Route::get('/{id}', [ProfileMatchingHistoryController::class, 'show'])
            ->name('profile-matching.history.show');
        Route::get('/vacancy/{vacancy_id}', [ProfileMatchingHistoryController::class, 'byVacancy'])
            ->name('profile-matching.history.by-vacancy');
    });

    Route::resource('users', UserController::class);
    Route::post('users/{user}/change-password', [UserController::class, 'changePassword'])
        ->name('users.change-password');

    Route::get('/interviews', [InterviewScheduleController::class, 'index'])->name('interviews.index');

    // Admin/HRD routes
    Route::get('/interviews/create', [InterviewScheduleController::class, 'create'])->name('interviews.create');
    Route::post('/interviews', [InterviewScheduleController::class, 'store'])->name('interviews.store');
    Route::get('/interviews/{interview}/edit', [InterviewScheduleController::class, 'edit'])->name('interviews.edit');
    Route::put('/interviews/{interview}', [InterviewScheduleController::class, 'update'])->name('interviews.update');
    Route::delete('/interviews/{interview}', [InterviewScheduleController::class, 'destroy'])->name('interviews.destroy');


    // Common routes
    Route::get('/interviews/{interview}', [InterviewScheduleController::class, 'show'])->name('interviews.show');

    // User routes
    Route::post('/interviews/{interview}/approve', [InterviewScheduleController::class, 'approve'])->name('interviews.approve');
    Route::post('/interviews/{interview}/reject', [InterviewScheduleController::class, 'reject'])->name('interviews.reject');

    Route::post('/interviews/{interview}/complete', [InterviewScheduleController::class, 'complete'])
        ->name('interviews.complete');

    Route::prefix('interview-results')->group(function () {
        Route::get('/create/{interview}', [InterviewResultController::class, 'create'])
            ->name('interview-results.create');
        Route::post('/store/{interview}', [InterviewResultController::class, 'store'])
            ->name('interview-results.store');
        Route::get('/{interviewResult}', [InterviewResultController::class, 'show'])->name('interview-results.show');
        Route::get('/{interviewResult}/edit', [InterviewResultController::class, 'edit'])->name('interview-results.edit');
        Route::put('/{interviewResult}', [InterviewResultController::class, 'update'])->name('interview-results.update');
        Route::delete('/{interviewResult}', [InterviewResultController::class, 'destroy'])->name('interview-results.destroy');
    });
});
