<div class="content">
    
    <!-- HEADER LUAR (Judul & Tombol Kembali) -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h1 style="margin: 0; font-size: 24px;">Proyek Terarsip (Sampah)</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 14px;">Data di sini tidak aktif, tapi bisa dipulihkan kapan saja.</p>
        </div>
        
        <a href="index.php?page=projects" 
           class="btn" 
           style="background: #fff; border: 1px solid #cbd5e1; padding: 12px 24px; border-radius: 8px; text-decoration: none; color: #475569; font-weight: 600; transition: 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            &larr; Kembali ke Proyek Aktif
        </a>
    </div>

    <!-- CARD PUTIH (Hanya Berisi Tabel) -->
    <div class="card" style="background: #fff; padding: 0; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #edf2f7; overflow: hidden;">
        <div class="table-responsive">
            <table class="project-list-table" style="width: 100%; border-collapse: collapse;">
                <!-- Header Tabel Merah Muda -->
                <thead style="background: #fff1f2; border-bottom: 1px solid #ffe4e6;"> 
                    <tr>
                        <th style="text-align: left; padding: 18px 20px; color: #be123c; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Nama Proyek</th>
                        <th style="text-align: left; padding: 18px 20px; color: #be123c; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Waktu Dihapus</th>
                        <th style="text-align: center; padding: 18px 20px; color: #be123c; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($projects)): ?>
                        <?php foreach ($projects as $row): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <!-- Nama Proyek -->
                                <td style="padding: 20px; font-weight: 600; color: #334155;">
                                    <?= htmlspecialchars($row['nama_proyek']) ?>
                                    <div style="font-size: 12px; color: #94a3b8; margin-top: 4px; font-weight: normal;">
                                        Budget: Rp <?= number_format($row['budget'], 0, ',', '.') ?>
                                    </div>
                                </td>
                                
                                <!-- Waktu Dihapus -->
                                <td style="padding: 20px; color: #64748b; font-size: 14px;">
                                    <span style="display:inline-block; background: #fef2f2; color: #ef4444; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                        <?= date('d M Y H:i', strtotime($row['deleted_at'])) ?>
                                    </span>
                                </td>
                                
                                <!-- Tombol Pulihkan -->
                                <td style="padding: 20px; text-align: center;">
                                    <a href="index.php?page=project_restore&id=<?= $row['id_proyek'] ?>" 
                                       onclick="return confirm('Kembalikan proyek ini ke daftar aktif?')"
                                       style="display: inline-block; background: #ecfdf5; color: #059669; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; border: 1px solid #d1fae5; transition: 0.2s;">
                                       ♻️ Pulihkan Data
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 60px; color: #94a3b8;">
                                <div style="font-size: 16px; font-weight: 500; margin-bottom: 5px;">Sampah Kosong</div>
                                <div style="font-size: 13px;">Tidak ada proyek yang diarsipkan saat ini.</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>