<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil data user berdasarkan username
    public function getByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk register user baru (buat bikin akun admin pertama kali)
    public function register($username, $password, $nama) {
        // Hash password agar aman
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("INSERT INTO users (username, password, nama_lengkap) VALUES (:u, :p, :n)");
        return $stmt->execute([
            ':u' => $username,
            ':p' => $hashed_password,
            ':n' => $nama
        ]);
    }
}