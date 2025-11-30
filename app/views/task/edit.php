<?php 
// app/views/team/edit.php
// Variabel $team dikirim dari TeamController::edit()
$t = $team; 
?>

<!-- Import Font Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Style Konsisten dengan Halaman Lain */
    .content-team { font-family: 'Poppins', sans-serif !important; }
    .content-team h1 { font-weight: 700; font-size: 28px; color: #2d3436; }
    
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

<div class="content content-team">
    <h1 style="margin-bottom: 25px;">Edit Tim: <?= htmlspecialchars($t['nama_tim']) ?></h1>

    <div class="card" style="max-width: 800px; padding: 40px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
        
        <?php if (isset($_GET['error'])): ?>
            <div style="background: #fff1f2; color: #e11d48; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #fda4af;">
                ⚠ <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=team_update" method="POST">
            
            <!-- Hidden ID Tim (PENTING untuk proses update) -->
            <input type="hidden" name="id_tim" value="<?= $t['id_tim'] ?>">

            <div style="margin-bottom: 25px;">
                <label style="display:block; margin-bottom:10px; font-weight:600; color:#475569;">Nama Tim <span style="color:red">*</span></label>
                <input type="text" name="nama_tim" required class="form-input-lg" value="<?= htmlspecialchars($t['nama_tim']) ?>">
            </div>

            <div style="margin-bottom: 35px;">
                <label style="display:block; margin-bottom:10px; font-weight:600; color:#475569;">Deskripsi</label>
                <textarea name="deskripsi" rows="5" class="form-input-lg"><?= htmlspecialchars($t['deskripsi']) ?></textarea>
            </div>
            
            <!-- Tombol Action -->
            <div style="border-top: 1px solid #f1f5f9; padding-top: 30px; text-align: right;">
                <a href="index.php?page=teams" class="btn" style="background: #fff; border: 1px solid #cbd5e1; color: #475569; padding: 14px 28px; border-radius: 8px; font-weight: 600; text-decoration: none; margin-right: 15px;">
                    Batal
                </a>
                <button type="submit" class="btn" style="background: linear-gradient(135deg, #4A8BFF, #3A6FE0); color: white; padding: 14px 35px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; box-shadow: 0 4px 10px rgba(74, 139, 255, 0.3);">
                    Update Tim
                </button>
            </div>

        </form>
    </div>
</div>