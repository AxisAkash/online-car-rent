<?php
// controllers/HomeController.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Car.php';

class HomeController
{
    private $carModel;

    public function __construct()
    {
        global $conn;
        $this->carModel = new Car($conn);
    }

    public function index()
    {
        requireLogin();

        $featuredCars = $this->carModel->getFeaturedCars(6);
        $categories = $this->carModel->getCategories();

        $pageTitle = 'Home';

        require __DIR__ . '/../views/home/index.php';
    }

    public function category()
    {
        requireLogin();

        $type = trim($_GET['type'] ?? '');

        if ($type === '') {
            setFlash('error', 'Please select a valid category.');
            redirect('home.php');
        }

        $categories = $this->carModel->getCategories();
        $cars = $this->carModel->getCarsByCategory($type);

        $pageTitle = 'Category - ' . $type;

        require __DIR__ . '/../views/home/category.php';
    }
}