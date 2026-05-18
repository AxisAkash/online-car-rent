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

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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

    public function handleInvoice()
    {
        $this->requireMember();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->placeOrderAndRedirectToInvoice();
        }

        $orderId = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;

        if ($orderId <= 0) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        $order = $this->orderModel->getOrderByIdForUser($orderId, (int) $_SESSION['user_id']);

        if (!$order) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        $pageTitle = 'Invoice';

        require __DIR__ . '/../views/orders/invoice.php';
    }

    private function placeOrderAndRedirectToInvoice()
    {
        if (!$this->isValidCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Invalid request token.');
        }

        $carId = isset($_POST['car_id']) ? (int) $_POST['car_id'] : 0;
        $startDate = trim($_POST['start_date'] ?? '');
        $endDate = trim($_POST['end_date'] ?? '');

        $error = $this->validateOrderInput($carId, $startDate, $endDate);

        if ($error !== '') {
            $_SESSION['order_error'] = $error;
            header('Location: ' . BASE_URL . 'car_details.php?id=' . $carId);
            exit;
        }

        $car = $this->orderModel->getCarById($carId);

        if (!$car) {
            $_SESSION['order_error'] = 'Selected car is not available.';
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        $totalDays = $this->calculateRentalDays($startDate, $endDate);
        $totalCost = $totalDays * (float) $car['price_per_day'];

        $orderId = $this->orderModel->createOrder(
            (int) $_SESSION['user_id'],
            $carId,
            $startDate,
            $endDate,
            $totalCost
        );

        if (!$orderId) {
            $_SESSION['order_error'] = 'Order could not be created. Please try again.';
            header('Location: ' . BASE_URL . 'car_details.php?id=' . $carId);
            exit;
        }

        header('Location: ' . BASE_URL . 'invoice.php?order_id=' . $orderId);
        exit;
    }

    public function cancelOrder()
    {
        $this->requireMember();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        if (!$this->isValidCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Invalid request token.');
        }

        $orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;

        if ($orderId <= 0) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        $this->orderModel->cancelOrderById($orderId, (int) $_SESSION['user_id']);

        header('Location: ' . BASE_URL . 'member_cars.php?order_cancelled=1');
        exit;
    }

    public function showPayment()
    {
        $this->requireMember();

        $orderId = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;

        if ($orderId <= 0) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        $order = $this->orderModel->getOrderByIdForUser($orderId, (int) $_SESSION['user_id']);

        if (!$order) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        if ($order['status'] !== 'pending') {
            header('Location: ' . BASE_URL . 'invoice.php?order_id=' . $orderId);
            exit;
        }

        $pageTitle = 'Payment Method';

        require __DIR__ . '/../views/orders/payment.php';
    }

    public function processPayment()
    {
        $this->requireMember();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        if (!$this->isValidCsrfToken($_POST['csrf_token'] ?? '')) {
            die('Invalid request token.');
        }

        $orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
        $paymentMethod = trim($_POST['payment_method'] ?? '');
        $transactionId = trim($_POST['transaction_id'] ?? '');

        $allowedMethods = [
            'credit_card',
            'bkash',
            'nagad',
            'bank_transfer',
            'cash_on_delivery'
        ];

        if ($orderId <= 0) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        if (!in_array($paymentMethod, $allowedMethods, true)) {
            $_SESSION['payment_error'] = 'Please select a valid payment method.';
            header('Location: ' . BASE_URL . 'payment.php?order_id=' . $orderId);
            exit;
        }

        if ($paymentMethod !== 'cash_on_delivery' && $transactionId === '') {
            $_SESSION['payment_error'] = 'Transaction ID is required for this payment method.';
            header('Location: ' . BASE_URL . 'payment.php?order_id=' . $orderId);
            exit;
        }

        if ($paymentMethod === 'cash_on_delivery' && $transactionId === '') {
            $transactionId = 'COD-' . time();
        }

        $order = $this->orderModel->getOrderByIdForUser($orderId, (int) $_SESSION['user_id']);

        if (!$order) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        if ($order['status'] !== 'pending') {
            $_SESSION['payment_error'] = 'Only pending orders can be paid.';
            header('Location: ' . BASE_URL . 'invoice.php?order_id=' . $orderId);
            exit;
        }

        $paymentSuccess = $this->orderModel->confirmOrderPayment(
            $orderId,
            (int) $_SESSION['user_id'],
            (float) $order['total_cost'],
            $paymentMethod,
            $transactionId
        );

        if (!$paymentSuccess) {
            $_SESSION['payment_error'] = 'Payment could not be completed. Please try again.';
            header('Location: ' . BASE_URL . 'payment.php?order_id=' . $orderId);
            exit;
        }

        header('Location: ' . BASE_URL . 'payment_success.php?order_id=' . $orderId);
        exit;
    }

    public function showPaymentSuccess()
    {
        $this->requireMember();

        $orderId = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;

        if ($orderId <= 0) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        $order = $this->orderModel->getOrderByIdForUser($orderId, (int) $_SESSION['user_id']);

        if (!$order) {
            header('Location: ' . BASE_URL . 'member_cars.php');
            exit;
        }

        if ($order['status'] !== 'confirmed') {
            header('Location: ' . BASE_URL . 'invoice.php?order_id=' . $orderId);
            exit;
        }

        $pageTitle = 'Payment Success';

        require __DIR__ . '/../views/orders/payment_success.php';
    }

    public function showRentalHistory()
    {
        $this->requireMember();

        $rentalHistory = $this->orderModel->getRentalHistoryForUser((int) $_SESSION['user_id']);

        $pageTitle = 'Rental History';

        require __DIR__ . '/../views/orders/rental_history.php';
    }

    private function requireMember()
    {
        if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'member') {
            header('Location: ' . BASE_URL . 'login.php');
            exit;
        }
    }

    private function validateOrderInput($carId, $startDate, $endDate)
    {
        if ($carId <= 0) {
            return 'Invalid car selected.';
        }

        if ($startDate === '' || $endDate === '') {
            return 'Start date and end date are required.';
        }

        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        $todayTimestamp = strtotime(date('Y-m-d'));

        if (!$startTimestamp || !$endTimestamp) {
            return 'Invalid rental dates.';
        }

        if ($startTimestamp < $todayTimestamp) {
            return 'Start date cannot be in the past.';
        }

        if ($endTimestamp <= $startTimestamp) {
            return 'End date must be after start date.';
        }

        return '';
    }

    private function calculateRentalDays($startDate, $endDate)
    {
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);

        return (int) (($endTimestamp - $startTimestamp) / 86400);
    }

    private function isValidCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}