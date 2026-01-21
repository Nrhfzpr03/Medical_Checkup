<?php
require_once 'functions.php';
requireLogin();

$user = currentUser();

$stmt = $pdo->prepare("
    SELECT b.*, p.name AS package_name, p.price
    FROM bookings b
    JOIN packages p ON p.id = b.package_id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
");
$stmt->execute([$user['id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Medical Check-Up History</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <script>
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
    .material-symbols-outlined {
      font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 20
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
  </style>
</head>
<?php
$paymentSuccess = isset($_GET['paid']) && $_GET['paid'] === 'success';
?>

<body class="font-display bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200">
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">

      <!-- HEADER -->
      <header class="sticky top-0 z-20 w-full bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-4 text-slate-800 dark:text-slate-200">
              <div class="text-primary text-3xl">
                <span class="material-symbols-outlined !text-4xl">health_and_safety</span>
              </div>
              <h1 class="text-xl font-bold leading-tight tracking-[-0.015em]">HealthCheck+</h1>
            </div>

            <!-- <nav class="hidden md:flex items-center gap-8">
                        <a class="text-slate-700 dark:text-slate-300 hover:text-primary dark:hover:text-primary text-sm font-medium leading-normal transition-colors"
                           href="index.php">Home</a>
                        <a class="text-primary dark:text-primary text-sm font-bold leading-normal"
                           href="packages.php">Layanan MCU</a>
                        <a class="text-slate-700 dark:text-slate-300 hover:text-primary dark:hover:text-primary text-sm font-medium leading-normal transition-colors"
                           href="#">Tentang Kami</a>
                        <a class="text-slate-700 dark:text-slate-300 hover:text-primary dark:hover:text-primary text-sm font-medium leading-normal transition-colors"
                           href="#">Kontak</a>
                    </nav> -->

            <div class="flex items-center gap-4">
              <!-- karena user sudah login, tombol = kembali ke dashboard -->
              <a href="index.php"
                class="flex min-w-[120px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                <span class="truncate">Kembali ke Dashboard</span>
              </a>
              <button class="md:hidden p-2 rounded-md text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800">
                <span class="material-symbols-outlined">menu</span>
              </button>
            </div>
          </div>
        </div>
      </header>

      <!-- MAIN -->
      <main class="px-4 sm:px-6 lg:px-10 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col w-full max-w-6xl flex-1">
          <div class="flex flex-wrap justify-between items-start gap-4 p-4">
            <div class="flex min-w-72 flex-col gap-2">
              <p class="text-gray-900 dark:text-white text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">
                My Medical Check-Up History
              </p>
              <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">
                View, manage, and download your past examination bookings.
              </p>
            </div>
            <a href="booking.php"
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
              <span class="material-symbols-outlined text-xl">add</span>
              <span class="truncate">Schedule New MCU</span>
            </a>
          </div>

          <!-- FILTER (belum dinamis, hanya UI) -->
          <div class="border-y border-gray-200 dark:border-gray-800 my-6">
            <div class="p-4">
              <h3 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em] pb-3">
                Filter Examinations
              </h3>
              <div class="flex gap-3 flex-wrap">
                <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-200/60 dark:bg-gray-800/60 pl-4 pr-2 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800">
                  <p class="text-sm font-medium leading-normal">Date Range</p>
                  <span class="material-symbols-outlined text-xl">calendar_month</span>
                </button>
                <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-200/60 dark:bg-gray-800/60 pl-4 pr-2 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800">
                  <p class="text-sm font-medium leading-normal">Examination Type</p>
                  <span class="material-symbols-outlined text-xl">arrow_drop_down</span>
                </button>
                <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-200/60 dark:bg-gray-800/60 pl-4 pr-2 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800">
                  <p class="text-sm font-medium leading-normal">Status</p>
                  <span class="material-symbols-outlined text-xl">arrow_drop_down</span>
                </button>
                <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-lg text-primary dark:text-primary hover:bg-primary/10 pl-3 pr-3">
                  <span class="material-symbols-outlined text-xl">close</span>
                  <p class="text-sm font-medium leading-normal">Clear Filters</p>
                </button>
              </div>
            </div>
          </div>

          <!-- TABLE / EMPTY STATE -->
          <div class="overflow-x-auto">
            <?php if (!empty($bookings)): ?>
              <table class="w-full text-left text-sm">
                <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-900/50">
                  <tr>
                    <th class="px-6 py-4 font-medium" scope="col">Examination Date</th>
                    <th class="px-6 py-4 font-medium" scope="col">Examination Type</th>
                    <th class="px-6 py-4 font-medium" scope="col">Clinic / Location</th>
                    <th class="px-6 py-4 font-medium" scope="col">Status</th>
                    <th class="px-6 py-4 font-medium text-right" scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($bookings as $b): ?>
                    <?php
                    // Format tanggal
                    $dateLabel = $b['booking_date']
                      ? date('F d, Y', strtotime($b['booking_date']))
                      : '-';

                    // Status badge mapping
                    $status = $b['status'] ?? 'unknown';
                    $badgeClass = 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200';
                    $dotClass   = 'bg-gray-500';
                    $statusText = ucfirst(str_replace('_', ' ', $status));

                    if ($status === 'completed') {
                      $badgeClass = 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300';
                      $dotClass   = 'bg-green-500';
                      $statusText = 'Completed';
                    } elseif ($status === 'paid') {
                      $badgeClass = 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300';
                      $dotClass   = 'bg-blue-500';
                      $statusText = 'Paid';
                    } elseif ($status === 'pending_payment') {
                      $badgeClass = 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300';
                      $dotClass   = 'bg-yellow-500';
                      $statusText = 'Pending Payment';
                    } elseif ($status === 'cancelled') {
                      $badgeClass = 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300';
                      $dotClass   = 'bg-red-500';
                      $statusText = 'Cancelled';
                    }
                    ?>
                    <tr class="bg-background-light dark:bg-background-dark border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                      <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        <?= htmlspecialchars($dateLabel) ?>
                      </td>
                      <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                        <?= htmlspecialchars($b['package_name']) ?>
                      </td>
                      <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                        MedCheck Main Clinic
                      </td>
                      <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium <?= $badgeClass ?>">
                          <span class="size-2 rounded-full <?= $dotClass ?>"></span>
                          <?= htmlspecialchars($statusText) ?>
                        </span>
                      </td>
                      <td class="px-6 py-4 text-right">
                        <a href="booking_detail.php?id=<?= (int)$b['id'] ?>"
                          class="flex items-center gap-2 h-9 px-4 text-sm font-medium rounded-lg text-primary dark:text-primary hover:bg-primary/10 ml-auto">
                          View Details
                          <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <!-- EMPTY STATE -->
              <div class="text-center py-20 px-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl mt-8">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-primary/10 text-primary">
                  <span class="material-symbols-outlined text-5xl">folder_off</span>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No Examination History Found</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                  You don't have any past medical check-up bookings yet.
                </p>
                <div class="mt-6">
                  <a href="booking.php"
                    class="flex mx-auto min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] gap-2">
                    <span class="material-symbols-outlined text-xl">add</span>
                    <span class="truncate">Schedule Your First MCU</span>
                  </a>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <!-- (optional pagination statis, kalau mau bisa dibuat dinamis) -->
          <!--
        <div class="flex items-center justify-between p-4 mt-6">
          <span class="text-sm text-gray-600 dark:text-gray-400">Showing ...</span>
          <div class="inline-flex items-center gap-2">
            <button class="flex items-center justify-center h-9 px-3 rounded-lg text-sm font-medium bg-gray-200/60 dark:bg-gray-800/60 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800">Previous</button>
            <button class="flex items-center justify-center h-9 px-3 rounded-lg text-sm font-medium bg-gray-200/60 dark:bg-gray-800/60 text-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800">Next</button>
          </div>
        </div>
        -->
        </div>
      </main>
    </div>
  </div>
  <?php if ($paymentSuccess): ?>
    <!-- Overlay Modal Pembayaran Berhasil -->
    <div id="payment-success-modal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      <div class="bg-white rounded-xl shadow-lg max-w-sm w-full mx-4 p-6 text-center">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100">
          <span class="material-symbols-outlined text-emerald-500">check_circle</span>
        </div>
        <h2 class="text-xl font-bold mb-2">Pembayaran Berhasil</h2>
        <p class="text-sm text-slate-600 mb-5">
          Terima kasih, pembayaran Anda telah diproses. Detail booking bisa dilihat pada riwayat di bawah ini.
        </p>
        <button id="btn-close-success"
          class="w-full rounded-lg bg-primary text-white font-semibold py-2.5 hover:bg-primary/90">
          Mengerti
        </button>
      </div>
    </div>

    <script>
      document.getElementById('btn-close-success').addEventListener('click', function() {
        const modal = document.getElementById('payment-success-modal');
        if (modal) modal.style.display = 'none';

        // opsional: bersihkan ?paid=success dari URL tanpa reload
        if (window.history.replaceState) {
          const url = new URL(window.location.href);
          url.searchParams.delete('paid');
          window.history.replaceState({}, '', url.toString());
        }
      });
    </script>
  <?php endif; ?>
</body>

</html>