<?php include "../app/views/layouts/header.php"; ?>
<?php include "../app/views/layouts/sidebar.php"; ?>

<div class="main">

<h2>Order History</h2>

<?php foreach($orders as $o): ?>
<div class="card">
    <?= $o['user_name'] ?> <br>
    <?= $o['car_name'] ?> <br>
    <?= $o['status'] ?> <br>
    <?= $o['total_cost'] ?>
</div>
<?php endforeach; ?>

</div>

<?php include "../app/views/layouts/footer.php"; ?>