<?php
// api/calculate_total_cost.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Order.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

$carId = isset($_POST['car_id']) ? (int) $_POST['car_id'] : 0;
$startDate = trim($_POST['start_date'] ?? '');
$endDate = trim($_POST['end_date'] ?? '');

if ($carId <= 0 || $startDate === '' || $endDate === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Car, start date, and end date are required.'
    ]);
    exit;
}

$startTimestamp = strtotime($startDate);
$endTimestamp = strtotime($endDate);
$todayTimestamp = strtotime(date('Y-m-d'));

if (!$startTimestamp || !$endTimestamp) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid rental dates.'
    ]);
    exit;
}

if ($startTimestamp < $todayTimestamp) {
    echo json_encode([
        'success' => false,
        'message' => 'Start date cannot be in the past.'
    ]);
    exit;
}

if ($endTimestamp <= $startTimestamp) {
    echo json_encode([
        'success' => false,
        'message' => 'End date must be after start date.'
    ]);
    exit;
}

$orderModel = new Order($conn);
$car = $orderModel->getCarById($carId);

if (!$car) {
    echo json_encode([
        'success' => false,
        'message' => 'Selected car is not available.'
    ]);
    exit;
}

$totalDays = (int) (($endTimestamp - $startTimestamp) / 86400);
$totalCost = $totalDays * (float) $car['price_per_day'];

echo json_encode([
    'success' => true,
    'days' => $totalDays,
    'total_cost' => number_format($totalCost, 2, '.', ''),
    'formatted_total' => 'BDT ' . number_format($totalCost, 2)
]);
exit;