<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TplLayout;
use App\Models\Site;

echo "=== DATABASE CLEANUP: KEEP ONLY GLOBAL TEMPLATES ===" . PHP_EOL;

// Step 1: Check current state
$totalTemplates = TplLayout::count();
$globalTemplates = TplLayout::where('tpl_id', 'like', 'global-%')->count();
$userTemplates = TplLayout::where('tpl_id', 'not like', 'global-%')->count();

echo "Current state:" . PHP_EOL;
echo "- Total templates: {$totalTemplates}" . PHP_EOL;
echo "- Global templates: {$globalTemplates}" . PHP_EOL;
echo "- User templates: {$userTemplates}" . PHP_EOL;

if ($userTemplates == 0) {
    echo "✅ Database already cleaned - only global templates exist!" . PHP_EOL;
    exit(0);
}

// Step 2: Get all non-global templates
$templatestoDelete = TplLayout::where('tpl_id', 'not like', 'global-%')->get();

echo PHP_EOL . "Non-global templates to delete:" . PHP_EOL;
foreach ($templatestoDelete as $template) {
    echo "- {$template->tpl_id} ({$template->layout_type}): {$template->name}" . PHP_EOL;
}

// Step 3: Update sites to use global templates if they're using non-global ones
$globalHeaders = TplLayout::where('tpl_id', 'like', 'global-%')
    ->where('layout_type', 'header')
    ->pluck('id', 'tpl_id');
    
$globalFooters = TplLayout::where('tpl_id', 'like', 'global-%')
    ->where('layout_type', 'footer')
    ->pluck('id', 'tpl_id');

$defaultHeaderId = $globalHeaders->first();
$defaultFooterId = $globalFooters->first();

echo PHP_EOL . "Default global templates:" . PHP_EOL;
echo "- Default header ID: {$defaultHeaderId}" . PHP_EOL;
echo "- Default footer ID: {$defaultFooterId}" . PHP_EOL;

// Update sites using non-global templates
$sitesUpdated = 0;
$sites = Site::all();

foreach ($sites as $site) {
    $updated = false;
    
    // Check and update header
    if ($site->active_header_id) {
        $currentHeader = TplLayout::find($site->active_header_id);
        if ($currentHeader && !str_starts_with($currentHeader->tpl_id, 'global-')) {
            $site->active_header_id = $defaultHeaderId;
            $updated = true;
            echo "Updated site '{$site->site_name}' header to global template" . PHP_EOL;
        }
    } else {
        $site->active_header_id = $defaultHeaderId;
        $updated = true;
        echo "Set default header for site '{$site->site_name}'" . PHP_EOL;
    }
    
    // Check and update footer
    if ($site->active_footer_id) {
        $currentFooter = TplLayout::find($site->active_footer_id);
        if ($currentFooter && !str_starts_with($currentFooter->tpl_id, 'global-')) {
            $site->active_footer_id = $defaultFooterId;
            $updated = true;
            echo "Updated site '{$site->site_name}' footer to global template" . PHP_EOL;
        }
    } else {
        $site->active_footer_id = $defaultFooterId;
        $updated = true;
        echo "Set default footer for site '{$site->site_name}'" . PHP_EOL;
    }
    
    if ($updated) {
        $site->save();
        $sitesUpdated++;
    }
}

echo PHP_EOL . "Sites updated: {$sitesUpdated}" . PHP_EOL;

// Step 4: Delete non-global templates
$deletedCount = 0;
foreach ($templatestoDelete as $template) {
    try {
        $template->delete();
        $deletedCount++;
        echo "Deleted template: {$template->tpl_id}" . PHP_EOL;
    } catch (Exception $e) {
        echo "Error deleting template {$template->tpl_id}: " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . "Templates deleted: {$deletedCount}" . PHP_EOL;

// Step 5: Final verification
$finalTotal = TplLayout::count();
$finalGlobal = TplLayout::where('tpl_id', 'like', 'global-%')->count();
$finalUser = TplLayout::where('tpl_id', 'not like', 'global-%')->count();

echo PHP_EOL . "=== CLEANUP COMPLETED ===" . PHP_EOL;
echo "Final state:" . PHP_EOL;
echo "- Total templates: {$finalTotal}" . PHP_EOL;
echo "- Global templates: {$finalGlobal}" . PHP_EOL;
echo "- User templates: {$finalUser}" . PHP_EOL;

if ($finalUser == 0) {
    echo "✅ SUCCESS: Only global templates remain!" . PHP_EOL;
} else {
    echo "⚠️  WARNING: Some non-global templates still exist" . PHP_EOL;
}

echo PHP_EOL . "Remaining templates:" . PHP_EOL;
TplLayout::orderBy('layout_type')->orderBy('tpl_id')->get(['tpl_id', 'layout_type', 'name'])->each(function($t) {
    echo "- {$t->tpl_id} ({$t->layout_type}): {$t->name}" . PHP_EOL;
});

echo PHP_EOL . "Database cleanup completed!" . PHP_EOL;
