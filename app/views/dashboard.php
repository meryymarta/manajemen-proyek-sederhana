<?php 
// app/views/dashboard.php
// $latest_project berisi array proyek (maksimal 3) dari ProjectModel
?>

<div class="content">

    <h1 style="margin-bottom: 25px;">Dashboard</h1>
    
    <!-- BARIS 1: STATISTIK -->
    <div class="stats-grid">
        <div class="card-dashboard">
            <h3>Total Proyek</h3>
            <p class="stat-value"><?= $total_projects ?? 0 ?></p>
        </div>

        <div class="card-dashboard">
            <h3>Total Tugas</h3>
            <p class="stat-value"><?= $total_tasks ?? 0 ?></p>
        </div>

        <div class="card-dashboard">
            <h3>Total Tim</h3>
            <p class="stat-value"><?= $total_teams ?? 0 ?></p>
        </div>
    </div> 

    <!-- BARIS 2: PROJECT AKTIF -->
    <div class="main-grid">
        
        <!-- KIRI: ACTIVE PROJECTS (LIST) -->
        <div class="active-project-card">
            
            <h3 style="margin-bottom: 20px;">Active Projects (Sedang Berjalan)</h3>

            <?php if (!empty($latest_project)): ?>
                
                <!-- LOOPING UNTUK MENAMPILKAN SEMUA PROYEK AKTIF -->
                <?php foreach ($latest_project as $proj): ?>
                    <?php 
                        $prog = floatval($proj['progress']); 
                        $name = htmlspecialchars($proj['nama_proyek']);
                    ?>
                    
                    <!-- Item Proyek -->
                    <div style="margin-bottom: 25px; border-bottom: 1px solid #f0f0f0; padding-bottom: 15px;">
                        
                        <!-- Info Judul & Persen -->
                        <div class="progress-info" style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-size: 15px; color: #2d3436; font-weight: 600;">
                                <?= $name ?>
                            </span>
                            <span style="font-size: 14px; color: #4A8BFF; font-weight: 700;">
                                <?= $prog ?>%
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress-bar-bg" style="margin-bottom: 10px;">
                            <div class="progress-fill" style="width: <?= $prog ?>%;"></div>
                        </div>

                    </div>
                <?php endforeach; ?>

                <!-- Tombol Aksi di Bawah -->
                <div class="progress-actions" style="margin-top: 10px;">
                    <a href="index.php?page=project_create" class="btn primary-btn" style="font-size: 13px; padding: 10px 20px;">Add Project</a>
                    <a href="index.php?page=projects" class="btn secondary-btn" style="font-size: 13px; padding: 10px 20px;">View All</a>
                </div>

            <?php else: ?>
                <!-- Tampilan Jika Tidak Ada Proyek Aktif -->
                <div style="text-align: center; padding: 30px; color: #999;">
                    <p style="margin-bottom: 15px;">Belum ada proyek yang sedang berjalan.</p>
                    <a href="index.php?page=project_create" class="btn primary-btn">Mulai Proyek Baru</a>
                </div>
            <?php endif; ?>

        </div>
        
        <!-- KANAN: EMPTY CARD (Placeholder) -->
        <div class="empty-card">
            <p>Team Performance (Coming Soon)</p>
        </div>

    </div>

</div>