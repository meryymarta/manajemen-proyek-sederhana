<?php
// app/controllers/DashboardController.php

require_once __DIR__ . "/../models/Project.php";
require_once __DIR__ . "/../models/Task.php";
require_once __DIR__ . "/../models/Team.php";

class DashboardController {

    private $projectModel;
    private $taskModel;
    private $teamModel;

    public function __construct($db) {
        // Inisialisasi Model, berikan koneksi DB ke setiap Model
        $this->projectModel = new Project($db);
        $this->taskModel = new Task($db);
        $this->teamModel = new Team($db);
    }

    public function index() {
        // 1. Ambil data dari Model (Sesuai Prinsip MVC)
        $total_projects = $this->projectModel->countAll();
        $total_tasks = $this->taskModel->countAll();
        $total_teams = $this->teamModel->countAll();
        
        // Ambil data lain yang dibutuhkan View (contoh statis untuk demo)
        $latest_project = $this->projectModel->getLatest();
        $team_performance = $this->taskModel->getTasksByTeam();
        
        // Buat array data untuk dikirim ke View (lebih rapi)
        $data = [
            'total_projects' => $total_projects,
            'total_tasks' => $total_tasks,
            'total_teams' => $total_teams,
            'latest_project' => $latest_project,
            'team_performance' => $team_performance
        ];

        // 2. Muat View (Menggunakan __DIR__ untuk jalur yang aman dan konsisten)
        // Agar data bisa diakses di dashboard.php, kita gunakan fungsi extract().
        extract($data); 
        
        include __DIR__ . "/../views/layout/header.php";
        include __DIR__ . "/../views/dashboard.php";
        include __DIR__ . "/../views/layout/footer.php";
    }
}