<?php

namespace App\Core;

class Router {
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];
    protected $errorController = 'ErrorController'; // Default error controller
    protected $errorAction = 'notFound'; // Default action for 404

    public function __construct() {
        // Can set a custom error controller/action if needed
        // $this->errorController = \App\Controllers\MyErrorController::class; 
    }

    public static function load($file) {
        $router = new static;
        require $file; // $file will be something like routes/web.php
        return $router;
    }

    public function get($uri, $controllerAction) {
        $this->routes['GET'][$this->normalizeUri($uri)] = $controllerAction;
    }

    public function post($uri, $controllerAction) {
        $this->routes['POST'][$this->normalizeUri($uri)] = $controllerAction;
    }

    private function normalizeUri($uri) {
        // Remove trailing slashes and ensure leading slash
        $uri = trim($uri, '/');
        return '/' . $uri;
    }
    
    public function dispatch($uri = null, $method = null) {
        if ($uri === null) {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }
        $uri = $this->normalizeUri($uri);

        if ($method === null) {
            $method = $_SERVER['REQUEST_METHOD'];
        }

        if (array_key_exists($uri, $this->routes[$method])) {
            $this->callAction(
                ...explode('@', $this->routes[$method][$uri])
            );
            return;
        }

        // Handle 404 Not Found
        $this->handleNotFound();
    }

    protected function callAction($controller, $action) {
        // Prepend namespace to controller
        $controller = "App\Controllers\" . $controller;

        if (!class_exists($controller)) {
            error_log("Controller class {$controller} not found.");
            $this->handleError("Controller class {$controller} not found.");
            return;
        }

        $controllerInstance = new $controller();

        if (!method_exists($controllerInstance, $action)) {
            error_log("Action {$action} not found in controller {$controller}.");
            $this->handleError("Action {$action} not found in controller {$controller}.");
            return;
        }

        try {
            $controllerInstance->$action();
        } catch (\Exception $e) {
            error_log("Exception during action {$action} in controller {$controller}: " . $e->getMessage());
            $this->handleError($e->getMessage());
        }
    }
    
    protected function handleNotFound() {
        // This is a simplified version. A real app would have a dedicated error controller.
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        echo "<p>The page you requested could not be found.</p>";
        // Example: $this->callAction($this->errorController, $this->errorAction);
        exit;
    }

    protected function handleError($message = "An error occurred.") {
        // This is a simplified version.
        http_response_code(500);
        echo "<h1>500 Server Error</h1>";
        echo "<p>{$message}</p>";
        // Log the error message
        error_log("Router dispatch error: " . $message);
        exit;
    }
}

?>
