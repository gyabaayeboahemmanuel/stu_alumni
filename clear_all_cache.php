<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Comprehensive Cache Clearing ===\n\n";

$commands = [
    'config:clear',
    'route:clear', 
    'cache:clear',
    'view:clear',
    'event:clear',
    'clear-compiled'
];

foreach ($commands as $command) {
    echo "Running: php artisan $command... ";
    try {
        \Illuminate\Support\Facades\Artisan::call($command);
        echo "âœ… Done\n";
    } catch (Exception $e) {
        echo "âŒ Failed: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Optimizing Application ===\n";
try {
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    echo "âœ… Config cached\n";
    
    \Illuminate\Support\Facades\Artisan::call('route:cache');
    echo "âœ… Routes cached\n";
    
    \Illuminate\Support\Facades\Artisan::call('view:cache');
    echo "âœ… Views cached\n";
} catch (Exception $e) {
    echo "âŒ Optimization failed: " . $e->getMessage() . "\n";
}

echo "\n=== Verification ===\n";
echo "Now run: php check_kernel.php\n";
echo "Then run: php test_middleware.php\n";
