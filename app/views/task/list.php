<?php 
// app/views/task/list.php
// Variabel $tasks harus tersedia di sini dari TaskController
$tasks = $tasks ?? []; 
?>

<div class="content"> <!-- PENTING: Pembungkus Konten Utama -->
    <h1>Daftar Tugas</h1>

    <!-- Tombol Tambah Tugas -->
    <a href="index.php?page=task_create" class="btn primary-btn" style="margin-bottom: 20px; display: inline-block;">
        + Tambah Tugas Baru
    </a>

    <!-- Pembungkus Card untuk tabel -->
    <div class="card">
        
        <table class="project-list-table" style="width:100%;">
            <thead>
                <tr>
                    <th style="width: 25%;">Nama Tugas</th>
                    <th style="width: 15%;">Proyek</th>
                    <th style="width: 15%;">Ditugaskan Kepada</th>
                    <th style="width: 15%;">Deadline</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Progress</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php if (!empty($tasks)): ?>
                <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['nama_tugas'] ?? '') ?></td>
                    <td><?= htmlspecialchars($task['nama_proyek'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($task['nama_anggota'] ?? 'Belum Ditugaskan') ?></td>
                    <td><?= htmlspecialchars($task['deadline'] ?? '') ?></td>
                    
                    <td>
                        <!-- Tampilkan Status dengan class warna jika perlu (e.g., status-overdue) -->
                        <span class="status-<?= strtolower($task['nama_status'] ?? 'pending') ?>">
                            <?= htmlspecialchars($task['nama_status'] ?? 'Pending') ?>
                        </span>
                    </td>

                    <td>
                        <!-- Kolom Progress Bar -->
                        <?php $progress_value = $task['progress_percent'] ?? 0; ?>
                        <div class="progress-bar-bg">
                             <div class="progress-fill" style="width: <?= $progress_value ?>%;"></div>
                        </div>
                        <small style="display:block; text-align:right; font-weight:bold;"><?= $progress_value ?>%</small>
                    </td>

                    <td>
                        <a href="index.php?page=task_edit&id=<?= $task['id_tugas'] ?>" style="color: var(--primary-color);">Edit</a> |
                        <a href="index.php?page=task_delete&id=<?= $task['id_tugas'] ?>"
                           onclick="return confirm('Hapus tugas ini?')" style="color: var(--overdue-status);">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <!-- Menyatukan 7 kolom ketika data kosong -->
                    <td colspan="7" style="text-align:center; padding:30px; color: var(--light-text-color);">
                        Belum ada tugas yang tersedia.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>

        </table>
    </div> 
</div> <!-- PENUTUP CONTENT -->