<?php
// public/index.php
// File ini berfungsi sebagai Front Controller, menangani semua request.

// -----------------------------------------------------------------------------
// 1. INISIALISASI DAN AUTOLOAD
// -----------------------------------------------------------------------------

require_once "../app/config/database.php";
$db = (new Database())->connect(); // Koneksi database PDO

// ** PERBAIKAN PATH KRITIS: Menghitung Base URL secara dinamis **
$base_uri = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', $base_uri); 
// ** END PERBAIKAN PATH KRITIS **


// Autoload: Memuat Class Controller dan Model secara otomatis
spl_autoload_register(function ($class) {
    // Coba temukan di controllers
    if (file_exists(__DIR__ . '/../app/controllers/' . $class . '.php')) {
        require_once __DIR__ . '/../app/controllers/' . $class . '.php';
        return;
    }
    // Coba temukan di models
    if (file_exists(__DIR__ . '/../app/models/' . $class . '.php')) {
        require_once __DIR__ . '/../app/models/' . $class . '.php';
        return;
    }
});


// -----------------------------------------------------------------------------
// 2. LOGIKA ROUTING
// -----------------------------------------------------------------------------

$page = $_GET['page'] ?? 'dashboard';

try {
    switch ($page) {

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
            
            // Logika baru untuk menentukan aksi:
            // 1. Ambil kata setelah prefix (e.g., 'create', 'store', 'archive', 'archived')
            $action = str_replace('project_', '', $page);
            
            // 2. Jika $page hanya 'projects', aksi default adalah 'index'
            if ($page === 'projects') $action = 'index';
            
            // Panggilan ke ProjectController
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
            // Memanggil ReportController untuk menampilkan data laporan yang sesungguhnya
            require_once "../app/controllers/ReportController.php";
            (new ReportController($db))->index();
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
    include "../app/views/layout/header.php";
    echo "<div class='content'><h1>Error Aplikasi Internal</h1>";
    echo "<p>Pesan Teknis: " . htmlspecialchars($e->getMessage()) . "</p></div>";
    include "../app/views/layout/footer.php";
}