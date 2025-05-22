<?php

// Define BASE_PATH - Root of the project
define('BASE_PATH', realpath(__DIR__ . '/../'));

// Basic error reporting (for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload Core Classes (simple autoloader for now)
// In a larger project, Composer's autoloader would be used.
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'App\\';
    // Base directory for the namespace prefix
    $base_dir = BASE_PATH . '/app/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Load helper functions
require_once BASE_PATH . '/app/Core/Helpers.php';

// Load configuration (this will be more structured later)
// For now, we might not have separate config files fully integrated yet for Database.
// $config = require_once BASE_PATH . '/config/app.php'; // Example

// Start the session
// Note: Session class constructor automatically calls session_start()
$session = new App\Core\Session();

// Initialize the Router
$router = new App\Core\Router();

// Load web routes
// The Router::load method is a placeholder; we'll define routes directly for now or adjust.
// For this structure, let's assume routes/web.php will directly call $router->get(), $router->post()
require_once BASE_PATH . '/routes/web.php';


// Dispatch the request
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

try {
    $router->dispatch($request_uri, $request_method);
} catch (Exception $e) {
    // Log the error message
    error_log("Unhandled exception in index.php: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    
    // Display a generic error message to the user
    // In a real application, you would have a nicer error page.
    http_response_code(500);
    echo "<h1>500 Internal Server Error</h1>";
    echo "<p>An unexpected error occurred. We are working to fix the problem. Please try again later.</p>";
    // Optionally, if in development mode, display more details:
    // if (defined('APP_DEBUG') && APP_DEBUG) {
    //     echo "<pre>";
    //     echo "Error: " . $e->getMessage() . "\n";
    //     echo "File: " . $e->getFile() . "\n";
    //     echo "Line: " . $e->getLine() . "\n";
    //     echo "Trace: \n" . $e->getTraceAsString() . "\n";
    //     echo "</pre>";
    // }
}

?>
