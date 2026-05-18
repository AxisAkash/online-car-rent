<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php
$isMember = isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'member';
?>

<link rel="stylesheet" href="<?= BASE_URL; ?>public/css/order.css">

<section class="order-page-hero">
    <div>
        <span class="order-badge">Member Rental</span>
        <h1>Choose Your Rental Car</h1>
        <p>
            Select a car type, compare available cars, and view details before you place a rental order.
        </p>
    </div>

    <div class="order-hero-card">
        <h3>Rental History</h3>
        <p>View your confirmed, cancelled, and pending rental orders.</p>

        <div class="hero-card-actions">
            <?php if ($isMember): ?>
                <a href="<?= BASE_URL; ?>rental_history.php" class="history-hero-btn">
                    View Rental History
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL; ?>login.php" class="history-hero-btn">
                    Login to View History
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="order-filter-box">
    <div class="filter-left">
        <label for="carTypeFilter">Choose Car Type</label>
        <select id="carTypeFilter">
            <option value="all">All Available Cars</option>

            <?php foreach ($carTypes as $type): ?>
                <option value="<?= e($type); ?>"><?= e($type); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-right">
        <label for="carSearchInput">Search Car</label>
        <input
            type="text"
            id="carSearchInput"
            placeholder="Search by name, model, or type"
        >
    </div>
</section>

<section class="order-section">
    <div class="order-section-title">
        <h2>Available Cars</h2>
        <p id="carCountText">
            <?= count($cars); ?> car(s) available
        </p>
    </div>

    <?php if (empty($cars)): ?>
        <div class="order-empty-box">
            No available cars found.
        </div>
    <?php else: ?>
        <div class="member-car-grid" id="memberCarGrid">
            <?php foreach ($cars as $car): ?>
                <?php
                    $carName = $car['name'] ?? '';
                    $carModel = $car['model'] ?? '';
                    $carType = $car['type'] ?? '';
                    $carPrice = $car['price_per_day'] ?? '0';
                    $imagePath = $car['image_path'] ?? '';
                ?>

                <article
                    class="member-car-card"
                    data-type="<?= e($carType); ?>"
                    data-search="<?= e(strtolower($carName . ' ' . $carModel . ' ' . $carType)); ?>"
                >
                    <div class="member-car-image">
                        <?php if (!empty($imagePath)): ?>
                            <img
                                src="<?= BASE_URL . e($imagePath); ?>"
                                alt="<?= e($carName); ?>"
                            >
                        <?php else: ?>
                            <div class="member-car-placeholder">
                                Car Image
                            </div>
                        <?php endif; ?>

                        <span class="availability-pill">
                            <?= e($car['availability_status']); ?>
                        </span>
                    </div>

                    <div class="member-car-body">
                        <div class="member-car-title-row">
                            <div>
                                <h3><?= e($carName); ?></h3>
                                <p><?= e($carModel); ?></p>
                            </div>

                            <span class="car-type-pill">
                                <?= e($carType); ?>
                            </span>
                        </div>

                        <p class="member-car-description">
                            <?= e($car['description'] ?? 'No description available.'); ?>
                        </p>

                        <div class="member-car-price-row">
                            <div>
                                <span class="price-label">Price per day</span>
                                <strong>BDT <?= e(number_format((float) $carPrice, 2)); ?></strong>
                            </div>

                            <a
                                class="btn view-details-btn"
                                href="<?= BASE_URL; ?>car_details.php?id=<?= e($car['id']); ?>"
                            >
                                View Details
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="order-empty-box hidden" id="noFilteredCars">
            No cars match your selected type or search.
        </div>
    <?php endif; ?>
</section>

<script src="<?= BASE_URL; ?>public/js/member_cars.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>