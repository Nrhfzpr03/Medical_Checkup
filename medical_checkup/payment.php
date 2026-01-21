<?php
require_once 'functions.php';
requireLogin();

// 1. Ambil input
$doctor_id = $_GET['doctor_id'] ?? null;
$day       = $_GET['day'] ?? null;
$time_url  = $_GET['time'] ?? null;
$booking_id = $_SESSION['current_booking_id'] ?? null;

// Variabel default
$serviceFee = 5000;

// 2. Logika pengambilan data (Source dari Dokter atau MCU)
if ($doctor_id) {
    $stmt = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
    $stmt->execute([$doctor_id]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$doctor) die('Dokter tidak ditemukan.');
    $packageName = "Konsultasi: " . $doctor['name'];
    $basePrice   = (int)$doctor['price'];
    $specialization = $doctor['specialization'];
} elseif ($booking_id) {
    $stmt = $pdo->prepare("SELECT b.*, p.name AS package_name, p.price FROM bookings b JOIN packages p ON p.id = b.package_id WHERE b.id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$booking) die('Booking tidak ditemukan.');
    $packageName = $booking['package_name'];
    $basePrice   = (int)$booking['price'];
} else {
    header('Location: index.php');
    exit;
}
$totalPrice = $basePrice + $serviceFee;
?>

<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pembayaran - Parahita Diagnostic</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1e293b"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<header class="bg-surface-light dark:bg-surface-dark border-b border-slate-200 dark:border-slate-700 sticky top-0 z-50">
    <div class="max-w-[1440px] mx-auto px-4 md:px-10 h-[72px] flex items-center justify-between">
        <a class="flex items-center gap-2.5" href="dashboard.php">
            <img src="img/logo.png" alt="Logo" class="h-10 w-auto object-contain">
        </a>
    </div>
</header>

<div class="layout-container flex flex-col min-h-screen max-w-[1440px] mx-auto px-4 md:px-10 py-6">

    <div class="flex flex-wrap gap-2 py-4 mb-2">
        <a class="text-[#617589] dark:text-gray-400 text-sm font-medium hover:underline" href="dashboard.php">Beranda</a>
        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>

        <?php if ($doctor_id): ?>
            <a class="text-[#617589] dark:text-gray-400 text-sm font-medium hover:underline" href="doctors.php">Cari Dokter</a>
            <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
            <a class="text-[#617589] dark:text-gray-400 text-sm font-medium hover:underline" href="doctor_detail.php?id=<?= $doctor_id ?>"><?= htmlspecialchars($specialization) ?></a>
            <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
            <a class="text-[#111418] dark:text-gray-100 text-sm font-medium"><?= htmlspecialchars($doctor['name']) ?></a>
        <?php else: ?>
            <a class="text-[#617589] dark:text-gray-400 text-sm font-medium hover:underline" href="packages.php">Medical Check Up</a>
            <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
            <a class="text-[#111418] dark:text-gray-100 text-sm font-medium"><?= htmlspecialchars($packageName) ?></a>
        <?php endif; ?>

        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
        <span class="text-[#111418] dark:text-gray-100 text-sm font-medium">Metode Pembayaran</span>
    </div>

    <main class="py-4">
        <div class="mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Metode Pembayaran</h1>
            <p class="mt-2 text-base text-slate-500">Pilih metode pembayaran untuk menyelesaikan pesanan.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 flex flex-col gap-4">
                <form id="payment-form" action="payment_process.php" method="post" class="flex flex-col gap-4">
                    <input type="hidden" name="booking_id" value="<?= (int)$booking_id ?>">

                    <details class="group rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-surface-dark overflow-hidden" open>
                        <summary class="flex cursor-pointer list-none items-center justify-between p-6">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="credit_card" class="h-5 w-5 text-primary focus:ring-primary" required checked />
                                <p class="font-bold text-slate-900 dark:text-white">Kartu Kredit/Debit</p>
                            </div>
                            <span class="material-symbols-outlined transition-transform group-open:rotate-180 text-slate-400">expand_more</span>
                        </summary>
                        <div class="px-6 pb-6 border-t border-slate-50 dark:border-slate-800 pt-6">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="col-span-2">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nomor Kartu</p>
                                    <input type="text" class="w-full rounded-2xl border-slate-200 dark:bg-slate-800 dark:border-slate-700 p-3.5 text-sm" placeholder="0000 0000 0000 0000">
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Masa Berlaku</p>
                                    <input type="text" class="w-full rounded-2xl border-slate-200 dark:bg-slate-800 dark:border-slate-700 p-3.5 text-sm" placeholder="MM / YY">
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">CVV</p>
                                    <input type="password" class="w-full rounded-2xl border-slate-200 dark:bg-slate-800 dark:border-slate-700 p-3.5 text-sm" placeholder="***">
                                </div>
                            </div>
                        </div>
                    </details>

                    <details class="group rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-surface-dark overflow-hidden">
                        <summary class="flex cursor-pointer list-none items-center justify-between p-6">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="virtual_account" class="h-5 w-5 text-primary" />
                                <p class="font-bold text-slate-900 dark:text-white">Virtual Account</p>
                            </div>
                            <span class="material-symbols-outlined transition-transform group-open:rotate-180 text-slate-400">expand_more</span>
                        </summary>
                        <div class="px-6 pb-6 text-sm text-slate-500 border-t border-slate-50 dark:border-slate-800 pt-4">
                            Mendukung transfer dari Mandiri, BNI, BRI, atau BCA.
                        </div>
                    </details>

                    <details class="group rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-surface-dark overflow-hidden">
                        <summary class="flex cursor-pointer list-none items-center justify-between p-6">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="ewallet" class="h-5 w-5 text-primary" />
                                <p class="font-bold text-slate-900 dark:text-white">E-Wallet (GoPay, OVO, Dana)</p>
                            </div>
                            <span class="material-symbols-outlined transition-transform group-open:rotate-180 text-slate-400">expand_more</span>
                        </summary>
                        <div class="px-6 pb-6 text-sm text-slate-500 border-t border-slate-50 dark:border-slate-800 pt-4">
                            QRIS otomatis akan dibuat setelah Anda klik bayar.
                        </div>
                    </details>
                </form>
            </div>

            <div class="lg:col-span-4 lg:sticky lg:top-24">
                <div class="bg-white dark:bg-surface-dark rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold mb-4 text-slate-900 dark:text-white">Ringkasan Pesanan</h3>

                    <div class="space-y-3 border-b border-slate-100 dark:border-slate-800 pb-5">
                        <div class="flex justify-between gap-4">
                            <span class="text-[13px] text-slate-500">Layanan</span>
                            <span class="text-[13px] font-bold text-right"><?= htmlspecialchars($packageName) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[13px] text-slate-500">Harga Dasar</span>
                            <span class="text-[13px] font-semibold">Rp <?= number_format($basePrice, 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[13px] text-slate-500">Biaya Admin</span>
                            <span class="text-[13px] font-semibold">Rp <?= number_format($serviceFee, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-4 mb-6">
                        <span class="text-sm font-bold text-slate-900 dark:text-white">Total Bayar</span>
                        <span class="text-xl font-black text-primary">Rp <?= number_format($totalPrice, 0, ',', '.') ?></span>
                    </div>

                    <button type="submit" form="payment-form" class="w-full bg-primary hover:bg-blue-600 text-white text-sm font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                        Bayar Sekarang
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </button>

                    <p class="text-[10px] text-center text-slate-400 mt-4">
                        Dengan menekan tombol, Anda menyetujui <br> Syarat & Ketentuan Parahita.
                    </p>
                </div>
            </div>
        </div>
</div>
</main>

<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-auto py-8">
    <div class="max-w-[1440px] mx-auto px-4 text-center text-slate-500 text-sm">
        © 2026 Parahita Diagnostic Center. All rights reserved.
    </div>
    </body>

</html>