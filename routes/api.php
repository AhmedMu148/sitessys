<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\ConfigurationApiController;

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
    
    // Legacy route for compatibility
    Route::get('/user-legacy', function (Request $request) {
        return $request->user();
    });
});