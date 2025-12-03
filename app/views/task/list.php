<?php 
// app/views/task/list.php
// Variabel $tasks harus tersedia di sini dari TaskController
$tasks = $tasks ?? []; 
?>

<div class="content"> <!-- PENTING: Pembungkus Konten Utama -->
    
    <h1 style="margin-bottom: 20px;">Daftar Tugas</h1>

    <!-- CONTAINER TOMBOL & SEARCH (Agar sejajar) -->
    <div style="margin-bottom: 25px; display: flex; align-items: center; flex-wrap: wrap; gap: 15px;">

        <!-- Tombol Tambah Tugas -->
        <a href="index.php?page=task_create" 
           class="btn btn-add-project" 
           style="display: inline-block; background: linear-gradient(135deg, #4A8BFF, #3A6FE0); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(74, 139, 255, 0.2);">
            + Tambah Tugas Baru
        </a>

        <!-- FORM PENCARIAN (BARU) -->
        <!-- margin-left: auto berfungsi mendorong form ini ke sisi paling kanan -->
        <form method="GET" action="index.php" style="display: flex; gap: 10px; margin-left: auto;">
            <input type="hidden" name="page" value="tasks">
            
            <input type="text" name="search" 
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" 
                   placeholder="Cari Tugas / Proyek..." 
                   style="padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; width: 250px; outline: none; transition: 0.3s;">
            
            <button type="submit" 
                    style="background: #334155; color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.2s;">
                Cari
            </button>
            
            <?php if(isset($_GET['search']) && $_GET['search'] != ''): ?>
                <a href="index.php?page=tasks" 
                   style="display: flex; align-items: center; justify-content: center; background: #ef4444; color: white; padding: 0 15px; border-radius: 8px; text-decoration: none; font-weight: bold;" 
                   title="Hapus Pencarian">X</a>
            <?php endif; ?>
        </form>

    </div>

    <!-- Pembungkus Card untuk tabel -->
    <div class="card">
        
        <div class="table-responsive">
            <!-- Gunakan class 'project-list-table' agar style sama dengan Proyek -->
            <table class="project-list-table" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width: 25%;">Nama Tugas</th>
                        <th style="width: 15%;">Proyek</th>
                        <th style="width: 15%;">Ditugaskan Kepada</th>
                        <th style="width: 15%;">Deadline</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Progress</th>
                        <th style="width: 10%; text-align: center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($tasks)): ?>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <!-- Nama Tugas -->
                                <td style="font-weight: 600; color: #2d3436;">
                                    <?= htmlspecialchars($task['nama_tugas'] ?? '') ?>
                                </td>
                                
                                <!-- Nama Proyek -->
                                <td style="color: #636e72; font-size: 13px;">
                                    <?= htmlspecialchars($task['nama_proyek'] ?? 'N/A') ?>
                                </td>
                                
                                <!-- Ditugaskan Kepada -->
                                <td>
                                    <?php if(!empty($task['nama_anggota'])): ?>
                                        <span style="background: #eef2ff; color: #4f46e5; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <?= htmlspecialchars($task['nama_anggota']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #999; font-style: italic; font-size: 12px;">Belum Ditugaskan</span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Deadline -->
                                <td style="color: #d63031; font-weight: 500; font-size: 13px;">
                                    <?= date('d M Y', strtotime($task['deadline'])) ?>
                                </td>
                                
                                <!-- Status (Badge Warna) -->
                                <td>
                                    <?php 
                                        $statusName = $task['nama_status'] ?? 'Pending';
                                        $statusColor = '#636e72'; // Default Abu
                                        $statusBg = '#f1f2f6';

                                        if (stripos($statusName, 'Done') !== false) {
                                            $statusColor = '#00b894'; $statusBg = '#daf7f0'; // Hijau
                                        } elseif (stripos($statusName, 'Progress') !== false) {
                                            $statusColor = '#0984e3'; $statusBg = '#e3f2fd'; // Biru
                                        } elseif (stripos($statusName, 'Overdue') !== false) {
                                            $statusColor = '#d63031'; $statusBg = '#fadbd8'; // Merah
                                        }
                                    ?>
                                    <span style="background: <?= $statusBg ?>; color: <?= $statusColor ?>; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                        <?= htmlspecialchars($statusName) ?>
                                    </span>
                                </td>

                                <!-- Progress Bar -->
                                <td>
                                    <?php $prog = floatval($task['progress_percent'] ?? 0); ?>
                                    <div class="progress-bar-bg" style="background: #e2e8f0; height: 6px; border-radius: 4px; overflow: hidden; width: 100%; margin-bottom: 3px;">
                                        <div class="progress-fill" style="width: <?= $prog ?>%; background: linear-gradient(90deg, #4A8BFF, #3A6FE0); height: 100%; border-radius: 4px;"></div>
                                    </div>
                                    <small style="font-weight: bold; color: #3b82f6; font-size: 11px;"><?= $prog ?>%</small>
                                </td>

                                <!-- Aksi -->
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                        <a href="index.php?page=task_edit&id=<?= $task['id_tugas'] ?>" 
                                           style="background: #f0f5ff; color: #4A8BFF; padding: 5px 10px; border-radius: 6px; text-decoration: none; font-size: 11px; font-weight: 600; border: 1px solid #dbeafe;">
                                           Edit
                                        </a>
                                        <a href="index.php?page=task_delete&id=<?= $task['id_tugas'] ?>"
                                           onclick="return confirm('Hapus tugas ini?')" 
                                           style="background: #fff1f2; color: #ff4757; padding: 5px 10px; border-radius: 6px; text-decoration: none; font-size: 11px; font-weight: 600; border: 1px solid #ffe4e6;">
                                           Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 40px; color: #94a3b8;">
                                <?php if(isset($_GET['search'])): ?>
                                    <div style="font-size: 15px; font-weight: 500; margin-bottom: 5px;">
                                        Tugas dengan kata kunci "<b><?= htmlspecialchars($_GET['search']) ?></b>" tidak ditemukan.
                                    </div>
                                <?php else: ?>
                                    <div style="font-size: 15px; font-weight: 500; margin-bottom: 5px;">Belum ada tugas</div>
                                    <div style="font-size: 13px;">Silakan tambah tugas baru untuk memulai.</div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div> 
</div>