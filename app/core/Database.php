<?php
class Database {
    private $host = "localhost";
    private $db = "car_rental";
    private $user = "root";
    private $pass = "";

    public $conn;

    public function __construct() {
        $this->conn = new PDO(
            "mysql:host=$this->host;dbname=$this->db;charset=utf8",
            $this->user,
            $this->pass
        );

        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}