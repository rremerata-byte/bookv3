<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// Test route registration
$routes = collect(Route::getRoutes()->getRoutes())->filter(function($route) {
    return str_contains($route->uri(), 'guard');
});

echo "Guard-related routes:\n";
foreach ($routes as $route) {
    echo "Method: {$route->methods()[0]}, URI: /{$route->uri()}, Name: {$route->getName()}\n";
}

echo "\nMiddleware test for guard role:\n";
try {
    $middleware = new App\Http\Middleware\CheckRole();
    echo "CheckRole middleware exists and is loadable.\n";
} catch (Exception $e) {
    echo "Error loading CheckRole middleware: " . $e->getMessage() . "\n";
}

// Test if view file exists
$viewPath = resource_path('js/Pages/Guard/Dashboard.vue');
if (file_exists($viewPath)) {
    echo "\nGuard Dashboard Vue file exists at: {$viewPath}\n";
    echo "File size: " . filesize($viewPath) . " bytes\n";
} else {
    echo "\nERROR: Guard Dashboard Vue file NOT found at: {$viewPath}\n";
}