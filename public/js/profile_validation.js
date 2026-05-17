// public/js/profile_validation.js

document.addEventListener('DOMContentLoaded', function () {
    const profileForm = document.getElementById('profileForm');

    if (!profileForm) {
        return;
    }

    profileForm.addEventListener('submit', function (event) {
        clearProfileErrors();

        let hasError = false;

        const name = document.getElementById('profileName').value.trim();
        const email = document.getElementById('profileEmail').value.trim();
        const phone = document.getElementById('profilePhone').value.trim();
        const address = document.getElementById('profileAddress').value.trim();

        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmNewPassword = document.getElementById('confirmNewPassword').value;

        const profilePicture = document.getElementById('profilePicture');

        if (name === '') {
            setProfileError('profileNameError', 'Name is required.');
            hasError = true;
        }

        if (email === '') {
            setProfileError('profileEmailError', 'Email is required.');
            hasError = true;
        } else if (!isValidEmail(email)) {
            setProfileError('profileEmailError', 'Enter a valid email address.');
            hasError = true;
        }

        if (phone === '') {
            setProfileError('profilePhoneError', 'Phone number is required.');
            hasError = true;
        }

        if (address === '') {
            setProfileError('profileAddressError', 'Address is required.');
            hasError = true;
        }

        if (profilePicture.files.length > 0) {
            const file = profilePicture.files[0];
            const allowedTypes = ['image/jpeg', 'image/png'];
            const maxSize = 2 * 1024 * 1024;

            if (!allowedTypes.includes(file.type)) {
                setProfileError('profilePictureError', 'Only JPG, JPEG, and PNG images are allowed.');
                hasError = true;
            }

            if (file.size > maxSize) {
                setProfileError('profilePictureError', 'Image must be less than 2MB.');
                hasError = true;
            }
        }

        const wantsPasswordChange = currentPassword !== '' || newPassword !== '' || confirmNewPassword !== '';

        if (wantsPasswordChange) {
            if (currentPassword === '') {
                setProfileError('currentPasswordError', 'Current password is required.');
                hasError = true;
            }

            if (newPassword === '') {
                setProfileError('newPasswordError', 'New password is required.');
                hasError = true;
            } else if (newPassword.length < 8) {
                setProfileError('newPasswordError', 'New password must be at least 8 characters.');
                hasError = true;
            }

            if (confirmNewPassword === '') {
                setProfileError('confirmNewPasswordError', 'Please confirm your new password.');
                hasError = true;
            } else if (newPassword !== confirmNewPassword) {
                setProfileError('confirmNewPasswordError', 'New passwords do not match.');
                hasError = true;
            }
        }

        if (hasError) {
            event.preventDefault();
        }
    });
});

function setProfileError(id, message) {
    const element = document.getElementById(id);

    if (element) {
        element.textContent = message;
    }
}

function clearProfileErrors() {
    const errorIds = [
        'profileNameError',
        'profileEmailError',
        'profilePhoneError',
        'profilePictureError',
        'profileAddressError',
        'currentPasswordError',
        'newPasswordError',
        'confirmNewPasswordError'
    ];

    errorIds.forEach(id => setProfileError(id, ''));
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}