<?php
// controllers/OrderController.php

require_once __DIR__ . '/../models/Order.php';

class OrderController
{
    private $orderModel;

    public function __construct($conn)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->orderModel = new Order($conn);
    }

    public function showMemberCars()
    {
        $cars = $this->orderModel->getAvailableCars();
        $carTypes = $this->orderModel->getCarTypes();

        $pageTitle = 'Available Cars';

        require __DIR__ . '/../views/orders/member_cars.php';
    }

    public function showCarDetails()
    {
        $carId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($carId <= 0) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        $car = $this->orderModel->getCarById($carId);

        if (!$car) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        $pageTitle = 'Car Details';

        require __DIR__ . '/../views/orders/car_details.php';
    }
}