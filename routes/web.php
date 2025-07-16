<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LayoutController;
use App\Http\Controllers\Admin\PageController as AdminPageController;

use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\ColorPaletteController;
use App\Http\Controllers\Admin\CustomCssController;
use App\Http\Controllers\Admin\CustomScriptController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\ConsistencyController;

use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\HeaderFooterController;
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

// Admin routes (protected)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function() {
    Route::get('/', [SiteContentController::class, 'index'])->name('dashboard');
    
    // Site Management
    Route::resource('sites', SiteController::class);

    // Site Content Management Dashboard (main dashboard)
    Route::get('site-content', [SiteContentController::class, 'index'])->name('site-content.index');
    Route::get('site-content/config', [SiteContentController::class, 'config'])->name('site-content.config');
    Route::post('site-content/config', [SiteContentController::class, 'updateConfig'])->name('site-content.config.update');

    // Content Management
    Route::resource('layouts', LayoutController::class);
    Route::resource('pages', AdminPageController::class);
    
    // Header & Footer Management
    Route::get('headers-footers', [HeaderFooterController::class, 'index'])->name('headers-footers.index');
    Route::get('headers-footers/create', [HeaderFooterController::class, 'create'])->name('headers-footers.create');
    Route::post('headers-footers', [HeaderFooterController::class, 'store'])->name('headers-footers.store');
    Route::get('headers-footers/{id}/edit', [HeaderFooterController::class, 'edit'])->name('headers-footers.edit');
    Route::put('headers-footers/{id}', [HeaderFooterController::class, 'update'])->name('headers-footers.update');
    Route::delete('headers-footers/{id}', [HeaderFooterController::class, 'destroy'])->name('headers-footers.destroy');
    Route::post('headers-footers/{id}/activate', [HeaderFooterController::class, 'activate'])->name('headers-footers.activate');
    
    // Page Section Management
    Route::prefix('page-sections')->name('page-sections.')->group(function () {
        Route::get('{page_id}', [PageSectionController::class, 'index'])->name('index');
        Route::get('{page_id}/create', [PageSectionController::class, 'create'])->name('create');
        Route::post('{page_id}', [PageSectionController::class, 'store'])->name('store');
        Route::get('{page_id}/{section_id}/edit', [PageSectionController::class, 'edit'])->name('edit');
        Route::put('{page_id}/{section_id}', [PageSectionController::class, 'update'])->name('update');
        Route::delete('{page_id}/{section_id}', [PageSectionController::class, 'destroy'])->name('destroy');
        Route::patch('{page_id}/{section_id}/toggle-active', [PageSectionController::class, 'toggleActive'])->name('toggle-active');
        Route::post('{page_id}/update-order', [PageSectionController::class, 'updateOrder'])->name('update-order');
    });
    
    // Settings
    Route::get('config', [ConfigController::class, 'index'])->name('config.index');
    Route::post('config', [ConfigController::class, 'update'])->name('config.update');
    
    Route::resource('color-palette', ColorPaletteController::class);
    Route::resource('custom-css', CustomCssController::class);
    Route::resource('custom-scripts', CustomScriptController::class);
    Route::resource('languages', LanguageController::class);
    
    // Consistency Management
    Route::get('consistency', [ConsistencyController::class, 'index'])->name('consistency.index');
    Route::post('consistency/navbar', [ConsistencyController::class, 'fixNavbar'])->name('consistency.navbar');
    Route::post('consistency/footer', [ConsistencyController::class, 'fixFooter'])->name('consistency.footer');
    
    // تعطيل وتفعيل Layout (بدون تكرار prefix/name)
    Route::post('layouts/{layout}/deactivate', [LayoutController::class, 'deactivate'])->name('layouts.deactivate');
    Route::post('layouts/{layout}/activate', [LayoutController::class, 'activate'])->name('layouts.activate');
});

// Frontend routes (with tenant middleware for multi-tenant support)
// These should come last to avoid conflicts with admin routes
Route::middleware(['tenant'])->group(function () {
    Route::get('/', [PageController::class, 'show'])->defaults('slug', 'home');
    Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '^(?!admin|login|register|logout).*$');
});
