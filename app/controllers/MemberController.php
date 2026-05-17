<?php
require_once "../app/models/User.php";

class MemberController {

    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function index() {
        $members = $this->user->members();
        require "../app/views/admin/members/index.php";
    }

    public function delete() {

        $id = $_GET['id'];
        $this->user->delete($id);

        header("Content-Type: application/json");
        echo json_encode(["status"=>"success"]);
    }
}