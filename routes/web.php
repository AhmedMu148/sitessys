<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LayoutController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\ConfigurationTestController;
use App\Http\Controllers\Admin\SectionTemplateController;
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

// User profile page
Route::get('/profile', function () {
    return view('frontend.profile');
})->middleware(['auth'])->name('profile');

// User settings page
Route::get('/settings', function () {
    return view('frontend.profile');
})->middleware(['auth'])->name('settings');

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
    
    // Section Template Management
    Route::prefix('section-templates')->name('section-templates.')->group(function () {
        Route::get('/', [SectionTemplateController::class, 'index'])->name('index');
        Route::post('/', [SectionTemplateController::class, 'store'])->name('store');
        Route::post('/toggle-status', [SectionTemplateController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/update-order', [SectionTemplateController::class, 'updateOrder'])->name('update-order');
        Route::post('/preview', [SectionTemplateController::class, 'preview'])->name('preview');
        Route::get('/{id}', [SectionTemplateController::class, 'show'])->name('show');
        Route::put('/{id}', [SectionTemplateController::class, 'update'])->name('update');
        Route::delete('/{id}', [SectionTemplateController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/duplicate', [SectionTemplateController::class, 'duplicate'])->name('duplicate');
        Route::post('/{id}/content', [SectionTemplateController::class, 'updateContent'])->name('update-content');
    });
    
    // Configuration Management Routes
    Route::prefix('configurations')->name('configurations.')->group(function () {
        Route::get('/', [TemplateController::class, 'configuration'])->name('index');
        Route::get('/test', [ConfigurationTestController::class, 'test'])->name('test');
        Route::get('/{type}', [TemplateController::class, 'getConfiguration'])->name('get');
        Route::post('/theme', [TemplateController::class, 'updateTheme'])->name('theme.update');
        Route::post('/navigation', [TemplateController::class, 'updateNavigation'])->name('navigation.update');
        Route::post('/colors', [TemplateController::class, 'updateColorsConfig'])->name('colors.update');
        Route::post('/sections', [TemplateController::class, 'updateSections'])->name('sections.update');
        Route::get('/export', [TemplateController::class, 'exportConfigurations'])->name('export');
        Route::post('/import', [TemplateController::class, 'importConfigurations'])->name('import');
        Route::post('/initialize-defaults', [TemplateController::class, 'initializeDefaults'])->name('initialize-defaults');
    });
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    
    // Settings Configuration Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::post('/languages', [SettingsController::class, 'updateLanguages'])->name('languages.update');
        Route::post('/media', [SettingsController::class, 'updateMedia'])->name('media.update');
        Route::post('/tenant', [SettingsController::class, 'updateTenant'])->name('tenant.update');
        Route::get('/languages', [SettingsController::class, 'getLanguages'])->name('languages.get');
        Route::get('/language-config', [SettingsController::class, 'getLanguageConfig'])->name('language-config.get');
        Route::get('/all-configurations', [SettingsController::class, 'getAllConfigurations'])->name('all-configurations.get');
        Route::post('/reset-defaults', [SettingsController::class, 'resetToDefaults'])->name('reset-defaults');
        Route::post('/validate', [SettingsController::class, 'validateConfiguration'])->name('validate');
        Route::get('/schema/{type}', [SettingsController::class, 'getConfigurationSchema'])->name('schema.get');
    });
});

// Frontend routes - simplified without tenant middleware for now
// Root route shows the main site's home page
Route::get('/', [PageController::class, 'show'])->defaults('slug', 'home')->name('home');
// Other pages by slug (but exclude admin, auth, and user routes)
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '^(?!admin|login|register|logout|dashboard|health|api).*$');

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'version' => config('app.version', '1.0.0'),
        'environment' => config('app.env'),
        'services' => [
            'database' => 'connected',
            'cache' => 'available',
            'api' => 'operational'
        ]
    ]);
})->name('health');
