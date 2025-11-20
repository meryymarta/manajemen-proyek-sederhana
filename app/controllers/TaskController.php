<?php
require_once __DIR__ . "/../models/Task.php";

class TaskController {
    private $task;

    public function __construct($db) {
        $this->task = new Task($db);
    }

    public function index() {
        $tasks = $this->task->all();
        include __DIR__ . "/../views/layout/header.php";
        include __DIR__ . "/../views/task/list.php";
        include __DIR__ . "/../views/layout/footer.php";
    }

    public function create() {
        include __DIR__ . "/../views/layout/header.php";
        include __DIR__ . "/../views/task/create.php";
        include __DIR__ . "/../views/layout/footer.php";
    }

    public function store() {
        $data = [
            ':nama' => $_POST['nama_tugas'],
            ':proyek' => $_POST['id_proyek'],
            ':anggota' => $_POST['id_anggota'],
            ':status' => $_POST['id_status'],
            ':deadline' => $_POST['deadline'],
        ];
        $this->task->create($data);
        header("Location: index.php?page=tasks");
    }
}
?>
