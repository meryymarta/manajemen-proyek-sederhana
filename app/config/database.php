<?php // app/config/database.php
class Database {
    private $host = "localhost";
    private $db = "manajemen_proyek_sederhana";
    private $user = "postgres";
    private $pass = "mery";
    public $conn;

    public function connect() {
        try {
            $this->conn = new PDO("pgsql:host={$this->host};dbname={$this->db}", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>