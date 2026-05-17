<?php
$url = $_GET['url'] ?? '';

require "../app/controllers/AdminController.php";
require "../app/controllers/CarController.php";
require "../app/controllers/MemberController.php";
require "../app/controllers/OrderController.php";

switch ($url) {

    case "admin/dashboard":
        (new AdminController())->dashboard();
        break;

    case "admin/cars":
        (new CarController())->index();
        break;

    case "admin/cars/store":
        (new CarController())->store();
        break;

    case "admin/cars/edit":
        (new CarController())->edit();
        break;

    case "admin/cars/update":
        (new CarController())->update();
        break;

    case "admin/cars/delete":
        (new CarController())->delete();
        break;

    case "admin/members":
        (new MemberController())->index();
        break;

    case "admin/members/delete":
        (new MemberController())->delete();
        break;

    case "admin/orders":
        (new OrderController())->index();
        break;
}