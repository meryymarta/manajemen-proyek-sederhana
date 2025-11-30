<?php
// app/models/Team.php

class Team {
    private $conn;
    private $table_name = "tim"; 

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- FUNGSI PENTING UNTUK DASHBOARD ---
    // Fungsi inilah yang dicari oleh DashboardController
    public function countAll() {
        try {
            $sql = "SELECT COUNT(*) FROM " . $this->table_name;
            $stmt = $this->conn->query($sql);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting teams: " . $e->getMessage());
            return 0; 
        }
    }

    // --- AMBIL SEMUA DATA (Daftar Tim + Anggota) ---
    public function all() {
        // Menggunakan Sub-query agar aman dari error GROUP BY
        $sql = "SELECT 
                    t.id_tim,
                    t.nama_tim,
                    t.deskripsi,
                    (
                        SELECT string_agg(nama, ', ') 
                        FROM anggota_tim 
                        WHERE id_tim = t.id_tim
                    ) AS daftar_anggota,
                    (
                        SELECT COUNT(*) 
                        FROM anggota_tim 
                        WHERE id_tim = t.id_tim
                    ) AS jumlah_anggota
                FROM tim t
                ORDER BY t.id_tim DESC";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // --- FIND BY ID ---
    public function find($id) {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE id_tim = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- CREATE ---
    public function create($data) {
        try {
            $sql = "INSERT INTO " . $this->table_name . " (nama_tim, deskripsi) VALUES (:nama, :deskripsi)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':nama'      => $data['nama'],
                ':deskripsi' => $data['deskripsi']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // --- UPDATE ---
    public function update($data) {
        try {
            $sql = "UPDATE " . $this->table_name . " SET nama_tim = :nama, deskripsi = :deskripsi WHERE id_tim = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id'        => $data['id'],
                ':nama'      => $data['nama'],
                ':deskripsi' => $data['deskripsi']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // --- DELETE ---
    public function delete($id) {
        try {
            $sql = "DELETE FROM " . $this->table_name . " WHERE id_tim = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}