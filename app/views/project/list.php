<div class="content">
    
    <h1 style="margin-bottom: 20px;">Daftar Proyek</h1>
    
    <div style="margin-bottom: 25px; display: flex; align-items: center; flex-wrap: wrap; gap: 15px;">
        
        <a href="index.php?page=project_create" 
           class="btn btn-add-project" 
           style="display: inline-block; background: linear-gradient(135deg, #4A8BFF, #3A6FE0); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(74, 139, 255, 0.2);">
            + Tambah Proyek Baru
        </a>

        <a href="index.php?page=project_archived" 
           style="display: inline-block; background: #fff; color: #64748b; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid #cbd5e1; transition: 0.2s;">
            📂 Lihat Arsip / Sampah
        </a>

        <form method="GET" action="index.php" style="display: flex; gap: 10px; margin-left: auto;">
            <input type="hidden" name="page" value="projects">
            
            <input type="text" name="search" 
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" 
                   placeholder="Cari Proyek / PJ..." 
                   style="padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; width: 250px; outline: none; transition: 0.3s;">
            
            <button type="submit" 
                    style="background: #334155; color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.2s;">
                Cari
            </button>
            
            <?php if(isset($_GET['search']) && $_GET['search'] != ''): ?>
                <a href="index.php?page=projects" 
                   style="display: flex; align-items: center; justify-content: center; background: #ef4444; color: white; padding: 0 15px; border-radius: 8px; text-decoration: none; font-weight: bold;" 
                   title="Hapus Pencarian">X</a>
            <?php endif; ?>
        </form>

    </div>

    <div class="card">
        
        <div class="table-responsive">
            <table class="project-list-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Proyek</th>
                        <th>Budget</th>
                        <th>Total Tugas</th>
                        <th>Selesai</th>
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
                                <td style="font-weight: 600; color: #2d3436;">
                                    <?= htmlspecialchars($row['nama_proyek']) ?>
                                    <?php if(isset($row['id_klien'])): ?>
                                        <div style="font-size: 11px; color: #94a3b8; font-weight: normal;">Klien ID: <?= $row['id_klien'] ?></div>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    Rp <?= number_format($row['budget'], 0, ',', '.') ?>
                                </td>

                                <td style="text-align: center; font-weight: bold;">
                                    <?= htmlspecialchars($row['total_tugas'] ?? 0) ?>
                                </td>
                                <td style="text-align: center; font-weight: bold; color: #10b981;">
                                    <?= htmlspecialchars($row['tugas_selesai'] ?? 0) ?>
                                </td>
                                <td><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal_selesai'])) ?></td>
                                
                                <td>
                                    <?php $prog = floatval($row['progress'] ?? 0); ?>
                                    <div class="progress-bar-bg" style="background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden; width: 100%; margin-bottom: 5px;">
                                        <div class="progress-fill" style="width: <?= $prog ?>%; background: linear-gradient(90deg, #4A8BFF, #3A6FE0); height: 100%; border-radius: 4px;"></div>
                                    </div>
                                    <small style="font-weight: bold; color: #3b82f6;"><?= $prog ?>%</small>
                                </td>
                                
                                <td>
                                    <span style="background: #eef2ff; color: #4f46e5; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <?= htmlspecialchars($row['nama_pj'] ?? 'Belum Ditunjuk') ?>
                                    </span>
                                </td>
                                
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
                            <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
                                <?php if(isset($_GET['search'])): ?>
                                    Proyek dengan kata kunci "<b><?= htmlspecialchars($_GET['search']) ?></b>" tidak ditemukan.
                                <?php else: ?>
                                    Belum ada data proyek aktif.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div> 
</div>