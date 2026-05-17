<?php
require_once "../app/core/Model.php";

class Order extends Model {

    public function all() {
        $sql = "SELECT o.*, u.name as user_name, c.name as car_name, c.model
                FROM orders o
                JOIN users u ON o.user_id=u.id
                JOIN cars c ON o.car_id=c.id
                ORDER BY o.id DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}