<?php 
class ProjectController {
    private $project;
    private $db; 

    public function __construct($db) {
        $this->db = $db; 
        $this->project = new Project($db);
    }

    public function index() {
        $projects = $this->project->all();

        include "../app/views/layout/header.php";
        include "../app/views/project/list.php";
        include "../app/views/layout/footer.php";
    }

    public function create() {
        // Ambil Data untuk Dropdown
        $stmt = $this->db->query("SELECT * FROM klien ORDER BY nama_klien ASC");
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->query("SELECT * FROM anggota_tim ORDER BY nama ASC");
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->query("SELECT * FROM tim ORDER BY nama_tim ASC");
        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include "../app/views/layout/header.php";
        include "../app/views/project/create.php";
        include "../app/views/layout/footer.php";
    }

    public function store() {
        // 1. Ambil Inputan
        $mulai = $_POST['tanggal_mulai'];
        $selesai = $_POST['tanggal_selesai'];
        $budget = $_POST['budget'];

        // 2. VALIDASI TANGGAL
        if (strtotime($selesai) < strtotime($mulai)) {
            $pesanError = "Tanggal selesai tidak boleh sebelum tanggal mulai!";
            header("Location: index.php?page=project_create&error=" . urlencode($pesanError));
            return; 
        }

        // 3. VALIDASI BUDGET
        if ($budget < 0) {
            header("Location: index.php?page=project_create&error=" . urlencode("Budget tidak boleh minus!"));
            return;
        }

        // 4. Data Siap Simpan
        $data = [
            'nama' => $_POST['nama_proyek'],
            'deskripsi' => $_POST['deskripsi'],
            'mulai' => $mulai,
            'selesai' => $selesai,
            'klien' => $_POST['id_klien'],
            'tim' => $_POST['id_tim'],
            'budget' => $budget,
            'pj' => $_POST['penanggung_jawab']
        ];

        if ($this->project->create($data)) {
            header("Location: index.php?page=projects");
        } else {
            header("Location: index.php?page=project_create&error=" . urlencode("Gagal menyimpan data"));
        }
    }

    public function edit() {
        $id = $_GET["id"];
        $project = $this->project->find($id);

        $stmt = $this->db->query("SELECT * FROM klien ORDER BY nama_klien ASC");
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->query("SELECT * FROM anggota_tim ORDER BY nama ASC");
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->query("SELECT * FROM tim ORDER BY nama_tim ASC");
        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include "../app/views/layout/header.php";
        include "../app/views/project/edit.php";
        include "../app/views/layout/footer.php";
    }
    
    public function update() {
        $id = $_POST['id_proyek'] ?? $_GET['id']; 
        $mulai = $_POST['tanggal_mulai'];
        $selesai = $_POST['tanggal_selesai'];

        if (strtotime($selesai) < strtotime($mulai)) {
            header("Location: index.php?page=project_edit&id=$id&error=" . urlencode("Tanggal selesai tidak valid!"));
            return;
        }

        $data = [
            'id' => $id,
            'nama' => $_POST['nama_proyek'],
            'deskripsi' => $_POST['deskripsi'],
            'mulai' => $mulai,
            'selesai' => $selesai,
            'klien' => $_POST['id_klien'],
            'tim' => $_POST['id_tim'],
            'budget' => $_POST['budget'],
            'pj' => $_POST['penanggung_jawab']
        ];

        if ($this->project->update($data)) {
            header("Location: index.php?page=projects");
        } else {
            header("Location: index.php?page=project_edit&id=$id&error=" . urlencode("Gagal mengupdate proyek"));
        }
    }

    // Fungsi Arsip (Soft Delete)
    public function archive() {
        $id = $_GET['id'];
        if ($this->project->archive($id)) {
            header("Location: index.php?page=projects");
        } else {
            header("Location: index.php?page=projects&error=" . urlencode("Gagal mengarsipkan proyek"));
        }
    }

    // --- TAMBAHAN YANG HILANG (PENTING!) ---

    // 1. Fungsi untuk Menampilkan Halaman Arsip
    public function archived() {
        // Ambil data sampah dari Model
        $projects = $this->project->getArchived();

        include "../app/views/layout/header.php";
        include "../app/views/project/archived.php"; 
        include "../app/views/layout/footer.php";
    }

    // 2. Fungsi untuk Memulihkan Data (Restore)
    public function restore() {
        $id = $_GET['id'];
        // Panggil fungsi restore di Model
        if ($this->project->restore($id)) {
            header("Location: index.php?page=projects");
        } else {
            header("Location: index.php?page=project_archived&error=" . urlencode("Gagal memulihkan proyek"));
        }
    }
}
?>