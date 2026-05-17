<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="profile-wrapper">
    <div class="profile-card">
        <h1>My Profile</h1>
        <p class="muted">Update your personal information</p>

        <?php if (!empty($success)): ?>
            <div class="alert success-alert"><?= e($success); ?></div>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert error-alert"><?= e($errors['general']); ?></div>
        <?php endif; ?>

        <div class="profile-image-box">
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="<?= BASE_URL . e($user['profile_picture']); ?>" alt="Profile Picture">
            <?php else: ?>
                <div class="profile-placeholder">No Image</div>
            <?php endif; ?>
        </div>

        <form id="profileForm" action="<?= BASE_URL; ?>profile.php" method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrfToken(); ?>">

            <div class="form-grid">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="profileName" value="<?= e($user['name']); ?>">
                    <small class="error-text" id="profileNameError"><?= e($errors['name'] ?? ''); ?></small>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="profileEmail" value="<?= e($user['email']); ?>">
                    <small class="error-text" id="profileEmailError"><?= e($errors['email'] ?? ''); ?></small>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" id="profilePhone" value="<?= e($user['phone']); ?>">
                    <small class="error-text" id="profilePhoneError"><?= e($errors['phone'] ?? ''); ?></small>
                </div>

                <div class="form-group">
                    <label>Profile Picture</label>
                    <input type="file" name="profile_picture" id="profilePicture" accept="image/jpeg,image/png">
                    <small class="error-text" id="profilePictureError"><?= e($errors['profile_picture'] ?? ''); ?></small>
                </div>
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address" id="profileAddress"><?= e($user['address']); ?></textarea>
                <small class="error-text" id="profileAddressError"><?= e($errors['address'] ?? ''); ?></small>
            </div>

            <hr>

            <h2>Change Password</h2>
            <p class="muted">Leave password fields blank if you do not want to change password.</p>

            <div class="form-grid">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" id="currentPassword">
                    <small class="error-text" id="currentPasswordError"><?= e($errors['current_password'] ?? ''); ?></small>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" id="newPassword">
                    <small class="error-text" id="newPasswordError"><?= e($errors['new_password'] ?? ''); ?></small>
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_new_password" id="confirmNewPassword">
                    <small class="error-text" id="confirmNewPasswordError"><?= e($errors['confirm_new_password'] ?? ''); ?></small>
                </div>
            </div>

            <button type="submit" class="btn primary-btn">Update Profile</button>
        </form>
    </div>
</section>

<script src="<?= BASE_URL; ?>public/js/profile_validation.js"></script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>