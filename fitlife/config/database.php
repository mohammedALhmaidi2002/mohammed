<?php

// Database Configuration

return [
    'driver' => 'mysql', // Or 'pgsql', 'sqlite', 'sqlsrv'

    'mysql' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'database' => $_ENV['DB_DATABASE'] ?? 'fitness_db',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => 'InnoDB', // Default engine for table creation
        'options' => [
            \PDO::ATTR_EMULATE_PREPARES => false, // Important for security and performance
            // Add other PDO options if needed
            // \PDO::ATTR_PERSISTENT => true, // For persistent connections (use with caution)
        ],
    ],

    // Example for SQLite (if you were to use it)
    // 'sqlite' => [
    //     'database' => BASE_PATH . '/database/production.sqlite', // Example path
    //     'prefix' => '',
    // ],
    
    // You can add configurations for other database systems like PostgreSQL, SQL Server, etc.
];

?>
