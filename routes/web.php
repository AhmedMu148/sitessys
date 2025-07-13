<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LayoutController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\DesignController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\ColorPaletteController;
use App\Http\Controllers\Admin\CustomCssController;
use App\Http\Controllers\Admin\CustomScriptController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Admin\ConsistencyController;

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

// Frontend routes
Route::get('/', [PageController::class, 'show'])->defaults('slug', 'home');
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '^(?!admin).*$');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('/', [SiteContentController::class, 'index'])->name('dashboard');
    
    // Site Content Management (for admins managing their own site)
    Route::prefix('site-content')->name('site-content.')->group(function() {
        Route::get('/', [SiteContentController::class, 'index'])->name('index');
        Route::get('/pages', [SiteContentController::class, 'pages'])->name('pages');
        Route::get('/sections', [SiteContentController::class, 'sections'])->name('sections');
        Route::get('/config', [SiteContentController::class, 'config'])->name('config');
        Route::post('/config', [SiteContentController::class, 'updateConfig'])->name('config.update');
    });
    
    // Content Management
    Route::resource('layouts', LayoutController::class);
    Route::resource('pages', AdminPageController::class);
    Route::resource('designs', DesignController::class);
    
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
});
