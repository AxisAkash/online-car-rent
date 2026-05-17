<?php
// controllers/AuthController.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        global $conn;
        $this->userModel = new User($conn);
    }

    public function showRegister($errors = [], $old = [])
    {
        $pageTitle = 'Register';
        require __DIR__ . '/../views/auth/register.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showRegister();
            return;
        }

        $errors = [];

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = trim($_POST['role'] ?? '');

        $old = [
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'role' => $role
        ];

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $errors['general'] = 'Invalid request. Please refresh the page and try again.';
        }

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = 'This email is already registered.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if ($confirmPassword === '') {
            $errors['confirm_password'] = 'Please confirm your password.';
        } elseif ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if ($address === '') {
            $errors['address'] = 'Address is required.';
        }

        if ($phone === '') {
            $errors['phone'] = 'Phone number is required.';
        }

        if (!in_array($role, ['admin', 'member'], true)) {
            $errors['role'] = 'Please select a valid role.';
        }

        if (!empty($errors)) {
            $this->showRegister($errors, $old);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $created = $this->userModel->createUser(
            $name,
            $email,
            $passwordHash,
            $role,
            $address,
            $phone
        );

        if ($created) {
            setFlash('success', 'Registration successful. Please login now.');
            redirect('login.php');
        }

        $errors['general'] = 'Registration failed. Please try again.';
        $this->showRegister($errors, $old);
    }

    public function showLogin($errors = [], $old = [])
    {
        $pageTitle = 'Login';
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }

        $errors = [];

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rememberMe = isset($_POST['remember_me']);

        $old = [
            'email' => $email
        ];

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $errors['general'] = 'Invalid request. Please refresh and try again.';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        }

        if (!empty($errors)) {
            $this->showLogin($errors, $old);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors['general'] = 'Invalid email or password.';
            $this->showLogin($errors, $old);
            return;
        }

        $this->createLoginSession($user);

        if ($rememberMe) {
            $this->createRememberMeToken($user['id']);
        }

        // For standalone Task 1, both admin and member go to home.php.
        // Later, Task 2 teammate can redirect admin to admin/dashboard.php.
        redirect('home.php');
    }

    private function createLoginSession($user)
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
    }

    private function createRememberMeToken($userId)
    {
        $selector = bin2hex(random_bytes(8));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = hash('sha256', $validator);

        $expiresTimestamp = time() + (86400 * 30);
        $expiresAt = date('Y-m-d H:i:s', $expiresTimestamp);

        $this->userModel->saveRememberToken(
            $userId,
            $selector,
            $hashedValidator,
            $expiresAt
        );

        $cookieValue = $selector . ':' . $validator;

        // httponly prevents JavaScript from reading the cookie
        setcookie('remember_me', $cookieValue, $expiresTimestamp, '/', '', false, true);
    }

    public function autoLoginFromCookie()
    {
        if (isLoggedIn() || empty($_COOKIE['remember_me'])) {
            return;
        }

        $parts = explode(':', $_COOKIE['remember_me']);

        if (count($parts) !== 2) {
            return;
        }

        [$selector, $validator] = $parts;

        $token = $this->userModel->findRememberToken($selector);

        if (!$token) {
            return;
        }

        if (strtotime($token['expires_at']) < time()) {
            $this->userModel->deleteRememberToken($selector);
            return;
        }

        $validatorHash = hash('sha256', $validator);

        if (!hash_equals($token['hashed_validator'], $validatorHash)) {
            $this->userModel->deleteRememberToken($selector);
            return;
        }

        $user = $this->userModel->findById($token['user_id']);

        if ($user) {
            $this->createLoginSession($user);
        }
    }

    public function logout()
    {
        if (!empty($_COOKIE['remember_me'])) {
            $parts = explode(':', $_COOKIE['remember_me']);

            if (count($parts) === 2) {
                $selector = $parts[0];
                $this->userModel->deleteRememberToken($selector);
            }

            setcookie('remember_me', '', time() - 3600, '/', '', false, true);
        }

        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        redirect('login.php');
    }
}