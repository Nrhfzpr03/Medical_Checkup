<?php
require_once 'functions.php';
requireLogin();

// 1. Ambil ID dari URL (Contoh: pack_detail.php?package_id=5)
$package_id = (int)($_GET['package_id'] ?? 0);

// 2. Query ke Database XAMPP
$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$package_id]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

// 3. Proteksi jika ID tidak ada di database
if (!$package) {
    die('Paket tidak ditemukan. <a href="packages.php">Kembali ke Daftar Paket</a>');
}

// Deskripsi pendek untuk meta/heading (opsional)
$shortDesc = mb_strimwidth($package['description'] ?? '', 0, 180, '...');
?>
<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?= htmlspecialchars($package['name']) ?> - Mitra Keluarga</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
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

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        details>summary {
            list-style: none;
        }

        details>summary::-webkit-details-marker {
            display: none;
        }
    </style>
</head>

<header class="sticky top-0 z-50 bg-white dark:bg-[#1e293b] border-b border-[#f0f2f4] dark:border-gray-700 shadow-sm">
    <div class="max-w-[1440px] mx-auto flex items-center justify-between px-4 md:px-10 py-3">
        <div class="flex items-center gap-8">
            <a href="dashboard.php" class="flex items-center">
                <img src="img/logo.png" alt="Parahita Medical" class="h-10 w-auto object-contain">
            </a>
        </div>

        <div class="flex items-center gap-6">
            <nav class="hidden md:flex items-center gap-6">
                <a class="text-sm font-medium hover:text-primary transition-colors" href="dashboard.php">Beranda</a>
                <a class="text-sm font-medium hover:text-primary transition-colors" href="packages.php">Layanan MCU</a>
                <a class="text-sm font-medium hover:text-primary transition-colors" href="doctors.php">Dokter</a>
            </nav>
        </div>
    </div>
</header>

<div class="layout-container flex flex-col min-h-screen max-w-[1440px] mx-auto px-4 md:px-10 py-6">
    <div class="flex flex-wrap gap-2 py-4 mb-2">
        <a class="text-[#617589] dark:text-gray-400 text-sm font-medium hover:underline" href="dashboard.php">Beranda</a>
        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
        <a class="text-[#617589] dark:text-gray-400 text-sm font-medium hover:underline" href="packages.php">Medical Check Up</a>
        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
        <a class="text-[#111418] dark:text-gray-100 text-sm font-medium"><?= htmlspecialchars($package['name']) ?></a>
        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
        <a class="text-[#617589] dark:text-gray-400 text-sm font-medium hover:underline" href="payment.php">Metode Pembayaran</a>
        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium"></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-8 flex flex-col gap-6">
            <div class="bg-white dark:bg-[#1e293b] rounded-xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-2 mb-1">
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-semibold text-blue-700">
                            <span class="material-symbols-outlined text-[16px]">person</span> Umum
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-1 text-xs font-semibold text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-[16px]">timer</span> 2-3 Jam
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-[#111418] dark:text-white leading-tight">
                        <?= htmlspecialchars($package['name']) ?>
                    </h1>
                    <p class="text-[#617589] dark:text-gray-400 text-base leading-relaxed">
                        <?= nl2br(htmlspecialchars($package['description'])) ?>
                    </p>
                    <div class="mt-4 flex items-end gap-3">
                        <span class="text-3xl font-black text-primary tracking-tight">
                            Rp <?= number_format($package['price'], 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-[#111418] dark:text-white">Rincian Pemeriksaan</h3>
                </div>
                <div class="p-6 md:p-8">
                    <div class="flex flex-col gap-4">
                        <details class="group rounded-lg border border-gray-200 dark:border-gray-700 open:bg-gray-50 dark:open:bg-gray-800/50 transition-colors" open>
                            <summary class="flex cursor-pointer items-center justify-between p-4 font-semibold text-[#111418] dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center size-8 rounded-full bg-blue-100 text-primary">
                                        <span class="material-symbols-outlined text-[20px]">stethoscope</span>
                                    </div>
                                    <span>Deskripsi Layanan</span>
                                </div>
                                <span class="material-symbols-outlined text-gray-400 group-open:rotate-180 transition">expand_more</span>
                            </summary>
                            <div class="px-4 pb-4 pt-2 text-sm text-gray-600 dark:text-gray-300 ml-11">
                                <?= nl2br(htmlspecialchars($package['description'])) ?>
                            </div>
                        </details>
                    </div>

                    <div class="mt-8 flex gap-3 rounded-lg border border-blue-100 bg-blue-50 dark:bg-blue-900/20 p-4">
                        <span class="material-symbols-outlined text-blue-600 mt-0.5">info</span>
                        <div>
                            <h4 class="font-bold text-blue-900 dark:text-blue-300 text-sm">Catatan Penting</h4>
                            <p class="text-sm text-blue-800 dark:text-blue-200 mt-1">Puasa minimal 8-10 jam sebelum pemeriksaan (hanya boleh minum air putih).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 relative h-full">
            <div class="sticky top-24 flex flex-col gap-6">
                <div class="bg-white dark:bg-[#1e293b] rounded-xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">calendar_add_on</span>
                        Atur Jadwal
                    </h2>

                    <form action="booking_process.php" method="POST" class="flex flex-col gap-5">
                        <input type="hidden" name="package_id" value="<?= $package['id'] ?>">

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pilih Lokasi Klinik</label>
                            <select name="location" class="w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 px-4 py-2.5 text-sm">
                                <option value="">Semua Lokasi</option>
                                <option value="Dharmawangsa">Klinik & Laboratorium Parahita Dharmawangsa</option>
                                <option value="Diponegoro">Klinik & Laboratorium Parahita Diponegoro</option>
                                <option value="Deltasari">Klinik & Laboratorium Parahita Deltasari</option>
                                <option value="Mulyosari">Klinik & Laboratorium Parahita Mulyosari</option>
                                <option value="Darmo Permai">Klinik & Laboratorium Parahita Darmo Permai</option>
                                <option value="Rungkut">Klinik & Laboratorium Parahita Rungkut</option>
                            </select>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Kedatangan</label>
                            <input type="date" name="appointment_date" required class="w-full rounded-lg border border-gray-300 dark:bg-gray-800 dark:border-gray-600 px-4 py-2.5 text-sm" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Jam Kedatangan</label>
                            <input type="time" name="appointment_time" required class="w-full rounded-lg border border-gray-300 dark:bg-gray-800 dark:border-gray-600 px-4 py-2.5 text-sm" />
                        </div>

                        <hr class="border-gray-200 dark:border-gray-700 my-2" />

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Total Biaya</span>
                            <span class="text-xl font-bold text-primary">Rp <?= number_format($package['price'], 0, ',', '.') ?></span>
                        </div>

                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary py-3 text-sm font-bold text-white transition hover:bg-blue-600 shadow-md">
                            Buat Janji Sekarang
                        </button>
                    </form>
                </div>

                <!-- <div class="bg-white dark:bg-[#1e293b] rounded-xl p-4 shadow-sm border border-gray-200 dark:border-gray-700 flex items-start gap-3">
                        <div class="size-10 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-gray-600 dark:text-gray-300">support_agent</span>
                        </div> -->
                <!-- <div>
                            <p class="text-sm font-bold">Butuh Bantuan?</p>
                            <p class="text-xs text-gray-500 mt-1">Hubungi kami di <span class="text-primary">1500-123</span></p>
                        </div> -->
            </div>
        </div>
    </div>
</div>
</div>

<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-auto py-8">
    <div class="max-w-[1440px] mx-auto px-4 text-center text-slate-500 text-sm">
        © 2026 Parahita Diagnostic Center. All rights reserved.
    </div>
</footer>
</body>

</html>