<?php

/**
 * User Model
 * Handles all database interactions for the users table.
 * All queries use PDO prepared statements to prevent SQL injection.
 */
class User {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Find a user by their email address.
     * @param string $email
     * @return object|bool User object or false if not found.
     */
    public function findUserByEmail(string $email): object|bool {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    /**
     * Register a new user. Password is hashed using bcrypt.
     * @param array $data Associative array with keys: name, email, password
     * @return bool True on success.
     */
    public function register(array $data): bool {
        $this->db->query('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password_hash', password_hash($data['password'], PASSWORD_BCRYPT));
        return $this->db->execute();
    }

    /**
     * Get a user by their ID.
     * @param int $id
     * @return object|bool
     */
    public function getUserById(int $id): object|bool {
        $this->db->query('SELECT id, name, email, created_at FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
