<?php
/* =============================
VIEW: PROJECT DETAIL (app/views/project/detail.php)
============================= */
?>
<h2>Detail Proyek</h2>
<p><strong>Nama:</strong> <?= $project['nama_proyek'] ?></p>
<p><strong>Budget:</strong> <?= $project['budget'] ?></p>
<p><strong>Timeline:</strong> <?= $project['tanggal_mulai'] ?> - <?= $project['tanggal_selesai'] ?></p>
<p><strong>Penanggung Jawab:</strong> <?= $project['penanggung_jawab'] ?></p>
<p><strong>Progress:</strong> <?= $project['progress'] ?>%</p>
<hr>
<h3>Daftar Tugas</h3>
<table border="1" cellpadding="8">
  <tr>
    <th>Nama Task</th>
    <th>Assignee</th>
    <th>Deadline</th>
    <th>Status</th>
    <th>Progress</th>
  </tr>
  <?php foreach ($tasks as $t): ?>
  <tr>
    <td><?= $t['nama_tugas'] ?></td>
    <td><?= $t['id_anggota'] ?></td>
    <td><?= $t['deadline'] ?></td>
    <td><?= $t['id_status'] ?></td>
    <td><?= $t['progress_percent'] ?>%</td>
  </tr>
  <?php endforeach; ?>
</table>
