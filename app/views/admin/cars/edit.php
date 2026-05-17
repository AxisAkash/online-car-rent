<?php include "../app/views/layouts/header.php"; ?>
<?php include "../app/views/layouts/sidebar.php"; ?>

<div class="main">

<h2>Edit Car</h2>

<form action="/admin/cars/update" method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $car['id'] ?>">

<input type="text" name="name" value="<?= $car['name'] ?>">

<input type="text" name="model" value="<?= $car['model'] ?>">

<input type="number" name="price_per_day" value="<?= $car['price_per_day'] ?>">

<textarea name="description"><?= $car['description'] ?></textarea>

<img src="/uploads/cars/<?= $car['image'] ?>" width="100">

<input type="file" name="image">

<button>Update</button>

</form>

</div>

<?php include "../app/views/layouts/footer.php"; ?>