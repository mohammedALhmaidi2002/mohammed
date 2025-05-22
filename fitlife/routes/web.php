<?php

// Web Routes
// The $router variable is expected to be an instance of App\Core\Router,
// already instantiated in public/index.php before this file is required.

if (!isset($router) || !($router instanceof \App\Core\Router)) {
    // This is a fallback or error state if $router isn't properly set up.
    // In a real application, you might log this or throw an exception.
    // For now, we can try to instantiate it, though this indicates an issue in index.php setup.
    // error_log("Router not available in web.php. Attempting to re-initialize.");
    // $router = new \App\Core\Router(); 
    // This line above is problematic if index.php didn't set it up, as it won't be the same instance.
    // Best to ensure index.php correctly makes $router available in this scope.
    // For this task, we'll assume $router is correctly passed from index.php.
    if(!isset($router)) {
        // Attempt to start session if not already started, to ensure Session class can be used by redirect if needed.
        if (session_status() == PHP_SESSION_NONE && class_exists('\App\Core\Session')) {
             new \App\Core\Session(); // Starts session if Session class constructor does it
        }
        // Ensure helpers are loaded for redirect() and base_url() if used in die message or error page
        if (!function_exists('redirect') && file_exists(BASE_PATH . '/app/Core/Helpers.php')) {
            require_once BASE_PATH . '/app/Core/Helpers.php';
        }
        die("<h1>Router not initialized</h1><p>The router object is not available in web.php. Please check public/index.php.</p>");
    }
}

// --- Authentication Routes ---
// Login
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@handleLogin');

// Register
$router->get('/register', 'AuthController@showRegisterForm');
$router->post('/register', 'AuthController@handleRegister');

// Logout
$router->get('/logout', 'AuthController@logout'); // Typically GET for simplicity, though POST with CSRF is safer

// --- Placeholder for a Home Page Route ---
$router->get('/', function() {
    // For now, a simple welcome message.
    // Later, this could be 'PageController@home' or similar.
    echo "<h1>Welcome to FitLife!</h1><p>Routes are being set up.</p>";
    echo "<p><a href='" . base_url('login') . "'>Login</a> or <a href='" . base_url('register') . "'>Register</a></p>";
    // Example of using the View class directly for a simple page:
    // (new \App\Core\View())->render('home.index', ['pageTitle' => 'Home']);
});


// --- Placeholder for User Dashboard (requires auth) ---
$router->get('/user/dashboard', function() {
    // Ensure session is started to check $_SESSION variables
    if (session_status() == PHP_SESSION_NONE && class_exists('\App\Core\Session')) {
        new \App\Core\Session(); // This will start session if Session class constructor does it
    }

    if (!isset($_SESSION['user_auth_id'])) { // Basic auth check
        redirect(base_url('login'));
        return;
    }
    echo "<h1>User Dashboard</h1><p>Welcome, user " . htmlspecialchars($_SESSION['user_name'] ?? ($_SESSION['user_username'] ?? ''), ENT_QUOTES, 'UTF-8') . " (ID: " . htmlspecialchars($_SESSION['user_auth_id'], ENT_QUOTES, 'UTF-8') . ")</p>";
    echo "<a href='" . base_url('logout') . "'>Logout</a>";
    // Later, this would be 'UserController@dashboard'
    // (new \App\Core\View())->render('user.dashboard', ['pageTitle' => 'Dashboard']);
});


// --- Example of how other routes will be added ---
// $router->get('/about', 'PageController@about');
// $router->get('/contact', 'PageController@contact');

// $router->get('/exercises', 'ExerciseController@index');
// $router->get('/exercises/{id}', 'ExerciseController@show'); // Example with parameter (router needs to support this)

// If your router supports parameters like {id}, the dispatch logic in Router.php
// would need to be enhanced to handle regex matching and parameter extraction.
// For now, the current Router.php handles exact matches.

?>
