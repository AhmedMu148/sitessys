<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\ConfigurationApiController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SectionTemplateController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-admin', [AuthController::class, 'registerAdmin']);
Route::post('/login', [AuthController::class, 'login']);

// Public configuration schema routes (for API documentation and validation)
Route::prefix('configurations')->name('api.configurations.public.')->group(function () {
    Route::get('/{type}/schema', [ConfigurationApiController::class, 'getConfigurationSchema'])->name('schema');
});

// Public site lookup route (for frontend developers)
Route::get('/site/lookup', [ConfigurationApiController::class, 'getSiteByDomain'])->name('api.site.lookup');

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'revokeAllTokens']);
    
    // Configuration API routes
    Route::prefix('configurations')->name('api.configurations.')->group(function () {
        Route::get('/', [ConfigurationApiController::class, 'getAllConfigurations'])->name('index');
        Route::get('/{type}', [ConfigurationApiController::class, 'getConfiguration'])->name('get');
        Route::post('/{type}', [ConfigurationApiController::class, 'updateConfiguration'])->name('update');
        Route::post('/', [ConfigurationApiController::class, 'updateConfiguration'])->name('store');
        Route::get('/{type}/schema', [ConfigurationApiController::class, 'getConfigurationSchema'])->name('schema')->withoutMiddleware(['auth:sanctum']);
        Route::get('/{type}/versions', [ConfigurationApiController::class, 'getConfigurationVersions'])->name('versions');
        Route::post('/export', [ConfigurationApiController::class, 'exportConfiguration'])->name('export');
        Route::post('/import', [ConfigurationApiController::class, 'importConfiguration'])->name('import');
        Route::post('/validate', [ConfigurationApiController::class, 'validateConfiguration'])->name('validate');
        Route::post('/reset', [ConfigurationApiController::class, 'resetToDefaults'])->name('reset');
        Route::post('/restore', [ConfigurationApiController::class, 'restoreConfigurationVersion'])->name('restore');
    });
    
    // Site management routes
    Route::get('/sites/my-sites', [ConfigurationApiController::class, 'getMySites'])->name('my-sites');
    Route::get('/sites/by-domain', [ConfigurationApiController::class, 'getSiteByDomain'])->name('sites.by-domain');
    
    // Theme management API routes
    Route::prefix('themes')->name('api.themes.')->group(function () {
        Route::get('/categories', [ThemeController::class, 'getCategories'])->name('categories');
        Route::get('/pages', [ThemeController::class, 'filterPagesByTheme'])->name('pages');
        Route::get('/statistics', [ThemeController::class, 'getThemeStats'])->name('statistics');
    });
    
    // Pages API routes
    Route::prefix('pages')->name('api.pages.')->group(function () {
        Route::get('/filter', [ThemeController::class, 'filterPagesByTheme'])->name('filter');
    });
    
    // Section templates API routes
    Route::prefix('section-templates')->name('api.section-templates.')->group(function () {
        Route::get('/', [SectionTemplateController::class, 'index'])->name('index');
        Route::get('/{id}', [SectionTemplateController::class, 'show'])->name('show');
    });
    
    // Colors API routes
    Route::prefix('colors')->name('api.colors.')->group(function () {
        Route::get('/schemes', [ColorController::class, 'getColorSchemes'])->name('schemes');
        Route::get('/current', [ColorController::class, 'getColors'])->name('current');
        Route::post('/apply-scheme', [ColorController::class, 'applyColorScheme'])->name('apply-scheme');
    });
    
    // Media API routes
    Route::prefix('media')->name('api.media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::get('/{id}', [MediaController::class, 'show'])->name('show');
    });
    
    // Legacy route for compatibility
    Route::get('/user-legacy', function (Request $request) {
        return $request->user();
    });
});