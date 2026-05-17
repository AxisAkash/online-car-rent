<?php
require_once "../app/core/Model.php";

class Car extends Model {

    public function all() {
        return $this->db->query("SELECT * FROM cars ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM cars WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO cars(name, model, type, price_per_day, description, image, availability_status)
                VALUES (?,?,?,?,?,?,?)";

        return $this->db->prepare($sql)->execute($data);
    }

    public function update($data) {
        $sql = "UPDATE cars SET name=?, model=?, type=?, price_per_day=?, description=?, image=?, availability_status=? WHERE id=?";
        return $this->db->prepare($sql)->execute($data);
    }

    public function delete($id) {

        $check = $this->db->prepare("SELECT * FROM orders WHERE car_id=? AND status IN ('Pending','Confirmed')");
        $check->execute([$id]);

        if ($check->rowCount() > 0) return false;

        return $this->db->prepare("DELETE FROM cars WHERE id=?")->execute([$id]);
    }
}