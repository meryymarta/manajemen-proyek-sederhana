<?php 
$projects = $projects ?? []; 
?>
<div class="content"> 
    <h1>Daftar Proyek</h1>

    <a href="index.php?page=project_create" 
       class="btn primary-btn" 
       style="margin-bottom: 20px; display: inline-block;">
        + Tambah Proyek Baru
    </a>

    <div class="card">
        <table class="project-list-table" style="width:100%;">
            <thead>
                <tr>
                    <th>Nama Proyek</th>
                    <th>Budget</th>
                    <th>Tgl Mulai</th>
                    <th>Tgl Selesai</th>
                    <th>Progress</th>
                    <th>Penanggung Jawab</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $project): ?>
                <?php $progress_value = $project['progress_percent'] ?? 0; ?>

                <tr>
                    <td><?= htmlspecialchars($project['nama_proyek']) ?></td>

                    <td>Rp <?= number_format($project['budget'] ?? 0, 0, ',', '.') ?></td>

                    <td><?= htmlspecialchars($project['tanggal_mulai']) ?></td>

                    <td><?= htmlspecialchars($project['tanggal_selesai']) ?></td>

                    <td>
                        <div class="progress-bar-bg">
                            <div class="progress-fill" style="width: <?= $progress_value ?>%;"></div>
                        </div>
                        <small style="font-weight:bold;"><?= $progress_value ?>%</small>
                    </td>

                    <td><?= htmlspecialchars($project['penanggung_jawab']) ?></td>

                    <td>
                        <a href="index.php?page=project_edit&id=<?= $project['id_proyek'] ?>"
                           style="color: var(--primary-color); font-weight:bold;">
                           Edit
                        </a> |
                        <a href="index.php?page=project_delete&id=<?= $project['id_proyek'] ?>"
                           onclick="return confirm('Hapus proyek ini?')"
                           style="color: var(--overdue-status); font-weight:bold;">
                           Hapus
                        </a>
                    </td>
                </tr>

                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center; padding:30px; color: var(--light-text-color);">
                        Belum ada data proyek.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div> 
</div>
