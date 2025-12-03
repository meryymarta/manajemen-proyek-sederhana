<?php 
// app/models/Task.php

class Task {
    private $conn;
    private $table_name = "tugas";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ==========================================================
    // METODE KHUSUS UNTUK DASHBOARD
    // ==========================================================

    public function countAll() {
        try {
            $sql = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE deleted_at IS NULL";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting tasks: " . $e->getMessage());
            return 0;
        }
    }

    public function getTasksByTeam() {
        // PERBAIKAN: Menggunakan tabel 'anggota_tim' agar konsisten
        $sql = "
            SELECT 
                a.nama AS member_name, 
                COUNT(t.id_tugas) AS total_tasks
            FROM " . $this->table_name . " t
            JOIN anggota_tim a ON a.id_anggota = t.id_anggota
            WHERE t.deleted_at IS NULL
            GROUP BY a.nama
            ORDER BY total_tasks DESC
            LIMIT 4
        ";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching team performance: " . $e->getMessage());
            return [];
        }
    }

    // ==========================================================
    // METODE CRUD STANDAR
    // ==========================================================
    
    /**
     * Mengambil semua data tugas dengan fitur pencarian.
     * PERBAIKAN: Menambahkan JOIN ke anggota_tim dan status
     */
    public function all($keyword = null) {
        try {
            // Kita SELECT nama_anggota dan nama_status agar View tidak menampilkan "Belum Ditugaskan"
            $sql = "SELECT t.*, 
                           p.nama_proyek, 
                           a.nama AS nama_anggota, 
                           s.nama_status
                    FROM " . $this->table_name . " t 
                    JOIN proyek p ON p.id_proyek = t.id_proyek
                    LEFT JOIN anggota_tim a ON t.id_anggota = a.id_anggota
                    LEFT JOIN status s ON t.id_status = s.id_status
                    WHERE t.deleted_at IS NULL";
            
            // Logika Pencarian
            if ($keyword) {
                // ILIKE untuk case-insensitive di PostgreSQL
                // Filter berdasarkan Nama Tugas, Proyek, atau Anggota Tim
                $sql .= " AND (t.nama_tugas ILIKE :keyword 
                               OR p.nama_proyek ILIKE :keyword 
                               OR a.nama ILIKE :keyword)";
            }

            // Urutkan berdasarkan deadline terdekat, lalu status, lalu nama
            $sql .= " ORDER BY t.deadline ASC, t.id_tugas DESC";
            
            $stmt = $this->conn->prepare($sql);

            // Binding parameter
            if ($keyword) {
                $searchTerm = "%" . $keyword . "%";
                $stmt->bindParam(':keyword', $searchTerm);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("SQL Error Task all(): " . $e->getMessage());
            return [];
        }
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (nama_tugas, id_proyek, id_anggota, id_status, deadline, progress_percent, created_at)
                    VALUES (:nama, :proyek, :anggota, :status, :deadline, 0, NOW())";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Error creating task: " . $e->getMessage());
            return false;
        }
    }
}