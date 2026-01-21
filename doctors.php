<?php
require_once 'functions.php';

// Ambil data filter dari URL (GET)
$search = $_GET['search'] ?? '';
$location = $_GET['location'] ?? '';
$specialization = $_GET['specialization'] ?? '';

// Query Dasar: Mengambil data dokter
$query = "SELECT * FROM doctors WHERE 1=1";
$params = [];

// Logika Filter Pencarian Nama/Spesialis
if (!empty($search)) {
    $query .= " AND (name LIKE ? OR specialization LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Logika Filter Lokasi
if (!empty($location)) {
    $query .= " AND location = ?";
    $params[] = $location;
}

// Logika Filter Spesialisasi
if (!empty($specialization)) {
    $query .= " AND specialization = ?";
    $params[] = $specialization;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Cari Dokter - Mitra Keluarga</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                    },
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

<header class="sticky top-0 z-50 border-b border-gray-200 bg-white/80 dark:bg-[#1a202c]/80 backdrop-blur-md shadow-sm">
    <div class="max-w-[1440px] mx-auto flex items-center justify-between px-4 md:px-10 py-3">

        <div class="flex items-center gap-4">
            <a href="doctors.php" class="flex items-center">
                <img src="img/logo.png" alt="Parahita Medical" class="h-10 w-auto object-contain">
            </a>
        </div>

        <div class="hidden lg:flex flex-1 justify-end gap-8">
            <nav class="flex items-center gap-6">
                <a class="text-sm font-medium hover:text-primary transition-colors" href="dashboard.php">Beranda</a>
                <a class="text-sm font-medium hover:text-primary transition-colors" href="packages.php">Layanan MCU</a>
                <a class="text-primary text-sm font-bold" href="doctors.php">Dokter</a>
            </nav>
        </div>

    </div>
</header>

<main class="flex-1 w-full max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col gap-2 mb-8">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Temukan Dokter Spesialis Kami</h1>
        <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl">Jadwalkan konsultasi dengan dokter terbaik di cabang terdekat Anda.</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 mb-8">
        <form action="doctors.php" method="GET" class="flex flex-col gap-5">
            <div class="w-full relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-primary text-2xl">search</span>
                </div>
                <input name="search" value="<?= htmlspecialchars($search) ?>" class="block w-full pl-12 pr-4 py-4 border border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary text-lg" placeholder="Cari nama dokter atau spesialisasi..." type="text" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <select name="location" class="block w-full pl-3 pr-10 py-3 border border-slate-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none cursor-pointer">
                        <option value="">Semua Lokasi</option>

                        <option value="Klinik & Laboratorium Parahita Deltasari" <?= $location == 'Klinik & Laboratorium Parahita Deltasari' ? 'selected' : '' ?>>
                            Klinik & Laboratorium Parahita Deltasari
                        </option>

                        <option value="Klinik & Laboratorium Parahita Mulyosari" <?= $location == 'Klinik & Laboratorium Parahita Mulyosari' ? 'selected' : '' ?>>
                            Klinik & Laboratorium Parahita Mulyosari
                        </option>

                        <option value="Klinik & Laboratorium Parahita Darmo Permai" <?= $location == 'Klinik & Laboratorium Parahita Darmo Permai' ? 'selected' : '' ?>>
                            Klinik & Laboratorium Parahita Darmo Permai
                        </option>

                        <option value="Klinik & Laboratorium Parahita Dharmawangsa" <?= $location == 'Klinik & Laboratorium Parahita Dharmawangsa' ? 'selected' : '' ?>>
                            Klinik & Laboratorium Parahita Dharmawangsa
                        </option>

                        <option value="Klinik & Laboratorium Parahita Diponegoro" <?= $location == 'Klinik & Laboratorium Parahita Diponegoro' ? 'selected' : '' ?>>
                            Klinik & Laboratorium Parahita Diponegoro
                        </option>

                        <option value="Klinik & Laboratorium Parahita Rungkut" <?= $location == 'Klinik & Laboratorium Parahita Rungkut' ? 'selected' : '' ?>>
                            Klinik & Laboratorium Parahita Rungkut
                        </option>
                    </select>
                </div>

                <div class="relative">
                    <select name="specialization" class="block w-full pl-3 pr-10 py-3 border border-slate-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none cursor-pointer">
                        <option value="">Semua Spesialisasi</option>
                        <option value="Penyakit Dalam" <?= $specialization == 'Penyakit Dalam' ? 'selected' : '' ?>>Penyakit Dalam</option>
                        <option value="Anak" <?= $specialization == 'Anak' ? 'selected' : '' ?>>Anak</option>
                        <option value="Jantung" <?= $specialization == 'Jantung' ? 'selected' : '' ?>>Jantung</option>
                        <option value="Mata" <?= $specialization == 'Mata' ? 'selected' : '' ?>>Mata</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="w-full px-6 py-3 bg-primary hover:bg-blue-600 text-white font-bold rounded-lg shadow-md transition-all">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php if (count($doctors) > 0): ?>
            <?php foreach ($doctors as $doc): ?>
                <div class="group bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden transition-all flex flex-col">
                    <div class="relative h-64 overflow-hidden bg-slate-100">
                        <img alt="<?= htmlspecialchars($doc['name']) ?>" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105" src="<?= htmlspecialchars($doc['image_url'] ?? 'https://via.placeholder.com/300x400?text=Dokter') ?>" />
                        <?php if ($doc['is_available']): ?>
                            <div class="absolute top-3 right-3 bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-md flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Tersedia
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="mb-1 flex items-center gap-1 text-amber-500 text-xs font-bold">
                            <span class="material-symbols-outlined text-[16px]">star</span>
                            <?= $doc['rating'] ?? '5.0' ?> (<?= $doc['reviews_count'] ?? '0' ?> Ulasan)
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-tight mb-1"><?= htmlspecialchars($doc['name']) ?></h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mb-3"><?= htmlspecialchars($doc['specialization']) ?></p>
                        <div class="flex flex-col gap-2 mt-auto mb-4">
                            <div class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-300">
                                <span class="material-symbols-outlined text-[16px] text-slate-400">work_history</span>
                                <?= $doc['experience'] ?> Tahun Pengalaman
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-300">
                                <span class="material-symbols-outlined text-[16px] text-slate-400">location_on</span>
                                <?= htmlspecialchars($doc['location']) ?>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            <a href="doctor_detail.php?id=<?= $doc['id'] ?>" class="block w-full text-center px-4 py-2.5 text-sm font-bold text-white bg-primary rounded-lg hover:bg-blue-600 transition-colors shadow-sm">
                                Lihat Profil
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full py-20 text-center">
                <span class="material-symbols-outlined text-6xl text-slate-300">person_search</span>
                <p class="mt-4 text-slate-500">Maaf, dokter yang Anda cari tidak ditemukan.</p>
                <a href="doctors.php" class="text-primary font-bold hover:underline">Lihat Semua Dokter</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-auto py-8">
    <div class="max-w-[1440px] mx-auto px-4 text-center text-slate-500 text-sm">
        © 2026 Parahita Diagnostic Center. All rights reserved.
    </div>
</footer>
</div>
</body>

</html>