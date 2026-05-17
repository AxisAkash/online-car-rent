<?php include "../app/views/layouts/header.php"; ?>
<?php include "../app/views/layouts/sidebar.php"; ?>

<div class="main">

<h2>Members</h2>

<?php foreach($members as $m): ?>
<div class="card">
    <?= $m['name'] ?> <br>
    <?= $m['email'] ?>

    <button onclick="deleteMember(<?= $m['id'] ?>)">Delete</button>
</div>
<?php endforeach; ?>

</div>

<?php include "../app/views/layouts/footer.php"; ?>