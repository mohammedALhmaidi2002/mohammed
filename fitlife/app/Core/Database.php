<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $conn;

    // Database connection parameters will be loaded from config
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;
    private $options;

    private function __construct() {
        $configPath = BASE_PATH . '/config/database.php';
        if (!file_exists($configPath)) {
            // Fallback or error if config file is missing
            error_log('Database configuration file not found: ' . $configPath);
            die('Database configuration is missing. Please check the setup.');
        }
        
        $config = require $configPath;

        // Assuming 'mysql' is the driver we want to use, as per plan
        $dbConfig = $config['mysql'];

        $this->host = $dbConfig['host'];
        $this->db_name = $dbConfig['database'];
        $this->username = $dbConfig['username'];
        $this->password = $dbConfig['password'];
        $this->charset = $dbConfig['charset'] ?? 'utf8mb4'; // Default charset if not specified
        
        // Define default options
        $defaultOptions = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // Merge default options with options from config file
        // Options from config file will overwrite defaults if they exist
        $this->options = array_merge($defaultOptions, $dbConfig['options'] ?? []);


        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=' . $this->charset;
        
        try {
            $this->conn = new PDO($dsn, $this->username, $this->password, $this->options);
        } catch (PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            // In a real app, you might want to throw an exception or handle this more gracefully
            // For now, die is okay for setup, but not for production.
            die('Could not connect to the database. Error: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    private function __clone() {}
    public function __wakeup() {}
}

?>
