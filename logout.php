<?php
<<<<<<< HEAD
// logout.php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/controllers/AuthController.php';

$authController = new AuthController();
$authController->logout();
=======
session_start();

session_unset();
session_destroy();

header("Location: /online-car-rent/index.php");
exit;
>>>>>>> origin/main
