<?php
// app/models/Project.php

class Project {
    private $conn;
    private $table_name = "proyek";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ==========================================================
    // 1. DASHBOARD METHODS
    // ==========================================================
    
    public function countAll() {
        try {
            $sql = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE deleted_at IS NULL";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting projects: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * PERUBAHAN: Mengambil data ringkasan dari Materialized View (mv_summary_proyek)
     */
    public function getLatest() {
        // MENGGUNAKAN MATERIALIZED VIEW UNTUK KECEPATAN:
        $sql = "SELECT m.nama_proyek, p.penanggung_jawab AS pj,
                        CASE 
                            WHEN p.tanggal_selesai < CURRENT_DATE AND m.progress < 100 THEN 'Overdue'
                            WHEN m.progress >= 100 THEN 'Completed'
                            WHEN p.tanggal_mulai > CURRENT_DATE THEN 'Upcoming'
                            ELSE 'Active'
                        END AS status,
                        m.progress
                FROM mv_summary_proyek m 
                JOIN proyek p ON m.id_proyek = p.id_proyek -- Join ke tabel proyek asli untuk tanggal & PJ
                WHERE m.progress < 100
                ORDER BY 
                    CASE WHEN m.progress > 0 THEN 1 ELSE 0 END DESC, 
                    p.updated_at DESC
                    LIMIT 5
                ";

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
    // 1.5 METODE MATERIALIZED VIEW (BARU)
    // ==========================================================
    
    /**
     * Menjalankan perintah REFRESH MATERIALIZED VIEW mv_summary_proyek.
     */
    public function refreshMView() {
        try {
            $sql = "REFRESH MATERIALIZED VIEW mv_summary_proyek"; 
            $this->conn->exec($sql); 
            return true;
        } catch (PDOException $e) {
            error_log("Error refreshing MView: " . $e->getMessage());
            return false;
        }
    }

    // ==========================================================
    // 2. CRUD METHODS
    // ==========================================================
    
    /**
     * Mengambil semua data proyek (dengan fitur pencarian opsional)
     * PERUBAHAN: LEFT JOIN ke M-View untuk mengambil total_tugas dan selesai.
     */
    public function all($keyword = null) {
        try {
            // LEFT JOIN ke M-View untuk mendapatkan total_tugas dan selesai
            $sql = "SELECT p.*, a.nama AS nama_pj, m.total_tugas, m.selesai AS tugas_selesai
                    FROM " . $this->table_name . " p
                    LEFT JOIN anggota_tim a ON p.penanggung_jawab = a.id_anggota
                    LEFT JOIN mv_summary_proyek m ON p.id_proyek = m.id_proyek -- JOIN M-VIEW
                    WHERE p.deleted_at IS NULL";
            
            // Tambahkan logika pencarian jika keyword ada
            if ($keyword) {
                $sql .= " AND (p.nama_proyek ILIKE :keyword OR a.nama ILIKE :keyword)";
            }

            $sql .= " ORDER BY p.id_proyek DESC";
            
            $stmt = $this->conn->prepare($sql);

            // Binding parameter jika ada keyword
            if ($keyword) {
                $searchTerm = "%" . $keyword . "%";
                $stmt->bindParam(':keyword', $searchTerm);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error Project all(): " . $e->getMessage());
            return [];
        }
    }

    public function find($id) {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE id_proyek = :id AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            $this->conn->beginTransaction();
            $sql = "INSERT INTO " . $this->table_name . " 
                    (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, id_klien, id_tim, budget, penanggung_jawab) 
                    VALUES (:nama, :deskripsi, :mulai, :selesai, :klien, :tim, :budget, :pj)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error creating project: " . $e->getMessage());
            return false;
        }
    }

    public function update($data) {
        try {
            $sql = "UPDATE " . $this->table_name . " SET 
                    nama_proyek = :nama, 
                    deskripsi = :deskripsi, 
                    tanggal_mulai = :mulai, 
                    tanggal_selesai = :selesai, 
                    id_klien = :klien, 
                    id_tim = :tim, 
                    budget = :budget, 
                    penanggung_jawab = :pj,
                    updated_at = NOW()
                    WHERE id_proyek = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Error updating project: " . $e->getMessage());
            return false;
        }
    }

    public function archive($id) {
        try {
            $sql = "CALL sp_arsipkan_proyek(:id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error archiving project: " . $e->getMessage());
            return false;
        }
    }

    public function getArchived() {
        try {
            $sql = "SELECT * FROM " . $this->table_name . " 
                    WHERE deleted_at IS NOT NULL 
                    ORDER BY deleted_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error getArchived: " . $e->getMessage());
            return [];
        }
    }

    public function restore($id) {
        try {
            $sql = "UPDATE " . $this->table_name . " SET deleted_at = NULL WHERE id_proyek = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error restoring project: " . $e->getMessage());
            return false;
        }
    }
}