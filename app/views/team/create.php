<?php 
// app/views/team/create.php
?>

<div class="content">
    <h1>Buat Tim Baru</h1>

    <div class="card">
        <form action="index.php?page=teams&action=store" method="POST">
            <div style="margin-bottom: 15px;">
                <label for="nama_tim" style="display: block; font-weight: bold;">Nama Tim:</label>
                <input type="text" id="nama_tim" name="nama_tim" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="deskripsi" style="display: block; font-weight: bold;">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            </div>
            
            <button type="submit" class="btn primary-btn">Simpan Tim</button>
            <a href="index.php?page=teams" class="btn secondary-btn" style="margin-left: 10px;">Batal</a>
        </form>
    </div>
</div>