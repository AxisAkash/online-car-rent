<?php include "../app/views/layouts/header.php"; ?>
<?php include "../app/views/layouts/sidebar.php"; ?>

<div class="main">

<h2>Cars</h2>

<a href="/admin/cars/create">+ Add Car</a>

<?php foreach($cars as $c): ?>
<div class="card">
    <img src="/uploads/cars/<?= $c['image'] ?>" width="100">

    <h4><?= $c['name'] ?></h4>
    <p><?= $c['model'] ?></p>

    <a href="/admin/cars/edit?id=<?= $c['id'] ?>">Edit</a>
    <button onclick="deleteCar(<?= $c['id'] ?>)">Delete</button>
</div>
<?php endforeach; ?>

</div>

<?php include "../app/views/layouts/footer.php"; ?>