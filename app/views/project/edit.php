<?php 
// app/views/project/edit.php
// Variabel dikirim dari Controller: $project, $clients, $teams, $members
$clients = $clients ?? []; 
$teams   = $teams ?? [];
$members = $members ?? [];
$p       = $project; // Singkatan biar nulisnya pendek
?>

<!-- 1. Import Font Baru (Poppins) - Sama seperti create.php -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* 2. Custom Style (Persis create.php) */
    .content-create {
        font-family: 'Poppins', sans-serif !important;
    }
    
    .content-create h1 {
        font-weight: 700;
        font-size: 28px;
        color: #2d3436;
    }

    .content-create label {
        font-size: 15px;
        color: #4b5563;
    }

    .form-input-lg {
        width: 100%;
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        transition: 0.3s;
    }

    .form-input-lg:focus {
        border-color: #4A8BFF;
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(74, 139, 255, 0.1);
    }
</style>

<div class="content content-create">
    <!-- Judul Halaman -->
    <h1 style="margin-bottom: 25px;">Edit Proyek</h1>

    <!-- Card Lebar (1100px) -->
    <div class="card" style="max-width: 1100px;">
        
        <?php if (isset($_GET['error'])): ?>
            <div style="background: #fff1f2; color: #e11d48; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #fda4af; font-weight: 500;">
                ⚠ <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=project_update" method="POST">
            
            <!-- Hidden Input ID Proyek (PENTING untuk Update) -->
            <input type="hidden" name="id_proyek" value="<?= $p['id_proyek'] ?>">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 35px;">
                
                <!-- KOLOM KIRI -->
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600;">Nama Proyek <span style="color:red">*</span></label>
                        <input type="text" name="nama_proyek" required class="form-input-lg" 
                               value="<?= htmlspecialchars($p['nama_proyek']) ?>">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600;">Klien <span style="color:red">*</span></label>
                        <select name="id_klien" class="form-input-lg" required>
                            <option value="">-- Pilih Klien --</option>
                            <?php foreach($clients as $c): ?>
                                <!-- Logika Selected: Jika ID di DB sama dengan ID Klien, maka pilih -->
                                <option value="<?= $c['id_klien'] ?>" <?= ($p['id_klien'] == $c['id_klien']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nama_klien']) ?> (<?= htmlspecialchars($c['perusahaan'] ?? '-') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600;">Tanggal Mulai <span style="color:red">*</span></label>
                        <input type="date" name="tanggal_mulai" required class="form-input-lg"
                               value="<?= $p['tanggal_mulai'] ?>">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600;">Budget (Rp) <span style="color:red">*</span></label>
                        <input type="number" name="budget" required min="0" step="1000" class="form-input-lg"
                               value="<?= $p['budget'] ?>">
                    </div>
                </div>

                <!-- KOLOM KANAN -->
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600;">Penanggung Jawab (PJ) <span style="color:red">*</span></label>
                        <select name="penanggung_jawab" class="form-input-lg" required>
                             <option value="">-- Pilih Anggota Tim (PJ) --</option>
                             <?php foreach($members as $m): ?>
                                <option value="<?= $m['id_anggota'] ?>" <?= ($p['penanggung_jawab'] == $m['id_anggota']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['nama']) ?>
                                </option>
                             <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600;">Tim Pelaksana</label>
                        <select name="id_tim" class="form-input-lg">
                            <option value="">-- Pilih Tim --</option>
                            <?php foreach($teams as $t): ?>
                                <option value="<?= $t['id_tim'] ?>" <?= ($p['id_tim'] == $t['id_tim']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['nama_tim']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600;">Tanggal Selesai <span style="color:red">*</span></label>
                        <input type="date" name="tanggal_selesai" required class="form-input-lg"
                               value="<?= $p['tanggal_selesai'] ?>">
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div style="margin-bottom: 35px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">Deskripsi Proyek</label>
                <textarea name="deskripsi" rows="5" class="form-input-lg"><?= htmlspecialchars($p['deskripsi']) ?></textarea>
            </div>
            
            <!-- Tombol Action -->
            <div style="border-top: 1px solid #f1f5f9; padding-top: 30px; text-align: right;">
                <a href="index.php?page=projects" class="btn secondary-btn" style="margin-right: 15px; padding: 14px 28px; font-size: 15px;">Batal</a>
                <button type="submit" class="btn primary-btn" style="padding: 14px 35px; font-size: 15px; font-weight: 600; width: auto;">Update Proyek</button>
            </div>

        </form>
    </div>
</div>