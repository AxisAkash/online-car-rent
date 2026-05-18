<?php require __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL; ?>public/css/order.css">

<?php
$start = new DateTime($order['start_date']);
$end = new DateTime($order['end_date']);
$totalDays = $start->diff($end)->days;
?>

<section class="order-page-hero invoice-hero">
    <div>
        <span class="order-badge">Invoice</span>
        <h1>Review Your Rental Invoice</h1>
        <p>
            Check your selected car, rental period, and total cost before you cancel or finalize the order.
        </p>
    </div>

    <div class="order-hero-card">
        <h3>Current Status</h3>
        <p><?= e(ucfirst($order['status'])); ?></p>
    </div>
</section>

<section class="invoice-wrapper">
    <div class="invoice-card">
        <div class="invoice-header">
            <div>
                <span>Invoice No.</span>
                <h2>#<?= e($order['id']); ?></h2>
            </div>

            <div class="invoice-status <?= e($order['status']); ?>">
                <?= e(ucfirst($order['status'])); ?>
            </div>
        </div>

        <div class="invoice-car-box">
            <div class="invoice-car-image">
                <?php if (!empty($order['image_path'])): ?>
                    <img src="<?= BASE_URL . e($order['image_path']); ?>" alt="<?= e($order['car_name']); ?>">
                <?php else: ?>
                    <div class="invoice-placeholder">Car Image</div>
                <?php endif; ?>
            </div>

            <div>
                <h3><?= e($order['car_name']); ?></h3>
                <p><?= e($order['description'] ?? 'No description available.'); ?></p>

                <div class="invoice-mini-grid">
                    <div>
                        <span>Model</span>
                        <strong><?= e($order['car_model']); ?></strong>
                    </div>

                    <div>
                        <span>Type</span>
                        <strong><?= e($order['car_type']); ?></strong>
                    </div>

                    <div>
                        <span>Price Per Day</span>
                        <strong>BDT <?= e(number_format((float) $order['price_per_day'], 2)); ?></strong>
                    </div>

                    <div>
                        <span>Rental Days</span>
                        <strong><?= e($totalDays); ?> day(s)</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-summary">
            <div>
                <span>Start Date</span>
                <strong><?= e($order['start_date']); ?></strong>
            </div>

            <div>
                <span>End Date</span>
                <strong><?= e($order['end_date']); ?></strong>
            </div>

            <div>
                <span>Order Date</span>
                <strong><?= e($order['order_date']); ?></strong>
            </div>

            <div>
                <span>Total Cost</span>
                <strong>BDT <?= e(number_format((float) $order['total_cost'], 2)); ?></strong>
            </div>
        </div>

        <?php if ($order['status'] === 'pending'): ?>
            <div class="invoice-actions">
                <form method="POST" action="<?= BASE_URL; ?>cancel_order.php">
                    <input type="hidden" name="order_id" value="<?= e($order['id']); ?>">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']); ?>">
                    <button type="submit" class="btn cancel-order-btn">
                        Cancel Order
                    </button>
                </form>

                <a
                    class="btn finalize-order-btn"
                    href="<?= BASE_URL; ?>payment.php?order_id=<?= e($order['id']); ?>"
                >
                    Finalize & Continue to Payment
                </a>
            </div>
        <?php else: ?>
            <div class="order-empty-box">
                This order is already <?= e($order['status']); ?>.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>