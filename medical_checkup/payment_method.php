<?php
require_once 'functions.php';
// requireLogin(); // Aktifkan jika sudah ada session login

$userId = $_SESSION['user_id'] ?? 1;

// --- Ambil data user untuk Sidebar agar konsisten ---
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
    <title>Metode Pembayaran | Parahita</title>
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
        /* Menggunakan CSS murni agar tidak error di editor */
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
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-50 text-primary" href="payment_method.php">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                        <span class="text-sm font-semibold">Metode Pembayaran</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-slate-500" href="pengaturan.php">
                        <span class="material-symbols-outlined text-[20px]">shield</span>
                        <span class="text-sm font-medium">Pengaturan & Keamanan</span>
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
                <div class="mb-10">
                    <h1 class="text-2xl font-bold text-slate-900 mb-1">Metode Pembayaran</h1>
                    <p class="text-sm text-slate-500">Kelola kartu dan dompet digital Anda untuk transaksi yang lebih mudah.</p>
                </div>

                <div class="space-y-10">
                    <section>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">credit_card</span> Kartu Kredit / Debit
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-6 rounded-2xl border border-border-light bg-white card-shadow relative overflow-hidden">
                                <div class="flex justify-between items-start mb-8">
                                    <div class="size-10 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <span class="material-symbols-outlined text-slate-600">contactless</span>
                                    </div>
                                    <span class="px-2 py-0.5 bg-blue-100 text-primary text-[10px] font-bold rounded uppercase">Utama</span>
                                </div>
                                <div class="space-y-4">
                                    <p class="text-xl font-medium tracking-[0.2em] text-slate-800">•••• •••• •••• 4242</p>
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-[10px] text-slate-400 uppercase font-bold">Pemilik</p>
                                            <p class="text-sm font-bold text-slate-700"><?= strtoupper(htmlspecialchars($namaUser)) ?></p>
                                        </div>
                                        <div class="flex gap-2">
                                            <button class="text-slate-400 hover:text-red-500 transition-colors"><span class="material-symbols-outlined text-xl">delete</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="p-6 rounded-2xl border-2 border-dashed border-slate-200 hover:border-primary hover:bg-blue-50 transition-all flex flex-col items-center justify-center gap-2 group">
                                <span class="material-symbols-outlined text-slate-300 group-hover:text-primary text-3xl">add_circle</span>
                                <span class="text-sm font-bold text-slate-400 group-hover:text-primary">Tambah Kartu Baru</span>
                            </button>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">account_balance_wallet</span> Dompet Digital
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-slate-50 border border-border-light rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 bg-white rounded-lg border border-border-light flex items-center justify-center font-bold text-blue-600">G</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">GoPay</p>
                                        <p class="text-[10px] text-slate-500">Terhubung</p>
                                    </div>
                                </div>
                                <button class="text-[10px] font-bold text-red-500 hover:underline">PUTUS</button>
                            </div>

                            <div class="p-4 bg-slate-50 border border-border-light rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 bg-white rounded-lg border border-border-light flex items-center justify-center font-bold text-purple-600">O</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">OVO</p>
                                        <p class="text-[10px] text-slate-500">Terhubung</p>
                                    </div>
                                </div>
                                <button class="text-[10px] font-bold text-red-500 hover:underline">PUTUS</button>
                            </div>
                        </div>
                    </section>

                    <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <span class="material-symbols-outlined text-primary text-xl">verified_user</span>
                        <p class="text-xs text-blue-700 leading-relaxed italic">
                            Semua data pembayaran Anda disimpan secara terenkripsi sesuai standar keamanan perbankan internasional (PCI-DSS). Parahita tidak menyimpan kode CVV kartu Anda.
                        </p>
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