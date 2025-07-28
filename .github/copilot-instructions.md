# Copilot Instructions for Laravel SPS (Site Page System)

<!-- Advanced Laravel 10 Template Management System with Multi-language Support and AdminLTE Dashboard -->

## 🏗️ Project Architecture Overview

**SPS (Site Page System)** is a comprehensive Laravel 10 application designed for dynamic template and content management with enterprise-level features:

### **Core Technologies Stack**
- **Framework**: Laravel 10.x with PHP 8.1+
- **Authentication**: Laravel Sanctum (API tokens) + Custom Admin middleware
- **Frontend**: Blade templates + Bootstrap 5 + AdminLTE 3
- **Database**: MySQL with JSON fields for flexible configurations
- **Asset Management**: Vite with SCSS compilation
- **Multi-language**: Custom implementation with RTL/LTR support
- **Media Handling**: Spatie Media Library integration
- **Permissions**: Spatie Laravel Permission package

## 🎯 Key Business Logic & Features

### **1. Multi-Tenant Site Management**
- Each `User` can own multiple `Site` records
- Sites have configurable domains and status management
- Site-specific configurations stored in JSON format
- Domain-based routing with middleware protection

### **2. Dynamic Template System**
- **Template Layouts** (`TplLayout`): Reusable HTML components (header/section/footer)
- **Theme Categories** (`ThemeCategory`): Business, Portfolio, Ecommerce, SEO Services
- **Theme Pages** (`ThemePage`): Pre-designed page templates with CSS variables
- **Page Sections** (`TplPageSection`): Individual content blocks with multilingual support
- **Layout Assignment**: Pages can use different templates per section

### **3. Configuration Management Architecture**
```php
// Configuration Types Supported:
- 'theme': Site-wide theme settings and page-specific themes
- 'language': Multi-language setup with RTL/LTR direction
- 'navigation': Header/footer navigation with custom links
- 'colors': Color palette management with CSS variable injection
- 'media': File upload settings and restrictions
- 'tenant': Site-specific settings and metadata
```

### **4. Advanced Routing Structure**
```php
// Frontend Routes (Dynamic Content)
Route::get('/', [PageController::class, 'show'])->defaults('slug', 'home');
Route::get('/{slug}', [PageController::class, 'show']);

// Admin Routes (Protected with admin middleware)
Route::prefix('admin')->middleware(['admin'])->group(function() {
    // Full CRUD for sites, pages, templates, users, media
    // Configuration management endpoints
    // Theme and color management
    // Language and localization settings
});

// API Routes (Sanctum protected)
Route::middleware('auth:sanctum')->group(function() {
    // RESTful configuration management
    // Site lookup and management
    // Media and template APIs
});
```

## 🔧 Core Services & Components

### **ConfigurationService**
- Centralized configuration management with caching
- JSON schema validation for all config types
- Version control for configuration changes
- Default configuration initialization

### **Custom Middleware Stack**
- `AdminAccess`: Role-based admin panel protection
- `DomainMiddleware`: Multi-tenant domain resolution
- `LogApiAccess`: API usage tracking and monitoring
- `EnsureUserSiteOwnership`: Site access control

### **Key Models & Relationships**
```php
User (hasMany) → Site (hasOne) → SiteConfig
Site (hasMany) → TplPage (hasMany) → TplPageSection
ThemeCategory (hasMany) → ThemePage
TplLayout (belongsToMany) → TplPageSection
```

## 🎨 Frontend Architecture

### **Blade Component System**
- Reusable components in `resources/views/frontend/components/`
- Layout inheritance with `layouts/app.blade.php`
- AdminLTE components for admin interface
- Responsive design with Bootstrap 5

### **Asset Management**
- Vite configuration with SCSS compilation
- AdminLTE 3 integration with custom styling
- Third-party libraries: Chart.js, Feather Icons, Flatpickr
- CSS/JS injection system for custom styling

### **Multi-language Implementation**
- Language files stored in database (`TplLang`)
- JSON-based content storage with language codes
- RTL/LTR direction handling in CSS
- Dynamic language switching with session management

## 🛡️ Security & Authentication

### **Admin Panel Security**
- Role-based access control (super-admin, admin, user)
- CSRF protection on all forms
- Admin-specific login routes and controllers
- Session-based authentication with Sanctum API tokens

### **API Security**
- Sanctum token authentication
- Rate limiting on API routes
- Input validation with custom request classes
- API access logging for monitoring

## 📊 Database Schema Highlights

### **Core Tables**
```sql
users (id, name, email, role, status_id, preferred_language)
sites (id, user_id, site_name, url, status_id, active_header_id, active_footer_id)
site_config (id, site_id, settings, data, language_code, tpl_name, tpl_colors)
tpl_pages (id, site_id, name, slug, data, show_in_nav, status, page_theme_id)
tpl_page_sections (id, page_id, tpl_layouts_id, name, content, status, sort_order)
theme_categories (id, name, description, icon, sort_order, status)
theme_pages (id, category_id, theme_id, name, path, css_variables)
tpl_layouts (id, tpl_id, layout_type, name, path, default_config, content)
```

### **JSON Field Usage**
- `site_config.settings`: Timezone, meta data, tenant configurations
- `site_config.language_code`: Active languages and primary language
- `tpl_pages.data`: Multilingual page titles and meta information
- `tpl_page_sections.content`: Multilingual section content
- `theme_pages.css_variables`: Theme-specific CSS customizations

## 🚀 Development Guidelines

### **Code Standards**
- Follow Laravel conventions and PSR-12 standards
- Use type hints and return types for all methods
- Implement proper exception handling with try-catch blocks
- Use Laravel's built-in validation and form requests

### **Database Practices**
- Use Eloquent relationships instead of raw queries
- Implement proper indexing for performance
- Use JSON fields for flexible configuration storage
- Follow migration naming conventions

### **Frontend Best Practices**
- Use Blade components for reusable UI elements
- Implement responsive design with Bootstrap 5 utility classes
- Follow semantic HTML structure
- Use Vite for asset compilation and optimization

### **API Development**
- Follow RESTful conventions for API endpoints
- Use resource controllers for CRUD operations
- Implement proper HTTP status codes
- Use Laravel's API resources for data transformation

### **Testing Approach**
- Write feature tests for API endpoints
- Use Postman collection for API testing (33 endpoints available)
- Test admin panel functionality with different user roles
- Validate configuration management workflows

## 📁 Key File Locations

### **Controllers**
- `app/Http/Controllers/Admin/`: Admin panel controllers
- `app/Http/Controllers/Api/`: API controllers
- `app/Http/Controllers/Frontend/`: Public-facing controllers
- `app/Http/Controllers/Auth/`: Authentication controllers

### **Models**
- `app/Models/`: Core Eloquent models with relationships
- `app/Traits/HasConfiguration.php`: Configuration management trait

### **Services**
- `app/Services/ConfigurationService.php`: Core configuration logic
- `app/Services/ContentRenderingService.php`: Template rendering
- `app/Services/BladeRenderingService.php`: Dynamic Blade template rendering
- `app/Services/GlobalTemplateService.php`: Global template management
- `app/Services/NavigationService.php`: Navigation and menu management
- `app/Services/DefaultTemplateAssignmentService.php`: User template assignment
- `app/Services/TemplateCloneService.php`: Template cloning for new users

### **Views**
- `resources/views/admin/`: AdminLTE dashboard views
- `resources/views/frontend/`: Public website templates
- `resources/views/components/`: Reusable Blade components

### **API Documentation**
- `postman/`: Complete Postman collection with 33 endpoints
- `routes/api.php`: API route definitions
- `routes/web.php`: Web route definitions (266 lines)

## 🎯 Common Development Tasks

When working on this project, focus on:

1. **Configuration Management**: Use the ConfigurationService for all config operations
2. **Multi-language Support**: Always consider language codes in content management
3. **Theme Customization**: Leverage the theme system for design variations
4. **Admin Panel Features**: Extend AdminLTE components for new functionality
5. **API Consistency**: Follow the established API patterns for new endpoints
6. **Performance**: Use caching for configuration data and database queries

## 📖 Documentation References

- **Setup Guide**: `ENHANCED_SETUP.md` (252 lines of detailed setup instructions)
- **Project README**: `README.md` (308 lines with feature overview)
- **API Documentation**: `postman/README.md` (719 lines of API documentation)
- **Database Schema**: Migration file with complete table structure
