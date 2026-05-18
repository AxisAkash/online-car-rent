<?php require __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL; ?>public/css/order.css">

<?php
$isMember = isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'member';
$isLoggedIn = isset($_SESSION['user_id']);
$today = date('Y-m-d');
?>

<section class="order-page-hero details-hero">
    <div>
        <span class="order-badge">Car Details</span>
        <h1><?= e($car['name']); ?></h1>
        <p>
            Review the car information, select your rental dates, and calculate the estimated rental cost.
        </p>
    </div>

</section>

<section class="car-details-wrapper">
    <div class="details-image-card">
        <?php if (!empty($car['image_path'])): ?>
            <img src="<?= BASE_URL . e($car['image_path']); ?>" alt="<?= e($car['name']); ?>">
        <?php else: ?>
            <div class="details-image-placeholder">
                Car Image
            </div>
        <?php endif; ?>

        <span class="details-status-pill">
            <?= e($car['availability_status']); ?>
        </span>
    </div>

    <div class="details-info-card">
        <div class="details-title-row">
            <div>
                <h2><?= e($car['name']); ?></h2>
                <p><?= e($car['description'] ?? 'No description available.'); ?></p>
            </div>

            <span class="details-type-pill">
                <?= e($car['type']); ?>
            </span>
        </div>

        <div class="details-info-grid">
            <div class="details-info-item">
                <span>Model</span>
                <strong><?= e($car['model']); ?></strong>
            </div>

            <div class="details-info-item">
                <span>Type</span>
                <strong><?= e($car['type']); ?></strong>
            </div>

            <div class="details-info-item">
                <span>Price Per Day</span>
                <strong>BDT <?= e(number_format((float) $car['price_per_day'], 2)); ?></strong>
            </div>

            <div class="details-info-item">
                <span>Status</span>
                <strong><?= e(ucfirst($car['availability_status'])); ?></strong>
            </div>
        </div>

        <?php if ($isMember): ?>
            <?php if (!empty($_SESSION['order_error'])): ?>
                <div class="order-alert-error">
                    <?= e($_SESSION['order_error']); ?>
                </div>
                <?php unset($_SESSION['order_error']); ?>
            <?php endif; ?>
            
            <form
                class="order-form-card"
                id="orderForm"
                method="POST"
                action="<?= BASE_URL; ?>invoice.php"
                data-price="<?= e($car['price_per_day']); ?>"
            >
                <input type="hidden" name="car_id" value="<?= e($car['id']); ?>">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']); ?>">

                <h3>Place Rental Order</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="startDate">Start Date</label>
                        <input type="date" id="startDate" name="start_date" min="<?= e($today); ?>">
                    </div>

                    <div class="form-group">
                        <label for="endDate">End Date</label>
                        <input type="date" id="endDate" name="end_date" min="<?= e($today); ?>">
                    </div>
                </div>

                <div class="cost-summary-box">
                    <div>
                        <span>Total Rental Days</span>
                        <strong id="totalDays">0 day</strong>
                    </div>

                    <div>
                        <span>Estimated Total Cost</span>
                        <strong id="totalCost">BDT 0.00</strong>
                    </div>
                </div>

                <p class="form-error" id="orderFormError"></p>

                <div class="details-action-row">
                    <a href="<?= BASE_URL; ?>member_cars.php" class="btn back-btn">Back to Cars</a>
                    <button type="submit" class="btn place-order-btn">Place Order</button>
                </div>
            </form>
        <?php elseif ($isLoggedIn): ?>
            <div class="order-notice-card">
                <h3>Member Access Required</h3>
                <p>Admin users can browse cars, but only members can place rental orders.</p>
                <a href="<?= BASE_URL; ?>member_cars.php" class="btn back-btn">Back to Cars</a>
            </div>
        <?php else: ?>
            <div class="order-notice-card">
                <h3>Login Required</h3>
                <p>Please login as a member to place a rental order for this car.</p>
                <div class="details-action-row">
                    <a href="<?= BASE_URL; ?>member_cars.php" class="btn back-btn">Back to Cars</a>
                    <a href="<?= BASE_URL; ?>login.php" class="btn place-order-btn">Login to Order</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script src="<?= BASE_URL; ?>public/js/car_details.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>