<?php
// Quick verification script
require_once __DIR__.'/vendor/autoload.php';

try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $middleware = $kernel->getRouteMiddleware();
    
    echo "Checking admin middleware registration... ";
    if (isset($middleware['admin'])) {
        echo "âœ… SUCCESS - Admin middleware is registered!\n";
        echo "Middleware class: " . $middleware['admin'] . "\n";
    } else {
        echo "âŒ FAILED - Admin middleware not found in routeMiddleware\n";
        echo "Available middleware: " . implode(', ', array_keys($middleware)) . "\n";
    }
} catch (Exception $e) {
    echo "âŒ ERROR: " . $e->getMessage() . "\n";
}
