<?php
require_once "../app/models/Report.php";

class ReportController {
    private $db;
    private $reportModel;

    public function __construct($db) {
        $this->db = $db;
        $this->reportModel = new Report($db);
    }

    public function index() {
        // Ambil semua data laporan
        $projectProgress = $this->reportModel->getProjectOverall();
        $overdueTasks = $this->reportModel->getOverdueTasks();
        $teamPerformance = $this->reportModel->getTeamPerformance();

        // Kirim ke View
        include "../app/views/layout/header.php";
        include "../app/views/report/index.php"; // Kita buat file ini di langkah selanjutnya
        include "../app/views/layout/footer.php";
    }
}