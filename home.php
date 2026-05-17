<?php
// home.php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/HomeController.php';

$authController = new AuthController();
$authController->autoLoginFromCookie();

$homeController = new HomeController();
$homeController->index();