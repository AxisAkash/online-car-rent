<?php
require_once "../app/models/Order.php";

class OrderController {

    private $order;

    public function __construct() {
        $this->order = new Order();
    }

    public function index() {
        $orders = $this->order->all();
        require "../app/views/admin/orders/index.php";
    }
}