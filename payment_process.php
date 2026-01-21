<?php
require_once 'functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = (int)$_POST['booking_id'];
    $method     = $_POST['payment_method'];

    try {
        $pdo->beginTransaction();

        // 1. Ambil total harga dari database
        $stmt = $pdo->prepare("SELECT p.price FROM bookings b JOIN packages p ON p.id = b.package_id WHERE b.id = ?");
        $stmt->execute([$booking_id]);
        $row          = $stmt->fetch();
        $total_amount = $row['price'] + 5000; // Harga + Biaya Layanan

        // 2. Catat ke tabel payments
        $stmt = $pdo->prepare("INSERT INTO payments (booking_id, payment_method, amount, payment_status) VALUES (?, ?, ?, 'success')");
        $stmt->execute([$booking_id, $method, $total_amount]);

        // 3. Update status di tabel bookings
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'paid' WHERE id = ?");
        $stmt->execute([$booking_id]);

        $pdo->commit();
        header('Location: payment_success.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Terjadi kesalahan: " . $e->getMessage());
    }
}
