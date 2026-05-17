<?php
require_once "../app/core/Model.php";

class Blog extends Model {

    // Get all blogs
    public function all() {
        $sql = "SELECT b.*, u.name AS author_name
                FROM blogs b
                LEFT JOIN users u ON b.user_id = u.id
                ORDER BY b.id DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find single blog
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create blog
    public function create($data) {
        $sql = "INSERT INTO blogs (user_id, title, content, image)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // Update blog
    public function update($data) {
        $sql = "UPDATE blogs
                SET title = ?, content = ?, image = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // Delete blog
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM blogs WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Get blogs by user
    public function getByUser($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM blogs WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}