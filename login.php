<?php
// login.php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/controllers/AuthController.php';

$authController = new AuthController();
$authController->autoLoginFromCookie();

if (isLoggedIn()) {
    redirect('home.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController->login();
} else {
    $authController->showLogin();
}