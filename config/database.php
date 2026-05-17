<?php

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

