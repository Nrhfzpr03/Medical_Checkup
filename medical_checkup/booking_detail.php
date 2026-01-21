<?php
require_once 'functions.php';
requireLogin();

$user = currentUser();

// Ambil ID booking dari query string
$bookingId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($bookingId <= 0) {
  header('Location: history.php');
  exit;
}

$stmt = $pdo->prepare("
    SELECT b.*, p.name AS package_name, p.description AS package_description, p.price
    FROM bookings b
    JOIN packages p ON p.id = b.package_id
    WHERE b.id = ? AND b.user_id = ?
");
$stmt->execute([$bookingId, $user['id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
  die('Data booking tidak ditemukan.');
}

// Format tanggal & jam
$dateLabel = $booking['booking_date']
  ? date('d F Y', strtotime($booking['booking_date']))
  : '-';

$timeLabel = $booking['booking_time']
  ? substr($booking['booking_time'], 0, 5)
  : '-';

// Mapping status booking ke badge
$status = $booking['status'] ?? 'unknown';
$statusText = ucfirst(str_replace('_', ' ', $status));
$statusColor = 'bg-gray-200 text-gray-800';

if ($status === 'completed') {
  $statusText  = 'Selesai';
  $statusColor = 'bg-emerald-100 text-emerald-700';
} elseif ($status === 'paid') {
  $statusText  = 'Sudah Dibayar';
  $statusColor = 'bg-blue-100 text-blue-700';
} elseif ($status === 'pending_payment') {
  $statusText  = 'Menunggu Pembayaran';
  $statusColor = 'bg-amber-100 text-amber-700';
} elseif ($status === 'cancelled') {
  $statusText  = 'Dibatalkan';
  $statusColor = 'bg-rose-100 text-rose-700';
}
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Detail Medical Check-Up</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#6FCF97;",
            "background-light": "#f6f7f8",
            "background-dark": "#111a21",
            "neutral-text-light": "#0e151b",
            "neutral-text-dark": "#e7eef3",
            "secondary-text-light": "#4d7899",
            "secondary-text-dark": "#a0b5c5",
            "border-light": "#d0dde7",
            "border-dark": "#34414e",
            "card-light": "#ffffff",
            "card-dark": "#1a252f",
            "success": "#2ECC71",
            "warning": "#F1C40F",
            "danger": "#E74C3C",
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
    .material-symbols-outlined {
      font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 20
    }
  </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark">
  <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">

      <!-- HEADER -->
      <header class="sticky top-0 z-20 w-full bg-card-light/80 dark:bg-card-dark/80 backdrop-blur-sm border-b border-border-light dark:border-border-dark">
        <div class="flex items-center justify-between whitespace-nowrap px-4 sm:px-6 md:px-10 lg:px-20 py-3 max-w-7xl mx-auto">
          <div class="flex items-center gap-4 text-neutral-text-light dark:text-neutral-text-dark">
            <a href="history.php" class="flex items-center justify-center rounded-full bg-background-light dark:bg-background-dark w-8 h-8 mr-1 hover:bg-gray-200 dark:hover:bg-gray-700">
              <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div class="size-6 text-primary">
              <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_6_330)">
                  <path clip-rule="evenodd" d="M24 0.757355L47.2426 24L24 47.2426L0.757355 24L24 0.757355ZM21 35.7574V12.2426L9.24264 24L21 35.7574Z" fill="currentColor" fill-rule="evenodd"></path>
                </g>
                <defs>
                  <clipPath id="clip0_6_330">
                    <rect fill="white" height="48" width="48"></rect>
                  </clipPath>
                </defs>
              </svg>
            </div>
            <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">MedCheck</h2>
          </div>
          <div class="hidden md:flex flex-1 justify-end gap-8">
            <a class="text-neutral-text-light dark:text-neutral-text-dark text-sm font-medium leading-normal" href="index.php">Dashboard</a>
            <a class="text-primary text-sm font-bold leading-normal" href="history.php">Riwayat MCU</a>
            <a class="text-neutral-text-light dark:text-neutral-text-dark text-sm font-medium leading-normal" href="booking.php">Jadwal</a>
          </div>
          <div class="flex items-center gap-2">
            <button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-background-light dark:bg-background-dark text-neutral-text-light dark:text-neutral-text-dark gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <span class="material-symbols-outlined text-xl">notifications</span>
            </button>
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
              style='background-image: url("https://ui-avatars.com/api/?name=<?= urlencode($user["name"] ?? "User") ?>&background=2594e9&color=fff");'></div>
          </div>
        </div>
      </header>

      <!-- MAIN -->
      <main class="px-4 sm:px-6 md:px-10 lg:px-20 flex flex-1 justify-center py-5 md:py-10">
        <div class="layout-content-container flex flex-col w-full max-w-5xl flex-1 gap-6 md:gap-8">

          <!-- Breadcrumb -->
          <div class="flex flex-wrap gap-2">
            <a class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary" href="index.php">Home</a>
            <span class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium leading-normal">/</span>
            <a class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium leading-normal hover:text-primary dark:hover:text-primary" href="history.php">Riwayat MCU</a>
            <span class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium leading-normal">/</span>
            <span class="text-neutral-text-light dark:text-neutral-text-dark text-sm font-medium leading-normal">
              Detail MCU <?= htmlspecialchars($dateLabel) ?>
            </span>
          </div>

          <!-- Title + Download -->
          <div class="flex flex-col md:flex-row flex-wrap justify-between gap-4 items-start">
            <div class="flex flex-col gap-2">
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">
                Detail Medical Check-Up
              </p>
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-base font-normal leading-normal">
                <?= htmlspecialchars($booking['package_name']) ?>, <?= htmlspecialchars($dateLabel) ?> (<?= htmlspecialchars($timeLabel) ?>)
              </p>
            </div>
            <!-- sementara tombol download diarahkan ke halaman bukti booking / invoice kalau nanti ada -->
            <button disabled
              class="flex w-full md:w-auto min-w-[84px] max-w-[480px] cursor-not-allowed items-center justify-center overflow-hidden rounded-lg h-11 px-5 bg-gray-300 text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
              <span class="material-symbols-outlined text-xl">download</span>
              <span class="truncate">Unduh Laporan (belum tersedia)</span>
            </button>
          </div>

          <!-- Info Singkat Booking -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 bg-card-light dark:bg-card-dark p-6 rounded-xl border border-border-light dark:border-border-dark">
            <div class="flex flex-col gap-1">
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium leading-normal">Tanggal Pemeriksaan</p>
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold leading-normal">
                <?= htmlspecialchars($dateLabel) ?>, <?= htmlspecialchars($timeLabel) ?>
              </p>
            </div>
            <div class="flex flex-col gap-1">
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium leading-normal">Lokasi</p>
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold leading-normal">
                MedCheck Main Clinic
              </p>
            </div>
            <div class="flex flex-col gap-1">
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium leading-normal">Jenis Paket</p>
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold leading-normal">
                <?= htmlspecialchars($booking['package_name']) ?>
              </p>
            </div>
            <div class="flex flex-col gap-1">
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium leading-normal">Status & Total</p>
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold leading-normal flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusColor ?>">
                  <?= htmlspecialchars($statusText) ?>
                </span>
                <span class="text-sm text-secondary-text-light dark:text-secondary-text-dark">
                  Rp <?= number_format($booking['price'] ?? 0, 0, ',', '.') ?>
                </span>
              </p>
            </div>
          </div>

          <!-- Catatan "dokter" sederhana (dari package description / placeholder) -->
          <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl border border-border-light dark:border-border-dark">
            <div class="flex items-center gap-3 mb-4">
              <span class="material-symbols-outlined text-primary text-2xl">clinical_notes</span>
              <h2 class="text-neutral-text-light dark:text-neutral-text-dark text-xl font-bold leading-tight tracking-[-0.015em]">
                Catatan Pemeriksaan
              </h2>
            </div>
            <?php if (!empty($booking['notes'])): ?>
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-base font-normal leading-relaxed">
                <?= nl2br(htmlspecialchars($booking['notes'])) ?>
              </p>
            <?php elseif (!empty($booking['package_description'])): ?>
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-base font-normal leading-relaxed">
                <?= nl2br(htmlspecialchars($booking['package_description'])) ?>
              </p>
            <?php else: ?>
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-base font-normal leading-relaxed">
                Detail hasil MCU belum tersedia. Catatan dokter dan hasil pemeriksaan akan muncul di halaman ini setelah
                pemeriksaan selesai dan data hasil dimasukkan ke sistem.
              </p>
            <?php endif; ?>
          </div>

          <!-- Placeholder Hasil Pemeriksaan (karena belum ada tabel hasil MCU) -->
          <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl border border-border-light dark:border-border-dark">
            <h2 class="text-neutral-text-light dark:text-neutral-text-dark text-xl font-bold leading-tight tracking-[-0.015em] mb-3">
              Hasil Pemeriksaan Lengkap
            </h2>
            <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm md:text-base leading-relaxed">
              Saat ini sistem hanya menyimpan data <span class="font-semibold">booking</span> dan <span class="font-semibold">paket MCU</span>.
              Modul hasil laboratorium (hemoglobin, kolesterol, gula darah, dll.) belum tersedia.
              <br><br>
              Jika nanti kamu menambahkan tabel <code>mcu_results</code> atau tabel hasil lab lainnya, bagian ini bisa diisi
              dengan tabel parameter tes, nilai hasil, nilai rujukan, dan status (Normal / Tinggi / Perhatian) seperti
              desain mockup awal.
            </p>
          </div>

          <!-- Footer -->
          <footer class="mt-10 border-t border-border-light dark:border-border-dark pt-8 pb-4">
            <div class="text-center text-secondary-text-light dark:text-secondary-text-dark text-sm">
              <p>© 2024 MedCheck. All rights reserved.</p>
              <p class="mt-2 text-xs">
                Informasi di halaman ini bukan pengganti nasihat medis profesional.
                Selalu konsultasikan dengan dokter Anda untuk diagnosis dan pengobatan.
              </p>
            </div>
          </footer>

        </div>
      </main>
    </div>
  </div>
</body>

</html>