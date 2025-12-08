<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

echo "=== Emergency Admin Middleware Fix ===\n\n";

// Method 1: Direct router registration
echo "Method 1: Direct router registration...\n";
try {
    $router = $app->make('router');
    $router->aliasMiddleware('admin', App\Http\Middleware\AdminMiddleware::class);
    echo "âœ… Direct router registration successful\n";
} catch (Exception $e) {
    echo "âŒ Direct registration failed: " . $e->getMessage() . "\n";
}

// Method 2: Check if it worked
echo "\nMethod 2: Verification...\n";
$middleware = $router->getMiddleware();
if (isset($middleware['admin'])) {
    echo "âœ… SUCCESS: 'admin' middleware is now registered!\n";
    echo "   Bound to: " . $middleware['admin'] . "\n";
} else {
    echo "âŒ FAILED: 'admin' middleware still not registered\n";
}

// Method 3: Test route resolution
echo "\nMethod 3: Testing route resolution...\n";
try {
    $request = Illuminate\Http\Request::create('/admin/dashboard', 'GET');
    $response = $app->handle($request);
    
    if ($response->getStatusCode() === 302) {
        echo "âœ… Route is working (redirects to login as expected)\n";
    } else {
        echo "âš ï¸  Route returned status: " . $response->getStatusCode() . "\n";
    }
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Target class [admin] does not exist') !== false) {
        echo "âŒ CRITICAL: Middleware still not resolved. Trying alternative approach...\n";
        
        // Alternative: Use the full class name in routes
        echo "\n=== Alternative: Using full class name in routes ===\n";
        $routesContent = file_get_contents('routes/web.php');
        $routesContent = str_replace(
            "Route::middleware(['auth', 'verified', 'admin'])",
            "Route::middleware(['auth', 'verified', \App\Http\Middleware\AdminMiddleware::class])",
            $routesContent
        );
        file_put_contents('routes/web.php', $routesContent);
        echo "âœ… Updated routes to use full class name\n";
    } else {
        echo "âŒ Route test error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Cache Clearing ===\n";
\Illuminate\Support\Facades\Artisan::call('route:clear');
\Illuminate\Support\Facades\Artisan::call('config:clear');
\Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "âœ… Caches cleared\n";

echo "\n=== Final Test ===\n";
try {
    $url = route('admin.dashboard');
    echo "âœ… Admin dashboard route: " . $url . "\n";
    echo "ðŸŽ‰ The admin middleware issue should be fixed!\n";
} catch (Exception $e) {
    echo "âŒ Final test failed: " . $e->getMessage() . "\n";
}
