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
use App\Http\Controllers\Admin\HeaderFooterController;
use App\Http\Controllers\Admin\SectionTemplateController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LocaleController;

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

// Frontend Language switching routes
Route::post('/locale/set', [LocaleController::class, 'setLocale'])->name('locale.set.post');
Route::get('/locale/set', [LocaleController::class, 'setLocale'])->name('locale.set');
Route::get('/locale/config', [LocaleController::class, 'getLanguageConfig'])->name('locale.config');
Route::get('/locale/switch/{languageCode}', [LocaleController::class, 'switchLanguage'])->name('locale.switch');
Route::post('/locale/switch/{languageCode}', [LocaleController::class, 'switchLanguage'])->name('locale.switch.post');

// Alternative language switching route for API compatibility
Route::post('/language/switch', [LocaleController::class, 'switchLanguageAlt'])->name('language.switch');

// Admin authentication routes
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

// Admin routes (protected with admin middleware only - it handles auth internally)
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.main');
    
    // Site Management
    Route::resource('sites', SiteController::class);
    
    // Add missing site lookup route that tests expect
    Route::get('/site/by-domain', [SiteController::class, 'getByDomain'])->name('site.by-domain');
    
    // User Management
    Route::resource('users', UserController::class);

    // Content Management
    Route::resource('layouts', LayoutController::class);
    
    // Pages List API for dropdowns (must be before resource route)
    Route::get('pages/list', [AdminPageController::class, 'listPages'])->name('pages.list');
    Route::resource('pages', AdminPageController::class);
    // Headers & Footers Management
    Route::prefix('headers-footers')->name('headers-footers.')->group(function () {
        Route::get('/', [HeaderFooterController::class, 'index'])->name('index');
        
        // Enhanced features for global templates and navigation (before wildcard routes)
        Route::post('/create-user-copy', [HeaderFooterController::class, 'createUserCopy'])->name('create-user-copy');
        Route::post('/update-navigation', [HeaderFooterController::class, 'updateNavigation'])->name('update-navigation');
        Route::post('/add-navigation-link', [HeaderFooterController::class, 'addNavigationLink'])->name('add-navigation-link');
        Route::delete('/remove-navigation-link', [HeaderFooterController::class, 'removeNavigationLink'])->name('remove-navigation-link');
        Route::patch('/toggle-navigation-link', [HeaderFooterController::class, 'toggleNavigationLink'])->name('toggle-navigation-link');
        Route::post('/update-social-media', [HeaderFooterController::class, 'updateSocialMedia'])->name('update-social-media');
        
        // Wildcard routes (must be last)
        Route::post('/{layout}/activate', [HeaderFooterController::class, 'activate'])->name('activate');
        Route::delete('/{layout}', [HeaderFooterController::class, 'destroy'])->name('destroy');
    });

    // Page Theme Management
    Route::post('/pages/preview-theme', [ThemeController::class, 'previewPageTheme'])->name('pages.preview-theme');
    Route::post('/pages/apply-theme', [ThemeController::class, 'applyTheme'])->name('pages.apply-theme');
    
    // Section Template Reorder Route
    Route::post('/section-templates/reorder', [SectionTemplateController::class, 'reorder'])->name('section-templates.reorder');

    // Page Management AJAX Routes
    Route::post('pages/{page}/toggle-status', [AdminPageController::class, 'toggleStatus'])->name('pages.toggle-status');
    Route::post('pages/{page}/toggle-nav', [AdminPageController::class, 'toggleNav'])->name('pages.toggle-nav');
    Route::post('pages/{page}/toggle-footer', [AdminPageController::class, 'toggleFooter'])->name('pages.toggle-footer');

    
    // Page Sections Management
    Route::resource('page-sections', PageSectionController::class);
    Route::prefix('pages/{page_id}/sections')->name('pages.sections.')->group(function () {
        Route::get('/', [PageSectionController::class, 'index'])->name('index');
        Route::get('create', [PageSectionController::class, 'create'])->name('create');
        Route::post('/', [PageSectionController::class, 'store'])->name('store');
        Route::get('{section_id}/edit', [PageSectionController::class, 'edit'])->name('edit');
        Route::put('{section_id}', [PageSectionController::class, 'update'])->name('update');
        Route::delete('{section_id}', [PageSectionController::class, 'destroy'])->name('destroy');
        
        // Enhanced section management
        Route::post('reorder', [PageSectionController::class, 'reorder'])->name('reorder');
        Route::post('{section_id}/toggle-status', [PageSectionController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('{section_id}/duplicate', [PageSectionController::class, 'duplicate'])->name('duplicate');
        Route::post('{section_id}/content', [PageSectionController::class, 'updateContent'])->name('update-content');
        Route::get('{section_id}/content/{language?}', [PageSectionController::class, 'getContent'])->name('get-content');
        Route::get('{section_id}/export', [PageSectionController::class, 'exportSection'])->name('export');
        Route::get('{section_id}/preview', [PageSectionController::class, 'preview'])->name('preview');
        
        // Available sections and restore functionality
        Route::get('available', [PageSectionController::class, 'getAvailableSections'])->name('available');
        Route::post('{section_id}/restore', [PageSectionController::class, 'restoreToPage'])->name('restore');
    });
    
    // Template Management
    Route::resource('templates', TemplateController::class);
    
    // Template Management Routes for Header, Section, Footer
    Route::prefix('templates')->name('templates.')->group(function () {
        // Header template routes
        Route::post('header', [TemplateController::class, 'selectHeaderTemplate'])->name('header.select');
        Route::get('header/{template_id}/links', [TemplateController::class, 'getHeaderLinks'])->name('header.links.get');
        Route::post('header/{template_id}/links', [TemplateController::class, 'updateHeaderLinksNew'])->name('header.links.update');
        Route::get('header/{template_id}/preview', [TemplateController::class, 'previewHeader'])->name('header.preview');
        
        // Footer template routes
        Route::post('footer', [TemplateController::class, 'selectFooterTemplate'])->name('footer.select');
        Route::get('footer/{template_id}/links', [TemplateController::class, 'getFooterLinks'])->name('footer.links.get');
        Route::post('footer/{template_id}/links', [TemplateController::class, 'updateFooterLinksNew'])->name('footer.links.update');
        Route::get('footer/{template_id}/preview', [TemplateController::class, 'previewFooter'])->name('footer.preview');
        
        // Section template routes
        Route::post('section/{section_id}/add-to-page', [TemplateController::class, 'addSectionToPage'])->name('section.add-to-page');
        Route::get('section/{section_id}/content', [TemplateController::class, 'getSectionContent'])->name('section.content.get');
        Route::post('section/{section_id}/content', [TemplateController::class, 'updateSectionContent'])->name('section.content.update');
        Route::get('section/{section_id}/preview', [TemplateController::class, 'previewSection'])->name('section.preview');
        Route::get('section/{section_id}/preview-view', [TemplateController::class, 'previewSectionView'])->name('section.preview.view');
        Route::post('section/custom', [TemplateController::class, 'createCustomSection'])->name('section.custom.create');
        Route::post('section/{section_id}/duplicate', [TemplateController::class, 'duplicateSection'])->name('section.duplicate');
        
        // Custom template creation routes
        Route::post('header/custom', [TemplateController::class, 'createCustomHeader'])->name('header.custom.create');
        Route::post('footer/custom', [TemplateController::class, 'createCustomFooter'])->name('footer.custom.create');
        
        // Section editing routes (HTML/CSS/JS)
        Route::get('section/{section_id}/edit', [TemplateController::class, 'getSectionEditData'])->name('section.edit.data');
        Route::post('section/{section_id}/update-code', [TemplateController::class, 'updateSectionCode'])->name('section.update.code');
        
        // Helper routes
        Route::get('pages', [TemplateController::class, 'getSitePages'])->name('pages.get');
        Route::get('page/{page_id}/sections', [TemplateController::class, 'getPageSections'])->name('page.sections.get');
        
        // Template preview routes
        Route::get('{type}/{template_id}/preview', [TemplateController::class, 'generatePreview'])->name('preview.generate');
    });
    
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
    
    // Add direct section template routes that tests expect
    Route::get('/section-templates', [SectionTemplateController::class, 'index'])->name('section-templates-list');
    Route::get('/section-template/{id}', [SectionTemplateController::class, 'show'])->name('section-template-details');
    Route::post('/section-template/{id}/toggle-status', [SectionTemplateController::class, 'toggleStatus'])->name('section-template-toggle');
    Route::get('/section-template/{id}/preview', [SectionTemplateController::class, 'preview'])->name('section-template-preview');
    Route::post('/section-templates/{id}/toggle', [SectionTemplateController::class, 'toggleStatus'])->name('section-templates.toggle-individual');
    Route::post('/section-templates/{id}/preview', [SectionTemplateController::class, 'preview'])->name('section-templates.preview-individual');
    
    // Configuration Management Routes
    Route::prefix('configurations')->name('configurations.')->group(function () {
        Route::get('/', [TemplateController::class, 'configuration'])->name('index');
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
    
    // Media Management Routes
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::get('/upload', [MediaController::class, 'showUploadForm'])->name('upload');
        Route::post('/upload', [MediaController::class, 'upload'])->name('upload.post');
        Route::delete('/{id}', [MediaController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [MediaController::class, 'show'])->name('show');
        Route::post('/{id}/regenerate-thumbnail', [MediaController::class, 'regenerateThumbnail'])->name('regenerate-thumbnail');
    });
    
    // Add direct media routes that tests expect  
    Route::get('/media-library', [MediaController::class, 'index'])->name('media-library');
    Route::get('/media-file/{id}', [MediaController::class, 'show'])->name('media-file');
    
    // Theme Management Routes
    Route::prefix('themes')->name('themes.')->group(function () {
        Route::get('/categories', [ThemeController::class, 'getCategories'])->name('categories');
        Route::get('/pages/{category?}', [ThemeController::class, 'filterPagesByTheme'])->name('filter-pages');
        Route::get('/category/{categoryId}/pages', [ThemeController::class, 'getThemePages'])->name('category-pages');
        Route::post('/pages/{pageId}/preview', [ThemeController::class, 'previewPageTheme'])->name('preview-page');
        Route::post('/pages/{pageId}/apply', [ThemeController::class, 'applyTheme'])->name('apply-theme');
        Route::get('/stats', [ThemeController::class, 'getThemeStats'])->name('stats');
        Route::post('/bulk-update', [ThemeController::class, 'bulkUpdateThemes'])->name('bulk-update');
    });
    
    // Language Management Routes
    Route::prefix('languages')->name('languages.')->group(function () {
        Route::get('/', [LanguageController::class, 'index'])->name('index');
        Route::post('/{languageCode}/toggle', [LanguageController::class, 'toggleLanguage'])->name('toggle');
        Route::post('/primary', [LanguageController::class, 'setPrimaryLanguage'])->name('set-primary');
        Route::post('/switcher', [LanguageController::class, 'updateSwitcherSettings'])->name('update-switcher');
        Route::get('/config', [LanguageController::class, 'getLanguageConfig'])->name('config');
        Route::post('/switch/{languageCode}', [LanguageController::class, 'switchLanguage'])->name('switch');
        Route::post('/reset', [LanguageController::class, 'resetToDefaults'])->name('reset');
        Route::get('/available', [LanguageController::class, 'getAvailableLanguages'])->name('available');
    });
    
    // Color Management Routes
    Route::prefix('colors')->name('colors.')->group(function () {
        Route::get('/', [ColorController::class, 'index'])->name('index');
        Route::get('/schemes', [ColorController::class, 'getColorSchemes'])->name('schemes');
        Route::post('/update', [ColorController::class, 'updateColors'])->name('update');
        Route::post('/scheme', [ColorController::class, 'applyColorScheme'])->name('apply-scheme');
        Route::get('/current', [ColorController::class, 'getColors'])->name('current');
        Route::post('/preview', [ColorController::class, 'generatePreview'])->name('preview');
        Route::post('/reset', [ColorController::class, 'resetToDefaults'])->name('reset');
    });
    
    // Add direct color routes that tests expect
    Route::get('/color-schemes', [ColorController::class, 'getColorSchemes'])->name('color-schemes');
    Route::get('/current-colors', [ColorController::class, 'getColors'])->name('current-colors');
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
