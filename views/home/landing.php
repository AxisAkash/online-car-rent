<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="landing-hero">
    <div class="landing-content">
        <h1>Welcome to Online Car Rent</h1>
        <p>
            Rent cars easily for personal trips, family tours, office work, or goods transportation.
            Browse available cars, choose your category, and start your rental journey.
        </p>

        <div class="landing-buttons">
            <a href="<?= BASE_URL; ?>login.php" class="btn primary-btn landing-btn">Login</a>
            <a href="<?= BASE_URL; ?>register.php" class="btn outline-btn landing-btn">Register</a>
        </div>
    </div>

    <div class="landing-card">
        <h2>Why Choose Us?</h2>

        <div class="feature-list">
            <div class="feature-item">
                <h3>Easy Booking</h3>
                <p>Members can browse cars and place rent orders easily.</p>
            </div>

            <div class="feature-item">
                <h3>Multiple Categories</h3>
                <p>Choose from private cars, microbus, pick-up, and more.</p>
            </div>

            <div class="feature-item">
                <h3>Secure Account</h3>
                <p>Login, profile update, and role-based access are protected.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-box">
    <div class="section-title">
        <h2>Available Car Categories</h2>
        <p>Login or register to browse cars by category.</p>
    </div>

    <?php if (empty($categories)): ?>
        <div class="empty-box">
            No categories available yet.
        </div>
    <?php else: ?>
        <div class="category-bar">
            <?php foreach ($categories as $category): ?>
                <a href="<?= BASE_URL; ?>login.php" class="category-link">
                    <?= e($category); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="section-box">
    <div class="section-title">
        <h2>Featured Cars</h2>
        <p>Some available cars from our system.</p>
    </div>

    <?php if (empty($featuredCars)): ?>
        <div class="empty-box">
            No cars available yet.
        </div>
    <?php else: ?>
        <div class="car-grid">
            <?php foreach ($featuredCars as $car): ?>
                <div class="car-card">
                    <?php if (!empty($car['image_path'])): ?>
                        <img src="<?= BASE_URL . e($car['image_path']); ?>" alt="<?= e($car['name']); ?>">
                    <?php else: ?>
                        <div class="car-placeholder">No Image</div>
                    <?php endif; ?>

                    <div class="car-card-body">
                        <h3><?= e($car['name']); ?></h3>
                        <p><strong>Model:</strong> <?= e($car['model']); ?></p>
                        <p><strong>Type:</strong> <?= e($car['type']); ?></p>
                        <p><strong>Price:</strong> BDT <?= e($car['price_per_day']); ?> / day</p>

                        <a class="btn small-btn" href="<?= BASE_URL; ?>login.php">
                            Login to View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>