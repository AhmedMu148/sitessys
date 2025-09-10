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
# SPS — Laravel Template & Page Management Platform

A production-capable Laravel 10 application for managing page templates, multi-language content, site-level configurations and reusable layout components (header, sections, footer). This README gives a practical developer-focused guide: setup, architecture, common tasks, debugging tips and where to look when header/footer or admin saves fail.

## Quick checklist (what this README will provide)
- [x] Local setup & dependencies
- [x] Run & build steps (dev + production)
- [x] Project architecture and important files
- [x] Admin & frontend data flow (header/footer saving)
- [x] Debugging and troubleshooting tips
- [x] Common tasks and commands

## Requirements
- PHP 8.1+
- Composer
- Node.js (16+) and npm/yarn
- MySQL (or compatible) or SQLite for quick local testing
- XAMPP (optional) — project was developed in a Windows/XAMPP environment

## Quick start (development)

1. Clone the repository and enter it:

```powershell
git clone https://github.com/SEOeStore/sps.git
cd sps
```

2. Install PHP & Node dependencies:

```powershell
composer install --no-interaction --prefer-dist
npm install
```

3. Copy environment and generate app key:

```powershell
copy .env.example .env
php artisan key:generate
```

4. Configure DB in `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD). Then run migrations and seeders:

```powershell
php artisan migrate --seed
```

5. Link storage and build assets:

```powershell
php artisan storage:link
npm run dev
```

6. Serve the app locally:

```powershell
php artisan serve --port=8001
```

Visit the frontend at `http://localhost:8001` and the admin at `http://localhost:8001/admin` (or adjust port as needed).

## Run tasks & scripts
- Watch assets: `npm run dev` (Vite)
- Build production assets: `npm run build`
- Run phpunit tests: `vendor/bin/phpunit` or `php artisan test`

## Project layout & important files

- `app/Http/Controllers/Frontend/PageController.php` — builds page payloads and merges site-level `tpl_site` data with layout defaults.
- `app/Http/Controllers/Admin/HeaderFooterController.php` — APIs used by the admin UI to read/update header/footer, social links and auth link toggles.
- `app/Models/TplSite.php` — Eloquent model holding `nav_data` and `footer_data` (JSON casts).
- `resources/views/admin/content/index.blade.php` — admin page that injects `window.navigationConfig` and `window.socialMediaConfig` used by the admin JS.
- `public/js/admin/headers-footers.js` — consolidated admin JS that performs POSTs to `/admin/headers-footers/*` endpoints.
- `public/js/admin/contant.js` — older/alternate admin JS (watch for duplicate inclusion).
- `resources/views/frontend/components/nav.blade.php` and `resources/views/frontend/components/footer.blade.php` — frontend components that render header/footer.

Use these files first when tracing admin save → DB → frontend rendering issues.

## Data flow: header/footer from admin to frontend

1. Admin page renders and injects `window.navigationConfig` and `window.socialMediaConfig` from the server.
2. Admin UI (modals) allow editing links, services, auth visibility and social links. The admin JS collects arrays like `header_links`, `footer_links`, `footer_services`, `social_media` and posts them to controller endpoints (e.g. `/admin/headers-footers/update-navigation`).
3. `HeaderFooterController` validates and persists the values into `tpl_site.nav_data` and `tpl_site.footer_data` (JSON columns). `app/Models/TplSite.php` casts those to arrays.
4. `PageController::show()` loads the active `TplLayout` and merges `tpl_site` nav/footer data with layout `default_config` when present. If `tpl_site` doesn't have corresponding keys, the controller may fall back to `TplLayout` defaults — this is a common source of confusion.

If an admin save appears successful but the frontend doesn't reflect changes, check whether:

- the DB row in `tpl_site` actually contains the new values (nav_data/footer_data),
- the active site_id used by the admin matches the site used by the frontend render,
- the frontend component is reading `tpl_site` values or falling back to `TplLayout` defaults,
- the admin page is loading the correct JS file (`public/js/admin/headers-footers.js`) instead of a stale duplicate.

## Common debugging steps

1. Open browser DevTools → Network. Perform a save in the admin. Inspect the POST request body to `/admin/headers-footers/update-navigation` and the JSON response.
2. Check the database row for the site: `select nav_data, footer_data from tpl_site where site_id = <your_site_id>;` Confirm the JSON contains the newly added links/services.
3. Tail Laravel logs around the save time:

```powershell
Get-Content -Path storage/logs/laravel.log -Tail 200 -Wait
```

Look for exceptions or any debug logs from `HeaderFooterController`.

4. Confirm admin JS inclusion: open the admin page source and ensure `public/js/admin/headers-footers.js` is included exactly once. Duplicate or older files like `public/js/admin/contant.js` can lead to unexpected behavior.

5. If frontend doesn't show updates but DB has them: inspect `app/Http/Controllers/Frontend/PageController.php` and the `resources/views/frontend/components/footer.blade.php` to verify they read `tpl_site.footer_data` instead of template defaults.

## Troubleshooting notes (specific issues seen during development)

- Symptom: Admin modal accepts new footer services but they "do not save" (UI error: "An error occurred while saving footer navigation settings.")
   - Check: Network request payload includes `footer_services` and CSRF token.
   - Check: `HeaderFooterController::updateNavigation()` validates and persists `footer_services` into `tpl_site.footer_data['services']`.
   - Check: After successful response, admin JS should update `window.navigationConfig` with the response payload so that UI refreshes reflect server state.

- Symptom: Changes exist in DB but frontend still shows older footer.
   - Cause: `PageController` fell back to `TplLayout` default_config because `tpl_site.footer_data` lacked the expected keys or the `site_id` didn't match.
   - Fix: Ensure keys are saved with the correct structure and the code that reads them merges `tpl_site` data into the layout config.

## Tests

- Run unit & feature tests with:

```powershell
php artisan test
vendor\bin\phpunit
```

- Add tests under `tests/Feature` for admin endpoints that update `tpl_site` (happy path + 2 edge cases: missing keys and malformed JSON).

## Useful artisan commands

- `php artisan migrate` — run migrations
- `php artisan migrate:rollback` — rollback last batch
- `php artisan db:seed` — run seeders
- `php artisan storage:link` — symlink storage
- `php artisan route:list` — view all routes
- `php artisan config:cache` — cache config
- `php artisan optimize` — optimize framework for deployment

## Deployment (brief)

1. Ensure `.env` is correct for production DB and cache.
2. Build assets: `npm run build`.
3. Deploy code to server, run migrations and seeders if necessary, ensure `storage` and `bootstrap/cache` are writable.
4. Configure web server (Nginx/Apache) to point to `public/` and set up supervisor/cron for queue workers if used.

## Where to look first for header/footer save problems

1. `resources/views/admin/content/index.blade.php` — ensures server-side injected `window.navigationConfig` exists.
2. `public/js/admin/headers-footers.js` and `public/js/admin/contant.js` — ensure the admin page includes the correct script and that the script sends the expected payload (header_links, footer_links, footer_services, social_media).
3. `app/Http/Controllers/Admin/HeaderFooterController.php` — verify validation and persistence of `footer_services` and `footer_data`.
4. `app/Models/TplSite.php` — check casts and helper methods for nav/footer data.
5. `app/Http/Controllers/Frontend/PageController.php` and `resources/views/frontend/components/footer.blade.php` — where merged data is prepared and rendered.

## Contribution

Please open a pull request and include:

- a clear description of the change
- which endpoints or views are affected
- tests for any logic change

## License & credits

This project follows the licensing terms included in the repository. Third-party libraries like AdminLTE, Bootstrap and Feather Icons keep their own licenses.

---

If you'd like, I can also:

- add a short `DEVELOPMENT.md` with step-by-step debugging recipes (Network/DB/log checks) tailored to the header/footer problems you reported,
- add an example PHPUnit feature test for the `HeaderFooterController` save endpoint.

Summary of change: replaced README with a developer-focused, actionable guide (setup, architecture, debugging and common tasks).
- **AdminLTE 3** - Admin Template

- **Feather Icons** - Icon Library

- **MySQL/SQLite** - Database

- **Blade** - Template Engine
