<?php 
class TeamController {
    private $db; 
    private $team; // Properti untuk Model

    public function __construct($db) {
        $this->db = $db; 
        
        // Memanggil Model Team (Pastikan file app/models/Team.php sudah ada)
        // Jika belum ada model, kita bisa pakai query manual dulu di sini.
        // Tapi untuk kerapian, lebih baik pakai model seperti Project.
        // Di sini saya asumsikan pakai Model agar konsisten.
        require_once "../app/models/Team.php";
        $this->team = new Team($db); 
    }

    // --- READ (DAFTAR TIM) ---
    public function index() {
        // Panggil fungsi all() dari Model Team
        $teams = $this->team->all();

        include "../app/views/layout/header.php";
        include "../app/views/team/list.php"; 
        include "../app/views/layout/footer.php";
    }

    // --- CREATE (FORM TAMBAH) ---
    public function create() {
        include "../app/views/layout/header.php";
        include "../app/views/team/create.php"; 
        include "../app/views/layout/footer.php";
    }

    // --- STORE (SIMPAN DATA BARU) ---
    public function store() {
        if (empty($_POST['nama_tim'])) {
            header("Location: index.php?page=team_create&error=" . urlencode("Nama Tim Wajib Diisi!"));
            return;
        }

        // Siapkan data array untuk dikirim ke Model
        $data = [
            'nama'      => $_POST['nama_tim'],
            'deskripsi' => $_POST['deskripsi']
        ];

        // Panggil fungsi create() di Model Team
        if ($this->team->create($data)) {
            header("Location: index.php?page=teams");
        } else {
            header("Location: index.php?page=team_create&error=" . urlencode("Gagal menyimpan tim"));
        }
    }

    // --- EDIT (FORM EDIT) ---
    public function edit() {
        $id = $_GET['id'];
        
        // Ambil data tim berdasarkan ID dari Model
        $team = $this->team->find($id);

        if (!$team) {
            echo "Data tim tidak ditemukan.";
            return;
        }

        include "../app/views/layout/header.php";
        include "../app/views/team/edit.php"; 
        include "../app/views/layout/footer.php";
    }

    // --- UPDATE (SIMPAN PERUBAHAN) ---
    public function update() {
        $id = $_POST['id_tim'];

        // Siapkan data update
        $data = [
            'id'        => $id,
            'nama'      => $_POST['nama_tim'],
            'deskripsi' => $_POST['deskripsi']
        ];

        // Panggil fungsi update() di Model Team
        if ($this->team->update($data)) {
            header("Location: index.php?page=teams");
        } else {
            header("Location: index.php?page=team_edit&id=$id&error=" . urlencode("Gagal mengupdate tim"));
        }
    }

    // --- DELETE (HAPUS TIM) ---
    public function delete() {
        $id = $_GET['id'];
        
        // Panggil fungsi delete() di Model Team
        if ($this->team->delete($id)) {
            header("Location: index.php?page=teams");
        } else {
            header("Location: index.php?page=teams&error=" . urlencode("Gagal menghapus tim."));
        }
    }
}
?>