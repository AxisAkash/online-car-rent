<?php require __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL; ?>public/css/order.css">

<section class="order-page-hero payment-hero">
    <div>
        <span class="order-badge">Payment</span>
        <h1>Select Payment Method</h1>
        <p>
            Choose your preferred payment method and confirm the rental order.
        </p>
    </div>

    <div class="order-hero-card">
        <h3>Amount Due</h3>
        <p>BDT <?= e(number_format((float) $order['total_cost'], 2)); ?></p>
    </div>
</section>

<section class="payment-wrapper">
    <div class="payment-summary-card">
        <h2>Order Summary</h2>

        <div class="payment-car-row">
            <div class="payment-car-image">
                <?php if (!empty($order['image_path'])): ?>
                    <img src="<?= BASE_URL . e($order['image_path']); ?>" alt="<?= e($order['car_name']); ?>">
                <?php else: ?>
                    <div class="payment-placeholder">Car Image</div>
                <?php endif; ?>
            </div>

            <div>
                <h3><?= e($order['car_name']); ?></h3>
                <p><?= e($order['car_model']); ?> | <?= e($order['car_type']); ?></p>
            </div>
        </div>

        <div class="payment-info-list">
            <div>
                <span>Start Date</span>
                <strong><?= e($order['start_date']); ?></strong>
            </div>

            <div>
                <span>End Date</span>
                <strong><?= e($order['end_date']); ?></strong>
            </div>

            <div>
                <span>Total Cost</span>
                <strong>BDT <?= e(number_format((float) $order['total_cost'], 2)); ?></strong>
            </div>

            <div>
                <span>Status</span>
                <strong><?= e(ucfirst($order['status'])); ?></strong>
            </div>
        </div>
    </div>

    <div class="payment-form-card">
        <h2>Payment Details</h2>

        <?php if (!empty($_SESSION['payment_error'])): ?>
            <div class="order-alert-error">
                <?= e($_SESSION['payment_error']); ?>
            </div>
            <?php unset($_SESSION['payment_error']); ?>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL; ?>confirm_payment.php" id="paymentForm">
            <input type="hidden" name="order_id" value="<?= e($order['id']); ?>">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']); ?>">

            <div class="form-group payment-method-group">
                <label for="paymentMethod">Payment Method</label>
                <select id="paymentMethod" name="payment_method">
                    <option value="">Select payment method</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="bkash">bKash</option>
                    <option value="nagad">Nagad</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                </select>
            </div>

            <div class="form-group">
                <label for="transactionId" id="transactionLabel">Transaction ID</label>
                <input
                    type="text"
                    id="transactionId"
                    name="transaction_id"
                    placeholder="Enter transaction ID"
                >
            </div>

            <p class="payment-help-text" id="paymentHelpText">
                For bKash, Nagad, card, or bank transfer, enter a demo transaction ID.
            </p>

            <p class="form-error" id="paymentFormError"></p>

            <div class="details-action-row">
                <a href="<?= BASE_URL; ?>invoice.php?order_id=<?= e($order['id']); ?>" class="btn back-btn">
                    Back to Invoice
                </a>

                <button type="submit" class="btn place-order-btn">
                    Confirm Payment
                </button>
            </div>
        </form>
    </div>
</section>

<script src="<?= BASE_URL; ?>public/js/payment.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>