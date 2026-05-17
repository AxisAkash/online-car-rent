
<?php
// Handles blog API requests, validation, and authorization.

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/blog_model.php";

class BlogController
{
    private $blogModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $database = new Database();
        $db = $database->connect();

        $this->blogModel = new BlogModel($db);
    }

    private function jsonResponse($success, $message, $data = [])
    {
        header("Content-Type: application/json");

        echo json_encode([
            "success" => $success,
            "message" => $message,
            "data" => $data
        ]);

        exit;
    }

    private function requireLogin()
    {
        if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"])) {
            $this->jsonResponse(false, "Please login first.");
        }
    }

    private function cleanInput($value)
    {
        $value = trim($value);
        $value = strip_tags($value);
        return $value;
    }

    public function listBlogs()
    {
        $this->requireLogin();

        $blogs = $this->blogModel->getAllBlogs();

        foreach ($blogs as &$blog) {
            $blog["title"] = htmlspecialchars($blog["title"], ENT_QUOTES, "UTF-8");
            $blog["content"] = nl2br(htmlspecialchars($blog["content"], ENT_QUOTES, "UTF-8"));
            $blog["author"] = htmlspecialchars($blog["author"], ENT_QUOTES, "UTF-8");

            $blog["can_delete"] = false;

            if ($_SESSION["role"] === "admin") {
                $blog["can_delete"] = true;
            }

            if ($_SESSION["role"] === "member" && (int)$blog["user_id"] === (int)$_SESSION["user_id"]) {
                $blog["can_delete"] = true;
            }
        }

        $this->jsonResponse(true, "Blogs loaded successfully.", $blogs);
    }

    public function createBlog()
    {
        $this->requireLogin();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->jsonResponse(false, "Invalid request method.");
        }

        $title = $this->cleanInput($_POST["title"] ?? "");
        $content = $this->cleanInput($_POST["content"] ?? "");

        if ($title === "") {
            $this->jsonResponse(false, "Blog title is required.");
        }

        if ($content === "") {
            $this->jsonResponse(false, "Blog content is required.");
        }

        if (strlen($title) > 255) {
            $this->jsonResponse(false, "Blog title must be less than 255 characters.");
        }

        if (strlen($content) < 10) {
            $this->jsonResponse(false, "Blog content must be at least 10 characters.");
        }

        $result = $this->blogModel->createBlog(
            (int)$_SESSION["user_id"],
            $title,
            $content
        );

        if ($result) {
            $this->jsonResponse(true, "Blog posted successfully.");
        }

        $this->jsonResponse(false, "Could not post blog.");
    }

    public function deleteBlog()
    {
        $this->requireLogin();

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->jsonResponse(false, "Invalid request method.");
        }

        $blogId = (int)($_POST["id"] ?? 0);

        if ($blogId <= 0) {
            $this->jsonResponse(false, "Invalid blog ID.");
        }

        $blog = $this->blogModel->getBlogById($blogId);

        if (!$blog) {
            $this->jsonResponse(false, "Blog not found.");
        }

        $isAdmin = $_SESSION["role"] === "admin";
        $isOwner = $_SESSION["role"] === "member" && (int)$blog["user_id"] === (int)$_SESSION["user_id"];

        if (!$isAdmin && !$isOwner) {
            $this->jsonResponse(false, "You are not allowed to delete this blog.");
        }

        $result = $this->blogModel->deleteBlog($blogId);

        if ($result) {
            $this->jsonResponse(true, "Blog deleted successfully.");
        }

        $this->jsonResponse(false, "Could not delete blog.");
    }
}