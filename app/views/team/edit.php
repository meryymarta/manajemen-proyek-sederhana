<?php 
// app/views/team/edit.php
// Pastikan variabel $team tersedia dari TeamController::edit()
?>

<div class="content">
    <h1>Edit Tim: <?= htmlspecialchars($team['nama_tim'] ?? 'Tim') ?></h1>

    <div class="card">
        <form action="index.php?page=teams&action=update" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($team['id_tim'] ?? '') ?>">
            
            <div style="margin-bottom: 15px;">
                <label for="nama_tim" style="display: block; font-weight: bold;">Nama Tim:</label>
                <input type="text" id="nama_tim" name="nama_tim" value="<?= htmlspecialchars($team['nama_tim'] ?? '') ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="deskripsi" style="display: block; font-weight: bold;">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"><?= htmlspecialchars($team['deskripsi'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" class="btn primary-btn">Update Tim</button>
            <a href="index.php?page=teams" class="btn secondary-btn" style="margin-left: 10px;">Batal</a>
        </form>
    </div>
</div>