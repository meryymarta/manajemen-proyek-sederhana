<?php
// app/controllers/TeamController.php

require_once __DIR__ . "/../models/Team.php";

class TeamController {
    private $team;

    public function __construct($db) {
        // Menginisialisasi Model Team
        $this->team = new Team($db);
    }

    public function index() {
        // Mengambil semua data tim
        $teams = $this->team->all();

        // Tampilkan halaman daftar tim dengan layout
        include __DIR__ . "/../views/layout/header.php";
        include __DIR__ . "/../views/team/list.php";
        include __DIR__ . "/../views/layout/footer.php";
    }

    public function create() {
        // Tampilkan form pembuatan tim
        include __DIR__ . "/../views/layout/header.php";
        include __DIR__ . "/../views/team/create.php";
        include __DIR__ . "/../views/layout/footer.php";
    }

    public function store() {
        // Proses penyimpanan data tim baru
        $data = [
            // Asumsi field di tabel tim adalah 'nama_tim' dan 'deskripsi'
            ':nama_tim' => $_POST['nama_tim'], 
            ':deskripsi' => $_POST['deskripsi'],
        ];

        $this->team->create($data);
        
        // Redirect setelah operasi POST (Pola PRG)
        header("Location: index.php?page=teams");
        exit;
    }

    public function edit() {
        // Ambil ID dari URL dan cari data tim
        $id = $_GET['id'];
        $team = $this->team->find($id);

        // Tampilkan form edit
        include __DIR__ . "/../views/layout/header.php";
        include __DIR__ . "/../views/team/edit.php";
        include __DIR__ . "/../views/layout/footer.php";
    }

    public function update() {
        // Proses update data tim
        $data = [
            ':id' => $_POST['id'],
            ':nama_tim' => $_POST['nama_tim'],
            ':deskripsi' => $_POST['deskripsi'],
        ];

        $this->team->update($data);
        header("Location: index.php?page=teams");
        exit;
    }

    public function delete() {
        // Proses penghapusan tim
        $this->team->delete($_GET['id']);
        header("Location: index.php?page=teams");
        exit;
    }
}
?>