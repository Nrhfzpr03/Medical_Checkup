<?php
require_once 'functions.php';
requireLogin();

$user = currentUser();

/**
 * Ambil semua hasil MCU milik user, join ke booking & paket
 */
$stmt = $pdo->prepare("
    SELECT 
        r.*,
        b.booking_date,
        b.booking_time,
        p.name AS package_name
    FROM mcu_results r
    JOIN bookings b ON b.id = r.booking_id
    JOIN packages p ON p.id = b.package_id
    WHERE r.user_id = ?
    ORDER BY r.result_date DESC, r.id DESC
");
$stmt->execute([$user['id']]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Hasil Medical Check-Up</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#2594e9",
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
      font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 20
    }
  </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200">
  <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">

      <!-- HEADER -->
      <header class="sticky top-0 z-20 w-full bg-white/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between px-4 sm:px-6 lg:px-10 py-3 max-w-6xl mx-auto">
          <div class="flex items-center gap-3">
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
            <h1 class="text-lg font-bold tracking-[-0.015em]">MedCheck</h1>
          </div>

          <div class="hidden md:flex flex-1 justify-center gap-6 text-sm font-medium">
            <a href="index.php" class="text-gray-700 dark:text-gray-300 hover:text-primary">Dashboard</a>
            <a href="booking.php" class="text-gray-700 dark:text-gray-300 hover:text-primary">MCU</a>
            <a href="history.php" class="text-gray-700 dark:text-gray-300 hover:text-primary">Riwayat Booking</a>
            <a href="mcu_results.php" class="text-primary font-bold">Hasil MCU</a>
            <a href="consultations.php" class="text-gray-700 dark:text-gray-300 hover:text-primary">Konsultasi</a>
            <a href="articles.php" class="text-gray-700 dark:text-gray-300 hover:text-primary">Artikel</a>
          </div>

          <div class="flex items-center gap-2">
            <button class="flex items-center justify-center rounded-lg h-10 w-10 bg-gray-200/60 dark:bg-gray-800/60">
              <span class="material-symbols-outlined text-xl">notifications</span>
            </button>
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-9"
              style='background-image: url("https://ui-avatars.com/api/?name=<?= urlencode($user["name"] ?? "User") ?>&background=2594e9&color=fff");'></div>
          </div>
        </div>
      </header>

      <!-- MAIN -->
      <main class="px-4 sm:px-6 lg:px-10 flex flex-1 justify-center py-6">
        <div class="w-full max-w-6xl flex flex-col gap-6">

          <!-- Header section -->
          <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
              <p class="text-2xl md:text-3xl font-black tracking-[-0.03em] text-gray-900 dark:text-white">
                Hasil Medical Check-Up
              </p>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Ringkasan seluruh hasil pemeriksaan MCU yang sudah selesai dan tercatat.
              </p>
            </div>
          </div>

          <!-- Filter (UI saja dulu) -->
          <div class="border-y border-gray-200 dark:border-gray-800 py-4">
            <div class="flex flex-wrap gap-3 items-center">
              <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2">Filter:</p>
              <button class="flex items-center gap-2 px-3 h-9 rounded-lg bg-gray-200/70 dark:bg-gray-800/70 text-xs md:text-sm">
                <span class="material-symbols-outlined text-base">event</span>
                Tahun
              </button>
              <button class="flex items-center gap-2 px-3 h-9 rounded-lg bg-gray-200/70 dark:bg-gray-800/70 text-xs md:text-sm">
                <span class="material-symbols-outlined text-base">medical_information</span>
                Paket MCU
              </button>
              <button class="flex items-center gap-1 px-3 h-9 rounded-lg text-xs md:text-sm text-primary hover:bg-primary/10">
                <span class="material-symbols-outlined text-base">close</span>
                Reset
              </button>
            </div>
          </div>

          <!-- List Hasil -->
          <?php if (empty($results)): ?>
            <div class="mt-8 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl py-10 px-6 text-center">
              <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary mb-3">
                <span class="material-symbols-outlined text-3xl">hourglass_empty</span>
              </div>
              <p class="text-base font-semibold text-gray-800 dark:text-gray-100">Belum ada hasil MCU</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Hasil MCU akan muncul di sini setelah pemeriksaan selesai dan data lab dimasukkan ke sistem.
              </p>
              <a href="booking.php"
                class="mt-4 inline-flex items-center justify-center h-10 px-4 rounded-lg bg-primary text-white text-sm font-semibold gap-2">
                <span class="material-symbols-outlined text-xl">add</span>
                Jadwalkan MCU
              </a>
            </div>
          <?php else: ?>

            <div class="overflow-x-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-sm">
              <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-800/60 text-gray-500 dark:text-gray-400">
                  <tr>
                    <th class="px-5 py-3 font-semibold">Tanggal</th>
                    <th class="px-5 py-3 font-semibold">Paket</th>
                    <th class="px-5 py-3 font-semibold">Klinik</th>
                    <th class="px-5 py-3 font-semibold text-center">Dokter</th>
                    <th class="px-5 py-3 font-semibold text-center">Health Score</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($results as $r): ?>
                    <?php
                    $dateLabel = $r['result_date']
                      ? date('d M Y', strtotime($r['result_date']))
                      : ($r['booking_date'] ? date('d M Y', strtotime($r['booking_date'])) : '-');

                    $clinicName  = $r['clinic_name'] ?: 'MedCheck Main Clinic';
                    $doctorName  = $r['doctor_name'] ?: 'Belum diisi';
                    $healthScore = $r['health_score'];

                    // warna score
                    $scoreClass = 'text-gray-700';
                    if ($healthScore !== null) {
                      if ($healthScore >= 90) $scoreClass = 'text-emerald-600';
                      elseif ($healthScore >= 75) $scoreClass = 'text-green-600';
                      elseif ($healthScore >= 60) $scoreClass = 'text-amber-600';
                      else                        $scoreClass = 'text-rose-600';
                    }
                    ?>
                    <tr class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/60">
                      <td class="px-5 py-3 text-gray-900 dark:text-gray-100 font-medium">
                        <?= htmlspecialchars($dateLabel) ?>
                      </td>
                      <td class="px-5 py-3 text-gray-700 dark:text-gray-300">
                        <?= htmlspecialchars($r['package_name']) ?>
                      </td>
                      <td class="px-5 py-3 text-gray-700 dark:text-gray-300">
                        <?= htmlspecialchars($clinicName) ?>
                      </td>
                      <td class="px-5 py-3 text-center text-gray-700 dark:text-gray-300 text-xs md:text-sm">
                        <?= htmlspecialchars($doctorName) ?>
                      </td>
                      <td class="px-5 py-3 text-center">
                        <?php if ($healthScore !== null): ?>
                          <span class="font-semibold <?= $scoreClass ?>">
                            <?= (int)$healthScore ?>/100
                          </span>
                        <?php else: ?>
                          <span class="text-xs text-gray-400">Belum dinilai</span>
                        <?php endif; ?>
                      </td>
                      <td class="px-5 py-3 text-right">
                        <a href="mcu_result_detail.php?id=<?= (int)$r['id'] ?>"
                          class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg text-sm font-medium text-primary hover:bg-primary/10">
                          Lihat Detail
                          <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

          <?php endif; ?>

        </div>
      </main>
    </div>
  </div>
</body>

</html>