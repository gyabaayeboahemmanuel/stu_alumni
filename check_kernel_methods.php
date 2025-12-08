<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Kernel Method Availability Check ===\n\n";

$kernel = $app->make(\App\Http\Kernel::class);

// Use reflection to access protected properties
$reflection = new ReflectionClass($kernel);
$properties = $reflection->getProperties(ReflectionProperty::IS_PROTECTED);

echo "Protected properties in Kernel:\n";
foreach ($properties as $property) {
    echo "- " . $property->getName() . "\n";
    
    if ($property->getName() === 'middlewareAliases' || $property->getName() === 'routeMiddleware') {
        $property->setAccessible(true);
        $value = $property->getValue($kernel);
        if (isset($value['admin'])) {
            echo "  âœ… 'admin' found in " . $property->getName() . "!\n";
            echo "  Value: " . $value['admin'] . "\n";
        } else {
            echo "  âŒ 'admin' NOT found in " . $property->getName() . "\n";
        }
    }
}

// Check methods
echo "\nMethods in Kernel:\n";
$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
foreach ($methods as $method) {
    if (strpos($method->getName(), 'Middleware') !== false) {
        echo "- " . $method->getName() . "\n";
    }
}

// Direct file check
echo "\n=== Direct Kernel File Analysis ===\n";
$kernelContent = file_get_contents('app/Http/Kernel.php');
if (preg_match("/'admin'\\s*=>\\s*[^,]+/", $kernelContent)) {
    echo "âœ… 'admin' middleware found in Kernel.php\n";
    
    // Extract the line
    preg_match("/.*'admin'\\s*=>\\s*[^,]+.*/", $kernelContent, $matches);
    if (!empty($matches)) {
        echo "   Line: " . trim($matches[0]) . "\n";
    }
} else {
    echo "âŒ 'admin' middleware NOT found in Kernel.php\n";
}

// Test if we can actually resolve the middleware
echo "\n=== Middleware Resolution Test ===\n";
try {
    $middleware = $app->make(\App\Http\Middleware\AdminMiddleware::class);
    echo "âœ… AdminMiddleware can be resolved from container\n";
} catch (Exception $e) {
    echo "âŒ AdminMiddleware resolution failed: " . $e->getMessage() . "\n";
}

// Test route middleware binding
echo "\n=== Route Middleware Binding Test ===\n";
try {
    $router = $app->make('router');
    $middleware = $router->getMiddleware();
    if (isset($middleware['admin'])) {
        echo "âœ… 'admin' middleware bound in router: " . $middleware['admin'] . "\n";
    } else {
        echo "âŒ 'admin' middleware NOT bound in router\n";
        echo "Available middleware aliases:\n";
        foreach ($middleware as $alias => $class) {
            echo "  $alias => $class\n";
        }
    }
} catch (Exception $e) {
    echo "âŒ Router middleware test failed: " . $e->getMessage() . "\n";
}
