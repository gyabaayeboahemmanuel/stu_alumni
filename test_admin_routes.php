<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Admin Route Access Test ===\n\n";

// Test 1: Check if we can access the admin dashboard route
try {
    $url = route('admin.dashboard');
    echo "âœ… Admin dashboard route exists: " . $url . "\n";
} catch (Exception $e) {
    echo "âŒ Admin dashboard route error: " . $e->getMessage() . "\n";
}

// Test 2: Try to make a request to admin route (without auth)
echo "\n=== Testing Admin Route Access ===\n";
try {
    $request = Illuminate\Http\Request::create('/admin/dashboard', 'GET');
    $response = $app->handle($request);
    
    if ($response->getStatusCode() === 302) {
        echo "âœ… Admin route redirects (expected for unauthenticated)\n";
        $location = $response->headers->get('Location');
        echo "   Redirects to: " . $location . "\n";
    } else {
        echo "âŒ Unexpected response: " . $response->getStatusCode() . "\n";
    }
} catch (Exception $e) {
    echo "âŒ Request failed: " . $e->getMessage() . "\n";
    echo "   This might indicate the middleware is working but there's an auth issue\n";
}

// Test 3: Check if middleware is properly applied to routes
echo "\n=== Route Middleware Assignment Test ===\n";
$routes = Illuminate\Support\Facades\Route::getRoutes();
$adminRoutes = [];

foreach ($routes->getRoutes() as $route) {
    $uri = $route->uri();
    if (strpos($uri, 'admin/') === 0) {
        $middleware = $route->gatherMiddleware();
        $adminRoutes[$uri] = $middleware;
    }
}

if (count($adminRoutes) > 0) {
    echo "Found " . count($adminRoutes) . " admin routes:\n";
    foreach ($adminRoutes as $uri => $middleware) {
        echo "- " . $uri . " => " . implode(', ', $middleware) . "\n";
        if (in_array('admin', $middleware)) {
            echo "  âœ… 'admin' middleware applied\n";
        } else {
            echo "  âŒ 'admin' middleware NOT applied\n";
        }
    }
} else {
    echo "âŒ No admin routes found\n";
}
