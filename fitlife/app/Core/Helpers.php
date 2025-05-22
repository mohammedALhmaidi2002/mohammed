<?php

// Define global helper functions here.
// They should be namespaced if you prefer, or global if loaded carefully.
// For simplicity in a small project, global functions can be acceptable if names are unique.

if (!function_exists('dd')) {
    /**
     * Dump and Die.
     * A common debugging helper.
     *
     * @param mixed ...$vars Variables to dump.
     * @return void
     */
    function dd(...$vars) {
        echo '<pre>';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        die();
    }
}

if (!function_exists('esc_html')) {
    /**
     * Escape HTML entities for secure output.
     *
     * @param string|null $string The string to escape.
     * @return string The escaped string.
     */
    function esc_html($string) {
        return htmlspecialchars((string)$string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('base_url')) {
    /**
     * Get the base URL of the application.
     * Assumes BASE_URL is defined in a config file or similar.
     *
     * @param string $path Optional path to append to the base URL.
     * @return string
     */
    function base_url($path = '') {
        // This will be improved once config/app.php is loaded.
        // For now, a simple guess.
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $script_name = dirname($_SERVER['SCRIPT_NAME']);
        
        // Remove /public if SCRIPT_NAME includes it (common in dev)
        if (basename($script_name) === 'public') {
            $script_name = dirname($script_name);
        }
        
        $base = rtrim("{$protocol}://{$host}{$script_name}", '/');
        
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a given URL.
     *
     * @param string $url
     * @return void
     */
    function redirect($url) {
        header("Location: " . $url);
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Retrieve an old input value, typically from a form submission
     * that failed validation. Requires session integration.
     *
     * @param string $key The key of the input.
     * @param mixed $default The default value if not found.
     * @return mixed
     */
    function old($key, $default = '') {
        // This is a placeholder. A real implementation would likely involve
        // storing form input in the session on validation failure.
        // For now, it might check $_REQUEST or a dedicated session flash.
        if (isset($_SESSION['_old_input'][$key])) {
            return esc_html($_SESSION['_old_input'][$key]);
        }
        return esc_html($default);
    }
}

// You can add more helpers like:
// - asset_url($path) for generating URLs to assets (CSS, JS, images)
// - route($name, $params = []) for generating URLs from named routes
// - csrf_token() for CSRF protection
// - __($key, $replacements = []) for localization

?>
