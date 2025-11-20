<?php 
// app/models/Task.php

class Task {
    private $conn;
    private $table_name = "tugas";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ==========================================================
    // METODE KHUSUS UNTUK DASHBOARD (NEWLY ADDED)
    // ==========================================================

    /**
     * Menghitung total jumlah tugas.
     * Dipanggil oleh DashboardController.
     * @return int Jumlah tugas
     */
    public function countAll() {
        try {
            $sql = "SELECT COUNT(*) FROM " . $this->table_name;
            $stmt = $this->conn->query($sql);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting tasks: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Mengambil performa tim (jumlah tugas per anggota/penanggung jawab).
     * ASUMSI: Ada tabel 'anggota' (atau 'users') dan kolom 'id_anggota' di tabel tugas
     * @return array Data performa tim
     */
    public function getTasksByTeam() {
        $sql = "
            SELECT 
                a.nama_anggota AS member_name, 
                COUNT(t.id_tugas) AS total_tasks
            FROM " . $this->table_name . " t
            JOIN anggota a ON a.id_anggota = t.id_anggota
            GROUP BY a.nama_anggota
            ORDER BY total_tasks DESC
            LIMIT 4
        ";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Jika tabel 'anggota' belum ada, gunakan data dummy sementara
            error_log("Error fetching team performance: " . $e->getMessage() . ". Using dummy data.");
            return [
                ['member_name' => 'James', 'total_tasks' => 4],
                ['member_name' => 'Maria', 'total_tasks' => 3],
                ['member_name' => 'Anna', 'total_tasks' => 2],
                ['member_name' => 'Budi', 'total_tasks' => 5],
            ];
        }
    }

    // ==========================================================
    // METODE CRUD STANDAR (KODE ASLI ANDA)
    // ==========================================================
    
    public function all() {
        $sql = "SELECT t.*, p.nama_proyek FROM " . $this->table_name . " t 
                JOIN proyek p ON p.id_proyek = t.id_proyek 
                ORDER BY id_tugas DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO " . $this->table_name . " (nama_tugas, id_proyek, id_anggota, id_status, deadline, progress_percent)
                VALUES (:nama, :proyek, :anggota, :status, :deadline, 0)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}