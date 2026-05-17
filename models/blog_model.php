<?php

class BlogModel
{
    private $conn;
    private $table = "blogs";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllBlogs()
    {
        $sql = "SELECT 
                    blogs.id,
                    blogs.user_id,
                    blogs.title,
                    blogs.content,
                    blogs.created_at,
                    users.name AS author
                FROM {$this->table}
                INNER JOIN users ON blogs.user_id = users.id
                ORDER BY blogs.created_at DESC";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return [];
        }

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $blogs = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $blogs[] = $row;
        }

        mysqli_stmt_close($stmt);

        return $blogs;
    }

    public function createBlog($userId, $title, $content)
    {
        $sql = "INSERT INTO {$this->table} 
                    (user_id, title, content, created_at, updated_at)
                VALUES 
                    (?, ?, ?, NOW(), NOW())";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "iss", $userId, $title, $content);

        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        return $success;
    }

    public function getBlogById($blogId)
    {
        $sql = "SELECT id, user_id, title, content
                FROM {$this->table}
                WHERE id = ?
                LIMIT 1";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "i", $blogId);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $blog = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        return $blog;
    }

    public function deleteBlog($blogId)
    {
        $sql = "DELETE FROM {$this->table}
                WHERE id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "i", $blogId);

        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        return $success;
    }
}