<div class="content">

    <h2>Tambah Tugas</h2>

    <div class="card">
        <form method="POST" action="index.php?page=task_store">

            <label>Nama Tugas:</label>
            <input type="text" name="nama_tugas" required class="form-input"><br><br>

            <label>ID Proyek:</label>
            <input type="number" name="id_proyek" required class="form-input"><br><br>

            <label>ID Anggota:</label>
            <input type="number" name="id_anggota" required class="form-input"><br><br>

            <label>ID Status:</label>
            <input type="number" name="id_status" required class="form-input"><br><br>

            <label>Deadline:</label>
            <input type="date" name="deadline" required class="form-input"><br><br>

            <button type="submit" class="btn primary-btn">Simpan</button>

        </form>
    </div>

</div>
