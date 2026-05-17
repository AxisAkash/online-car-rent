<?php
require_once "../app/models/Car.php";

class CarController {

    private $car;

    public function __construct() {
        $this->car = new Car();
    }

    public function index() {
        $cars = $this->car->all();
        require "../app/views/admin/cars/index.php";
    }

    public function store() {

        $name = $_POST['name'];
        $price = $_POST['price_per_day'];

        if ($price <= 0) die("Invalid price");

        $image = $_FILES['image'];

        if (!in_array($image['type'], ['image/jpeg','image/png'])) {
            die("Invalid image");
        }

        if ($image['size'] > 2*1024*1024) {
            die("Image too large");
        }

        $imageName = time().$image['name'];
        move_uploaded_file($image['tmp_name'], "../public/uploads/cars/".$imageName);

        $this->car->create([
            $name,
            $_POST['model'],
            $_POST['type'],
            $price,
            $_POST['description'],
            $imageName,
            $_POST['status']
        ]);

        header("Location: /admin/cars");
    }

    public function edit() {
        $car = $this->car->find($_GET['id']);
        require "../app/views/admin/cars/edit.php";
    }

    public function update() {

        $id = $_POST['id'];
        $old = $this->car->find($id);

        $imageName = $old['image'];

        if (!empty($_FILES['image']['name'])) {

            unlink("../public/uploads/cars/".$old['image']);

            $imageName = time().$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],
                "../public/uploads/cars/".$imageName);
        }

        $this->car->update([
            $_POST['name'],
            $_POST['model'],
            $_POST['type'],
            $_POST['price_per_day'],
            $_POST['description'],
            $imageName,
            $_POST['status'],
            $id
        ]);

        header("Location: /admin/cars");
    }

    public function delete() {

        $id = $_GET['id'];

        $car = $this->car->find($id);
        $result = $this->car->delete($id);

        header("Content-Type: application/json");

        if ($result) {
            unlink("../public/uploads/cars/".$car['image']);
            echo json_encode(["status"=>"success"]);
        } else {
            echo json_encode(["status"=>"error","message"=>"Car has active orders"]);
        }
    }
}