<?php

// Authentication Configuration

return [
    // Default authentication guard
    'guard' => 'user', // Can be 'user', 'admin', 'trainer'

    // Password hashing algorithm
    'password_algo' => PASSWORD_DEFAULT, // Or PASSWORD_BCRYPT, PASSWORD_ARGON2I, etc.
    'password_options' => [
        // Options for the chosen algorithm, e.g., 'cost' for BCRYPT
        // 'cost' => 12,
    ],

    // Session key for storing authenticated user ID
    'session_key' => [
        'user' => 'user_auth_id',
        'admin' => 'admin_auth_id',
        'trainer' => 'trainer_auth_id',
    ],
    
    // Remember me cookie name and duration (in seconds)
    'remember_cookie_name' => 'fitlife_remember_me',
    'remember_duration' => 60 * 60 * 24 * 30, // 30 days

    // Rate limiting for login attempts (example, not implemented by default core classes)
    'login_attempts' => 5, // Max attempts
    'login_lockout_time' => 60 * 15, // 15 minutes in seconds
];

?>
