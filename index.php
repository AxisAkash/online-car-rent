<?php
// index.php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Car.php';

$authController = new AuthController();
$authController->autoLoginFromCookie();

// If user is already logged in, send to dashboard/home
if (isLoggedIn()) {
    redirect('home.php');
}

$carModel = new Car($conn);

$featuredCars = $carModel->getFeaturedCars(3);
$categories = $carModel->getCategories();

$pageTitle = 'Welcome';

require __DIR__ . '/views/home/landing.php';