<?php 
// app/views/project/create.php
// View untuk form penambahan proyek baru
?>

<div class="content">
    <h1>Tambah Proyek</h1>

    <!-- Card pembungkus Form -->
    <div class="card">
        
        <!-- Form mengirim data ke ProjectController::store() -->
        <form action="index.php?page=project_store" method="POST" style="max-width: 600px;">
            
            <div style="margin-bottom: 15px;">
                <label for="nama_proyek" style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Proyek:</label>
                <input type="text" id="nama_proyek" name="nama_proyek" required 
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="deskripsi" style="display: block; margin-bottom: 5px; font-weight: bold;">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" 
                          style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;"></textarea>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="tanggal_mulai" style="display: block; margin-bottom: 5px; font-weight: bold;">Tanggal Mulai:</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="tanggal_selesai" style="display: block; margin-bottom: 5px; font-weight: bold;">Tanggal Selesai:</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="id_klien" style="display: block; margin-bottom: 5px; font-weight: bold;">ID Klien:</label>
                <input type="number" id="id_klien" name="id_klien"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="id_tim" style="display: block; margin-bottom: 5px; font-weight: bold;">ID Tim:</label>
                <input type="number" id="id_tim" name="id_tim"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="budget" style="display: block; margin-bottom: 5px; font-weight: bold;">Budget (Rp.):</label>
                <input type="number" id="budget" name="budget" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label for="penanggung_jawab" style="display: block; margin-bottom: 5px; font-weight: bold;">Penanggung Jawab:</label>
                <input type="text" id="penanggung_jawab" name="penanggung_jawab" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <!-- Tombol Simpan (Menggunakan kelas CSS yang benar) -->
            <button type="submit" class="btn primary-btn">Simpan</button>
            
            <!-- Tombol Batal -->
            <a href="index.php?page=projects" class="btn" style="background: #f0f0f0; color: #333; margin-left: 10px;">Batal</a>
            
        </form>
    </div>
</div>