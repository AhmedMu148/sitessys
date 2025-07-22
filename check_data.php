<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

// Boot the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== Database Data Check ===\n";
    echo "Users: " . DB::table('users')->count() . "\n";
    echo "Sites: " . DB::table('sites')->count() . "\n";
    echo "Pages: " . DB::table('tpl_pages')->count() . "\n";
    echo "Sections: " . DB::table('tpl_page_sections')->count() . "\n";
    echo "Layouts: " . DB::table('tpl_layouts')->count() . "\n";
    echo "Languages: " . DB::table('tpl_langs')->count() . "\n";
    echo "Theme Categories: " . DB::table('theme_categories')->count() . "\n";
    echo "Theme Pages: " . DB::table('theme_pages')->count() . "\n";
    echo "Site Configs: " . DB::table('site_config')->count() . "\n";
    echo "Site Templates: " . DB::table('tpl_site')->count() . "\n";
    echo "Media: " . DB::table('site_img_media')->count() . "\n";
    
    echo "\n=== Sample User Data ===\n";
    $users = DB::table('users')->select('name', 'email')->limit(3)->get();
    foreach ($users as $user) {
        echo "- {$user->name} ({$user->email})\n";
    }
    
    echo "\n=== Sample Site Data ===\n";
    $sites = DB::table('sites')->select('site_name', 'url')->limit(3)->get();
    foreach ($sites as $site) {
        echo "- {$site->site_name} ({$site->url})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
