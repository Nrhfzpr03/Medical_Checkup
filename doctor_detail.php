<?php
require_once 'functions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: doctors.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->execute([$id]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    die("Data dokter tidak ditemukan.");
}

$schedules = [];
if (!empty($doctor['schedule_json'])) {
    $schedules = json_decode($doctor['schedule_json'], true);
}

// Nama lokasi disesuaikan dengan Parahita
$locationName = "Parahita " . ($doctor['location'] ?? 'Pusat');
?>
<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profil <?= htmlspecialchars($doctor['name']) ?> - Klinik Parahita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
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
                }
            }
        }
    </script>
    <style>
        /* Custom adjustment agar timeline tidak terlihat aneh */
        .timeline-line::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 8px;
            bottom: 8px;
            width: 1px;
            background-color: #e2e8f0;
            /* slate-200 */
        }

        .dark .timeline-line::before {
            background-color: #334155;
            /* slate-700 */
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
        <a class="text-[#617589] dark:text-gray-400 text-sm font-medium hover:underline" href="doctors.php">Cari Dokter</a>
        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
        <a class="text-[#111418] dark:text-gray-100 text-sm font-medium"><?= htmlspecialchars($doctor['specialization']) ?></a>
        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
        <a class="text-[#111418] dark:text-gray-100 text-sm font-medium"><?= htmlspecialchars($doctor['name']) ?></a>
        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium">/</span>
        <a class="text-[#617589] dark:text-gray-400 text-sm font-medium hover:underline" href="payment.php">Metode Pembayaran</a>
        <span class="text-[#617589] dark:text-gray-400 text-sm font-medium"></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <aside class="lg:col-span-4 lg:sticky lg:top-24">
            <div class="bg-surface-light dark:bg-surface-dark rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col items-center text-center">
                <div class="size-44 rounded-full overflow-hidden border-4 border-white dark:border-slate-800 shadow-lg mb-5">
                    <img class="w-full h-full object-cover" src="<?= htmlspecialchars($doctor['image_url'] ?? 'img/default-doctor.png') ?>" alt="Foto">
                </div>
                <h2 class="text-2xl font-bold mb-1 tracking-tight"><?= htmlspecialchars($doctor['name']) ?></h2>
                <p class="text-primary font-semibold"><?= htmlspecialchars($doctor['specialization']) ?></p>

                <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 text-primary px-5 py-2 rounded-full font-bold text-sm">
                    Rp <?= number_format($doctor['price'] ?? 250000, 0, ',', '.') ?> / Sesi
                </div>

                <div class="w-full grid grid-cols-2 gap-4 border-t border-slate-100 dark:border-slate-700 mt-8 pt-6">
                    <div class="text-center border-r border-slate-100 dark:border-slate-700">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Pengalaman</p>
                        <p class="text-lg font-bold"><?= $doctor['experience'] ?> Thn</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Rating</p>
                        <p class="text-lg font-bold">⭐ <?= $doctor['rating'] ?></p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="lg:col-span-8 space-y-6">
            <section class="bg-surface-light dark:bg-surface-dark rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">

                <div class="mb-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/30 p-2.5 rounded-xl text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">person_search</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Tentang Dokter</h3>
                    </div>
                    <div class="text-slate-600 dark:text-slate-300 leading-relaxed text-[15px]">
                        <?= !empty($doctor['about']) ? nl2br(htmlspecialchars($doctor['about'])) : "Dokter spesialis berpengalaman di " . $locationName . "." ?>
                    </div>
                </div>

                <div class="mb-12">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">KEAHLIAN MEDIS UTAMA</h4>
                    <div class="flex flex-wrap gap-2.5">
                        <?php
                        if (!empty($doctor['expertise'])) {
                            $skills = preg_split('/\r\n|\r|\n|•/', $doctor['expertise']);
                            foreach ($skills as $skill) {
                                if (trim($skill) != "") {
                                    echo '<span class="px-5 py-2.5 bg-blue-50/50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-full text-sm font-medium text-slate-700 dark:text-slate-300">' . htmlspecialchars(trim($skill)) . '</span>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 my-10"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                    <div>
                        <h3 class="text-[14px] font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-8">RIWAYAT PENDIDIKAN</h3>
                        <div class="relative space-y-10 timeline-line">
                            <?php
                            if (!empty($doctor['education'])) {
                                $eduItems = preg_split('/\r\n|\r|\n|•/', $doctor['education']);
                                foreach ($eduItems as $item): if (trim($item) == "") continue; ?>
                                    <div class="relative pl-10">
                                        <div class="absolute left-0 top-1 size-6 rounded-full border-[2px] border-primary bg-white dark:bg-surface-dark z-10 flex items-center justify-center">
                                            <div class="size-2 rounded-full bg-primary/40"></div>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[15px] text-slate-800 dark:text-white leading-snug"><?= htmlspecialchars(trim($item)) ?></p>
                                            <p class="text-[13px] text-slate-400 mt-1">Institusi Pendidikan Terakreditasi</p>
                                        </div>
                                    </div>
                            <?php endforeach;
                            } ?>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-[14px] font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-8">PENGALAMAN KERJA</h3>
                        <div class="relative space-y-10 timeline-line">
                            <?php
                            if (!empty($doctor['experience_detail'])) {
                                $expItems = preg_split('/\r\n|\r|\n|•/', $doctor['experience_detail']);
                                foreach ($expItems as $item): if (trim($item) == "") continue; ?>
                                    <div class="relative pl-10">
                                        <div class="absolute left-0 top-1 size-6 rounded-full border-[2px] border-primary bg-white dark:bg-surface-dark z-10 flex items-center justify-center">
                                            <div class="size-2 rounded-full bg-primary/40"></div>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[15px] text-slate-800 dark:text-white leading-snug"><?= htmlspecialchars(trim($item)) ?></p>
                                            <p class="text-[13px] text-slate-400 mt-1"><?= $locationName ?></p>
                                        </div>
                                    </div>
                                <?php endforeach;
                            } else { ?>
                                <div class="relative pl-10">
                                    <div class="absolute left-0 top-1 size-6 rounded-full border-[2px] border-primary bg-white dark:bg-surface-dark z-10 flex items-center justify-center">
                                        <div class="size-2 rounded-full bg-primary/40"></div>
                                    </div>
                                    <p class="font-bold text-[15px] text-slate-800 dark:text-white"><?= $doctor['experience'] ?> Tahun Praktik Aktif</p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-surface-light dark:bg-surface-dark rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="text-xl font-bold mb-8 flex items-center gap-3 text-slate-900 dark:text-white">
                    <span class="material-symbols-outlined text-primary">event_available</span>
                    Pilih Jadwal Praktik
                </h3>
                <?php if (!empty($schedules)): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        <?php foreach ($schedules as $hari => $jam): ?>
                            <a href="payment.php?doctor_id=<?= $id ?>&day=<?= urlencode($hari) ?>&time=<?= urlencode($jam) ?>"
                                class="group relative border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:border-primary hover:bg-blue-50/30 transition-all bg-white dark:bg-slate-800 text-left">
                                <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($hari) ?></p>
                                <p class="text-[13px] text-slate-500 mt-1"><?= htmlspecialchars($jam) ?></p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-[10px] bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-md font-bold text-slate-600 dark:text-slate-400 uppercase tracking-tighter">Klinik</span>
                                    <span class="text-primary material-symbols-outlined text-xl transition-transform group-hover:translate-x-1">arrow_forward</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-10">
                        <p class="text-slate-500 italic text-sm">Jadwal belum tersedia untuk dokter ini.</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
    </main>

    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-auto py-8">
        <div class="max-w-[1440px] mx-auto px-4 text-center text-slate-500 text-sm">
            © 2026 Parahita Diagnostic Center. All rights reserved.
        </div>
    </footer>
    </body>

</html>