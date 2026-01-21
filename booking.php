<?php
require_once 'functions.php';
requireLogin();

// Ambil package_id dari GET atau dari session
$package_id = (int)($_GET['package_id'] ?? ($_SESSION['booking_package_id'] ?? 0));

if (!$package_id) {
    header('Location: packages.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$package_id]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    die('Paket tidak ditemukan. <a href="packages.php">Kembali</a>');
}

// Simpan package_id di session untuk step berikutnya
$_SESSION['booking_package_id'] = $package_id;
?>
<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pilih Jadwal Pemeriksaan</title>

    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#6FCF97;",
                        "background-light": "#f6f7f8",
                        "background-dark": "#111a21",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
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
            font-family: "Manrope", sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 24px;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">

    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">

            <!-- (opsional) bisa tambahkan topbar di sini -->

            <main class="px-4 sm:px-6 lg:px-8 xl:px-20 2xl:px-40 flex flex-1 justify-center py-10 sm:py-12 lg:py-16">
                <div class="layout-content-container flex flex-col w-full max-w-6xl flex-1 gap-8">

                    <!-- Breadcrumbs -->
                    <div class="flex flex-wrap gap-2 px-4">
                        <a class="text-primary/80 dark:text-primary/70 text-base font-medium leading-normal" href="payment.php">
                            Pilih Layanan
                        </a>
                        <span class="text-primary/80 dark:text-primary/70 text-base font-medium leading-normal">/</span>
                        <span class="text-gray-900 dark:text-white text-base font-bold leading-normal">Pilih Jadwal</span>
                        <span class="text-gray-400 dark:text-gray-600 text-base font-medium leading-normal">/</span>
                        <span class="text-gray-400 dark:text-gray-600 text-base font-medium leading-normal">Konfirmasi</span>
                    </div>

                    <!-- Page Heading -->
                    <div class="flex flex-wrap justify-between gap-4 px-4">
                        <div class="flex min-w-72 flex-col gap-2">
                            <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">
                                Pilih Tanggal &amp; Waktu Pemeriksaan
                            </h1>
                            <p class="text-gray-500 dark:text-gray-400 text-base leading-normal">
                                Paket: <span class="font-semibold"><?= htmlspecialchars($package['name']); ?></span><br>
                                Silakan pilih tanggal yang tersedia pada kalender di bawah ini.
                            </p>
                        </div>
                    </div>

                    <!-- FORM ke payment.php -->
                    <form method="post" action="booking_process.php" id="schedule-form">
                        <input type="hidden" name="package_id" value="<?= (int)$package_id ?>">
                        <input type="hidden" name="appointment_date" id="appointment_date" required>
                        <input type="hidden" name="appointment_time" id="appointment_time" required>
                        <!-- Main Content: Calendar and Time Slots -->
                        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mt-4">

                            <!-- Left Column: Calendar -->
                            <div class="lg:col-span-2 bg-white dark:bg-background-dark/50 p-6 rounded-xl shadow-sm">
                                <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-1 pb-4">
                                    Pilih Tanggal
                                </h2>

                                <div class="flex flex-col gap-0.5">
                                    <div class="flex items-center p-1 justify-between">
                                        <button type="button" class="hover:bg-gray-100 dark:hover:bg-white/10 rounded-full">
                                            <span class="material-symbols-outlined text-gray-800 dark:text-gray-200 flex size-10 items-center justify-center">
                                                chevron_left
                                            </span>
                                        </button>
                                        <p class="text-gray-900 dark:text-white text-base font-bold leading-tight flex-1 text-center">
                                            Juni 2024
                                        </p>
                                        <button type="button" class="hover:bg-gray-100 dark:hover:bg-white/10 rounded-full">
                                            <span class="material-symbols-outlined text-gray-800 dark:text-gray-200 flex size-10 items-center justify-center">
                                                chevron_right
                                            </span>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-7 gap-y-1 mt-2">
                                        <!-- headers -->
                                        <p class="text-gray-500 dark:text-gray-400 text-[13px] font-bold flex h-12 items-center justify-center">S</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-[13px] font-bold flex h-12 items-center justify-center">M</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-[13px] font-bold flex h-12 items-center justify-center">T</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-[13px] font-bold flex h-12 items-center justify-center">W</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-[13px] font-bold flex h-12 items-center justify-center">T</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-[13px] font-bold flex h-12 items-center justify-center">F</p>
                                        <p class="text-gray-500 dark:text-gray-400 text-[13px] font-bold flex h-12 items-center justify-center">S</p>

                                        <!-- contoh tanggal, sebagian disabled -->
                                        <button type="button" class="h-12 w-full text-gray-400 col-start-4 text-sm font-medium" disabled>
                                            <div class="flex size-full items-center justify-center rounded-full">1</div>
                                        </button>
                                        <button type="button" class="h-12 w-full text-gray-400 text-sm font-medium" disabled>
                                            <div class="flex size-full items-center justify-center rounded-full">2</div>
                                        </button>
                                        <button type="button" class="h-12 w-full text-gray-400 text-sm font-medium" disabled>
                                            <div class="flex size-full items-center justify-center rounded-full">3</div>
                                        </button>
                                        <button type="button" class="h-12 w-full text-gray-400 text-sm font-medium" disabled>
                                            <div class="flex size-full items-center justify-center rounded-full">4</div>
                                        </button>

                                        <!-- tanggal aktif -> tambahkan data-date -->
                                        <button type="button"
                                            class="calendar-day h-12 w-full text-gray-800 text-sm font-medium"
                                            data-date="2024-06-05">
                                            <div class="flex size-full items-center justify-center rounded-full hover:bg-primary/10">5</div>
                                        </button>
                                        <button type="button"
                                            class="calendar-day h-12 w-full text-gray-800 text-sm font-medium"
                                            data-date="2024-06-06">
                                            <div class="flex size-full items-center justify-center rounded-full hover:bg-primary/10">6</div>
                                        </button>
                                        <button type="button"
                                            class="calendar-day h-12 w-full text-gray-800 text-sm font-medium"
                                            data-date="2024-06-07">
                                            <div class="flex size-full items-center justify-center rounded-full hover:bg-primary/10">7</div>
                                        </button>

                                        <!-- ... kamu bisa teruskan pola ini sesuai kebutuhan ... -->

                                        <!-- contoh tanggal default terpilih: 2024-06-28 -->
                                        <button type="button"
                                            class="calendar-day h-12 w-full text-white text-sm font-bold"
                                            data-date="2024-06-28">
                                            <div class="flex size-full items-center justify-center rounded-full bg-primary">
                                                28
                                            </div>
                                        </button>
                                        <!-- sisanya bisa disabled seperti di desain -->
                                        <button type="button" class="h-12 w-full text-gray-400 text-sm font-medium" disabled>
                                            <div class="flex size-full items-center justify-center rounded-full">29</div>
                                        </button>
                                        <button type="button" class="h-12 w-full text-gray-400 text-sm font-medium" disabled>
                                            <div class="flex size-full items-center justify-center rounded-full">30</div>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Time Slots -->
                            <div class="lg:col-span-3 flex flex-col">
                                <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">
                                    Slot Waktu Tersedia
                                </h2>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4">
                                    <!-- slot waktu: tambahkan data-time -->
                                    <button type="button"
                                        class="time-slot w-full text-center px-4 py-3 rounded-lg border-2 border-primary bg-primary/20 dark:bg-primary/30 text-primary font-bold">
                                        08:00 - 09:00
                                    </button>
                                    <button type="button"
                                        class="time-slot w-full text-center px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:border-primary hover:bg-primary/10 text-gray-700 dark:text-gray-300 font-semibold">
                                        09:00 - 10:00
                                    </button>
                                    <button type="button"
                                        class="time-slot w-full text-center px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:border-primary hover:bg-primary/10 text-gray-700 dark:text-gray-300 font-semibold">
                                        10:00 - 11:00
                                    </button>
                                    <button type="button"
                                        class="time-slot w-full text-center px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:border-primary hover:bg-primary/10 text-gray-700 dark:text-gray-300 font-semibold">
                                        11:00 - 12:00
                                    </button>
                                    <button type="button"
                                        class="w-full text-center px-4 py-3 rounded-lg border border-gray-200 bg-gray-100 dark:border-gray-800 dark:bg-gray-800/50 text-gray-400 dark:text-gray-600 font-semibold cursor-not-allowed">
                                        13:00 - 14:00
                                    </button>
                                    <button type="button"
                                        class="time-slot w-full text-center px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:border-primary hover:bg-primary/10 text-gray-700 dark:text-gray-300 font-semibold">
                                        14:00 - 15:00
                                    </button>
                                    <button type="button"
                                        class="time-slot w-full text-center px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 hover:border-primary hover:bg-primary/10 text-gray-700 dark:text-gray-300 font-semibold">
                                        15:00 - 16:00
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Summary and Action -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 mt-8 p-4 border-t border-gray-200 dark:border-gray-800">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-primary text-3xl">calendar_month</span>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Jadwal Terpilih</p>
                                    <p class="text-gray-900 dark:text-white font-bold text-lg" id="selected_display">
                                        Belum ada jadwal dipilih
                                    </p>
                                </div>
                            </div>
                            <button
                                type="submit"
                                id="btn-confirm"
                                class="w-full sm:w-auto bg-primary hover:bg-primary/90 text-white font-bold py-3 px-10 rounded-lg shadow-md transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                                Konfirmasi Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        // --- pilih tanggal ---
        const dateInput = document.getElementById('appointment_date');
        const timeInput = document.getElementById('appointment_time');
        const displayText = document.getElementById('selected_display');
        const btnConfirm = document.getElementById('btn-confirm');

        function updateConfirmState() {
            btnConfirm.disabled = !(dateInput.value && timeInput.value);
            if (dateInput.value && timeInput.value) {
                displayText.textContent = `${dateInput.value} - ${timeInput.value}`;
            }
        }

        document.querySelectorAll('.calendar-day').forEach(btn => {
            btn.addEventListener('click', () => {
                // reset style
                document.querySelectorAll('.calendar-day div').forEach(d => {
                    d.classList.remove('bg-primary', 'text-white');
                });
                document.querySelectorAll('.calendar-day').forEach(b => {
                    b.classList.remove('text-white', 'font-bold');
                    b.classList.add('text-gray-800');
                });

                const inner = btn.querySelector('div');
                inner.classList.add('bg-primary');
                btn.classList.add('text-white', 'font-bold');

                dateInput.value = btn.dataset.date || '';
                updateConfirmState();
            });
        });

        // --- pilih waktu ---
        document.querySelectorAll('.time-slot').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.time-slot').forEach(b => {
                    b.classList.remove('border-2', 'border-primary', 'bg-primary/20', 'text-primary', 'font-bold');
                    b.classList.add('border-gray-300', 'text-gray-700');
                });
                btn.classList.remove('border-gray-300', 'text-gray-700');
                btn.classList.add('border-2', 'border-primary', 'bg-primary/20', 'text-primary', 'font-bold');

                timeInput.value = btn.textContent.trim();
                updateConfirmState();
            });
        });

        // disable tombol saat form pertama kali dibuka
        updateConfirmState();

        // validasi sebelum submit
        document.getElementById('schedule-form').addEventListener('submit', function(e) {
            if (!dateInput.value || !timeInput.value) {
                e.preventDefault();
                alert('Silakan pilih tanggal dan waktu terlebih dahulu.');
            }
        });
    </script>

</body>

</html>