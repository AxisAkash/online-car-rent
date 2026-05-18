<?php require __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL; ?>public/css/order.css">

<?php
$methodNames = [
    'credit_card' => 'Credit Card',
    'bkash' => 'bKash',
    'nagad' => 'Nagad',
    'bank_transfer' => 'Bank Transfer',
    'cash_on_delivery' => 'Cash on Delivery'
];
?>

<section class="order-page-hero history-hero">
    <div>
        <span class="order-badge">Rental History</span>
        <h1>Your Rental Orders</h1>
        <p>
            Review your car rental orders, payment method, rental dates, total cost, and current status.
        </p>
    </div>

    <div class="order-hero-card">
        <h3>Total Orders</h3>
        <p><?= e(count($rentalHistory)); ?> order(s)</p>
    </div>
</section>

<section class="history-wrapper">
    <div class="history-top-row">
        <div>
            <h2>Order History</h2>
            <p>Confirmed, cancelled, and pending orders appear here.</p>
        </div>

        <a href="<?= BASE_URL; ?>member_cars.php" class="btn place-order-btn">
            Rent Another Car
        </a>
    </div>

    <?php if (empty($rentalHistory)): ?>
        <div class="history-empty-card">
            <h3>No rental history found</h3>
            <p>You have not placed any rental order yet.</p>
            <a href="<?= BASE_URL; ?>member_cars.php" class="btn place-order-btn">
                Browse Cars
            </a>
        </div>
    <?php else: ?>
        <div class="history-list">
            <?php foreach ($rentalHistory as $history): ?>
                <?php
                    $paymentMethod = $history['payment_method'] ?? '';
                    $paymentName = $methodNames[$paymentMethod] ?? 'Not paid yet';

                    $start = new DateTime($history['start_date']);
                    $end = new DateTime($history['end_date']);
                    $totalDays = $start->diff($end)->days;
                ?>

                <article class="history-card">
                    <div class="history-car-image">
                        <?php if (!empty($history['image_path'])): ?>
                            <img src="<?= BASE_URL . e($history['image_path']); ?>" alt="<?= e($history['car_name']); ?>">
                        <?php else: ?>
                            <div class="history-placeholder">Car Image</div>
                        <?php endif; ?>
                    </div>

                    <div class="history-main">
                        <div class="history-title-row">
                            <div>
                                <h3><?= e($history['car_name']); ?></h3>
                                <p><?= e($history['car_model']); ?> | <?= e($history['car_type']); ?></p>
                            </div>

                            <span class="history-status <?= e($history['status']); ?>">
                                <?= e(ucfirst($history['status'])); ?>
                            </span>
                        </div>

                        <div class="history-grid">
                            <div>
                                <span>Order No.</span>
                                <strong>#<?= e($history['id']); ?></strong>
                            </div>

                            <div>
                                <span>Rental Period</span>
                                <strong><?= e($history['start_date']); ?> to <?= e($history['end_date']); ?></strong>
                            </div>

                            <div>
                                <span>Total Days</span>
                                <strong><?= e($totalDays); ?> day(s)</strong>
                            </div>

                            <div>
                                <span>Total Cost</span>
                                <strong>BDT <?= e(number_format((float) $history['total_cost'], 2)); ?></strong>
                            </div>

                            <div>
                                <span>Payment Method</span>
                                <strong><?= e($paymentName); ?></strong>
                            </div>

                            <div>
                                <span>Transaction ID</span>
                                <strong><?= e($history['transaction_id'] ?? 'N/A'); ?></strong>
                            </div>

                            <div>
                                <span>Order Date</span>
                                <strong><?= e($history['order_date']); ?></strong>
                            </div>

                            <div>
                                <span>Payment Date</span>
                                <strong><?= e($history['payment_date'] ?? 'N/A'); ?></strong>
                            </div>
                        </div>

                        <div class="history-actions">
                            <?php if ($history['status'] === 'pending'): ?>
                                <a href="<?= BASE_URL; ?>invoice.php?order_id=<?= e($history['id']); ?>" class="btn finalize-order-btn">
                                    Continue Order
                                </a>
                            <?php else: ?>
                                <a href="<?= BASE_URL; ?>invoice.php?order_id=<?= e($history['id']); ?>" class="btn back-btn">
                                    View Invoice
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>