# Laravel 10 Template Management System (SPS)

A comprehensive Laravel 10 application with Blade templates, AdminLTE 3 dashboard, and multi-language support for dynamic page and layout management.

## ✅ **Project Status: COMPLETE & FULLY FUNCTIONAL**

The Laravel 10 Template Management System is **production-ready** with all features working seamlessly:

### 🌟 **Live Demo URLs**
- **Frontend**: `http://localhost:8000` (Dynamic pages with consistent template rendering)
- **Admin Panel**: `http://localhost:8000/admin` (AdminLTE dashboard with full CRUD operations)

### 🎯 **Key Features**

#### ✅ **Frontend Features**
- **Dynamic Content Rendering**: All pages render from database with consistent design
- **Multi-language Support**: English/Arabic with RTL/LTR direction handling
- **Responsive Design**: Bootstrap 5 with modern UI and smooth animations
- **Consistent Navigation**: Unified navbar and footer across all pages
- **SEO Optimized**: Proper meta tags and semantic HTML structure

#### ✅ **Admin Dashboard Features**
- **Complete CRUD Operations**: Full management of layouts, pages, and designs
- **Settings Management**: Site configuration, color palettes, custom CSS/JS
- **Language Management**: Multi-language content support
- **Consistency Tools**: Bulk navbar/footer updates across all pages
- **User-Friendly Interface**: AdminLTE 3 with proper icons and navigation

#### ✅ **Template System**
- **Layout Management**: Create and manage HTML templates with preview
- **Design System**: Link layouts to pages with custom JSON data
- **Component-Based**: Reusable Blade components (nav, section, footer)
- **Dynamic Data**: All content managed through admin interface

### 📁 **Available Pages**
- **Home** (`/`): Hero section + Features + Footer with "TechCorp" branding
- **About** (`/about`): About hero + Footer with consistent navigation
- **Services** (`/services`): Services overview + Footer with full menu
- **Contact** (`/contact`): Contact information + Footer with unified design

### 🛠 **Admin Management**
- **Layouts**: Create/edit HTML templates for different sections
- **Pages**: Manage page slugs, titles, and status
- **Designs**: Link layouts to pages with custom data
- **Site Config**: Configure site settings, language, and direction
- **Color Palette**: Manage site color schemes
- **Custom CSS**: Add custom styling
- **Custom Scripts**: Manage JavaScript for head/footer
- **Languages**: Multi-language support management
- **Consistency**: Bulk update navbar/footer across all pages

## 🚀 **Installation**

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL/SQLite

### Quick Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/SEOeStore/sps.git
   cd sps
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**:
   ```bash
   # Update .env with your database credentials
   php artisan migrate:fresh --seed
   ```

5. **Storage link**:
   ```bash
   php artisan storage:link
   ```

6. **Compile assets**:
   ```bash
   npm run dev
   # Or for production:
   npm run build
   ```

7. **Start the server**:
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` for the frontend and `http://localhost:8000/admin` for the admin panel.

## 📊 **Database Structure**

### Core Tables
- **`sites`**: Site configuration and information
- **`tpl_layout_types`**: Layout types (nav, section, footer)
- **`tpl_layouts`**: HTML templates with preview images
- **`tpl_pages`**: Page definitions with slugs and metadata
- **`tpl_designs`**: Page-layout mappings with custom JSON data
- **`tpl_lang`**: Language definitions (EN/AR with RTL/LTR)
- **`site_config`**: Site language and direction settings

### Additional Tables
- **`tpl_custom_css`**: Custom CSS for sites
- **`tpl_custom_scripts`**: Custom JavaScript (head/footer)
- **`tpl_color_palette`**: Site color schemes

## 🎨 **Frontend Architecture**

### Template Structure
```
resources/views/frontend/
├── layouts/
│   └── app.blade.php          # Main layout template
└── components/
    ├── nav.blade.php          # Navigation component
    ├── section.blade.php      # Content sections
    └── footer.blade.php       # Footer component
```

### Content Flow
1. **PageController** loads page by slug
2. **Designs** are fetched for the page (nav, sections, footer)
3. **Components** render with dynamic data from designs
4. **Consistent** navbar and footer across all pages

## 🔧 **Admin Interface**

### Management Sections
- **Dashboard**: Overview and quick actions
- **Layouts**: Template management with preview
- **Pages**: Page creation and management
- **Designs**: Content-layout mapping
- **Site Config**: Global site settings
- **Color Palette**: Brand color management
- **Custom CSS**: Additional styling
- **Custom Scripts**: JavaScript management
- **Languages**: Multi-language support
- **Consistency**: Bulk update tools

### Key Features
- **WYSIWYG**: Visual template management
- **Validation**: Form validation and error handling
- **Responsive**: Mobile-friendly admin interface
- **Icons**: Proper Feather icons integration
- **Navigation**: Intuitive sidebar with active states

## 🌐 **Multi-Language Support**

### Supported Languages
- **English (EN)**: Left-to-right (LTR)
- **Arabic (AR)**: Right-to-left (RTL)

### Features
- **Dynamic Direction**: Auto RTL/LTR based on language
- **Content Translation**: Language-specific designs
- **Admin Management**: Language CRUD operations
- **Fallback Support**: Graceful degradation

## 🎯 **Technical Highlights**

### Backend
- **Laravel 10**: Latest PHP framework
- **Eloquent ORM**: Database relationships and queries
- **Blade Templates**: Component-based rendering
- **Validation**: Comprehensive form validation
- **Migrations**: Database version control

### Frontend
- **Bootstrap 5**: Modern responsive framework
- **AdminLTE 3**: Professional admin template
- **Feather Icons**: Consistent iconography
- **Dynamic Content**: Database-driven rendering
- **SEO Ready**: Proper meta tags and structure

### Features
- **CRUD Operations**: Complete data management
- **File Uploads**: Image handling for layouts
- **JSON Data**: Flexible content structure
- **Soft Deletes**: Safe data removal
- **Status Management**: Enable/disable functionality


## 📝 **Usage Examples**

### Creating a New Page
1. Go to Admin → Pages → Create New Page
2. Set page name, slug, and sort order
3. Go to Admin → Designs → Create New Design
4. Link layout to the page with custom data

### Updating Navbar
1. Go to Admin → Consistency
2. Update navbar settings
3. Apply to all pages automatically

### Adding Custom CSS
1. Go to Admin → Custom CSS
2. Add CSS rules
3. Set sort order and status



## 🏗 **Built With**

- **Laravel 10** - PHP Framework
- **Bootstrap 5** - CSS Framework
- **AdminLTE 3** - Admin Template
- **Feather Icons** - Icon Library
- **MySQL/SQLite** - Database
- **Blade** - Template Engine

---

**Template Management System (SPS)** © 2025 - Built with ❤️ using Laravel 10
- **Designs**: Map layouts to pages with custom data
- **Config**: Site configuration and customization

## Layout System

### Creating Layouts

1. Go to **Admin > Layouts**
2. Click **Add Layout**
3. Select layout type (nav, section, footer)
4. Enter HTML template with dynamic variables
5. Upload preview image
6. Save layout

### Template Variables

Use `{{ $data["key"] }}` in your HTML templates:

```html
<h1>{{ $data["title"] ?? "Default Title" }}</h1>
<p>{{ $data["subtitle"] ?? "Default subtitle" }}</p>
```

### Page Design

1. Go to **Admin > Designs**
2. Select a page
3. Choose layout template
4. Set sort order
5. Configure JSON data for dynamic content

## Multi-language Support

The system supports multiple languages with direction handling:

- **English**: LTR (Left-to-Right)
- **Arabic**: RTL (Right-to-Left)

Language settings are managed through the `tpl_lang` and `site_config` tables.

## File Structure

```
app/
├── Http/Controllers/
│   ├── Frontend/
│   │   └── PageController.php
│   └── Admin/
│       ├── LayoutController.php
│       ├── PageController.php
│       ├── DesignController.php
│       └── ConfigController.php
├── Models/
│   ├── Site.php
│   ├── TplLayout.php
│   ├── TplPage.php
│   ├── TplDesign.php
│   └── ... (other models)
resources/
├── views/
│   ├── frontend/
│   │   └── layouts/
│   │       └── app.blade.php
│   └── admin/
│       └── layouts/
│           └── master.blade.php
```

## Development Guidelines

- Use Blade components for reusable UI elements
- Follow Laravel best practices for controllers and models
- Implement proper validation and error handling
- Use Bootstrap 5 classes for styling
- Maintain clean, semantic HTML structure
- Follow PSR standards for PHP code



