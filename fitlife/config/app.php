<?php

// Application Configuration

return [
    'name' => 'FitLife',

    'base_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . 
                  ($_SERVER['HTTP_HOST'] ?? 'localhost') . 
                  str_replace('/public/index.php', '', dirname(htmlspecialchars($_SERVER['SCRIPT_NAME'], ENT_QUOTES, 'UTF-8'))),


    'timezone' => 'UTC', // Set your application's default timezone

    'debug' => true, // Set to false in production

    // Add other application-wide settings here
    // e.g., 'key' => 'YourSecretEncryptionKey', for encryption purposes
];

?>
