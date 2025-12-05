<?php
require_once __DIR__ . '/../../core/Database.php';

class User {
    private $id, $username, $email, $password, $role, $created_at;

    public function __construct($id, $username, $email, $password, $role, $created_at) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->created_at = $created_at;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }
}

class UserRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($username, $email, $password, $role = 'user') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$username, $email, $hashedPassword, $role])) {
            $id = $this->db->lastInsertId();
            return new User($id, $username, $email, $hashedPassword, $role, date('Y-m-d H:i:s'));
        }
        return null;
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $data = $stmt->fetch();
        if ($data) {
            return new User($data['id'], $data['username'], $data['email'], $data['password'], $data['role'], $data['created_at']);
        }
        return null;
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch();
        if ($data) {
            return new User($data['id'], $data['username'], $data['email'], $data['password'], $data['role'], $data['created_at']);
        }
        return null;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if ($data) {
            return new User($data['id'], $data['username'], $data['email'], $data['password'], $data['role'], $data['created_at']);
        }
        return null;
    }
}