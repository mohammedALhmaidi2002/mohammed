<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Session;
// No direct User model usage here yet, will be added as methods are built
// For admin authentication, AuthController would handle login and role check.

class AdminController {
    private $view;
    private $session;
    // private $userModel; // Uncomment and initialize in constructor when needed

    public function __construct() {
        $this->view = new View();
        $this->session = new Session();
        // $this->userModel = new \App\Models\User(); // If needed for admin-specific data fetching directly by AdminController

        // Basic Admin Access Control:
        // This is a very basic check. In a real application, this would be
        // handled by a dedicated middleware or a more robust role check in a BaseController
        // or directly within each method of controllers requiring admin access.
        // We also need to ensure the 'user_role' is set in session upon login by AuthController.
        if (!$this->session->has('user_auth_id') || $this->session->get('user_role') !== 'admin') {
            $this->session->flash('error_message', 'Access denied. You do not have admin privileges.');
            if (!$this->session->has('user_auth_id')) {
                redirect(base_url('login')); // Redirect to user login
            } else {
                redirect(base_url('user/dashboard')); // Redirect to user dashboard if logged in but not admin
            }
            exit; // Ensure no further code execution
        }
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard() {
        // Data for the dashboard can be fetched here using models if needed
        $adminName = $this->session->get('user_name', 'Admin'); // Assuming user_name is stored in session

        $this->view->render('admin.dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'adminName' => $adminName
        ]);
    }
    
    // Other admin-specific methods will be added later, for example:
    // public function manageUsers() { ... }
    // public function manageTrainers() { ... }
    // public function siteSettings() { ... }

}

?>
