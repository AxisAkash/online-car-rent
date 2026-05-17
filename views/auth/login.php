<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="auth-wrapper">
    <div class="auth-card">
        <h1>Login</h1>
        <p class="muted">Access your Online Car Rent account</p>

        <?php if ($success = getFlash('success')): ?>
            <div class="alert success-alert"><?= e($success); ?></div>
        <?php endif; ?>

        <?php if ($error = getFlash('error')): ?>
            <div class="alert error-alert"><?= e($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert error-alert"><?= e($errors['general']); ?></div>
        <?php endif; ?>

        <form id="loginForm" action="<?= BASE_URL; ?>login.php" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrfToken(); ?>">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="loginEmail" value="<?= e($old['email'] ?? ''); ?>">
                <small class="error-text" id="loginEmailClientError"><?= e($errors['email'] ?? ''); ?></small>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="loginPassword">
                <small class="error-text" id="loginPasswordClientError"><?= e($errors['password'] ?? ''); ?></small>
            </div>

            <div class="checkbox-group">
                <label>
                    <input type="checkbox" name="remember_me">
                    Remember Me
                </label>
            </div>

            <button type="submit" class="btn primary-btn">Login</button>

            <p class="form-bottom-text">
                Do not have an account?
                <a href="<?= BASE_URL; ?>register.php">Register here</a>
            </p>
        </form>
    </div>
</section>

<script src="<?= BASE_URL; ?>public/js/auth_validation.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>