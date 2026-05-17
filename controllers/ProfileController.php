<?php
// controllers/ProfileController.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        global $conn;
        $this->userModel = new User($conn);
    }

    public function showProfile($errors = [])
    {
        requireLogin();

        $user = $this->userModel->findById($_SESSION['user_id']);

        if (!$user) {
            setFlash('error', 'User account not found.');
            redirect('logout.php');
        }

        $success = getFlash('success');
        $pageTitle = 'My Profile';

        require __DIR__ . '/../views/profile/profile.php';
    }

    public function updateProfile()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showProfile();
            return;
        }

        $errors = [];
        $userId = $_SESSION['user_id'];
        $currentUser = $this->userModel->findById($userId);

        if (!$currentUser) {
            setFlash('error', 'User account not found.');
            redirect('logout.php');
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $errors['general'] = 'Invalid request. Please refresh and try again.';
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif ($this->userModel->emailExists($email, $userId)) {
            $errors['email'] = 'This email is already used by another account.';
        }

        if ($address === '') {
            $errors['address'] = 'Address is required.';
        }

        if ($phone === '') {
            $errors['phone'] = 'Phone number is required.';
        }

        $profilePicturePath = null;

        if (
            isset($_FILES['profile_picture']) &&
            $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $profilePicturePath = $this->handleProfilePictureUpload($_FILES['profile_picture'], $errors);
        }

        $wantsPasswordChange = $currentPassword !== '' || $newPassword !== '' || $confirmNewPassword !== '';

        if ($wantsPasswordChange) {
            if ($currentPassword === '') {
                $errors['current_password'] = 'Current password is required.';
            } elseif (!password_verify($currentPassword, $currentUser['password_hash'])) {
                $errors['current_password'] = 'Current password is incorrect.';
            }

            if ($newPassword === '') {
                $errors['new_password'] = 'New password is required.';
            } elseif (strlen($newPassword) < 8) {
                $errors['new_password'] = 'New password must be at least 8 characters.';
            }

            if ($confirmNewPassword === '') {
                $errors['confirm_new_password'] = 'Please confirm your new password.';
            } elseif ($newPassword !== $confirmNewPassword) {
                $errors['confirm_new_password'] = 'New passwords do not match.';
            }
        }

        if (!empty($errors)) {
            $this->showProfile($errors);
            return;
        }

        $updated = $this->userModel->updateProfile(
            $userId,
            $name,
            $email,
            $address,
            $phone,
            $profilePicturePath
        );

        if ($wantsPasswordChange) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->userModel->updatePassword($userId, $newPasswordHash);
        }

        if ($updated) {
            $_SESSION['name'] = $name;
            setFlash('success', 'Profile updated successfully.');
            redirect('profile.php');
        }

        $errors['general'] = 'Profile update failed. Please try again.';
        $this->showProfile($errors);
    }

    private function handleProfilePictureUpload($file, &$errors)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['profile_picture'] = 'File upload failed.';
            return null;
        }

        $maxSize = 2 * 1024 * 1024; // 2MB

        if ($file['size'] > $maxSize) {
            $errors['profile_picture'] = 'Profile picture must be less than 2MB.';
            return null;
        }

        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png'
        ];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mimeType, $allowedMimeTypes)) {
            $errors['profile_picture'] = 'Only JPG, JPEG, and PNG images are allowed.';
            return null;
        }

        $extension = $allowedMimeTypes[$mimeType];

        $uploadDir = ROOT_PATH . 'public/uploads/profiles/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
        $destination = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $errors['profile_picture'] = 'Unable to save uploaded image.';
            return null;
        }

        return 'public/uploads/profiles/' . $fileName;
    }
}