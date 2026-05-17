<?php
require_once "../app/core/Database.php";

class AdminController {

    public function dashboard() {

        $db = (new Database())->conn;

        $cars = $db->query("SELECT COUNT(*) FROM cars")->fetchColumn();
        $members = $db->query("SELECT COUNT(*) FROM users WHERE role='member'")->fetchColumn();
        $orders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $blogs = $db->query("SELECT COUNT(*) FROM blogs")->fetchColumn();

        require "../app/views/admin/dashboard.php";
    }
}