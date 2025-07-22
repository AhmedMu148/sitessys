<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LayoutController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'webRegister'])->name('register.post');
Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

// User dashboard for authenticated regular users
Route::get('/dashboard', function () {
    return view('welcome');
})->middleware(['auth'])->name('user.dashboard');

// Admin authentication routes
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

// Admin routes (protected with admin middleware only - it handles auth internally)
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Site Management
    Route::resource('sites', SiteController::class);
    
    // User Management
    Route::resource('users', UserController::class);

    // Content Management
    Route::resource('layouts', LayoutController::class);
    Route::resource('pages', AdminPageController::class);
    
    // Page Management AJAX Routes
    Route::post('pages/{page}/toggle-status', [AdminPageController::class, 'toggleStatus'])->name('pages.toggle-status');
    Route::post('pages/{page}/toggle-nav', [AdminPageController::class, 'toggleNav'])->name('pages.toggle-nav');
    Route::post('pages/{page}/toggle-footer', [AdminPageController::class, 'toggleFooter'])->name('pages.toggle-footer');
    
    // Page Sections Management
    Route::prefix('pages/{page_id}/sections')->name('pages.sections.')->group(function () {
        Route::get('/', [PageSectionController::class, 'index'])->name('index');
        Route::get('create', [PageSectionController::class, 'create'])->name('create');
        Route::post('/', [PageSectionController::class, 'store'])->name('store');
        Route::get('{section_id}/edit', [PageSectionController::class, 'edit'])->name('edit');
        Route::put('{section_id}', [PageSectionController::class, 'update'])->name('update');
        Route::delete('{section_id}', [PageSectionController::class, 'destroy'])->name('destroy');
    });
    
    // Template Management
    Route::resource('templates', TemplateController::class);
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');
