<?php
require_once "../app/core/Model.php";

class User extends Model {

    public function members() {
        return $this->db->query("SELECT * FROM users WHERE role='member'")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        return $this->db->prepare("DELETE FROM users WHERE id=? AND role='member'")
                        ->execute([$id]);
    }
}