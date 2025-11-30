<style>
    /* Styling khusus untuk badge status di laporan */
    .badge-status { padding: 5px 10px; border-radius: 5px; font-size: 11px; font-weight: bold; }
    .bg-green { background: #d1fae5; color: #065f46; } /* Selesai */
    .bg-red { background: #fee2e2; color: #991b1b; }   /* Terlambat */
    .bg-blue { background: #dbeafe; color: #1e40af; }  /* Berjalan */
    
    .section-title { font-size: 18px; font-weight: 700; color: #2d3436; margin-bottom: 15px; margin-top: 30px; }
    .first-title { margin-top: 0; }
</style>

<div class="content">
    <h1 style="margin-bottom: 30px;">Laporan Proyek & Kinerja</h1>

    <!-- 1. LAPORAN PROGRESS PROYEK -->
    <div class="section-title first-title">📊 Progress Keseluruhan Proyek</div>
    <div class="card">
        <div class="table-responsive">
            <table class="project-list-table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Nama Proyek</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Progress</th>
                        <th style="text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($projectProgress)): ?>
                        <?php foreach($projectProgress as $row): ?>
                        <tr>
                            <td style="font-weight:600;"><?= htmlspecialchars($row['nama_proyek']) ?></td>
                            <td style="color:#666;"><?= date('d M Y', strtotime($row['tanggal_mulai'])) ?></td>
                            <td style="color:#666;"><?= date('d M Y', strtotime($row['tanggal_selesai'])) ?></td>
                            <td>
                                <div class="progress-bar-bg" style="height:6px; margin-bottom:5px;">
                                    <div class="progress-fill" style="width: <?= $row['progress'] ?>%;"></div>
                                </div>
                                <span style="font-size:11px; font-weight:bold; color:#4A8BFF;"><?= $row['progress'] ?>%</span>
                            </td>
                            <td style="text-align:center;">
                                <?php 
                                    $statusClass = 'bg-blue';
                                    if($row['status_waktu'] == 'Selesai') $statusClass = 'bg-green';
                                    if($row['status_waktu'] == 'Terlambat') $statusClass = 'bg-red';
                                ?>
                                <span class="badge-status <?= $statusClass ?>">
                                    <?= $row['status_waktu'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center; padding:20px;">Belum ada data proyek.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. LAPORAN TUGAS TERLAMBAT (OVERDUE) -->
    <div class="section-title">⚠️ Tugas Terlambat (Overdue)</div>
    <div class="card" style="border-left: 5px solid #ef4444;">
        <div class="table-responsive">
            <table class="project-list-table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Nama Tugas</th>
                        <th>Proyek</th>
                        <th>Penanggung Jawab</th>
                        <th>Deadline</th>
                        <th>Progress Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($overdueTasks)): ?>
                        <?php foreach($overdueTasks as $row): ?>
                        <tr>
                            <td style="font-weight:600; color:#ef4444;"><?= htmlspecialchars($row['nama_tugas']) ?></td>
                            <td><?= htmlspecialchars($row['nama_proyek']) ?></td>
                            <td><?= htmlspecialchars($row['penanggung_jawab'] ?? 'Belum ada') ?></td>
                            <td style="font-weight:bold; color:#ef4444;">
                                <?= date('d M Y', strtotime($row['deadline'])) ?>
                            </td>
                            <td>
                                <span class="badge-status bg-red"><?= $row['progress_percent'] ?>%</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center; padding:20px; color:#059669;">Hebat! Tidak ada tugas yang terlambat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. LAPORAN KINERJA TIM -->
    <div class="section-title">🏆 Kinerja Anggota Tim</div>
    <div class="card">
        <div class="table-responsive">
            <table class="project-list-table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Nama Anggota</th>
                        <th style="text-align:center;">Total Tugas</th>
                        <th style="text-align:center;">Selesai</th>
                        <th style="text-align:center;">Terlambat</th>
                        <th style="text-align:center;">Rasio Penyelesaian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($teamPerformance)): ?>
                        <?php foreach($teamPerformance as $row): ?>
                        <tr>
                            <td style="font-weight:600;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:30px; height:30px; background:#e0e7ff; color:#4338ca; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:12px;">
                                        <?= strtoupper(substr($row['nama'], 0, 2)) ?>
                                    </div>
                                    <?= htmlspecialchars($row['nama']) ?>
                                </div>
                            </td>
                            <td style="text-align:center; font-weight:bold;"><?= $row['total_tugas'] ?></td>
                            <td style="text-align:center; color:#059669; font-weight:bold;"><?= $row['tugas_selesai'] ?></td>
                            <td style="text-align:center; color:#ef4444; font-weight:bold;"><?= $row['tugas_terlambat'] ?></td>
                            <td style="text-align:center;">
                                <?php 
                                    $total = $row['total_tugas'];
                                    $selesai = $row['tugas_selesai'];
                                    $persen = ($total > 0) ? round(($selesai / $total) * 100) : 0;
                                ?>
                                <div style="width:100px; height:6px; background:#e2e8f0; border-radius:3px; margin:0 auto 5px auto; overflow:hidden;">
                                    <div style="width:<?= $persen ?>%; height:100%; background:#10b981; border-radius:3px;"></div>
                                </div>
                                <span style="font-size:11px; color:#666;"><?= $persen ?>%</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center; padding:20px;">Belum ada data kinerja.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>