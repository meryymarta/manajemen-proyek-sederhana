<?php 
// Perlu ada Model Project di folder models
require_once '../app/models/Project.php';

class TaskController {
    private $db; 
    private $task; 
    private $project; // DITAMBAHKAN UNTUK REFRESH M-VIEW

    public function __construct($db) {
        $this->db = $db; 
        $this->task = new Task($db); 
        $this->project = new Project($db); // Inisialisasi Model Project
    }

    // --- READ (DAFTAR TUGAS) ---
    public function index() {
        $keyword = isset($_GET['search']) ? $_GET['search'] : null;
        $tasks = $this->task->all($keyword);

        include "../app/views/layout/header.php";
        include "../app/views/task/list.php"; 
        include "../app/views/layout/footer.php";
    }

    // --- CREATE (TAMBAH TUGAS) ---
    public function create() {
        // Ambil Data Dropdown (Query Asli)
        $projects = $this->db->query("SELECT * FROM proyek WHERE deleted_at IS NULL ORDER BY nama_proyek ASC")->fetchAll(PDO::FETCH_ASSOC);
        $members  = $this->db->query("SELECT * FROM anggota_tim ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
        $statuses = $this->db->query("SELECT * FROM status ORDER BY id_status ASC")->fetchAll(PDO::FETCH_ASSOC);

        include "../app/views/layout/header.php";
        include "../app/views/task/create.php";
        include "../app/views/layout/footer.php";
    }

    public function store() {
        if (empty($_POST['nama_tugas']) || empty($_POST['id_proyek'])) {
            header("Location: index.php?page=task_create&error=" . urlencode("Nama Tugas dan Proyek Wajib Diisi!"));
            return;
        }

        try {
            $sql = "INSERT INTO tugas (nama_tugas, id_proyek, id_anggota, id_status, deadline, progress_percent, created_at) 
                    VALUES (:nama, :proyek, :anggota, :status, :deadline, :progress, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nama'     => $_POST['nama_tugas'],
                ':proyek'   => $_POST['id_proyek'],
                ':anggota'  => !empty($_POST['id_anggota']) ? $_POST['id_anggota'] : null,
                ':status'   => $_POST['id_status'],
                ':deadline' => $_POST['deadline'],
                ':progress' => $_POST['progress_percent'] ?? 0
            ]);

            // --- REFRESH M-VIEW SETELAH INSERT ---
            $this->project->refreshMView(); 

            header("Location: index.php?page=tasks");

        } catch (PDOException $e) {
            header("Location: index.php?page=task_create&error=" . urlencode("Gagal: " . $e->getMessage()));
        }
    }

    // --- EDIT (UBAH TUGAS) ---
    public function edit() {
        $id = $_GET['id'];

        $stmt = $this->db->prepare("SELECT * FROM tugas WHERE id_tugas = :id");
        $stmt->execute([':id' => $id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            echo "Tugas tidak ditemukan.";
            return;
        }

        // Ambil Data Dropdown (Query Asli)
        $projects = $this->db->query("SELECT * FROM proyek WHERE deleted_at IS NULL ORDER BY nama_proyek ASC")->fetchAll(PDO::FETCH_ASSOC);
        $members  = $this->db->query("SELECT * FROM anggota_tim ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
        $statuses = $this->db->query("SELECT * FROM status ORDER BY id_status ASC")->fetchAll(PDO::FETCH_ASSOC);

        include "../app/views/layout/header.php";
        include "../app/views/task/edit.php"; 
        include "../app/views/layout/footer.php";
    }

    public function update() {
        $id = $_POST['id_tugas'];

        try {
            $sql = "UPDATE tugas SET 
                    nama_tugas = :nama,
                    id_proyek = :proyek,
                    id_anggota = :anggota,
                    id_status = :status,
                    deadline = :deadline,
                    progress_percent = :progress,
                    updated_at = NOW()
                    WHERE id_tugas = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id'       => $id,
                ':nama'     => $_POST['nama_tugas'],
                ':proyek'   => $_POST['id_proyek'],
                ':anggota'  => !empty($_POST['id_anggota']) ? $_POST['id_anggota'] : null,
                ':status'   => $_POST['id_status'],
                ':deadline' => $_POST['deadline'],
                ':progress' => $_POST['progress_percent']
            ]);
            
            // --- REFRESH M-VIEW SETELAH UPDATE ---
            $this->project->refreshMView(); 

            header("Location: index.php?page=tasks");

        } catch (PDOException $e) {
            header("Location: index.php?page=task_edit&id=$id&error=" . urlencode("Gagal update: " . $e->getMessage()));
        }
    }

    // --- DELETE (HAPUS TUGAS) ---
    public function delete() {
        $id = $_GET['id'];
        try {
            // Soft Delete (Isi deleted_at)
            $stmt = $this->db->prepare("UPDATE tugas SET deleted_at = NOW() WHERE id_tugas = :id");
            $stmt->execute([':id' => $id]);
            
            // --- REFRESH M-VIEW SETELAH DELETE ---
            $this->project->refreshMView(); 
            
            header("Location: index.php?page=tasks");
        } catch (PDOException $e) {
            echo "Gagal menghapus tugas.";
        }
    }
}
?>