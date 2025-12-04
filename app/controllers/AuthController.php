<?php
require_once "../app/models/User.php";

class AuthController {
    private $db;
    private $userModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    // Tampilkan Halaman Login
    public function login() {
        // Jika sudah login, lempar ke dashboard
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?page=dashboard");
            exit;
        }
        include "../app/views/auth/login.php";
    }

    // Proses Verifikasi Login
    public function verify() {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 1. Cari user di database
        $user = $this->userModel->getByUsername($username);

        // 2. Cek apakah user ada DAN password cocok (verifikasi hash)
        if ($user && password_verify($password, $user['password'])) {
            // SUKSES: Simpan data di sesi
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama_lengkap'];
            
            header("Location: index.php?page=dashboard");
            exit;
        } else {
            // GAGAL
            header("Location: index.php?page=login&error=Username atau Password Salah");
            exit;
        }
    }

    // Proses Logout
    public function logout() {
        session_destroy(); // Hapus semua sesi
        header("Location: index.php?page=login");
        exit;
    }
    
    // FUNGSI RAHASIA: Buat bikin user pertama (Panggil sekali saja via URL)
    // Akses: index.php?page=seed_admin
    public function seedAdmin() {
        // Buat user: admin / admin123
        if ($this->userModel->register('admin', 'admin123', 'Super Admin')) {
            echo "User admin berhasil dibuat. Username: admin, Pass: admin123. <a href='index.php?page=login'>Login Disini</a>";
        } else {
            echo "Gagal atau user sudah ada.";
        }
    }
}