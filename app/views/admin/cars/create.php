<?php include "../app/views/layouts/header.php"; ?>
<?php include "../app/views/layouts/sidebar.php"; ?>

<div class="main">

<h2>Add Car</h2>

<form action="/admin/cars/store"
      method="POST"
      enctype="multipart/form-data"
      onsubmit="return validateCarForm()">

    <input type="text" name="name" placeholder="Car Name" required>

    <input type="text" name="model" placeholder="Model" required>

    <select name="type">
        <option>Private car</option>
        <option>Microbus</option>
        <option>Pick-up</option>
    </select>

    <input type="number" id="price" name="price_per_day" placeholder="Price" required>

    <textarea name="description"></textarea>

    <input type="file" id="image" name="image" required>

    <select name="status">
        <option>Available</option>
        <option>Booked</option>
    </select>

    <button type="submit">Add Car</button>

</form>

</div>

<?php include "../app/views/layouts/footer.php"; ?>