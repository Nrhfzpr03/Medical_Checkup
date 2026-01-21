<?php
require_once 'functions.php';
// requireLogin(); // Aktifkan jika sudah ada session login

$userId = $_SESSION['user_id'] ?? 1;

// --- Ambil data user untuk Sidebar ---
$stmtUser = $pdo->prepare("SELECT full_name FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$userMain = $stmtUser->fetch(PDO::FETCH_ASSOC);

$namaUser = $userMain['full_name'] ?? 'Pengguna';
$status   = 'Pasien Premium';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Pengaturan Umum | Parahita</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            "primary": "#137fec",
            "accent": "#2563eb",
            "neutral-gray": "#f8fafc",
            "border-light": "#e2e8f0"
          },
          fontFamily: {
            "display": ["Inter", "sans-serif"]
          },
        },
      },
    }
  </script>
  <style>
    /* Gunakan CSS standar untuk Body agar editor tidak komplain */
    body {
      font-family: 'Inter', sans-serif;
      background-color: #ffffff;
      /* Sama dengan bg-white */
      color: #0f172a;
      /* Sama dengan text-slate-900 */
    }

    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    /* Custom Toggle Switch Style */
    .switch-checkbox:checked+.switch-label {
      background-color: #137fec;
    }

    .switch-checkbox:checked+.switch-label .switch-dot {
      transform: translateX(1.25rem);
    }

    /* Shadow kartu yang konsisten */
    .card-shadow {
      box-shadow: 0 4px 20px -5px rgba(19, 127, 236, 0.1);
    }
  </style>
</head>

<body class="min-h-screen">
  <div class="flex flex-col min-h-screen">
    <header class="sticky top-0 z-50 w-full border-b border-border-light bg-white/90 backdrop-blur-md px-6 py-4">
      <div class="max-w-[1440px] mx-auto flex items-center justify-between">
        <div class="flex items-center gap-10">
          <a href="dashboard.php" class="flex items-center gap-2">
            <img src="img/logo.png" alt="Parahita Logo" class="h-10 w-auto object-contain">
          </a>
          <nav class="hidden md:flex items-center gap-8">
            <a class="text-sm font-medium text-slate-500 hover:text-primary transition-colors" href="dashboard.php">Beranda</a>
            <a class="text-sm font-semibold text-primary" href="pengaturan.php">Pengaturan</a>
            <a class="text-sm font-medium text-slate-500 hover:text-primary transition-colors" href="packages.php">Layanan MCU</a>
            <a class="text-sm font-medium text-slate-500 hover:text-primary transition-colors" href="doctors.php">Dokter</a>
          </nav>
        </div>
        <div class="flex items-center gap-4">
          <img src="img/profile.jpeg" class="size-9 rounded-full border border-border-light object-cover">
        </div>
      </div>
    </header>

    <div class="flex flex-1 max-w-[1440px] mx-auto w-full px-6 py-10 gap-12">
      <aside class="w-64 shrink-0 hidden lg:flex flex-col">
        <div class="mb-8 px-2 flex items-center gap-3">
          <img src="img/profile.jpeg" class="size-10 rounded-full object-cover">
          <div>
            <h3 class="font-bold text-sm text-slate-900"><?= htmlspecialchars($namaUser) ?></h3>
            <p class="text-xs text-slate-500"><?= htmlspecialchars($status) ?></p>
          </div>
        </div>
        <nav class="space-y-1">
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-slate-500" href="profile.php">
            <span class="material-symbols-outlined text-[20px]">person</span>
            <span class="text-sm font-medium">Profil Pribadi</span>
          </a>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-slate-500" href="anggota_keluarga.php">
            <span class="material-symbols-outlined text-[20px]">family_restroom</span>
            <span class="text-sm font-medium">Anggota Keluarga</span>
          </a>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-slate-500" href="payment_method.php">
            <span class="material-symbols-outlined text-[20px]">payments</span>
            <span class="text-sm font-medium">Metode Pembayaran</span>
          </a>
          <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-50 text-primary" href="pengaturan.php">
            <span class="material-symbols-outlined text-[20px]">settings</span>
            <span class="text-sm font-semibold">Pengaturan Umum</span>
          </a>
        </nav>
        <div class="mt-auto pt-6 border-t border-border-light">
          <a href="logout.php" class="flex items-center gap-3 px-3 py-2.5 w-full rounded-lg hover:bg-red-50 text-red-500 transition-colors">
            <span class="material-symbols-outlined text-[20px]">logout</span>
            <span class="text-sm font-medium">Keluar Akun</span>
          </a>
        </div>
      </aside>

      <main class="flex-1">
        <header class="mb-10">
          <h1 class="text-2xl font-bold text-slate-900 mb-1">Pengaturan Umum</h1>
          <p class="text-sm text-slate-500">Kelola preferensi notifikasi dan tampilan aplikasi Anda.</p>
        </header>

        <div class="space-y-8">
          <section class="bg-white rounded-2xl border border-border-light shadow-sm overflow-hidden">
            <div class="p-5 border-b border-border-light bg-slate-50/50">
              <h2 class="text-sm font-bold flex items-center gap-2 text-slate-900 uppercase tracking-wider">
                <span class="material-symbols-outlined text-primary text-xl font-bold">notifications</span>
                Preferensi Notifikasi
              </h2>
            </div>
            <div class="p-6 space-y-6">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                  <div class="size-10 rounded-full bg-blue-50 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-xl">mail</span>
                  </div>
                  <div>
                    <h3 class="text-sm font-bold text-slate-800">Email Notifikasi</h3>
                    <p class="text-xs text-slate-500">Hasil lab dan invoice akan dikirim ke email.</p>
                  </div>
                </div>
                <div class="relative inline-flex items-center cursor-pointer">
                  <input checked class="sr-only switch-checkbox" id="email-notif" type="checkbox" />
                  <label class="switch-label block w-11 h-6 bg-slate-200 rounded-full transition-colors relative" for="email-notif">
                    <span class="switch-dot absolute left-0.5 top-0.5 bg-white size-5 rounded-full shadow-sm transition-transform transform"></span>
                  </label>
                </div>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                  <div class="size-10 rounded-full bg-blue-50 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-xl">chat_bubble</span>
                  </div>
                  <div>
                    <h3 class="text-sm font-bold text-slate-800">WhatsApp</h3>
                    <p class="text-xs text-slate-500">Pengingat jadwal via WhatsApp.</p>
                  </div>
                </div>
                <div class="relative inline-flex items-center cursor-pointer">
                  <input checked class="sr-only switch-checkbox" id="wa-notif" type="checkbox" />
                  <label class="switch-label block w-11 h-6 bg-slate-200 rounded-full transition-colors relative" for="wa-notif">
                    <span class="switch-dot absolute left-0.5 top-0.5 bg-white size-5 rounded-full shadow-sm transition-transform transform"></span>
                  </label>
                </div>
              </div>
            </div>
          </section>

          <section class="bg-white rounded-2xl border border-border-light shadow-sm overflow-hidden">
            <div class="p-5 border-b border-border-light bg-slate-50/50">
              <h2 class="text-sm font-bold flex items-center gap-2 text-slate-900 uppercase tracking-wider">
                <span class="material-symbols-outlined text-primary text-xl font-bold">language</span>
                Bahasa & Tampilan
              </h2>
            </div>
            <div class="p-6 space-y-6">
              <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                  <h3 class="text-sm font-bold text-slate-800">Bahasa Aplikasi</h3>
                  <p class="text-xs text-slate-500">Pilih bahasa untuk antarmuka.</p>
                </div>
                <select class="bg-white border border-border-light rounded-xl text-sm px-4 py-2 focus:ring-primary focus:border-primary w-full md:w-64">
                  <option value="id">Bahasa Indonesia</option>
                  <option value="en">English (US)</option>
                </select>
              </div>
            </div>
          </section>

          <div class="flex justify-end gap-3">
            <button class="px-6 py-2 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
            <button class="px-8 py-2 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-100">Simpan Perubahan</button>
          </div>
        </div>
      </main>
    </div>

    <footer class="bg-white border-t border-slate-200 py-6">
      <div class="max-w-[1440px] mx-auto px-6 text-center text-slate-500 text-sm">
        © 2026 Parahita Diagnostic Center. All rights reserved.
      </div>
    </footer>

  </div>
</body>

</html>