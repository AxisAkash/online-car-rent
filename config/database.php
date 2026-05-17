<?php
<<<<<<< HEAD
// config/database.php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'online_car_rent';
$port = 3307;

$conn = mysqli_connect($host, $username, $password, $database, $port);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
=======

class Database
{
    private $host = "localhost";
    private $db_name = "online-car-rent";
    private $username = "root";
    private $password = "";
    private $conn;

    public function connect()
    {
        $this->conn = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->db_name
        );

        if (!$this->conn) {
            die("Database connection failed.");
        }

        mysqli_set_charset($this->conn, "utf8mb4");

        return $this->conn;
    }
}

>>>>>>> origin/main
