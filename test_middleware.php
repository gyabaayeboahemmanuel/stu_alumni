<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Middleware Verification Test ===\n\n";

// Test 1: Check if AdminMiddleware exists
try {
    $middleware = new \App\Http\Middleware\AdminMiddleware();
    echo "âœ… AdminMiddleware class exists and can be instantiated\n";
} catch (Exception $e) {
    echo "âŒ AdminMiddleware error: " . $e->getMessage() . "\n";
}

// Test 2: Check if middleware is registered in Kernel
$kernel = $app->make(\App\Http\Kernel::class);
$middlewareAliases = $kernel->getMiddlewareAliases();

if (isset($middlewareAliases['admin'])) {
    echo "âœ… 'admin' middleware alias is registered in Kernel\n";
    echo "   Maps to: " . $middlewareAliases['admin'] . "\n";
} else {
    echo "âŒ 'admin' middleware alias is NOT registered in Kernel\n";
}

// Test 3: Check route definitions
echo "\n=== Route Middleware Check ===\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();

$adminRoutes = [];
foreach ($routes->getRoutes() as $route) {
    if (in_array('admin', $route->middleware())) {
        $adminRoutes[] = $route->uri();
    }
}

if (count($adminRoutes) > 0) {
    echo "âœ… Found " . count($adminRoutes) . " routes using 'admin' middleware:\n";
    foreach ($adminRoutes as $uri) {
        echo "   - " . $uri . "\n";
    }
} else {
    echo "âŒ No routes found using 'admin' middleware\n";
}

echo "\nDone.\n";
