<?php
require_once 'config.php';
require_once 'functions.php';

requireLogin();

/* helper escape */
if (!function_exists('e')) {
    function e($s)
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}

/* nama user */
$namaUser = $_SESSION['full_name'] ?? 'Pengguna';

$artikel = $pdo->query(
    "SELECT id, title, content, image_url, link 
     FROM artikel 
     ORDER BY created_at DESC
     LIMIT 4"
)->fetchAll(PDO::FETCH_ASSOC);

// Ambil 1 janji temu mendatang yang paling dekat untuk user ini
$stmtJanji = $pdo->prepare("
    SELECT * FROM bookings 
    WHERE user_id = ? 
    AND status = 'confirmed' 
    AND booking_date >= CURDATE()
    ORDER BY booking_date ASC, booking_time ASC
    LIMIT 1
");
$stmtJanji->execute([$_SESSION['user_id']]);
$janjiMendatang = $stmtJanji->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Parahita Diagnostic Center Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1C252E",
                        "text-main-light": "#111418",
                        "text-main-dark": "#ffffff",
                        "text-sub-light": "#617589",
                        "text-sub-dark": "#94a3b8",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>
</head>

<header class="sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800 bg-surface-light dark:bg-surface-dark shadow-sm">
    <div class="max-w-[1440px] mx-auto px-4 lg:px-8 w-full flex items-center justify-between py-3">

        <div class="flex items-center gap-4">
            <a href="dashboard.php" class="flex items-center">
                <img src="img/logo.png" alt="Logo" class="h-10 w-auto object-contain">
            </a>
        </div>

        <div class="flex items-center gap-6 ml-auto">
            <nav class="hidden md:flex items-center gap-6">
                <a class="text-primary text-sm font-bold" href="dashboard.php">Beranda</a>
                <a class="text-sm font-medium hover:text-primary transition-colors" href="packages.php">Layanan MCU</a>
                <a class="text-sm font-medium hover:text-primary transition-colors" href="doctors.php">Dokter</a>
            </nav>

            <div class="flex items-center gap-3">
                <button class="relative flex items-center justify-center size-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                    <span class="material-symbols-outlined text-text-sub-light">notifications</span>
                    <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full"></span>
                </button>

                <div class="relative ml-2">
                    <button id="profile-menu-button" class="flex items-center gap-1 focus:outline-none group">
                        <img src="img/profile.jpeg" class="h-9 w-9 rounded-full object-cover border-2 border-transparent group-hover:border-primary transition-all">
                        <span class="material-symbols-outlined text-text-sub-light group-hover:text-primary text-lg">expand_more</span>
                    </button>

                    <div id="profile-menu" class="hidden absolute right-0 mt-3 w-48 rounded-xl bg-surface-light dark:bg-surface-dark border border-gray-100 dark:border-gray-800 shadow-xl z-[100]">
                        <div class="p-2 space-y-1">
                            <div class="px-4 py-2 border-b border-gray-50 dark:border-gray-800 mb-1">
                                <p class="text-[10px] uppercase font-bold text-text-sub-light">Akun</p>
                                <p class="text-sm font-bold truncate"><?= e($namaUser) ?></p>
                            </div>
                            <a href="pengaturan.php" class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                <span class="material-symbols-outlined text-lg">settings</span> Pengaturan
                            </a>
                            <hr class="border-gray-100 dark:border-gray-800 my-1">
                            <a href="logout.php" class="flex items-center gap-3 px-4 py-2 text-sm text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                                <span class="material-symbols-outlined text-lg">logout</span> Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="flex-1 w-full max-w-[1440px] mx-auto px-4 lg:px-8 py-8 flex flex-col gap-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 rounded-xl bg-gradient-to-r from-primary/10 to-transparent p-6 lg:p-8 border border-primary/10">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl md:text-3xl font-black tracking-tight">Selamat Datang, <?= e($namaUser) ?></h1>
            <p class="text-text-sub-light dark:text-text-sub-dark">Pantau kesehatan Anda dengan mudah hari ini.</p>
        </div>
        <button class="flex items-center justify-center gap-2 h-12 px-6 bg-primary hover:bg-blue-600 text-white font-bold rounded-lg transition-all shadow-md active:scale-95">
            <span class="material-symbols-outlined text-xl">calendar_add_on</span>
            <span>Buat Janji Baru</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 flex flex-col gap-8">
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Janji Temu Mendatang</h2>
                    <a class="text-sm font-semibold text-primary hover:underline" href="riwayat.php">Lihat Semua</a>
                </div>

                <?php if ($janjiMendatang): ?>
                    <div class="flex flex-col sm:flex-row items-stretch gap-6 rounded-xl bg-surface-light dark:bg-surface-dark p-5 shadow-sm border border-gray-100 dark:border-gray-800">
                        <div class="w-full sm:w-32 h-32 shrink-0 rounded-lg overflow-hidden relative group bg-gray-100">
                            <img src="img/doctor-placeholder.jpg" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 left-0 right-0 bg-green-500 text-white text-[10px] font-bold py-1 text-center uppercase">
                                <?= e($janjiMendatang['status']) ?>
                            </div>
                        </div>

                        <div class="flex flex-col flex-1 justify-between gap-4">
                            <div>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-bold"><?= e($janjiMendatang['item_name'] ?? 'Layanan Medis') ?></h3>
                                        <p class="text-primary font-medium text-sm"><?= e($janjiMendatang['category'] ?? 'General') ?></p>
                                    </div>
                                    <div class="flex items-center gap-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 px-2 py-1 rounded text-xs font-semibold">
                                        <span class="material-symbols-outlined text-sm">location_on</span> In-Clinic
                                    </div>
                                </div>
                                <div class="mt-3 space-y-2 text-text-sub-light text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-base">calendar_today</span>
                                        <?= date('d M Y', strtotime($janjiMendatang['booking_date'])) ?>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-base">schedule</span>
                                        <?= date('H:i', strtotime($janjiMendatang['booking_time'])) ?> WIB
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-3 pt-2 border-t border-gray-100 dark:border-gray-800">
                                <button class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 dark:bg-gray-800 text-sm font-semibold rounded-lg">Detail</button>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-10 rounded-xl bg-gray-50 dark:bg-gray-800/30 border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">event_busy</span>
                        <p class="text-gray-500 text-sm italic">Anda tidak memiliki janji temu aktif.</p>
                        <a href="packages.php" class="mt-3 text-primary text-sm font-bold hover:underline">Booking Sekarang →</a>
                    </div>
                <?php endif; ?>
            </section>

            <section>
                <h2 class="text-xl font-bold mb-4">Akses Cepat</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php
                    $menus = [
                        ['label' => 'Booking MCU', 'img' => 'booking_mcu.png', 'url' => 'packages.php'],
                        ['label' => 'Konsultasi', 'img' => 'konsultasi_dokter.png', 'url' => 'doctors.php'],
                        ['label' => 'Interpretasi', 'img' => 'interpretasi_hasil.png', 'url' => 'interpretasi.php'],
                        ['label' => 'Riwayat MCU', 'img' => 'Riwayat.png', 'url' => 'riwayat.php']
                    ];
                    foreach ($menus as $m): ?>
                        <button onclick="location.href='<?= $m['url'] ?>'" class="group flex flex-col items-center justify-center gap-3 p-4 h-32 rounded-xl bg-surface-light dark:bg-surface-dark border border-gray-100 dark:border-gray-800 hover:border-primary transition-all">
                            <div class="w-14 h-14 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <img src="img/<?= $m['img'] ?>" alt="<?= $m['label'] ?>" class="w-8 h-8 object-contain">
                            </div>
                            <span class="font-semibold text-sm"><?= $m['label'] ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <div class="lg:col-span-1">
            <section class="h-full flex flex-col">
                <h2 class="text-xl font-bold mb-4">Hasil MCU Terbaru</h2>
                <div class="flex-1 rounded-xl bg-surface-light dark:bg-surface-dark p-6 shadow-sm border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="size-12 rounded-full bg-blue-50 dark:bg-blue-900/20 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined">health_and_safety</span>
                        </div>
                        <div>
                            <h3 class="font-bold leading-tight">Paket Silver Check-up</h3>
                            <p class="text-xs text-text-sub-light">10 Sep 2023</p>
                        </div>
                    </div>
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <span class="text-sm font-medium">Gula Darah</span>
                            <span class="px-2 py-1 rounded text-[10px] font-bold bg-green-100 text-green-700">Normal</span>
                        </div>
                        <div class="flex justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                            <span class="text-sm font-medium">Kolesterol</span>
                            <span class="px-2 py-1 rounded text-[10px] font-bold bg-yellow-100 text-yellow-700">Perhatian</span>
                        </div>
                    </div>
                    <button class="w-full py-2.5 bg-primary text-white font-semibold rounded-lg mb-3">Lihat Laporan</button>
                    <button class="w-full py-2.5 border border-gray-200 font-semibold rounded-lg">Unduh PDF</button>
                </div>
            </section>
        </div>
    </div>

    <section class="mt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (!empty($artikel)): foreach ($artikel as $row):
                    $path_gambar = !empty($row['image_url']) ? $row['image_url'] : 'img/default-article.jpg';
            ?>
                    <article class="group cursor-pointer flex flex-col rounded-xl overflow-hidden bg-surface-light dark:bg-surface-dark border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-lg transition-all" onclick="window.open('<?= e($row['link']) ?>','_blank')">
                        <div class="aspect-video overflow-hidden bg-gray-200">
                            <img src="<?= e($path_gambar) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-bold text-sm mb-2 line-clamp-2"><?= e($row['title']) ?></h3>
                            <p class="text-xs text-text-sub-light line-clamp-2 mt-auto">
                                <?= e(substr(strip_tags($row['content']), 0, 80)) ?>...
                            </p>
                        </div>
                    </article>
                <?php endforeach;
            else: ?>
                <p class="col-span-full text-center text-gray-500 italic">Belum ada artikel.</p>
            <?php endif; ?>
        </div>
    </section>
</main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('profile-menu-button');
        const menu = document.getElementById('profile-menu');

        if (btn && menu) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        }
    });
</script>
<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-auto py-8">
    <div class="max-w-[1440px] mx-auto px-4 text-center text-slate-500 text-sm">
        © 2026 Parahita Diagnostic Center. All rights reserved.
    </div>
    </body>

    </html>