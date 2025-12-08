<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Business Database Diagnosis ===\n\n";

// Check all businesses with slug 'eduhub'
$businesses = \App\Models\Business::where('slug', 'like', 'eduhub%')->get();

echo "Found " . $businesses->count() . " businesses with slug starting with 'eduhub':\n";
foreach ($businesses as $business) {
    echo "ID: " . $business->id . " | Slug: '" . $business->slug . "' | Name: '" . $business->name . "' | Alumni ID: " . $business->alumni_id . " | Created: " . $business->created_at . "\n";
}

echo "\n=== Testing Slug Generation ===\n";
$testName = "Eduhub";
$baseSlug = Illuminate\Support\Str::slug($testName);
echo "Base slug for 'Eduhub': " . $baseSlug . "\n";

$slug = $baseSlug;
$counter = 1;
while (\App\Models\Business::where('slug', $slug)->exists()) {
    echo "Slug '" . $slug . "' exists in database\n";
    $slug = $baseSlug . '-' . $counter;
    $counter++;
    
    if ($counter > 10) {
        echo "Reached safety limit\n";
        break;
    }
}
echo "Final generated slug: " . $slug . "\n";

echo "\n=== Current Business Count ===\n";
echo "Total businesses: " . \App\Models\Business::count() . "\n";

echo "\nDone.\n";
