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

    public function createOrder($userId, $carId, $startDate, $endDate, $totalCost)
    {
        $sql = "INSERT INTO orders (user_id, car_id, start_date, end_date, total_cost, status, payment_method)
                VALUES (?, ?, ?, ?, ?, ?, NULL)";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return false;
        }

        $status = 'pending';
        mysqli_stmt_bind_param($stmt, "iissds", $userId, $carId, $startDate, $endDate, $totalCost, $status);

        $success = mysqli_stmt_execute($stmt);

        if (!$success) {
            mysqli_stmt_close($stmt);
            return false;
        }

        $orderId = mysqli_insert_id($this->conn);
        mysqli_stmt_close($stmt);

        return $orderId;
    }

    public function getOrderByIdForUser($orderId, $userId)
    {
        $sql = "SELECT 
                    orders.id,
                    orders.user_id,
                    orders.car_id,
                    orders.start_date,
                    orders.end_date,
                    orders.total_cost,
                    orders.status,
                    orders.payment_method,
                    orders.order_date,
                    cars.name AS car_name,
                    cars.model AS car_model,
                    cars.type AS car_type,
                    cars.price_per_day,
                    cars.image_path,
                    cars.description
                FROM orders
                INNER JOIN cars ON orders.car_id = cars.id
                WHERE orders.id = ? AND orders.user_id = ?
                LIMIT 1";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return null;
        }

        mysqli_stmt_bind_param($stmt, "ii", $orderId, $userId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        return $order ?: null;
    }

    public function cancelOrderById($orderId, $userId)
    {
        $sql = "UPDATE orders
                SET status = ?
                WHERE id = ? AND user_id = ? AND status = ?";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return false;
        }

        $newStatus = 'cancelled';
        $currentStatus = 'pending';

        mysqli_stmt_bind_param($stmt, "siis", $newStatus, $orderId, $userId, $currentStatus);
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);

        return $success;
    }
}