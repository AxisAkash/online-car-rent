<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="hero-section">
    <div>
        <h1>Welcome, <?= e($_SESSION['name']); ?></h1>
        <p>Find your perfect rental car by browsing available categories.</p>
    </div>
</section>

<?php if ($error = getFlash('error')): ?>
    <div class="alert error-alert"><?= e($error); ?></div>
<?php endif; ?>

<section class="section-box">
    <div class="section-title">
        <h2>Featured Cars</h2>
        <p>Recently added available cars</p>
    </div>

    <?php if (empty($featuredCars)): ?>
        <div class="empty-box">
            No featured cars available right now. Ask Task 2 teammate to add cars.
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

                        <a class="btn small-btn" href="<?= BASE_URL; ?>car_details.php?id=<?= e($car['id']); ?>">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="section-box" id="categories">
    <div class="section-title">
        <h2>Browse by Category</h2>
        <p>Click a category to load cars dynamically using AJAX</p>
    </div>

    <?php if (empty($categories)): ?>
        <div class="empty-box">
            No categories found. Categories will appear after cars are added.
        </div>
    <?php else: ?>
        <div class="category-bar">
            <?php foreach ($categories as $category): ?>
                <a
                    href="<?= BASE_URL; ?>category.php?type=<?= urlencode($category); ?>"
                    class="category-link"
                    data-ajax="true"
                    data-type="<?= e($category); ?>"
                >
                    <?= e($category); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h3 id="ajaxResultTitle" class="ajax-title"></h3>
    <div id="ajaxCarContainer" class="car-grid"></div>
</section>

<section class="section-box">
    <div class="section-title">
        <h2>Quick Navigation</h2>
    </div>

    <div class="quick-links">
        <a href="<?= BASE_URL; ?>profile.php">My Profile</a>
        <a href="<?= BASE_URL; ?>blog.php">Blog Page</a>
        <a href="<?= BASE_URL; ?>rental_history.php">Order History</a>
    </div>
</section>

<script src="<?= BASE_URL; ?>public/js/category_ajax.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>