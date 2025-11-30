<?php
class Report {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 1. Laporan Progress Keseluruhan Proyek
    public function getProjectOverall() {
        $sql = "SELECT nama_proyek, 
                       tanggal_mulai, 
                       tanggal_selesai, 
                       progress,
                       CASE 
                           WHEN progress = 100 THEN 'Selesai'
                           WHEN tanggal_selesai < CURRENT_DATE AND progress < 100 THEN 'Terlambat'
                           ELSE 'Berjalan'
                       END as status_waktu
                FROM proyek 
                WHERE deleted_at IS NULL
                ORDER BY progress DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Laporan Tugas Terlambat (Overdue)
    public function getOverdueTasks() {
        // Ambil tugas yang deadline < hari ini DAN progress belum 100%
        $sql = "SELECT t.nama_tugas, t.deadline, t.progress_percent, 
                       p.nama_proyek, 
                       a.nama as penanggung_jawab
                FROM tugas t
                JOIN proyek p ON t.id_proyek = p.id_proyek
                LEFT JOIN anggota_tim a ON t.id_anggota = a.id_anggota
                WHERE t.deadline < CURRENT_DATE 
                AND t.progress_percent < 100
                AND t.deleted_at IS NULL
                ORDER BY t.deadline ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Laporan Kinerja Tim
    public function getTeamPerformance() {
        // Hitung total tugas & tugas selesai per anggota
        $sql = "SELECT a.nama, 
                       COUNT(t.id_tugas) as total_tugas,
                       SUM(CASE WHEN t.progress_percent = 100 THEN 1 ELSE 0 END) as tugas_selesai,
                       SUM(CASE WHEN t.progress_percent < 100 AND t.deadline < CURRENT_DATE THEN 1 ELSE 0 END) as tugas_terlambat
                FROM anggota_tim a
                LEFT JOIN tugas t ON a.id_anggota = t.id_anggota AND t.deleted_at IS NULL
                GROUP BY a.id_anggota, a.nama
                ORDER BY tugas_selesai DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}