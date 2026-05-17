<?php
// api/get_cars_by_category.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Car.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized. Please login first.'
    ]);

    exit();
}

$type = trim($_GET['type'] ?? '');

if ($type === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Category type is required.'
    ]);

    exit();
}

$carModel = new Car($conn);
$cars = $carModel->getCarsByCategory($type);

echo json_encode([
    'success' => true,
    'type' => $type,
    'cars' => $cars
]);