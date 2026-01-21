<?php
require_once 'functions.php';
requireLogin();

$user = currentUser();

// ambil id hasil MCU
$resultId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($resultId <= 0) {
  die('Hasil MCU tidak ditemukan.');
}

// ambil header hasil + booking + paket
$stmt = $pdo->prepare("
    SELECT 
        r.*,
        b.booking_date,
        b.booking_time,
        p.name AS package_name,
        p.description AS package_description
    FROM mcu_results r
    JOIN bookings b ON b.id = r.booking_id
    JOIN packages p ON p.id = b.package_id
    WHERE r.id = ? AND r.user_id = ?
");
$stmt->execute([$resultId, $user['id']]);
$header = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$header) {
  die('Hasil MCU tidak ditemukan atau tidak milik akun ini.');
}

// ambil detail parameter tes
$stmt = $pdo->prepare("
    SELECT *
    FROM mcu_result_details
    WHERE result_id = ?
    ORDER BY test_group, id
");
$stmt->execute([$resultId]);
$details = $stmt->fetchAll(PDO::FETCH_ASSOC);

// format tanggal & jam
$dateLabel = $header['result_date']
  ? date('d F Y', strtotime($header['result_date']))
  : ($header['booking_date'] ? date('d F Y', strtotime($header['booking_date'])) : '-');

$timeLabel = $header['booking_time']
  ? substr($header['booking_time'], 0, 5)
  : '-';

$clinicName  = $header['clinic_name'] ?: 'MedCheck Main Clinic';
$packageName = $header['package_name'] ?? 'Paket MCU';

// ambil indikator utama kalau ada
$fastingSugar = null;
$totalChol    = null;

foreach ($details as $row) {
  if (stripos($row['test_name'], 'Gula Darah Puasa') !== false) {
    $fastingSugar = $row;
  }
  if (stripos($row['test_name'], 'Kolesterol Total') !== false) {
    $totalChol = $row;
  }
}

// helper status chip
function mapStatusClass($status)
{
  $status = strtolower($status);
  if ($status === 'normal') {
    return ['label' => 'Normal',   'class' => 'bg-success/20 text-success'];
  }
  if ($status === 'warning') {
    return ['label' => 'Perhatian', 'class' => 'bg-warning/20 text-warning'];
  }
  if ($status === 'high') {
    return ['label' => 'Tinggi',   'class' => 'bg-danger/20 text-danger'];
  }
  if ($status === 'low') {
    return ['label' => 'Rendah',   'class' => 'bg-warning/20 text-warning'];
  }
  return ['label' => ucfirst($status), 'class' => 'bg-gray-200 text-gray-800'];
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
            "primary": "#2594e9",
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
            <div class="flex items-center gap-9 text-sm font-medium">
              <a class="text-neutral-text-light dark:text-neutral-text-dark hover:text-primary" href="index.php">Dashboard</a>
              <a class="text-neutral-text-light dark:text-neutral-text-dark hover:text-primary" href="history.php">Riwayat MCU</a>
              <a class="text-neutral-text-light dark:text-neutral-text-dark hover:text-primary" href="consultations.php">Konsultasi</a>
              <a class="text-neutral-text-light dark:text-neutral-text-dark hover:text-primary" href="articles.php">Artikel</a>
            </div>
          </div>
          <button class="md:hidden flex items-center justify-center rounded-lg h-10 w-10 bg-background-light dark:bg-background-dark text-neutral-text-light dark:text-neutral-text-dark">
            <span class="material-symbols-outlined text-2xl">menu</span>
          </button>
        </div>
      </header>

      <main class="px-4 sm:px-6 md:px-10 lg:px-20 flex flex-1 justify-center py-5 md:py-10">
        <div class="layout-content-container flex flex-col w-full max-w-5xl flex-1 gap-6 md:gap-8">

          <!-- Breadcrumb -->
          <div class="flex flex-wrap gap-2">
            <a class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium hover:text-primary" href="index.php">Home</a>
            <span class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium">/</span>
            <a class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium hover:text-primary" href="history.php">Riwayat MCU</a>
            <span class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium">/</span>
            <span class="text-neutral-text-light dark:text-neutral-text-dark text-sm font-medium">
              Detail Hasil MCU <?= htmlspecialchars($dateLabel) ?>
            </span>
          </div>

          <!-- Title -->
          <div class="flex flex-col md:flex-row flex-wrap justify-between gap-4 items-start">
            <div class="flex flex-col gap-2">
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">
                Detail Medical Check-Up
              </p>
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-base">
                <?= htmlspecialchars($packageName) ?>, <?= htmlspecialchars($dateLabel) ?> <?= $timeLabel !== '-' ? '(' . htmlspecialchars($timeLabel) . ')' : '' ?>
              </p>
            </div>
            <button class="flex w-full md:w-auto min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold gap-2">
              <span class="material-symbols-outlined text-xl">download</span>
              <span class="truncate">Unduh Laporan (PDF)</span>
            </button>
          </div>

          <!-- Info ringkas -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 bg-card-light dark:bg-card-dark p-6 rounded-xl border border-border-light dark:border-border-dark">
            <div class="flex flex-col gap-1">
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium">Tanggal Pemeriksaan</p>
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold"><?= htmlspecialchars($dateLabel) ?> <?= $timeLabel !== '-' ? '• ' . htmlspecialchars($timeLabel) : '' ?></p>
            </div>
            <div class="flex flex-col gap-1">
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium">Lokasi</p>
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold"><?= htmlspecialchars($clinicName) ?></p>
            </div>
            <div class="flex flex-col gap-1">
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium">Jenis Paket</p>
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold"><?= htmlspecialchars($packageName) ?></p>
            </div>
            <div class="flex flex-col gap-1">
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm font-medium">Dokter & Skor</p>
              <p class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold">
                <?= htmlspecialchars($header['doctor_name'] ?? 'Belum diisi') ?>
                <?php if (!empty($header['health_score'])): ?>
                  · <span class="text-success font-bold"><?= (int)$header['health_score'] ?></span>/100
                <?php endif; ?>
              </p>
            </div>
          </div>

          <!-- Catatan Dokter -->
          <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl border border-border-light dark:border-border-dark">
            <div class="flex items-center gap-3 mb-4">
              <span class="material-symbols-outlined text-primary text-2xl">clinical_notes</span>
              <h2 class="text-neutral-text-light dark:text-neutral-text-dark text-xl font-bold">Catatan Dokter</h2>
            </div>
            <p class="text-secondary-text-light dark:text-secondary-text-dark text-base leading-relaxed">
              <?php if (!empty($header['doctor_notes'])): ?>
                <?= nl2br(htmlspecialchars($header['doctor_notes'])) ?>
              <?php else: ?>
                Catatan dokter belum tersedia untuk pemeriksaan ini. Silakan hubungi klinik jika Anda membutuhkan ringkasan tertulis dari dokter.
              <?php endif; ?>
            </p>
          </div>

          <!-- Indikator Kunci (kalau ada data) -->
          <?php if ($fastingSugar || $totalChol): ?>
            <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl border border-border-light dark:border-border-dark">
              <h2 class="text-neutral-text-light dark:text-neutral-text-dark text-xl font-bold mb-6">Indikator Kesehatan Kunci</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php if ($fastingSugar): ?>
                  <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-baseline">
                      <h3 class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold">Gula Darah Puasa</h3>
                      <p class="text-neutral-text-light dark:text-neutral-text-dark text-base">
                        <span class="font-bold text-2xl text-success"><?= htmlspecialchars($fastingSugar['value']) ?></span>
                        <?= htmlspecialchars($fastingSugar['unit']) ?>
                      </p>
                    </div>
                    <p class="text-xs text-secondary-text-light dark:text-secondary-text-dark">Rujukan: <?= htmlspecialchars($fastingSugar['ref_range'] ?? '-') ?></p>
                  </div>
                <?php endif; ?>

                <?php if ($totalChol):
                  $map = mapStatusClass($totalChol['status']);
                ?>
                  <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-baseline">
                      <h3 class="text-neutral-text-light dark:text-neutral-text-dark text-base font-semibold">Kolesterol Total</h3>
                      <p class="text-neutral-text-light dark:text-neutral-text-dark text-base">
                        <span class="font-bold text-2xl <?= strpos($map['class'], 'danger') !== false || strpos($map['class'], 'warning') !== false ? 'text-warning' : 'text-success' ?>">
                          <?= htmlspecialchars($totalChol['value']) ?>
                        </span>
                        <?= htmlspecialchars($totalChol['unit']) ?>
                      </p>
                    </div>
                    <p class="text-xs text-secondary-text-light dark:text-secondary-text-dark mb-1">Rujukan: <?= htmlspecialchars($totalChol['ref_range'] ?? '-') ?></p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $map['class'] ?>">
                      <?= htmlspecialchars($map['label']) ?>
                    </span>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- Tabel Hasil Lengkap -->
          <div class="flex flex-col">
            <h2 class="text-neutral-text-light dark:text-neutral-text-dark text-xl font-bold px-1 pb-3 pt-5">
              Hasil Pemeriksaan Lengkap
            </h2>

            <?php if (empty($details)): ?>
              <p class="text-secondary-text-light dark:text-secondary-text-dark text-sm px-1">
                Belum ada data parameter laboratorium yang tersimpan untuk hasil MCU ini.
              </p>
            <?php else: ?>
              <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-secondary-text-light dark:text-secondary-text-dark">
                  <thead class="text-xs uppercase bg-background-light dark:bg-background-dark">
                    <tr>
                      <th class="px-6 py-3 rounded-l-lg font-semibold">Parameter Tes</th>
                      <th class="px-6 py-3 font-semibold text-center">Hasil Anda</th>
                      <th class="px-6 py-3 font-semibold">Nilai Rujukan</th>
                      <th class="px-6 py-3 rounded-r-lg font-semibold text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $currentGroup = null;
                    foreach ($details as $row):
                      if ($row['test_group'] && $row['test_group'] !== $currentGroup):
                        $currentGroup = $row['test_group'];
                    ?>
                        <tr class="font-bold bg-background-light dark:bg-background-dark text-neutral-text-light dark:text-neutral-text-dark">
                          <td class="px-6 py-3" colspan="4"><?= htmlspecialchars($currentGroup) ?></td>
                        </tr>
                      <?php
                      endif;
                      $map = mapStatusClass($row['status']);
                      ?>
                      <tr class="bg-card-light dark:bg-card-dark border-b dark:border-border-dark">
                        <td class="px-6 py-4 font-medium text-neutral-text-light dark:text-neutral-text-dark">
                          <?= htmlspecialchars($row['test_name']) ?>
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-neutral-text-light dark:text-neutral-text-dark">
                          <?= htmlspecialchars($row['value']) ?> <?= htmlspecialchars($row['unit']) ?>
                        </td>
                        <td class="px-6 py-4">
                          <?= htmlspecialchars($row['ref_range'] ?? '-') ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $map['class'] ?>">
                            <?= htmlspecialchars($map['label']) ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>

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