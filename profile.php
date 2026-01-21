<?php
require_once 'functions.php';
requireLogin();

// Inisialisasi variabel
$userId = $_SESSION['user_id'] ?? 0;
$successMessage = '';
$errorMessage   = '';

// --- Ambil data user dari database ---
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('User tidak ditemukan.');
}

// Map data user ke variabel
$namaUser  = $user['full_name'] ?? 'Budi Santoso';
$emailUser = $user['email'] ?? 'budi.santoso@email.com';
$telpUser  = $user['phone'] ?? '+62 812-3456-7890';
$alamat    = $user['address'] ?? 'Jl. Merdeka No. 123, Jakarta';
$status    = $user['membership_status'] ?? 'Premium Member';

// --- Proses Update Profil ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $fullName = $_POST['full_name'] ?? '';
    $email    = $_POST['email'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $address  = $_POST['address'] ?? '';

    if (empty($fullName) || empty($email)) {
        $errorMessage = "Nama dan Email wajib diisi.";
    } else {
        $updateStmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        if ($updateStmt->execute([$fullName, $email, $phone, $address, $userId])) {
            $successMessage = "Profil berhasil diperbarui!";
            // Refresh data terbaru
            $namaUser = $fullName;
            $emailUser = $email;
            $telpUser = $phone;
            $alamat = $address;
        } else {
            $errorMessage = "Gagal memperbarui profil.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Pengaturan: Profil Pribadi | Parahita</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec", // Menggunakan biru Parahita sesuai kodingan sebelumnya
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
        /* Gunakan CSS standar agar editor tidak error */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #0f172a;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* Styling input tanpa @apply */
        input,
        select,
        textarea {
            border-color: #e2e8f0;
            border-radius: 0.75rem;
            /* rounded-xl */
            color: #0f172a;
        }

        input:focus,
        select:focus,
        textarea:focus {
            --tw-ring-color: #137fec;
            /* Warna primary Parahita */
            border-color: #137fec;
            outline: none;
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
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-50 text-primary" href="profile.php">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                        <span class="text-sm font-semibold">Profil Pribadi</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-slate-500" href="anggota_keluarga.php">
                        <span class="material-symbols-outlined text-[20px]">family_restroom</span>
                        <span class="text-sm font-medium">Anggota Keluarga</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-slate-500" href="payment_method.php">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                        <span class="text-sm font-medium">Metode Pembayaran</span>
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

            <main class="flex-1 max-w-3xl">
                <?php if ($successMessage): ?>
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 font-bold">
                        <span class="material-symbols-outlined">check_circle</span> <?= $successMessage ?>
                    </div>
                <?php endif; ?>

                <div class="mb-10">
                    <h1 class="text-2xl font-bold text-slate-900 mb-1">Profil Pribadi</h1>
                    <p class="text-sm text-slate-500">Lengkapi data diri Anda untuk mempermudah proses administrasi medis.</p>
                </div>

                <section class="space-y-10">
                    <div class="flex items-center gap-8 pb-10 border-b border-border-light">
                        <div class="relative group">
                            <img src="img/profile.jpeg" class="size-28 rounded-full border border-border-light object-cover">
                            <button class="absolute -bottom-1 -right-1 bg-white border border-border-light text-slate-600 p-2 rounded-full shadow-sm hover:bg-slate-50 transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </button>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900"><?= htmlspecialchars($namaUser) ?></h2>
                            <p class="text-sm text-slate-500 mb-2">Pasien Terdaftar • Parahita Pro</p>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 border border-primary text-primary rounded"><?= htmlspecialchars($status) ?></span>
                        </div>
                    </div>

                    <form action="" method="POST" class="space-y-8">
                        <input type="hidden" name="action" value="update_profile">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-tight">Nama Lengkap</label>
                                <input name="full_name" class="w-full" type="text" value="<?= htmlspecialchars($namaUser) ?>" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-tight">Alamat Email</label>
                                <input name="email" class="w-full" type="email" value="<?= htmlspecialchars($emailUser) ?>" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-tight">Nomor Telepon</label>
                                <input name="phone" class="w-full" type="tel" value="<?= htmlspecialchars($telpUser) ?>" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-tight">Status Keanggotaan</label>
                                <select class="w-full bg-slate-50 text-slate-500 cursor-not-allowed" disabled>
                                    <option selected><?= htmlspecialchars($status) ?></option>
                                </select>
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-tight">Alamat Rumah</label>
                                <textarea name="address" class="w-full" rows="3"><?= htmlspecialchars($alamat) ?></textarea>
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <a href="pengaturan.php" class="flex items-center gap-2 text-slate-500 hover:text-primary font-medium text-sm transition-colors">
                                <span class="material-symbols-outlined text-lg">key</span> Ganti Kata Sandi
                            </a>
                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <button class="flex-1 sm:flex-none px-6 py-2 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors" type="reset">
                                    Batalkan
                                </button>
                                <button class="flex-1 sm:flex-none px-8 py-2 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200" type="submit">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="material-symbols-outlined text-slate-400 text-xl">info</span>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Data profil Anda akan digunakan untuk keperluan administrasi medis di lab Parahita. Pastikan data yang dimasukkan sesuai dengan identitas resmi (KTP/Passport) Anda.
                        </p>
                    </div>
                </section>
            </main>
        </div>

        <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-auto py-8">
            <div class="max-w-[1440px] mx-auto px-4 text-center text-slate-500 text-sm">
                © 2026 Parahita Diagnostic Center. All rights reserved.
            </div>
    </div>
</body>

</html>