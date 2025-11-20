<?php 
// app/views/team/list.php
// Pastikan variabel $teams tersedia dari TeamController::index()
?>

<div class="content">
    <h1>Daftar Tim</h1>
    <a href="index.php?page=teams&action=create" class="btn primary-btn" style="margin-bottom: 20px;">+ Tambah Tim Baru</a>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Tim</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($teams)): ?>
                    <?php foreach ($teams as $team): ?>
                        <tr>
                            <td><?= htmlspecialchars($team['id_tim']) ?></td>
                            <td><?= htmlspecialchars($team['nama_tim']) ?></td>
                            <td><?= htmlspecialchars($team['deskripsi']) ?></td>
                            <td>
                                <a href="index.php?page=teams&action=edit&id=<?= $team['id_tim'] ?>">Edit</a> |
                                <a href="index.php?page=teams&action=delete&id=<?= $team['id_tim'] ?>" onclick="return confirm('Yakin ingin menghapus tim ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Belum ada data tim yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>