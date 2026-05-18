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

$paymentMethodName = $methodNames[$order['payment_method']] ?? $order['payment_method'];
?>

<section class="success-wrapper">
    <div class="success-card">
        <div class="success-icon">✓</div>

        <h1>Payment Successful</h1>

        <p>
            Your rental order has been confirmed. You can now view this order in your rental history.
        </p>

        <div class="success-summary">
            <div>
                <span>Order No.</span>
                <strong>#<?= e($order['id']); ?></strong>
            </div>

            <div>
                <span>Car</span>
                <strong><?= e($order['car_name']); ?></strong>
            </div>

            <div>
                <span>Rental Period</span>
                <strong><?= e($order['start_date']); ?> to <?= e($order['end_date']); ?></strong>
            </div>

            <div>
                <span>Total Paid</span>
                <strong>BDT <?= e(number_format((float) $order['total_cost'], 2)); ?></strong>
            </div>

            <div>
                <span>Payment Method</span>
                <strong><?= e($paymentMethodName); ?></strong>
            </div>

            <div>
                <span>Status</span>
                <strong><?= e(ucfirst($order['status'])); ?></strong>
            </div>
        </div>

        <div class="success-actions">
            <a href="<?= BASE_URL; ?>member_cars.php" class="btn back-btn">Rent Another Car</a>
            <a href="<?= BASE_URL; ?>rental_history.php" class="btn place-order-btn">View Rental History</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>