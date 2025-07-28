<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Header Templates:\n";
foreach(App\Models\TplLayout::where('layout_type', 'header')->get() as $t) {
    echo $t->id . ': ' . $t->name . ' (' . $t->layout_type . ")\n";
}

echo "\nSection Templates:\n";
foreach(App\Models\TplLayout::where('layout_type', 'section')->get() as $t) {
    echo $t->id . ': ' . $t->name . ' (' . $t->layout_type . ")\n";
}

echo "\nFooter Templates:\n";
foreach(App\Models\TplLayout::where('layout_type', 'footer')->get() as $t) {
    echo $t->id . ': ' . $t->name . ' (' . $t->layout_type . ")\n";
}

echo "\nTotal Templates: " . App\Models\TplLayout::count() . "\n";
