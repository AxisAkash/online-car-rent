<?php
// models/Car.php

require_once __DIR__ . '/../config/database.php';

class Car
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function getFeaturedCars($limit = 6)
    {
        $sql = "SELECT * FROM cars 
                WHERE LOWER(availability_status) = 'available'
                ORDER BY created_at DESC
                LIMIT ?";

        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $cars = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $cars[] = $row;
        }

        return $cars;
    }

    public function getCategories()
    {
        $sql = "SELECT DISTINCT type 
                FROM cars 
                WHERE type IS NOT NULL AND type != ''
                ORDER BY type ASC";

        $result = mysqli_query($this->conn, $sql);

        $categories = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row['type'];
        }

        return $categories;
    }

    public function getCarsByCategory($type)
    {
        $sql = "SELECT * FROM cars 
                WHERE type = ?
                ORDER BY created_at DESC";

        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $type);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $cars = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $cars[] = $row;
        }

        return $cars;
    }

    public function getAllAvailableCars()
    {
        $sql = "SELECT * FROM cars 
                WHERE LOWER(availability_status) = 'available'
                ORDER BY created_at DESC";

        $result = mysqli_query($this->conn, $sql);

        $cars = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $cars[] = $row;
        }

        return $cars;
    }
}