<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="auth-wrapper">
    <div class="auth-card">
        <h1>Create Account</h1>
        <p class="muted">Register as Admin or Member</p>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert error-alert"><?= e($errors['general']); ?></div>
        <?php endif; ?>

        <form id="registerForm" action="<?= BASE_URL; ?>register.php" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrfToken(); ?>">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="regName" value="<?= e($old['name'] ?? ''); ?>">
                <small class="error-text" id="nameClientError"><?= e($errors['name'] ?? ''); ?></small>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="regEmail" value="<?= e($old['email'] ?? ''); ?>">
                <small class="error-text" id="emailClientError"><?= e($errors['email'] ?? ''); ?></small>
                <small class="info-text" id="emailStatus"></small>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="regPassword">
                <small class="error-text" id="passwordClientError"><?= e($errors['password'] ?? ''); ?></small>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id="regConfirmPassword">
                <small class="error-text" id="confirmPasswordClientError"><?= e($errors['confirm_password'] ?? ''); ?></small>
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address" id="regAddress"><?= e($old['address'] ?? ''); ?></textarea>
                <small class="error-text" id="addressClientError"><?= e($errors['address'] ?? ''); ?></small>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" id="regPhone" value="<?= e($old['phone'] ?? ''); ?>">
                <small class="error-text" id="phoneClientError"><?= e($errors['phone'] ?? ''); ?></small>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" id="regRole">
                    <option value="">Select Role</option>
                    <option value="member" <?= (($old['role'] ?? '') === 'member') ? 'selected' : ''; ?>>Member</option>
                    <option value="admin" <?= (($old['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
                <small class="error-text" id="roleClientError"><?= e($errors['role'] ?? ''); ?></small>
            </div>

            <button type="submit" class="btn primary-btn">Register</button>

            <p class="form-bottom-text">
                Already have an account?
                <a href="<?= BASE_URL; ?>login.php">Login here</a>
            </p>
        </form>
    </div>
</section>

<script src="<?= BASE_URL; ?>public/js/auth_validation.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>