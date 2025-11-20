<?php
/* =============================
VIEW: PROJECT EDIT (app/views/project/edit.php)
============================= */
?>
<h2>Edit Proyek</h2>
<form method="POST" action="index.php?page=project_update">
  <input type="hidden" name="id" value="<?= $project['id_proyek'] ?>">

  <label>Nama Proyek:</label>
  <input type="text" name="nama_proyek" value="<?= $project['nama_proyek'] ?>" required><br>

  <label>Deskripsi:</label>
  <textarea name="deskripsi"><?= $project['deskripsi'] ?></textarea><br>

  <label>Tanggal Mulai:</label>
  <input type="date" name="tanggal_mulai" value="<?= $project['tanggal_mulai'] ?>"><br>

  <label>Tanggal Selesai:</label>
  <input type="date" name="tanggal_selesai" value="<?= $project['tanggal_selesai'] ?>"><br>

  <label>ID Klien:</label>
  <input type="number" name="id_klien" value="<?= $project['id_klien'] ?>" required><br>

  <label>ID Tim:</label>
  <input type="number" name="id_tim" value="<?= $project['id_tim'] ?>" required><br>

  <label>Budget:</label>
  <input type="number" name="budget" value="<?= $project['budget'] ?>"><br>

  <label>Penanggung Jawab:</label>
  <input type="number" name="penanggung_jawab" value="<?= $project['penanggung_jawab'] ?>"><br>

  <button type="submit">Update</button>
</form>