<?php

// Run this script to find and remove duplicate businesses
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Looking for duplicate businesses with slug 'eduhub'...\n";

$duplicates = \App\Models\Business::where('slug', 'eduhub')->get();

if ($duplicates->count() > 0) {
    echo "Found " . $duplicates->count() . " duplicate businesses:\n";
    
    foreach ($duplicates as $business) {
        echo "ID: " . $business->id . " | Name: " . $business->name . " | Slug: " . $business->slug . "\n";
        echo "Created: " . $business->created_at . "\n";
        echo "---\n";
    }
    
    // Keep the first one, delete the rest
    $first = $duplicates->first();
    $toDelete = $duplicates->slice(1);
    
    if ($toDelete->count() > 0) {
        echo "Deleting " . $toDelete->count() . " duplicates...\n";
        foreach ($toDelete as $business) {
            $business->delete();
            echo "Deleted business ID: " . $business->id . "\n";
        }
        echo "Duplicates removed successfully!\n";
    } else {
        echo "No duplicates to delete (only one record found).\n";
    }
} else {
    echo "No businesses found with slug 'eduhub'.\n";
}

echo "Done.\n";
