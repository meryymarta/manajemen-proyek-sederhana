<?php 
class ProjectController {
    private $project;
    private $db; 

    public function __construct($db) {
        $this->db = $db; 
        $this->project = new Project($db);
    }

    public function index() {
        // Menangkap keyword pencarian dari URL
        $keyword = isset($_GET['search']) ? $_GET['search'] : null;
        
        // Kirim keyword ke model
        $projects = $this->project->all($keyword);

        include "../app/views/layout/header.php";
        include "../app/views/project/list.php";
        include "../app/views/layout/footer.php";
    }

    public function create() {
        // Ambil Data untuk Dropdown (Query Asli)
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
        // 1. Ambil Inputan Dasar
        $mulai = $_POST['tanggal_mulai'];
        $selesai = $_POST['tanggal_selesai'];
        
        // [PERBAIKAN] Amankan Budget: Jika kosong, set jadi 0
        $budget = !empty($_POST['budget']) ? $_POST['budget'] : 0;

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

        // [PERBAIKAN UTAMA] Logika NULL Check
        // Jika form kosong (""), ubah menjadi NULL agar database mau menerimanya.
        $id_tim = !empty($_POST['id_tim']) ? $_POST['id_tim'] : null;
        $id_pj  = !empty($_POST['penanggung_jawab']) ? $_POST['penanggung_jawab'] : null;

        // 4. Data Siap Simpan
        $data = [
            'nama'      => $_POST['nama_proyek'],
            'deskripsi' => $_POST['deskripsi'],
            'mulai'     => $mulai,
            'selesai'   => $selesai,
            'klien'     => $_POST['id_klien'],
            
            'tim'       => $id_tim, // Menggunakan variabel yang sudah dicek NULL-nya
            'budget'    => $budget,
            'pj'        => $id_pj   // Menggunakan variabel yang sudah dicek NULL-nya
        ];

        if ($this->project->create($data)) {
            // --- REFRESH M-VIEW SETELAH CREATE SUKSES ---
            $this->project->refreshMView(); 
            
            header("Location: index.php?page=projects");
        } else {
            header("Location: index.php?page=project_create&error=" . urlencode("Gagal menyimpan data"));
        }
    }

    public function edit() {
        $id = $_GET["id"];
        $project = $this->project->find($id);

        // Ambil Data untuk Dropdown (Query Asli)
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
        
        // [PERBAIKAN] Amankan Budget di Update
        $budget = !empty($_POST['budget']) ? $_POST['budget'] : 0;

        if (strtotime($selesai) < strtotime($mulai)) {
            header("Location: index.php?page=project_edit&id=$id&error=" . urlencode("Tanggal selesai tidak valid!"));
            return;
        }

        // [PERBAIKAN UTAMA] Logika NULL Check untuk Update juga
        $id_tim = !empty($_POST['id_tim']) ? $_POST['id_tim'] : null;
        $id_pj  = !empty($_POST['penanggung_jawab']) ? $_POST['penanggung_jawab'] : null;

        $data = [
            'id' => $id,
            'nama' => $_POST['nama_proyek'],
            'deskripsi' => $_POST['deskripsi'],
            'mulai' => $mulai,
            'selesai' => $selesai,
            'klien' => $_POST['id_klien'],
            
            'tim' => $id_tim,       // Menggunakan variabel aman
            'budget' => $budget,    // Menggunakan variabel aman
            'pj' => $id_pj          // Menggunakan variabel aman
        ];

        if ($this->project->update($data)) {
            // --- REFRESH M-VIEW SETELAH UPDATE SUKSES ---
            $this->project->refreshMView();
            
            header("Location: index.php?page=projects");
        } else {
            header("Location: index.php?page=project_edit&id=$id&error=" . urlencode("Gagal mengupdate proyek"));
        }
    }

    // Fungsi Arsip (Soft Delete)
    public function archive() {
        $id = $_GET['id'];
        if ($this->project->archive($id)) {
            // --- REFRESH M-VIEW SETELAH ARSIP SUKSES ---
            $this->project->refreshMView();
            
            header("Location: index.php?page=projects");
        } else {
            header("Location: index.php?page=projects&error=" . urlencode("Gagal mengarsipkan proyek"));
        }
    }

    // Fungsi untuk Menampilkan Halaman Arsip
    public function archived() {
        $projects = $this->project->getArchived();

        include "../app/views/layout/header.php";
        include "../app/views/project/archived.php"; 
        include "../app/views/layout/footer.php";
    }

    // Fungsi untuk Memulihkan Data (Restore)
    public function restore() {
        $id = $_GET['id'];
        if ($this->project->restore($id)) {
            // --- REFRESH M-VIEW SETELAH RESTORE SUKSES ---
            $this->project->refreshMView();
            
            header("Location: index.php?page=projects");
        } else {
            header("Location: index.php?page=project_archived&error=" . urlencode("Gagal memulihkan proyek"));
        }
    }
}
?>