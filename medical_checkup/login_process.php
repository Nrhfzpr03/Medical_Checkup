<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        header('Location: login.php?err=1');
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, full_name, email, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Simpan data ke session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email']     = $user['email'];

        // REDIRECT KE DASHBOARD, BUKAN LOGIN LAGI!
        header('Location: dashboard.php');
        exit;
    } else {
        // Kalau gagal baru balik ke login dengan pesan error
        header('Location: login.php?err=1');
        exit;
    }
}
