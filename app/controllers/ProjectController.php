<?php 
class ProjectController {
    private $project;

    public function __construct($db) {
        $this->project = new Project($db);
    }

    public function index() {
        $projects = $this->project->all();

        // PENTING!!!
        include "../app/views/layout/header.php";
        include "../app/views/project/list.php";
        include "../app/views/layout/footer.php";
    }

    public function create() {
        include "../app/views/layout/header.php";
        include "../app/views/project/create.php";
        include "../app/views/layout/footer.php";
    }

    public function edit() {
        $id = $_GET["id"];
        $project = $this->project->find($id);

        include "../app/views/layout/header.php";
        include "../app/views/project/edit.php";
        include "../app/views/layout/footer.php";
    }
}
?>
