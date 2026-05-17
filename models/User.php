<?php
// models/User.php

require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result) ?: null;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result) ?: null;
    }

    public function emailExists($email, $excludeUserId = null)
    {
        if ($excludeUserId) {
            $sql = "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1";
            $stmt = mysqli_prepare($this->conn, $sql);

            mysqli_stmt_bind_param($stmt, "si", $email, $excludeUserId);
        } else {
            $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
            $stmt = mysqli_prepare($this->conn, $sql);

            mysqli_stmt_bind_param($stmt, "s", $email);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_num_rows($result) > 0;
    }

    public function createUser($name, $email, $passwordHash, $role, $address, $phone)
    {
        $sql = "INSERT INTO users (name, email, password_hash, role, address, phone)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $name,
            $email,
            $passwordHash,
            $role,
            $address,
            $phone
        );

        return mysqli_stmt_execute($stmt);
    }

    public function updateProfile($id, $name, $email, $address, $phone, $profilePicturePath = null)
    {
        if ($profilePicturePath) {
            $sql = "UPDATE users 
                    SET name = ?, email = ?, address = ?, phone = ?, profile_picture = ?
                    WHERE id = ?";

            $stmt = mysqli_prepare($this->conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sssssi",
                $name,
                $email,
                $address,
                $phone,
                $profilePicturePath,
                $id
            );
        } else {
            $sql = "UPDATE users 
                    SET name = ?, email = ?, address = ?, phone = ?
                    WHERE id = ?";

            $stmt = mysqli_prepare($this->conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ssssi",
                $name,
                $email,
                $address,
                $phone,
                $id
            );
        }

        return mysqli_stmt_execute($stmt);
    }

    public function updatePassword($id, $passwordHash)
    {
        $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param($stmt, "si", $passwordHash, $id);

        return mysqli_stmt_execute($stmt);
    }

    public function saveRememberToken($userId, $selector, $hashedValidator, $expiresAt)
    {
        $sql = "INSERT INTO remember_tokens (user_id, selector, hashed_validator, expires_at)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "isss",
            $userId,
            $selector,
            $hashedValidator,
            $expiresAt
        );

        return mysqli_stmt_execute($stmt);
    }

    public function findRememberToken($selector)
    {
        $sql = "SELECT * FROM remember_tokens WHERE selector = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $selector);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result) ?: null;
    }

    public function deleteRememberToken($selector)
    {
        $sql = "DELETE FROM remember_tokens WHERE selector = ?";
        $stmt = mysqli_prepare($this->conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $selector);

        return mysqli_stmt_execute($stmt);
    }

    public function deleteExpiredRememberTokens()
    {
        $sql = "DELETE FROM remember_tokens WHERE expires_at < NOW()";
        return mysqli_query($this->conn, $sql);
    }
}