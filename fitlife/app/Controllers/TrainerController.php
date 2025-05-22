<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Session;
// When specific trainer actions are added, the User model might be needed
// use App\Models\User;

class TrainerController {
    private $view;
    private $session;
    // private $userModel; // Uncomment and initialize if needed for trainer-specific data

    public function __construct() {
        $this->view = new View();
        $this->session = new Session();
        // $this->userModel = new \App\Models\User();

        // Basic Trainer Access Control:
        // Similar to AdminController, this is a rudimentary check.
        // Relies on AuthController setting 'user_role' in session.
        if (!$this->session->has('user_auth_id') || $this->session->get('user_role') !== 'trainer') {
            $this->session->flash('error_message', 'Access denied. You do not have trainer privileges.');
            if (!$this->session->has('user_auth_id')) {
                redirect(base_url('login')); // Redirect to user login
            } else {
                // If logged in but not a trainer, redirect to their own user dashboard
                redirect(base_url('user/dashboard')); 
            }
            exit; // Ensure no further code execution
        }
    }

    /**
     * Show the trainer dashboard.
     */
    public function dashboard() {
        // Data for the dashboard can be fetched here using models if needed
        $trainerName = $this->session->get('user_name', 'Trainer'); // Assuming user_name is stored

        $this->view->render('trainer.dashboard', [
            'pageTitle' => 'Trainer Dashboard',
            'trainerName' => $trainerName
        ]);
    }
    
    // Other trainer-specific methods will be added later, for example:
    // public function myClients() { ... }
    // public function manageWorkoutPlans() { ... }
    // public function manageDietaryGuidance() { ... }

}

?>
