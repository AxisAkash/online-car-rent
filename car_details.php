<?php
// car_details.php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/OrderController.php';

$orderController = new OrderController($conn);
$orderController->showCarDetails();