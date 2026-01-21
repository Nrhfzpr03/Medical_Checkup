<?php
// booking_process.php
require_once 'functions.php';
requireLogin();

// 1. Pastikan data dikirim lewat POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: packages.php');
    exit;
}

// 2. Tangkap data dari form pack_detail.php
$package_id       = (int)($_POST['package_id'] ?? 0);
$location         = $_POST['location'] ?? '';
$appointment_date = $_POST['appointment_date'] ?? '';
$appointment_time = $_POST['appointment_time'] ?? '';
$user_id          = $_SESSION['user_id'] ?? 0;

// 3. Validasi: Jangan sampai ada data kosong
if (!$package_id || empty($location) || empty($appointment_date) || empty($appointment_time)) {
    die('Data jadwal tidak lengkap. <a href="javascript:history.back()">Kembali dan lengkapi data</a>');
}

try {
    // 4. Ambil harga paket terbaru dari database (untuk keamanan agar tidak dimanipulasi)
    $stmt = $pdo->prepare("SELECT price FROM packages WHERE id = ?");
    $stmt->execute([$package_id]);
    $pkg  = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pkg) {
        die('Paket tidak ditemukan.');
    }

    // 5. Masukkan data ke tabel bookings
    // Pastikan tabel kamu punya kolom: user_id, package_id, location, booking_date, booking_time, status, created_at
    $stmt = $pdo->prepare("
        INSERT INTO bookings (user_id, package_id, location, booking_date, booking_time, status, created_at)
        VALUES (?, ?, ?, ?, ?, 'pending', NOW())
    ");

    $stmt->execute([
        $user_id,
        $package_id,
        $location,
        $appointment_date,
        $appointment_time
    ]);

    // 6. Ambil ID booking yang baru saja dibuat
    $booking_id = $pdo->lastInsertId();

    // 7. Simpan ID ke session agar bisa dipanggil di payment.php
    $_SESSION['current_booking_id'] = $booking_id;

    // 8. Lanjut ke halaman pembayaran
    header('Location: payment.php');
    exit;
} catch (PDOException $e) {
    // Jika ada error database (misal tabel belum dibuat)
    die("Error: " . $e->getMessage());
}
