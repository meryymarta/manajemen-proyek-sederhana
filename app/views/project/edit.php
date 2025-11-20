<?php 
// app/views/project/edit.php
// Variabel $project harus tersedia di sini dari ProjectController::edit()
// Jika $project kosong (misal ID tidak ditemukan), atur sebagai array kosong agar tidak error
$project = $project ?? []; 
?>

<!-- PENTING: Gunakan div class="content" sebagai pembungkus utama -->
<div class="content">
    <h1>Edit Proyek: <?= htmlspecialchars($project['nama_proyek'] ?? 'Proyek Baru') ?></h1>

    <div class="card">
        
        <!-- Form mengirim data ke ProjectController::update() -->
        <form action="index.php?page=project_update" method="POST" style="max-width: 600px;">
            
            <!-- Hidden field untuk ID proyek yang akan diedit -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($project['id_proyek'] ?? '') ?>">

            <div style="margin-bottom: 15px;">
                <label for="nama_proyek" style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Proyek:</label>
                <!-- Menampilkan nilai lama di attribute 'value' -->
                <input type="text" id="nama_proyek" name="nama_proyek" required 
                       value="<?= htmlspecialchars($project['nama_proyek'] ?? '') ?>"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="deskripsi" style="display: block; margin-bottom: 5px; font-weight: bold;">Deskripsi:</label>
                <!-- Menampilkan nilai lama di dalam tag textarea -->
                <textarea id="deskripsi" name="deskripsi" rows="4" 
                          style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;"><?= htmlspecialchars($project['deskripsi'] ?? '') ?></textarea>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="tanggal_mulai" style="display: block; margin-bottom: 5px; font-weight: bold;">Tanggal Mulai:</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" required
                       value="<?= htmlspecialchars($project['tanggal_mulai'] ?? '') ?>"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <!-- ... (Tambahkan field sisanya seperti Tanggal Selesai, ID Klien, ID Tim, Budget, Penanggung Jawab) ... -->
            
            <div style="margin-bottom: 25px;">
                <label for="penanggung_jawab" style="display: block; margin-bottom: 5px; font-weight: bold;">Penanggung Jawab:</label>
                <input type="text" id="penanggung_jawab" name="penanggung_jawab" required
                       value="<?= htmlspecialchars($project['penanggung_jawab'] ?? '') ?>"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <!-- Tombol Update -->
            <button type="submit" class="btn primary-btn">Update Proyek</button>
            
            <!-- Tombol Batal -->
            <a href="index.php?page=projects" class="btn" style="background: #f0f0f0; color: #333; margin-left: 10px;">Batal</a>
            
        </form>
    </div>
</div>