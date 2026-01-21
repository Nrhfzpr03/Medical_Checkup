<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $password  = $_POST['password'] ?? '';

    if ($full_name === '' || $email === '' || $password === '') {
        die('Data belum lengkap. <a href="signup.php">Kembali</a>');
    }

    // Cek apakah email sudah dipakai
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header('Location: signup.php?err=email');
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (full_name, email, phone, password_hash)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$full_name, $email, $phone, $hash]);

    header('Location: login.php?registered=1');
    exit;
} else {
    header('Location: signup.php');
    exit;
}
