<?php
// views/layouts/navbar.php

require_once __DIR__ . '/../../config/app.php';
?>

<div class="nav-brand">
    <a href="<?= BASE_URL; ?>index.php">Online Car Rent</a>
</div>

    <button class="nav-toggle" id="navToggle">☰</button>

    <ul class="nav-links" id="navLinks">
        <?php if (!isLoggedIn()): ?>
            <li><a class="<?= activeClass('login.php'); ?>" href="<?= BASE_URL; ?>login.php">Login</a></li>
            <li><a class="<?= activeClass('register.php'); ?>" href="<?= BASE_URL; ?>register.php">Register</a></li>

        <?php elseif (userRole() === 'admin'): ?>
            <li><a class="<?= activeClass('home.php'); ?>" href="<?= BASE_URL; ?>home.php">Home</a></li>
            <li><a href="<?= BASE_URL; ?>admin/dashboard.php">Admin Dashboard</a></li>
            <li><a href="<?= BASE_URL; ?>admin/cars.php">Manage Cars</a></li>
            <li><a href="<?= BASE_URL; ?>admin/members.php">Members</a></li>
            <li><a href="<?= BASE_URL; ?>admin/orders.php">All Orders</a></li>
            <li><a href="<?= BASE_URL; ?>blog.php">Blog</a></li>
            <li><a class="<?= activeClass('profile.php'); ?>" href="<?= BASE_URL; ?>profile.php">Profile</a></li>
            <li><a href="<?= BASE_URL; ?>logout.php">Logout</a></li>

        <?php elseif (userRole() === 'member'): ?>
            <li><a class="<?= activeClass('home.php'); ?>" href="<?= BASE_URL; ?>home.php">Home</a></li>
            <li><a href="<?= BASE_URL; ?>home.php#categories">Browse Cars</a></li>
            <li><a href="<?= BASE_URL; ?>blog.php">Blog</a></li>
            <li><a href="<?= BASE_URL; ?>rental_history.php">Order History</a></li>
            <li><a class="<?= activeClass('profile.php'); ?>" href="<?= BASE_URL; ?>profile.php">Profile</a></li>
            <li><a href="<?= BASE_URL; ?>logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</nav>

<script>
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');

    if (navToggle) {
        navToggle.addEventListener('click', function () {
            navLinks.classList.toggle('show');
        });
    }
</script>