<?php
// functions.php
require_once __DIR__ . '/config.php';

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function currentUser(): ?array
{
    if (!isLoggedIn()) return null;
    return [
        'id'        => $_SESSION['user_id'],
        'full_name' => $_SESSION['full_name'],
        'email'     => $_SESSION['email'],
    ];
}

function generateBookingCode(): string
{
    return 'MCU-' . date('YmdHis') . '-' . rand(100, 999);
}
