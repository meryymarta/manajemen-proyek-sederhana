<?php 
// app/views/team/list.php
$teams = $teams ?? [];
?>

<div class="content">
    
    <h1 style="margin-bottom: 20px;">Daftar Tim</h1>
    
    <a href="index.php?page=team_create" 
       class="btn btn-add-project" 
       style="margin-bottom: 25px; display: inline-block; background: linear-gradient(135deg, #4A8BFF, #3A6FE0); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(74, 139, 255, 0.2);">
        + Tambah Tim Baru
    </a>

    <div class="card">
        
        <div class="table-responsive">
            <table class="project-list-table" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width: 20%;">Nama Tim</th>
                        <th style="width: 30%;">Deskripsi</th>
                        <th style="width: 35%;">Anggota Tim</th> <!-- Kolom Baru -->
                        <th style="width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($teams)): ?>
                        <?php foreach ($teams as $team): ?>
                            <tr>
                                <!-- Nama Tim -->
                                <td style="font-weight: 600; color: #2d3436;">
                                    <?= htmlspecialchars($team['nama_tim']) ?>
                                    <div style="font-size: 11px; color: #94a3b8; font-weight: normal; margin-top: 4px;">
                                        Total: <?= $team['jumlah_anggota'] ?? 0 ?> Anggota
                                    </div>
                                </td>
                                
                                <!-- Deskripsi -->
                                <td style="color: #636e72; font-size: 13px;">
                                    <?= htmlspecialchars($team['deskripsi']) ?>
                                </td>

                                <!-- Daftar Anggota (KOLOM BARU) -->
                                <td>
                                    <?php if (!empty($team['daftar_anggota'])): ?>
                                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                            <?php 
                                            // Pecah string nama menjadi array
                                            $anggota_list = explode(', ', $team['daftar_anggota']); 
                                            foreach ($anggota_list as $nama): 
                                            ?>
                                                <span style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 20px; font-size: 11px; border: 1px solid #c7d2fe; font-weight: 600;">
                                                    <?= htmlspecialchars($nama) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #cbd5e1; font-style: italic; font-size: 12px;">Belum ada anggota</span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Aksi -->
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="index.php?page=team_edit&id=<?= $team['id_tim'] ?>" 
                                           style="background: #f0f5ff; color: #4A8BFF; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; border: 1px solid #dbeafe;">
                                           Edit
                                        </a>
                                        <a href="index.php?page=team_delete&id=<?= $team['id_tim'] ?>" 
                                           onclick="return confirm('Yakin ingin menghapus tim ini?');"
                                           style="background: #fff1f2; color: #ff4757; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; border: 1px solid #ffe4e6;">
                                           Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding: 40px; color: #94a3b8;">
                                <div style="font-size: 15px; font-weight: 500; margin-bottom: 5px;">Belum ada tim</div>
                                <div style="font-size: 13px;">Silakan tambah tim baru untuk memulai.</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div> 
</div>