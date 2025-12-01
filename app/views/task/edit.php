<?php 
// app/views/task/edit.php
// Variabel dari Controller: $task, $projects, $members, $statuses
$t = $task; // Mengambil data $task dari TaskController
?>

<!-- Import Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    .content-task { font-family: 'Poppins', sans-serif !important; }
    .content-task h1 { font-weight: 700; font-size: 28px; color: #2d3436; }
    
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

<div class="content content-task">
    <!-- Judul harusnya Edit Tugas, bukan Tim -->
    <h1 style="margin-bottom: 25px;">Edit Tugas: <?= htmlspecialchars($t['nama_tugas'] ?? '') ?></h1>

    <div class="card" style="max-width: 1100px; padding: 40px; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        
        <?php if (isset($_GET['error'])): ?>
            <div style="background: #fff1f2; color: #e11d48; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #fda4af;">
                ⚠ <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=task_update" method="POST">
            
            <!-- Hidden ID Tugas -->
            <input type="hidden" name="id_tugas" value="<?= $t['id_tugas'] ?>">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                
                <!-- KOLOM KIRI -->
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#475569;">Nama Tugas <span style="color:red">*</span></label>
                        <input type="text" name="nama_tugas" required class="form-input-lg" value="<?= htmlspecialchars($t['nama_tugas']) ?>">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#475569;">Proyek Terkait <span style="color:red">*</span></label>
                        <select name="id_proyek" class="form-input-lg" required>
                            <option value="">-- Pilih Proyek --</option>
                            <?php foreach($projects as $p): ?>
                                <option value="<?= $p['id_proyek'] ?>" <?= ($p['id_proyek'] == $t['id_proyek']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nama_proyek']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#475569;">Ditugaskan Kepada</label>
                        <select name="id_anggota" class="form-input-lg">
                            <option value="">-- Pilih Anggota Tim --</option>
                            <?php foreach($members as $m): ?>
                                <option value="<?= $m['id_anggota'] ?>" <?= ($m['id_anggota'] == $t['id_anggota']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['nama']) ?> (<?= htmlspecialchars($m['jabatan'] ?? 'Staff') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- KOLOM KANAN -->
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#475569;">Deadline <span style="color:red">*</span></label>
                        <input type="date" name="deadline" required class="form-input-lg" value="<?= $t['deadline'] ?>">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#475569;">Status</label>
                        <select name="id_status" class="form-input-lg" required>
                            <?php foreach($statuses as $s): ?>
                                <option value="<?= $s['id_status'] ?>" <?= ($s['id_status'] == $t['id_status']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['nama_status']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight:600; color:#475569;">Progress (%)</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="number" name="progress_percent" min="0" max="100" class="form-input-lg" value="<?= $t['progress_percent'] ?>">
                            <span style="font-weight: bold; color: #64748b;">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="border-top: 1px solid #f1f5f9; padding-top: 30px; margin-top: 10px; text-align: right;">
                <a href="index.php?page=tasks" class="btn" style="background: #fff; border: 1px solid #cbd5e1; color: #475569; padding: 12px 25px; border-radius: 8px; font-weight: 600; text-decoration: none; margin-right: 15px;">
                    Batal
                </a>
                <button type="submit" class="btn" style="background: linear-gradient(135deg, #4A8BFF, #3A6FE0); color: white; padding: 12px 30px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; box-shadow: 0 4px 10px rgba(74, 139, 255, 0.3);">
                    Update Tugas
                </button>
            </div>

        </form>
    </div>
</div>