<!-- Pastikan file ini berada di dalam layout utama -->

<div class="content">
    
    <h1 style="margin-bottom: 20px;">Daftar Proyek</h1>
    
    <!-- CONTAINER TOMBOL (Agar sejajar) -->
    <div style="margin-bottom: 25px;">
        
        <!-- Tombol Tambah (Biru) -->
        <a href="index.php?page=project_create" 
           class="btn btn-add-project" 
           style="display: inline-block; background: linear-gradient(135deg, #4A8BFF, #3A6FE0); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(74, 139, 255, 0.2); margin-right: 15px;">
            + Tambah Proyek Baru
        </a>

        <!-- TOMBOL BARU: LIHAT ARSIP (Putih/Abu) -->
        <a href="index.php?page=project_archived" 
           style="display: inline-block; background: #fff; color: #64748b; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid #cbd5e1; transition: 0.2s;">
            📂 Lihat Arsip / Sampah
        </a>

    </div>

    <div class="card">
        
        <div class="table-responsive">
            <table class="project-list-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Proyek</th>
                        <th>Budget</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Selesai</th>
                        <th>Status Progress</th>
                        <th>Penanggung Jawab</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($projects)): ?>
                        <?php foreach ($projects as $row): ?>
                            <tr>
                                <!-- Nama Proyek -->
                                <td style="font-weight: 600; color: #2d3436;">
                                    <?= htmlspecialchars($row['nama_proyek']) ?>
                                    <?php if(isset($row['id_klien'])): ?>
                                        <div style="font-size: 11px; color: #94a3b8; font-weight: normal;">Klien ID: <?= $row['id_klien'] ?></div>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Budget -->
                                <td>
                                    Rp <?= number_format($row['budget'], 0, ',', '.') ?>
                                </td>
                                
                                <!-- Tanggal -->
                                <td><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal_selesai'])) ?></td>
                                
                                <!-- Status Progress (Bar) -->
                                <td>
                                    <?php $prog = floatval($row['progress'] ?? 0); ?>
                                    <div class="progress-bar-bg" style="background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden; width: 100%; margin-bottom: 5px;">
                                        <div class="progress-fill" style="width: <?= $prog ?>%; background: linear-gradient(90deg, #4A8BFF, #3A6FE0); height: 100%; border-radius: 4px;"></div>
                                    </div>
                                    <small style="font-weight: bold; color: #3b82f6;"><?= $prog ?>%</small>
                                </td>
                                
                                <!-- Penanggung Jawab (Nama) -->
                                <td>
                                    <span style="background: #eef2ff; color: #4f46e5; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <?= htmlspecialchars($row['nama_pj'] ?? 'Belum Ditunjuk') ?>
                                    </span>
                                </td>
                                
                                <!-- Aksi (Tombol Kecil) -->
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="index.php?page=project_edit&id=<?= $row['id_proyek'] ?>" 
                                           style="background: #f0f5ff; color: #4A8BFF; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; border: 1px solid #dbeafe;">
                                           Edit
                                        </a>
                                        <a href="index.php?page=project_archive&id=<?= $row['id_proyek'] ?>" 
                                           onclick="return confirm('Yakin ingin mengarsipkan proyek ini?')"
                                           style="background: #fff1f2; color: #ff4757; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; border: 1px solid #ffe4e6;">
                                           Arsip
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                                Belum ada data proyek aktif.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div> 
</div>