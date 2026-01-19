<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\QuoteRequestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComparisonController;
use Kreait\Firebase\Factory;
use App\Http\Controllers\DashboardController;


// Root redirect
Route::get('/', fn() => redirect()->route('login'));

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Recommendation
Route::get('/recommendationform', [RecommendationController::class, 'index'])->name('recommendationform');
Route::post('/recommendationform/submit', [RecommendationController::class, 'submit'])->name('recommendationform.submit');

// ===== CATEGORY & PLAN PAGES ===== //

// Show all plans in a category
Route::get('/plans/category/{category}', [CategoryController::class, 'showCategory'])
    ->name('categories.view');

// View single plan
Route::get('/plan/{id}', [CategoryController::class, 'viewPlan'])
    ->name('plans.view');

// ===== QUOTE REQUEST ===== //
Route::get('/quote-request', fn() => view('user.quote_request'))->name('quote.request');
Route::post('/quote-request', [QuoteRequestController::class, 'store'])->name('quote.submit');

// Pending quotes (quote assignment page)
Route::get('/admin/quote-requests', [QuoteRequestController::class, 'index'])->name('quote.assignment');

// Assign staff to a quote
Route::post('/admin/quote-requests/{id}/assign', [QuoteRequestController::class, 'assign'])->name('quote.assign');

// Assigned quotes (current method)
Route::get('/admin/quote-requests/assigned', [QuoteRequestController::class, 'assigned'])->name('quote.assigned');

// Delete (soft delete)
Route::delete('/admin/quote-requests/{id}', [QuoteRequestController::class, 'destroy'])->name('quote.delete');


// ===== ADMIN ===== //
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/manage-plans', [AdminController::class, 'managePlans'])->name('admin.manage-plans');
    Route::post('/add-plan', [AdminController::class, 'addPlan'])->name('admin.add-plan');
    Route::get('/edit-plan/{id}', [AdminController::class, 'editPlanForm'])->name('admin.edit-plan');
    Route::put('/edit-plan/{id}', [AdminController::class, 'editPlan'])->name('admin.update-plan');
    Route::delete('/delete-plan/{id}', [AdminController::class, 'deletePlan'])->name('admin.delete-plan');
    
});

// ===== COMPARISON ===== //
Route::get('/plans', [ComparisonController::class, 'index'])->name('plans');
Route::post('/plans/compare', [ComparisonController::class, 'compare'])->name('plans.compare');

// Download brochure safely
Route::get('/brochures/download/{filename}', [CategoryController::class, 'downloadBrochure'])
    ->name('brochures.download');


Route::get('/firebase-test', function () {
    try {
        $firebase = (new Factory())
            ->withServiceAccount(env('FIREBASE_CREDENTIALS'));
        $db = $firebase->createDatabase();
        return 'Firebase loaded successfully!';
    } catch (\Exception $e) {
        return 'Error: '.$e->getMessage();
    }
});

// Show reset password form
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');

// Handle reset password submission
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


