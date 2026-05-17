<?php
// config/app.php

// Start session for the entire application
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Change this if your project folder name is different
define('BASE_URL', 'http://localhost/online-car-rent/');

// Root path of the project
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Escape output to prevent XSS
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Redirect helper
function redirect($path)
{
    header('Location: ' . BASE_URL . ltrim($path, '/'));
    exit();
}

// Check login status
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Get current user role
function userRole()
{
    return $_SESSION['role'] ?? null;
}

// Require login for protected pages
function requireLogin()
{
    if (!isLoggedIn()) {
        setFlash('error', 'Please login first.');
        redirect('login.php');
    }
}

// CSRF token generation
function csrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// CSRF token verification
function verifyCsrfToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Flash message setter
function setFlash($key, $message)
{
    $_SESSION['flash'][$key] = $message;
}

// Flash message getter
function getFlash($key)
{
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }

    return null;
}

// Active navbar class helper
function activeClass($file)
{
    return basename($_SERVER['PHP_SELF']) === $file ? 'active' : '';
}