<?php
require_once 'functions.php';
requireLogin();

// Opsional: Ambil detail booking untuk ditampilkan
$booking_id = $_SESSION['current_booking_id'] ?? null;
?>
<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pembayaran Berhasil - HealthCheck+</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        body {
            font-family: "Manrope", sans-serif;
        }
    </style>
</head>

<body class="bg-[#f6f7f8] flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-green-500 py-10 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-4">
                <span class="material-symbols-outlined text-white text-5xl">check_circle</span>
            </div>
            <h1 class="text-white text-2xl font-extrabold tracking-tight">Pembayaran Berhasil!</h1>
        </div>

        <div class="p-8 text-center">
            <p class="text-slate-600 mb-6 leading-relaxed">
                Terima kasih! Transaksi Anda telah kami terima. Jadwal pemeriksaan Anda kini telah dikonfirmasi oleh sistem.
            </p>

            <div class="bg-slate-50 rounded-2xl p-4 mb-8 border border-slate-100">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-slate-500">Status</span>
                    <span class="font-bold text-green-600">LUNAS</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Metode</span>
                    <span class="font-medium text-slate-800 italic">Dikonfirmasi Otomatis</span>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <a href="dashboard.php" class="bg-[#2594e9] text-white font-bold py-3 px-6 rounded-xl hover:bg-blue-600 transition-all shadow-lg shadow-blue-200">
                    Lihat Jadwal Saya
                </a>
                <a href="dashboard.php" class="text-slate-500 text-sm font-medium hover:text-slate-800 transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <div class="bg-slate-50 py-4 border-t border-slate-100 flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-sm text-slate-400">verified</span>
            <span class="text-xs text-slate-400 font-medium uppercase tracking-widest">Official Receipt</span>
        </div>
    </div>

</body>

</html>