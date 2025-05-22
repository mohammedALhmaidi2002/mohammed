<?php

namespace App\Core;

class Session {

    public function __construct() {
        $this->startSession();
    }

    private function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            // Set session cookie parameters for security if needed
            // session_set_cookie_params([
            //     'lifetime' => 3600,
            //     'path' => '/',
            //     'domain' => YOUR_DOMAIN, // e.g., .example.com
            //     'secure' => isset($_SERVER['HTTPS']), // Send only over HTTPS
            //     'httponly' => true, // Prevent JavaScript access
            //     'samesite' => 'Lax' // Or 'Strict'
            // ]);
            session_start();
        }
    }

    /**
     * Set a session variable.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param string $key
     * @param mixed $default Default value if key not found.
     * @return mixed
     */
    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if a session variable exists.
     *
     * @param string $key
     * @return bool
     */
    public function has($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session variable.
     *
     * @param string $key
     */
    public function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy the entire session.
     */
    public function destroy() {
        if (session_status() == PHP_SESSION_ACTIVE) {
            // Unset all session variables
            $_SESSION = [];

            // Delete the session cookie
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // Finally, destroy the session
            session_destroy();
        }
    }

    /**
     * Flash a message to the session (retrieved once then removed).
     *
     * @param string $key
     * @param string $message
     */
    public function flash($key, $message) {
        $this->set('flash_' . $key, $message);
    }

    /**
     * Retrieve a flashed message.
     *
     * @param string $key
     * @param mixed $default Default value if not found.
     * @return mixed
     */
    public function getFlash($key, $default = null) {
        $message = $this->get('flash_' . $key, $default);
        $this->remove('flash_' . $key);
        return $message;
    }

    /**
     * Regenerate session ID (good for security, e.g., after login).
     * @param bool $deleteOldSession Whether to delete the old session file.
     */
    public function regenerate($deleteOldSession = true) {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_regenerate_id($deleteOldSession);
        }
    }
}

?>
