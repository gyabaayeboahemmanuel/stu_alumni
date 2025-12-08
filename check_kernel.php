<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Current Kernel Middleware Aliases ===\n\n";

$kernel = $app->make(\App\Http\Kernel::class);
$middlewareAliases = $kernel->getMiddlewareAliases();

foreach ($middlewareAliases as $alias => $class) {
    echo "$alias => $class\n";
}

echo "\nLooking for 'admin' alias...\n";
if (isset($middlewareAliases['admin'])) {
    echo "âœ… 'admin' alias found: " . $middlewareAliases['admin'] . "\n";
} else {
    echo "âŒ 'admin' alias NOT found in Kernel\n";
}

// Let's also check the actual Kernel file content
echo "\n=== Kernel File Content Check ===\n";
$kernelFile = file_get_contents('app/Http/Kernel.php');
if (strpos($kernelFile, "'admin' =>") !== false) {
    echo "âœ… 'admin' middleware found in Kernel.php file\n";
} else {
    echo "âŒ 'admin' middleware NOT found in Kernel.php file\n";
}
