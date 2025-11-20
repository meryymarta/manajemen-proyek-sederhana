<?php 
// app/views/dashboard.php
// Pastikan variabel-variabel PHP: $total_projects, $total_tasks, $total_teams, $latest_project, $team_performance
// sudah tersedia melalui extract($data) di Controller.

// ASUMSI: Proyek aktif pertama adalah yang terbaru
$active_project = $latest_project[0] ?? ['nama_proyek' => 'No Active Project', 'progress' => 0];

$project_progress_percent = 65; 
$project_name_display = $active_project['nama_proyek'] ?? 'New Website';
?>

<div class="content">

    <h1>Dashboard</h1>
    
    <!-- BARIS 1: STATISTIK RINGKAS -->
    <div class="grid-3 stats-row">

        <!-- Card 1: Total Proyek -->
        <div class="card-dashboard">
            <h3>Total Proyek</h3>
            <p class="stat-value"><?= $total_projects ?? 0 ?></p>
        </div>

        <!-- Card 2: Total Tugas -->
        <div class="card-dashboard">
            <h3>Total Tugas</h3>
            <p class="stat-value"><?= $total_tasks ?? 0 ?></p>
        </div>

        <!-- Card 3: Total Tim -->
        <div class="card-dashboard">
            <h3>Total Tim</h3>
            <p class="stat-value"><?= $total_teams ?? 0 ?></p>
        </div>

    </div> <!-- Tutup grid-3 stats-row -->

    <!-- BARIS 2: PROJECT AKTIF & TEAM PERFORMANCE -->
    <div class="main-grid">
        
        <!-- KOLOM KIRI: PROJECT AKTIF -->
        <div class="active-project-card card">
            <h3>Active Project</h3>
            
            <div class="active-project-section">
                
                <div class="progress-info">
                    <span><?= htmlspecialchars($project_name_display) ?></span>
                    <span>Progress</span>
                </div>

                <div class="progress-bar-bg">
                    <div class="progress-fill" style="width: <?= $project_progress_percent ?>%;"></div>
                </div>

                <div class="progress-actions">
                    <a href="index.php?page=project_create" class="btn-add primary-btn">Add Project</a>
                    <a href="index.php?page=projects" class="btn secondary-btn">View Projects</a>
                </div>
            </div>

        </div>

</div> <!-- Tutup content -->