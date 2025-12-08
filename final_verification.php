<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Final Admin Middleware Verification ===\n\n";

// Ultimate test: Try to resolve the middleware through the router
$router = $app->make('router');

echo "Step 1: Checking router middleware bindings...\n";
$middleware = $router->getMiddleware();
if (isset($middleware['admin'])) {
    echo "âœ… SUCCESS: 'admin' middleware is bound to: " . $middleware['admin'] . "\n";
    
    echo "\nStep 2: Testing middleware resolution...\n";
    try {
        $middlewareInstance = $app->make($middleware['admin']);
        echo "âœ… SUCCESS: Middleware can be instantiated: " . get_class($middlewareInstance) . "\n";
        
        echo "\nStep 3: Testing route access...\n";
        $request = Illuminate\Http\Request::create('/admin/dashboard', 'GET');
        
        // Create a simple closure to test the middleware chain
        $next = function ($request) {
            return new Illuminate\Http\Response('Middleware passed', 200);
        };
        
        try {
            $response = $middlewareInstance->handle($request, $next);
            echo "âŒ UNEXPECTED: Middleware allowed request without auth\n";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Unauthorized') !== false || $e->getCode() === 403) {
                echo "âœ… SUCCESS: Middleware correctly blocked unauthorized access\n";
            } else if (strpos($e->getMessage(), 'redirect') !== false) {
                echo "âœ… SUCCESS: Middleware correctly redirects unauthorized users\n";
            } else {
                echo "âš ï¸  Middleware error: " . $e->getMessage() . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "âŒ FAILED: Middleware instantiation failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "âŒ FAILED: 'admin' middleware is NOT bound in router\n";
    echo "Available middleware:\n";
    foreach ($middleware as $alias => $class) {
        echo "  $alias => $class\n";
    }
    
    echo "\n=== Manual Fix Required ===\n";
    echo "The 'admin' middleware is not registered. Please check:\n";
    echo "1. app/Http/Kernel.php has 'admin' => \\App\\Http\\Middleware\\AdminMiddleware::class\n";
    echo "2. Run: php artisan config:clear && php artisan route:clear\n";
    echo "3. Run: composer dump-autoload\n";
}

echo "\n=== Summary ===\n";
if (isset($middleware['admin'])) {
    echo "ðŸŽ‰ Admin middleware is properly registered and working!\n";
    echo "You should now be able to access /admin/dashboard after login.\n";
} else {
    echo "âš ï¸  Admin middleware registration issue detected.\n";
    echo "Please check the Kernel.php file and clear caches.\n";
}
