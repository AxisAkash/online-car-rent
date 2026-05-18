<?php
// models/Order.php

class Order
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAvailableCars()
    {
        $sql = "SELECT id, name, model, type, price_per_day, availability_status, image_path, description
                FROM cars
                WHERE availability_status = ?
                ORDER BY created_at DESC";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return [];
        }

        $status = 'available';
        mysqli_stmt_bind_param($stmt, "s", $status);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $cars = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $cars[] = $row;
        }

        mysqli_stmt_close($stmt);

        return $cars;
    }

    public function getCarTypes()
    {
        $sql = "SELECT DISTINCT type
                FROM cars
                WHERE availability_status = ?
                ORDER BY type ASC";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return [];
        }

        $status = 'available';
        mysqli_stmt_bind_param($stmt, "s", $status);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $types = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $types[] = $row['type'];
        }

        mysqli_stmt_close($stmt);

        return $types;
    }

    public function getCarById($carId)
    {
        $sql = "SELECT id, name, model, type, price_per_day, availability_status, image_path, description
                FROM cars
                WHERE id = ? AND availability_status = ?
                LIMIT 1";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return null;
        }

        $status = 'available';
        mysqli_stmt_bind_param($stmt, "is", $carId, $status);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $car = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        return $car ?: null;
    }
}