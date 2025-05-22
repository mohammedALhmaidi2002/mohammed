<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User {
    private $db;
    private $table = 'users'; // Table name as per SQL schema (corrected from 'user' to 'users')

    // User Properties (matching table columns)
    public $id; // Changed from user_id to id to match schema
    public $username; // Added username as per schema
    public $password_hash; // Changed from password to password_hash
    public $email;
    public $first_name; // Added
    public $last_name; // Added
    public $role; // Added
    public $date_of_birth; // Added
    public $gender;
    public $phone_number; // Added
    public $address; // Added
    public $profile_picture_url; // Added
    public $registration_date; // Added
    public $last_login; // Added
    public $is_active; // Added
    public $reset_token; // Added
    public $reset_token_expires_at; // Added


    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find a user by their email address.
     *
     * @param string $email
     * @return object|false User object if found, false otherwise.
     */
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            return $user ? $user : false;
        } catch (\PDOException $e) {
            error_log("Error in User::findByEmail(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find a user by their ID.
     *
     * @param int $id
     * @return object|false User object if found, false otherwise.
     */
    public function findById($id) { // Changed $user_id to $id
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1"); // Changed user_id to id
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Changed :user_id to :id
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            return $user ? $user : false;
        } catch (\PDOException $e) {
            error_log("Error in User::findById(): " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find a user by their username.
     *
     * @param string $username
     * @return object|false User object if found, false otherwise.
     */
    public function findByUsername($username) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username LIMIT 1");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            return $user ? $user : false;
        } catch (\PDOException $e) {
            error_log("Error in User::findByUsername(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new user.
     *
     * @param array $data (e.g., ['username' => 'johndoe', 'email' => 'john@example.com', 'password' => 'password123', ...])
     * @return int|false The ID of the newly created user, or false on failure.
     */
    public function create($data) {
        // Ensure all required fields are present
        if (empty($data['username']) || empty($data['email']) || empty($data['password_hash'])) { // Check for username and password_hash
            error_log("Error in User::create(): Missing required fields (username, email, password_hash).");
            return false;
        }

        // Password should already be hashed before calling this method, or hash it here.
        // For consistency with the property name, let's assume $data['password_hash'] is already hashed.
        // If $data['password'] is provided plain, then hash it:
        // $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO {$this->table} (username, email, password_hash, first_name, last_name, role, date_of_birth, gender, phone_number, address, profile_picture_url, is_active) 
                VALUES (:username, :email, :password_hash, :first_name, :last_name, :role, :date_of_birth, :gender, :phone_number, :address, :profile_picture_url, :is_active)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password_hash', $data['password_hash']); // Use password_hash
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':role', $data['role'] ?? 'client'); // Default role to client
            $stmt->bindParam(':date_of_birth', $data['date_of_birth']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':phone_number', $data['phone_number']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':profile_picture_url', $data['profile_picture_url'] ?? 'assets/images/default_profile.png'); // Default profile pic
            $stmt->bindParam(':is_active', $data['is_active'] ?? true, PDO::PARAM_BOOL); // Default to active
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (\PDOException $e) {
            error_log("Error in User::create(): " . $e->getMessage());
            if ($e->getCode() == 23000) { // SQLSTATE[23000]: Integrity constraint violation
                 if (strpos(strtolower($e->getMessage()), 'duplicate entry') !== false) {
                    if (strpos(strtolower($e->getMessage()), 'email') !== false) {
                        error_log("User::create() - Duplicate email: " . $data['email']);
                    } elseif (strpos(strtolower($e->getMessage()), 'username') !== false) {
                        error_log("User::create() - Duplicate username: " . $data['username']);
                    }
                 }
            }
            return false;
        }
    }

    /**
     * Verify user's password.
     *
     * @param string $loginIdentifier User's email or username.
     * @param string $plainPassword User's plain text password.
     * @return object|false User object if password matches, false otherwise.
     */
    public function verifyPassword($loginIdentifier, $plainPassword) {
        $user = $this->findByEmail($loginIdentifier);
        if (!$user) {
            $user = $this->findByUsername($loginIdentifier);
        }

        if ($user && isset($user->password_hash) && password_verify($plainPassword, $user->password_hash)) {
            return $user; // Password matches
        }
        return false; // Password does not match or user not found
    }
    
    // TODO: Add methods for updating user details, changing password, deleting user, etc.
    // public function update($id, $data) { ... }
    // public function changePassword($id, $newPassword) { ... }
    // public function delete($id) { ... }
}

?>
