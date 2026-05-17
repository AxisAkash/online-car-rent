<?php
// views/layouts/header.php

require_once __DIR__ . '/../../config/app.php';

if (!isset($pageTitle)) {
    $pageTitle = 'Online Car Rent';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= e($pageTitle); ?> | Online Car Rent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        const BASE_URL = "<?= BASE_URL; ?>";
    </script>

    <link rel="stylesheet" href="<?= BASE_URL; ?>public/css/style.css">
</head>
<body>

<?php require __DIR__ . '/navbar.php'; ?>

<main class="main-container">