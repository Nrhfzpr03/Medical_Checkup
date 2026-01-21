<?php
require_once 'functions.php';
requireLogin();

$user = currentUser();

// Mengambil input filter
$location_filter = isset($_GET['location']) ? $_GET['location'] : '';
$month_filter = isset($_GET['month']) ? $_GET['month'] : ''; // Format: YYYY-MM

try {
    // UPDATE: Query sekarang mengambil status 'paid' DAN 'pending'
    $sql = "SELECT b.*, 
                   d.name as doctor_name, 
                   d.specialization, 
                   p.name as package_name, 
                   p.price
            FROM bookings b 
            LEFT JOIN doctors d ON b.doctor_id = d.id 
            LEFT JOIN packages p ON b.package_id = p.id
            WHERE b.user_id = :user_id 
            AND (b.status = 'paid' OR b.status = 'pending')";

    $params = [':user_id' => $user['id']];

    // Filter Lokasi
    if ($location_filter != '') {
        $sql .= " AND b.location = :location";
        $params[':location'] = $location_filter;
    }

    // Filter Bulanan
    if ($month_filter != '') {
        $sql .= " AND DATE_FORMAT(b.booking_date, '%Y-%m') = :month";
        $params[':month'] = $month_filter;
    }

    $sql .= " ORDER BY b.booking_date DESC, b.booking_time DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$paymentSuccess = isset($_GET['paid']) && $_GET['paid'] === 'success';
?>

<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Riwayat Medis | Parahita Diagnostic</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <style>
        /* Menghilangkan panah default browser agar tidak double dengan ikon custom */
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }

        select::-ms-expand {
            display: none !important;
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "parahita-blue": "#0054A6",
                        "parahita-orange": "#F37021",
                        "background-light": "#f6f7f8",
                        "background-dark": "#111a21",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#111418] dark:text-white antialiased">
    <header class="sticky top-0 z-50 bg-white/90 dark:bg-[#1e293b]/90 backdrop-blur-md border-b border-[#f0f2f4] dark:border-gray-700 px-4 md:px-10 py-3 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="riwayat.php" class="flex items-center">
                <img src="img/logo.png" alt="Parahita Medical" class="h-10 w-auto object-contain">
            </a>
            <div class="flex items-center gap-4">
                <a href="dashboard.php" class="text-sm font-bold text-parahita-blue hover:underline">Kembali ke Layanan</a>
            </div>
        </div>
    </header>

    <main class="px-4 sm:px-6 lg:px-10 flex flex-1 justify-center py-10">
        <div class="w-full max-w-6xl flex flex-col gap-8">

            <div class="flex flex-col gap-6 bg-white dark:bg-gray-900 p-8 rounded-[32px] shadow-sm border border-gray-100 dark:border-gray-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="max-w-xl">
                        <h2 class="text-gray-900 dark:text-white text-3xl font-black leading-tight tracking-tight mb-2">Riwayat Aktivitas</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Lihat hasil pemeriksaan dan status pembayaran Anda.</p>
                    </div>
                    <?php if ($month_filter || $location_filter): ?>
                        <a href="riwayat.php" class="text-xs font-bold text-red-500 hover:text-red-600 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">close</span> Reset Filter
                        </a>
                    <?php endif; ?>
                </div>

                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Lokasi Klinik</label>
                        <div class="relative flex items-center">
                            <select name="location" onchange="this.form.submit()"
                                class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-2xl py-3.5 pl-5 pr-12 text-sm focus:ring-2 focus:ring-parahita-blue appearance-none cursor-pointer transition-all">
                                <option value="">Semua Lokasi</option>
                                <?php
                                $clinics = ["Klinik & Laboratorium Parahita Deltasari", "Klinik & Laboratorium Parahita Mulyosari", "Klinik & Laboratorium Parahita Darmo Permai", "Klinik & Laboratorium Parahita Dharmawangsa", "Klinik & Laboratorium Parahita Diponegoro", "Klinik & Laboratorium Parahita Rungkut"];
                                foreach ($clinics as $clinic): ?>
                                    <option value="<?= htmlspecialchars($clinic) ?>" <?= $location_filter == $clinic ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($clinic) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 text-gray-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Periode Bulan</label>
                        <div class="relative">
                            <input type="month" name="month" value="<?= htmlspecialchars($month_filter) ?>" onchange="this.form.submit()"
                                class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-2xl py-3.5 pl-5 pr-5 text-sm focus:ring-2 focus:ring-parahita-blue cursor-pointer transition-all">
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $b): ?>
                        <div class="bg-white dark:bg-gray-900 rounded-[28px] p-6 border border-gray-100 dark:border-gray-800 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:shadow-xl hover:shadow-parahita-blue/5 transition-all duration-300">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center <?= $b['doctor_id'] ? 'bg-blue-50 text-parahita-blue' : 'bg-orange-50 text-parahita-orange' ?>">
                                    <span class="material-symbols-outlined !text-4xl"><?= $b['doctor_id'] ? 'stethoscope' : 'biotech' ?></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">
                                        <?= $b['doctor_id'] ? 'Konsultasi Spesialis' : 'Medical Check-Up' ?>
                                    </span>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-none mb-3">
                                        <?= $b['doctor_id'] ? "Dr. " . htmlspecialchars($b['doctor_name']) : htmlspecialchars($b['package_name']) ?>
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-gray-500 text-xs font-medium">
                                        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-sm text-parahita-blue">calendar_month</span> <?= date('d M Y', strtotime($b['booking_date'])) ?></span>
                                        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-sm text-parahita-blue">schedule</span> <?= date('H:i', strtotime($b['booking_time'])) ?> WIB</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-8 border-t md:border-t-0 pt-5 md:pt-0">
                                <div class="text-right">
                                    <span class="block text-[10px] font-black text-gray-300 uppercase mb-1.5 tracking-tighter">Status</span>

                                    <?php if ($b['status'] == 'paid'): ?>
                                        <span class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold bg-emerald-50 text-emerald-600">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            PAID
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold bg-amber-50 text-amber-600">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                            PENDING
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <a href="booking_detail.php?id=<?= $b['id'] ?>" class="bg-parahita-blue text-white p-4 rounded-2xl hover:bg-blue-800 transition-all">
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-24 bg-white dark:bg-gray-900/50 rounded-[40px] border border-gray-100 dark:border-gray-800">
                        <span class="material-symbols-outlined !text-7xl text-gray-200 mb-4 block">history_toggle_off</span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tidak Ada Aktivitas</h3>
                        <p class="text-gray-400 max-w-xs mx-auto mt-2 text-sm">Belum ada riwayat pemeriksaan atau pemesanan yang tercatat.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>