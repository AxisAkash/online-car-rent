<?php
// profile.php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProfileController.php';

$authController = new AuthController();
$authController->autoLoginFromCookie();

$profileController = new ProfileController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profileController->updateProfile();
} else {
    $profileController->showProfile();
}