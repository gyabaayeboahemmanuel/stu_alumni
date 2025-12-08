<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

echo "=== Checking App Configuration ===\n\n";

$config = $app->make('config');
$providers = $config->get('app.providers');

echo "Registered Service Providers:\n";
foreach ($providers as $provider) {
    echo "- " . $provider . "\n";
}

// Check if our provider is there
if (in_array('App\Providers\MiddlewareServiceProvider', $providers)) {
    echo "\nâœ… MiddlewareServiceProvider is registered\n";
} else {
    echo "\nâŒ MiddlewareServiceProvider is NOT registered\n";
    echo "Adding it to config/app.php...\n";
    
    // Read the current config
    $appConfigPath = 'config/app.php';
    $appConfig = file_get_contents($appConfigPath);
    
    // Find the providers array and add our provider
    if (strpos($appConfig, 'App\Providers\MiddlewareServiceProvider::class') === false) {
        $appConfig = str_replace(
            "App\Providers\RouteServiceProvider::class,",
            "App\Providers\RouteServiceProvider::class,\n        App\Providers\MiddlewareServiceProvider::class,",
            $appConfig
        );
        file_put_contents($appConfigPath, $appConfig);
        echo "âœ… Added MiddlewareServiceProvider to config/app.php\n";
    }
}
