<?php

require_once __DIR__ . "/../controllers/blog_controller.php";

$controller = new BlogController();

$action = $_GET["action"] ?? "";

if ($action === "list") {
    $controller->listBlogs();
} elseif ($action === "create") {
    $controller->createBlog();
} elseif ($action === "delete") {
    $controller->deleteBlog();
} else {
    header("Content-Type: application/json");

    echo json_encode([
        "success" => false,
        "message" => "Invalid API action."
    ]);
}