<?php
// app/models/Project.php
// Menggunakan CURRENT_DATE untuk PostgreSQL

class Project {
    private $conn;
    private $table_name = "proyek"; 

    public function __construct($db) {
        $this->conn = $db;
    }


    // ==========================================================
    // METODE KHUSUS UNTUK DASHBOARD (countAll, getLatest)
    // ==========================================================
    
    // DIPANGGIL OLEH DashboardController::index
    public function countAll() {
        try {
            $sql = "SELECT COUNT(*) FROM " . $this->table_name;
            $stmt = $this->conn->query($sql);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting projects: " . $e->getMessage());
            return 0; 
        }
    }
    
    // DIPANGGIL OLEH DashboardController::index
    public function getLatest() {
        // Menggunakan CURRENT_DATE untuk PostgreSQL
        $sql = "SELECT nama_proyek, penanggung_jawab AS pj, 
                       CASE 
                           WHEN tanggal_selesai < CURRENT_DATE AND tanggal_selesai IS NOT NULL THEN 'Overdue'
                           WHEN tanggal_mulai <= CURRENT_DATE AND tanggal_selesai >= CURRENT_DATE THEN 'Active'
                           ELSE 'Pending' 
                       END AS status 
                FROM " . $this->table_name . " 
                ORDER BY tanggal_mulai DESC 
                LIMIT 3";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error Project getLatest(): " . $e->getMessage());
            return []; 
        }
    }

    // ==========================================================
    // METODE CRUD STANDAR (DIPANGGIL OLEH ProjectController)
    // ==========================================================
    
    // GET ALL (DIPANGGIL OLEH ProjectController::index)
    public function all() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " ORDER BY id_proyek DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error Project all(): " . $e->getMessage());
            return [];
        }
    }

    // FIND BY ID (DIPANGGIL OLEH ProjectController::edit)
    public function find($id) {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE id_proyek = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREATE (DIPANGGIL OLEH ProjectController::store)
    public function create($data) {
        $sql = "INSERT INTO " . $this->table_name . " 
                (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, id_klien, id_tim, budget, penanggung_jawab)
                VALUES (:nama, :deskripsi, :mulai, :selesai, :klien, :tim, :budget, :pj)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // UPDATE (DIPANGGIL OLEH ProjectController::update)
    public function update($data) {
        $sql = "UPDATE " . $this->table_name . " SET 
                nama_proyek = :nama,
                deskripsi = :deskripsi,
                tanggal_mulai = :mulai,
                tanggal_selesai = :selesai,
                id_klien = :klien,
                id_tim = :tim,
                budget = :budget,
                penanggung_jawab = :pj
                WHERE id_proyek = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // DELETE (DIPANGGIL OLEH ProjectController::delete)
    public function delete($id) {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id_proyek = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}