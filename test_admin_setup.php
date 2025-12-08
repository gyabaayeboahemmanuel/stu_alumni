<?php

// Test script to verify admin middleware setup
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

try {
    echo "Testing Admin Middleware Setup...\n";
    
    // Check if Role constants exist
    echo "1. Checking Role constants... ";
    if (defined('App\Models\Role::SUPER_ADMIN')) {
        echo "âœ… OK\n";
    } else {
        echo "âŒ MISSING\n";
    }
    
    // Check if User model has isAdmin method
    echo "2. Checking User model... ";
    $user = new User();
    if (method_exists($user, 'isAdmin')) {
        echo "âœ… OK\n";
    } else {
        echo "âŒ MISSING\n";
    }
    
    // Check if middleware is registered
    echo "3. Checking middleware registration... ";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $middleware = $kernel->getRouteMiddleware();
    if (isset($middleware['admin'])) {
        echo "âœ… OK\n";
    } else {
        echo "âŒ MISSING\n";
    }
    
    echo "\nâœ… Admin middleware setup complete!\n";
    
} catch (Exception $e) {
    echo "âŒ Error: " . $e->getMessage() . "\n";
}
