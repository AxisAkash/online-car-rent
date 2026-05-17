<?php
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