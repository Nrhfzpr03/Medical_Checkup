<?php
require_once 'functions.php';
requireLogin();

// Ambil filter dari URL
$category_filter = isset($_GET['cat']) ? $_GET['cat'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// --- LOGIKA QUERY DINAMIS ---
$sql = "SELECT * FROM packages WHERE 1=1";
$params = [];

if ($category_filter) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
}

if ($search_query) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

$sql .= " ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Parahita - Daftar Paket Medical Check-up</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#00468b",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.375rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 24px;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<header class="sticky top-0 z-50 border-b border-gray-200 bg-white/80 dark:bg-[#1a202c]/80 backdrop-blur-md shadow-sm">
    <div class="max-w-[1440px] mx-auto px-4 md:px-10 w-full flex items-center justify-between py-3">

        <div class="flex items-center gap-4">
            <a href="packages.php" class="flex items-center">
                <img src="img/logo.png" alt="Parahita Medical" class="h-10 w-auto object-contain">
            </a>
        </div>

        <div class="hidden lg:flex flex-1 justify-end gap-8">
            <nav class="flex items-center gap-6">
                <a class="text-sm font-medium hover:text-primary transition-colors" href="dashboard.php">Beranda</a>
                <a class="text-primary text-sm font-bold" href="packages.php">Layanan MCU</a>
                <a class="text-sm font-medium hover:text-primary transition-colors" href="doctors.php">Dokter</a>
            </nav>
        </div>

    </div>
</header>

<main class="flex-1 flex flex-col items-center">
    <section class="w-full max-w-[1440px] px-4 md:px-10 py-6">
        <div class="rounded-xl overflow-hidden relative">
            <div class="flex min-h-[350px] flex-col gap-6 bg-cover bg-center items-start justify-center px-6 py-10 md:px-16"
                style='background-image: linear-gradient(90deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 100%), url("img/gambar_packages.png");'>
                <div class="flex flex-col gap-4 max-w-2xl text-white">
                    <h1 class="text-4xl md:text-5xl font-black leading-tight">Investasi Terbaik Adalah Kesehatan Anda</h1>
                    <p class="text-gray-200 text-base md:text-lg">Pilih paket Medical Check-Up yang sesuai untuk deteksi dini dan pencegahan penyakit.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full max-w-[1440px] px-4 md:px-10 py-4">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">

            <form action="packages.php" method="GET" class="w-full md:w-1/3 relative group">
                <?php if ($category_filter): ?>
                    <input type="hidden" name="cat" value="<?= htmlspecialchars($category_filter) ?>">
                <?php endif; ?>
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary">search</span>
                <input
                    type="text"
                    name="search"
                    placeholder="Cari paket (contoh: Jantung, Blue)"
                    value="<?= htmlspecialchars($search_query) ?>"
                    class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all">
            </form>

            <div class="flex gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 hide-scrollbar justify-start md:justify-end">
                <a href="packages.php<?= $search_query ? '?search=' . urlencode($search_query) : '' ?>"
                    class="px-5 py-2 rounded-full text-sm font-bold transition-all <?= empty($category_filter) ? 'bg-primary text-white shadow-md' : 'bg-white border text-gray-600' ?>">
                    Semua
                </a>
                <?php
                $cats = ['General MCU', 'Pranikah', 'Lifestyle', 'Skrining Lainnya'];
                foreach ($cats as $cat):
                    $url = "packages.php?cat=" . urlencode($cat);
                    if ($search_query) $url .= "&search=" . urlencode($search_query);
                ?>
                    <a href="<?= $url ?>"
                        class="px-5 py-2 rounded-full text-sm font-bold transition-all whitespace-nowrap <?= $category_filter == $cat ? 'bg-primary text-white shadow-md' : 'bg-white border text-gray-600' ?>">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="w-full max-w-[1440px] px-4 md:px-10 py-6 pb-20">
        <div class="flex items-center justify-between mb-8 px-2">
            <h3 class="text-2xl font-bold">
                <?php
                if ($search_query) echo "Hasil pencarian: '" . htmlspecialchars($search_query) . "'";
                else echo $category_filter ? htmlspecialchars($category_filter) : 'Semua Paket MCU';
                ?>
                (<?= count($packages); ?>)
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-2">
            <?php if (empty($packages)): ?>
                <div class="col-span-full text-center py-20 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-300">
                    <span class="material-symbols-outlined text-5xl text-gray-400 mb-4">inventory_2</span>
                    <p class="text-gray-500">Paket yang Anda cari tidak ditemukan.</p>
                    <a href="packages.php" class="text-primary font-bold mt-2 inline-block">Reset Pencarian</a>
                </div>
            <?php else: ?>
                <?php foreach ($packages as $p): ?>
                    <div class="flex flex-col gap-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">medical_services</span>
                                    <h1 class="text-lg font-bold leading-tight"><?= htmlspecialchars($p['name']); ?></h1>
                                </div>
                                <span class="bg-blue-50 dark:bg-blue-900/30 text-primary text-[10px] font-bold px-2 py-1 rounded uppercase">
                                    <?= htmlspecialchars($p['category'] ?? 'MCU'); ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-3 min-h-[60px]">
                                <?= nl2br(htmlspecialchars($p['description'])); ?>
                            </p>
                            <div class="h-px bg-gray-100 dark:bg-gray-700 my-2"></div>
                            <p class="flex items-baseline gap-1 mt-1">
                                <span class="text-3xl font-black text-primary">Rp <?= number_format($p['price'], 0, ',', '.'); ?></span>
                                <span class="text-sm font-medium text-gray-500">/ orang</span>
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 mt-auto">
                            <!-- <a href="booking.php?package_id=<?= $p['id']; ?>"
                                        class="flex w-full items-center justify-center rounded-lg h-11 bg-primary text-white text-sm font-bold shadow-sm hover:bg-blue-800 transition-colors">
                                        Pesan Sekarang
                                    </a> -->
                            <a href="pack_detail.php?package_id=<?= $p['id']; ?>"
                                class="flex w-full items-center justify-center rounded-lg h-11 bg-gray-100 dark:bg-gray-700 text-sm font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-auto py-8">
    <div class="max-w-[1440px] mx-auto px-4 text-center text-slate-500 text-sm">
        © 2026 Parahita Diagnostic Center. All rights reserved.
    </div>
</footer>
</div>
</body>

</html>