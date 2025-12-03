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
    
    // PERBAIKAN: Menampilkan 3 Proyek Aktif Teratas
    public function getLatest() {
        // Logika:
        // 1. Ambil yang BELUM dihapus (deleted_at IS NULL)
        // 2. Ambil yang progressnya SUDAH ada (> 0) TAPI belum selesai (< 100)
        // 3. Urutkan dari yang progressnya paling tinggi (mendekati selesai)
        // 4. Jika tidak ada yang > 0, maka tampilkan yang paling baru diedit (updated_at)
        
        $sql = "SELECT nama_proyek, penanggung_jawab AS pj,
                        CASE 
                            WHEN tanggal_selesai < CURRENT_DATE AND progress < 100 THEN 'Overdue'
                            WHEN progress >= 100 THEN 'Completed'
                            WHEN tanggal_mulai > CURRENT_DATE THEN 'Upcoming'
                            ELSE 'Active'
                        END AS status,
                        progress
                FROM " . $this->table_name . "
                WHERE deleted_at IS NULL 
                AND progress < 100  -- Jangan tampilkan yang sudah selesai di widget 'Active'
                ORDER BY 
                    CASE WHEN progress > 0 THEN 1 ELSE 0 END DESC, -- Prioritaskan yang sudah ada progress
                    updated_at DESC -- Kemudian yang paling baru diedit
                "; // Menampilkan maksimal 3 proyek aktif

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
    // 2. CRUD METHODS
    // ==========================================================
    
    /**
     * Mengambil semua data proyek (dengan fitur pencarian opsional)
     * Memenuhi spesifikasi: Search pada minimal 2 field (nama_proyek DAN nama_pj)
     */
    public function all($keyword = null) {
        try {
            $sql = "SELECT p.*, a.nama AS nama_pj 
                    FROM " . $this->table_name . " p
                    LEFT JOIN anggota_tim a ON p.penanggung_jawab = a.id_anggota
                    WHERE p.deleted_at IS NULL";
            
            // Tambahkan logika pencarian jika keyword ada
            if ($keyword) {
                // Menggunakan ILIKE untuk PostgreSQL (case-insensitive)
                // Mencari di nama_proyek ATAU nama anggota tim (penanggung jawab)
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