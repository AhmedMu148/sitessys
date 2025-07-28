<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Templates with preview images:\n";
foreach(App\Models\TplLayout::all() as $t) {
    echo $t->id . ': ' . $t->name . ' - Preview: ' . ($t->preview_image ? $t->preview_image : 'No image') . "\n";
}
