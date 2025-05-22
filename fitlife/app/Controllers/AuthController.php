<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Session;
use App\Models\User; // Assuming the User model is in App\Models

class AuthController {
    private $userModel;
    private $session;
    private $view;

    public function __construct() {
        $this->userModel = new User();
        $this->session = new Session();
        $this->view = new View();
        // Load auth configuration for session keys
        // $this->authConfig = require BASE_PATH . '/config/auth.php';
    }

    /**
     * Show the login form.
     */
    public function showLoginForm() {
        // If user is already logged in, redirect them (e.g., to dashboard)
        if ($this->session->has('user_auth_id')) { // Replace 'user_auth_id' with config key
            redirect(base_url('user/dashboard')); // Example redirect
            return;
        }
        $this->view->render('auth.login', ['pageTitle' => 'Login']);
    }

    /**
     * Handle the login request.
     */
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('login')); // Or show 405 Method Not Allowed
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']); // Check if "remember me" is ticked

        if (empty($email) || empty($password)) {
            $this->session->flash('error_message', 'Email and password are required.');
            $this->session->set('_old_input', $_POST);
            redirect(base_url('login'));
            return;
        }

        $user = $this->userModel->verifyPassword($email, $password);

        if ($user) {
            // Password matches, log the user in
            $this->session->regenerate(true); // Regenerate session ID for security
            // Use $user->id as per the User model's property for the user's ID
            $this->session->set('user_auth_id', $user->id); 
            // Use $user->username as the display name, or $user->first_name as fallback
            $displayName = $user->username ?? ($user->first_name ?? 'User');
            $this->session->set('user_name', $displayName); 

            // Store user role from the user object
            if (isset($user->role)) {
                $this->session->set('user_role', $user->role);
            } else {
                // Fallback or default role if not set, though it should be from the DB
                $this->session->set('user_role', 'user'); 
                error_log("User role not found for user ID: " . ($user->id) . ". Defaulting to 'user'.");
            }

            // Handle "remember me" if applicable (implementation details for remember me are more complex)
            if ($remember) {
                // This is a simplified placeholder. Real remember-me needs secure tokens.
                // $auth_config = require BASE_PATH . '/config/auth.php';
                // $cookieName = $auth_config['remember_cookie_name'];
                // $cookieDuration = $auth_config['remember_duration'];
                // $token = bin2hex(random_bytes(32)); // Generate a secure token
                // Store token in DB associated with user_id
                // setcookie($cookieName, $user->id . ':' . $token, time() + $cookieDuration, "/", "", isset($_SERVER['HTTPS']), true);
                 $this->session->flash('info_message', 'Remember me selected (feature placeholder).'); // Changed to info_message
            }
            
            $this->session->flash('success_message', 'Login successful! Welcome back, ' . htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') . '.');

            // Redirect based on role
            $userRole = $this->session->get('user_role');
            if ($userRole === 'admin') {
                redirect(base_url('admin/dashboard'));
            } elseif ($userRole === 'trainer') {
                redirect(base_url('trainer/dashboard')); // New redirect for trainers
            } else {
                redirect(base_url('user/dashboard'));
            }
        } else {
            // Login failed
            $this->session->flash('error_message', 'Invalid email or password.');
            $this->session->set('_old_input', $_POST); // Keep email in form
            redirect(base_url('login'));
        }
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm() {
        if ($this->session->has('user_auth_id')) {
            redirect(base_url('user/dashboard'));
            return;
        }
        $this->view->render('auth.register', ['pageTitle' => 'Register']);
    }

    /**
     * Handle the registration request.
     */
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('register'));
            return;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $age = $_POST['age'] ?? null;
        $gender = $_POST['gender'] ?? null;

        // Basic Validation
        $errors = [];
        if (empty($name)) $errors['name'] = 'Name is required.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required.';
        if (empty($password)) $errors['password'] = 'Password is required.';
        if (strlen($password) < 6) $errors['password_length'] = 'Password must be at least 6 characters.'; // Example rule
        if ($password !== $confirm_password) $errors['confirm_password'] = 'Passwords do not match.';
        if (!empty($age) && !filter_var($age, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) $errors['age'] = 'Age must be a positive number.';


        if (!empty($errors)) {
            $this->session->flash('error_messages', $errors);
            $this->session->set('_old_input', $_POST);
            redirect(base_url('register'));
            return;
        }

        // Check if user already exists
        if ($this->userModel->findByEmail($email)) {
            $this->session->flash('error_message', 'An account with this email already exists.');
            $this->session->set('_old_input', $_POST);
            redirect(base_url('register'));
            return;
        }
        
        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $password, // Model will hash this
            'age' => $age ? (int)$age : null,
            'gender' => $gender
        ];

        $userId = $this->userModel->create($userData);

        if ($userId) {
            // Optionally log the user in directly after registration
            $this->session->regenerate(true);
            $this->session->set('user_auth_id', $userId);
            $this->session->set('user_name', $name);

            $this->session->flash('success_message', 'Registration successful! Welcome to FitLife.');
            redirect(base_url('user/dashboard')); // Redirect to dashboard
        } else {
            $this->session->flash('error_message', 'Registration failed. Please try again.');
            $this->session->set('_old_input', $_POST);
            redirect(base_url('register'));
        }
    }

    /**
     * Handle user logout.
     */
    public function logout() {
        // Clear "remember me" cookie if implemented
        // $auth_config = require BASE_PATH . '/config/auth.php';
        // $cookieName = $auth_config['remember_cookie_name'];
        // if (isset($_COOKIE[$cookieName])) {
        //     // Invalidate cookie and remove from DB if storing tokens
        //     setcookie($cookieName, '', time() - 3600, "/");
        // }

        $this->session->destroy();
        $this->session->flash('success_message', 'You have been logged out successfully.');
        redirect(base_url('login'));
    }
}

?>
