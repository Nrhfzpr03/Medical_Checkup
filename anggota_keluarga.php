<?php
require_once 'functions.php';
// requireLogin(); // Aktifkan jika sudah ada session login

$userId = $_SESSION['user_id'] ?? 1;

// --- Ambil data user agar Sidebar sama dengan Profil ---
$stmtUser = $pdo->prepare("SELECT full_name FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$userMain = $stmtUser->fetch(PDO::FETCH_ASSOC);

$namaUser = $userMain['full_name'] ?? 'Pengguna';
$status   = 'Pasien Premium'; // Sesuaikan atau ambil dari DB jika sudah ada kolomnya

// --- Logika Hapus ---
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $stmtDelete = $pdo->prepare("DELETE FROM family_members WHERE id = ? AND user_id = ?");
    $stmtDelete->execute([$deleteId, $userId]);
    header("Location: anggota_keluarga.php?status=deleted");
    exit;
}

// --- Ambil data anggota keluarga ---
$stmtFamily = $pdo->prepare("SELECT * FROM family_members WHERE user_id = ? ORDER BY created_at DESC");
$stmtFamily->execute([$userId]);
$familyMembers = $stmtFamily->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Anggota Keluarga | Parahita</title>
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
    <style type="text/tailwindcss">
        /* Gunakan CSS standar untuk menghindari error 'Unknown at rule' di editor */
    body { 
        font-family: 'Inter', sans-serif; 
        background-color: #ffffff; /* Ini pengganti @apply bg-white */
        color: #0f172a;           /* Ini pengganti @apply text-slate-900 */
    }
    .material-symbols-outlined { 
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; 
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
                    <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-50 text-primary" href="anggota_keluarga.php">
                        <span class="material-symbols-outlined text-[20px]">family_restroom</span>
                        <span class="text-sm font-semibold">Anggota Keluarga</span>
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

            <main class="flex-1">
                <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">Anggota Keluarga</h1>
                        <p class="text-sm text-slate-500">Kelola daftar keluarga untuk pemesanan layanan kolektif.</p>
                    </div>
                    <button onclick="window.location.href='tambah_keluarga.php'" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                        <span class="material-symbols-outlined text-lg">person_add</span>
                        Tambah Anggota
                    </button>
                </div>

                <div class="bg-white border border-border-light rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-border-light text-slate-600">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama Anggota</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Hubungan</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">NIK</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light text-sm">
                            <?php if ($familyMembers): ?>
                                <?php foreach ($familyMembers as $member): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-5 font-semibold text-slate-900"><?= htmlspecialchars($member['name']) ?></td>
                                        <td class="px-6 py-5">
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-bold uppercase">
                                                <?= htmlspecialchars($member['relationship']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 font-mono text-slate-500"><?= htmlspecialchars($member['nik']) ?></td>
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex justify-end gap-3">
                                                <a href="edit_keluarga.php?id=<?= $member['id'] ?>" class="text-slate-400 hover:text-primary transition-colors">
                                                    <span class="material-symbols-outlined">edit</span>
                                                </a>
                                                <a href="?delete_id=<?= $member['id'] ?>" onclick="return confirm('Hapus anggota?')" class="text-slate-400 hover:text-red-500 transition-colors">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-slate-400 italic">Belum ada anggota keluarga terdaftar.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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