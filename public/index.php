<?php
// public/index.php
// File ini berfungsi sebagai Front Controller, menangani semua request.

// -----------------------------------------------------------------------------
// 1. MULAI SESI & INISIALISASI
// -----------------------------------------------------------------------------
session_start(); // WAJIB: Memulai sesi untuk login

require_once "../app/config/database.php";
$db = (new Database())->connect(); // Koneksi database PDO

// ** PERBAIKAN PATH KRITIS **
$base_uri = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', $base_uri); 
// ** END PERBAIKAN PATH KRITIS **


// Autoload: Memuat Class Controller dan Model secara otomatis
spl_autoload_register(function ($class) {
    if (file_exists(__DIR__ . '/../app/controllers/' . $class . '.php')) {
        require_once __DIR__ . '/../app/controllers/' . $class . '.php';
        return;
    }
    if (file_exists(__DIR__ . '/../app/models/' . $class . '.php')) {
        require_once __DIR__ . '/../app/models/' . $class . '.php';
        return;
    }
});


// -----------------------------------------------------------------------------
// 2. GATEKEEPER (CEK LOGIN)
// -----------------------------------------------------------------------------
$page = $_GET['page'] ?? 'dashboard';

// Daftar halaman yang boleh diakses TANPA login (Public)
$public_pages = ['login', 'auth_verify', 'seed_admin'];

// Jika user BELUM login DAN mencoba akses halaman rahasia -> Tendang ke Login
if (!isset($_SESSION['user_id']) && !in_array($page, $public_pages)) {
    header("Location: index.php?page=login");
    exit;
}


// -----------------------------------------------------------------------------
// 3. LOGIKA ROUTING
// -----------------------------------------------------------------------------

try {
    switch ($page) {

        /* ====== AUTHENTICATION ====== */
        case 'login':
            (new AuthController($db))->login();
            break;
        case 'auth_verify':
            (new AuthController($db))->verify();
            break;
        case 'logout':
            (new AuthController($db))->logout();
            break;
        case 'seed_admin': // Buat user pertama
            (new AuthController($db))->seedAdmin();
            break;

        /* ====== DASHBOARD ====== */
        case 'dashboard':
            (new DashboardController($db))->index();
            break;

        /* ====== PROJECT ROUTING ====== */
        case 'projects':
        case 'project_create':
        case 'project_store': 
        case 'project_edit':
        case 'project_update':
        case 'project_delete':
        case 'project_archive':   // Aksi Arsipkan (Soft Delete)
        case 'project_archived':  // Halaman Lihat Arsip (Recycle Bin)
        case 'project_restore':   // Aksi Pulihkan Data
            
            $action = str_replace('project_', '', $page);
            if ($page === 'projects') $action = 'index';
            
            (new ProjectController($db))->$action();
            break;

        /* ====== TASK ROUTING ====== */
        case 'tasks':
        case 'task_create':
        case 'task_store':
        case 'task_edit':
        case 'task_update':
        case 'task_delete':
            $action = str_replace('task_', '', $page);
            if ($page === 'tasks') $action = 'index';
            
            (new TaskController($db))->$action();
            break;
            
        /* ====== TEAM ROUTING ====== */
        case 'teams':
        case 'team_create':
        case 'team_store':
        case 'team_edit':
        case 'team_update':
        case 'team_delete':
            $action = str_replace('team_', '', $page);
            if ($page === 'teams') $action = 'index';
            
            (new TeamController($db))->$action();
            break;

        /* ====== REPORTS ====== */
        case 'reports':
            // Memanggil ReportController
            // Pastikan file app/controllers/ReportController.php sudah ada
            if (file_exists("../app/controllers/ReportController.php")) {
                require_once "../app/controllers/ReportController.php";
                (new ReportController($db))->index();
            } else {
                // Fallback jika belum ada controller report
                include "../app/views/layout/header.php";
                echo "<div class='content'><h1>Laporan Proyek (Under Construction)</h1></div>";
                include "../app/views/layout/footer.php";
            }
            break;

        default:
            // 404 Handler
            header("HTTP/1.0 404 Not Found");
            include "../app/views/layout/header.php";
            echo "<div class='content'><h1>404 Halaman Tidak Ditemukan</h1></div>";
            include "../app/views/layout/footer.php";
            break;
    }
} catch (Exception $e) {
    // Penanganan Error Aplikasi
    header("HTTP/1.0 500 Internal Server Error");
    
    // Cek jika header sudah dikirim (untuk menghindari error output)
    if (!headers_sent()) {
         include "../app/views/layout/header.php";
    }
    
    echo "<div class='content' style='padding:20px;'><h1>Error Aplikasi Internal</h1>";
    echo "<p>Pesan Teknis: " . htmlspecialchars($e->getMessage()) . "</p></div>";
    
    if (!headers_sent()) {
        include "../app/views/layout/footer.php";
    }
}