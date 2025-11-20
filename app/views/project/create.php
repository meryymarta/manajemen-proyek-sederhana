<div class="content">
    <h1>Tambah Proyek</h1>

    <div class="card">
        <form method="POST" action="index.php?page=project_store">

            <label>Nama Proyek:</label>
            <input type="text" name="nama_proyek" required><br><br>

            <label>Deskripsi:</label>
            <textarea name="deskripsi"></textarea><br><br>

            <label>Tanggal Mulai:</label>
            <input type="date" name="tanggal_mulai"><br><br>

            <label>Tanggal Selesai:</label>
            <input type="date" name="tanggal_selesai"><br><br>

            <label>ID Klien:</label>
            <input type="number" name="id_klien" required><br><br>

            <label>ID Tim:</label>
            <input type="number" name="id_tim" required><br><br>

            <label>Budget:</label>
            <input type="number" name="budget"><br><br>

            <label>Penanggung Jawab:</label>
            <input type="number" name="penanggung_jawab"><br><br>

            <button class="btn primary-btn" type="submit">Simpan</button>

        </form>
    </div>
</div>
