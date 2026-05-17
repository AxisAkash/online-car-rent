<?php
// api/check_email.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

header('Content-Type: application/json');

$email = trim($_GET['email'] ?? '');

if ($email === '') {
    echo json_encode([
        'success' => false,
        'exists' => false,
        'message' => 'Email is required.'
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'exists' => false,
        'message' => 'Invalid email format.'
    ]);
    exit();
}

$userModel = new User($conn);
$exists = $userModel->emailExists($email);

echo json_encode([
    'success' => true,
    'exists' => $exists,
    'message' => $exists ? 'Email already exists.' : 'Email is available.'
]);