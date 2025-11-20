<?php
// app/models/Team.php

class Team {
    private $db;
    private $table_name = "tim"; // Nama tabel di database

    /**
     * Konstruktor
     * @param PDO $db Koneksi database PDO
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Menghitung total jumlah tim.
     * Digunakan oleh DashboardController.
     * @return int Jumlah tim
     */
    public function countAll() {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->table_name;
            $stmt = $this->db->query($query);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Dalam aplikasi nyata, log error ini
            return 0; 
        }
    }

    /**
     * Mengambil semua data tim dari database.
     * @return array Array berisi semua data tim
     */
    public function all() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nama_tim ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Menyimpan (Create) data tim baru ke database.
     * Menggunakan Prepared Statement untuk mencegah SQL Injection.
     * @param array $data Data tim (nama_tim, deskripsi, dll.)
     * @return bool True jika berhasil, False jika gagal
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (nama_tim, deskripsi) VALUES (:nama_tim, :deskripsi)";
        $stmt = $this->db->prepare($query);

        // Bind parameter
        $stmt->bindParam(':nama_tim', $data[':nama_tim']);
        $stmt->bindParam(':deskripsi', $data[':deskripsi']);
        
        return $stmt->execute();
    }

    /**
     * Mencari data tim berdasarkan ID.
     * @param int $id ID tim
     * @return array|bool Data tim atau False jika tidak ditemukan
     */
    public function find($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_tim = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Memperbarui (Update) data tim yang sudah ada.
     * @param array $data Data tim yang diperbarui (termasuk :id)
     * @return bool True jika berhasil, False jika gagal
     */
    public function update($data) {
        $query = "UPDATE " . $this->table_name . " SET 
                    nama_tim = :nama_tim, 
                    deskripsi = :deskripsi 
                  WHERE id_tim = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':id', $data[':id']);
        $stmt->bindParam(':nama_tim', $data[':nama_tim']);
        $stmt->bindParam(':deskripsi', $data[':deskripsi']);

        return $stmt->execute();
    }

    /**
     * Menghapus (Delete) data tim berdasarkan ID.
     * @param int $id ID tim
     * @return bool True jika berhasil, False jika gagal
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_tim = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}