<?php
require_once 'config.php';

// Kalau sudah login, langsung lempar ke home
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Error message
$errorMsg = '';
if (isset($_GET['err']) && $_GET['err'] == '1') {
    $errorMsg = 'Email atau password salah.';
}

// ✅ Success message setelah register
$successMsg = '';
if (isset($_GET['registered']) && $_GET['registered'] == '1') {
    $successMsg = 'Akun berhasil dibuat. Silakan login dengan email dan password yang baru kamu daftarkan.';
}
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login - Medical Check-up</title>
    <base href="http://localhost:88/medical_checkup/">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
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
</head>

<body class="font-display">
    <div
        class="relative flex h-auto min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark overflow-hidden">
        <div class="flex h-full grow flex-col w-full">
            <div class="flex flex-1 justify-center items-center p-4 md:p-8">
                <div class="flex w-full max-w-6xl bg-white dark:bg-background-dark shadow-xl rounded-xl overflow-hidden">
                    <div class="w-full flex flex-col md:flex-row">
                        <!-- Left Panel: Image (boleh copy persis dari HTML lama) -->
                        <div class="hidden md:flex md:w-1/2 relative">
                            <div class="w-full bg-center bg-no-repeat bg-cover aspect-auto"
                                data-alt="Abstract image of a modern medical facility interior"
                                style='background-image: url("img/gambar_login.png");'>
                                <div class="absolute inset-0 bg-primary/30"></div>
                                <div class="relative p-12 flex flex-col justify-end h-full text-white">
                                    <h2 class="text-4xl font-bold mb-4">Kenali Tubuhmu,</h2>
                                    <p class="text-2xl font-medium">Mulai Hari Ini dengan Parahita.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Login Form -->
                        <div
                            class="w-full md:w-1/2 flex flex-col justify-center p-8 sm:p-12 lg:p-16 bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-200">
                            <div class="w-full max-w-md mx-auto">
                                <div class="mb-8 flex justify-center">
                                    <div class="h-16 flex items-center">
                                        <img
                                            src="img/logo.png"
                                            alt="Logo Parahita"
                                            class="h-full max-w-[180px] object-contain">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 mb-4">
                                    <p class="text-3xl font-black leading-tight tracking-tighter text-slate-900 dark:text-white">
                                        Welcome Back
                                    </p>
                                    <p class="text-base font-normal leading-normal text-slate-500 dark:text-slate-400">
                                        Log In to Your Account
                                    </p>
                                </div>

                                <?php if ($errorMsg): ?>
                                    <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm px-3 py-2 border border-red-200">
                                        <?= htmlspecialchars($errorMsg) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($successMsg): ?>
                                    <div class="mb-4 rounded-lg bg-green-50 text-green-700 text-sm px-3 py-2 border border-green-200">
                                        <?= htmlspecialchars($successMsg) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($errorMsg): ?>
                                    <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm px-3 py-2 border border-red-200">
                                        <?= htmlspecialchars($errorMsg) ?>
                                    </div>
                                <?php endif; ?>

                                <form class="flex flex-col gap-6" action="login_process.php" method="post">
                                    <label class="flex flex-col w-full">
                                        <p class="text-sm font-medium leading-normal pb-2 text-slate-700 dark:text-slate-200">
                                            Email
                                        </p>
                                        <input
                                            type="email"
                                            name="email"
                                            required
                                            class="form-input flex w-full rounded-lg text-slate-900 dark:text-slate-50 focus:ring-primary/60" />
                                    </label>

                                    <label class="flex flex-col w-full">
                                        <p class="text-sm font-medium leading-normal pb-2 text-slate-700 dark:text-slate-200">
                                            Password
                                        </p>
                                        <input
                                            type="password"
                                            name="password"
                                            required
                                            class="form-input flex w-full rounded-lg text-slate-900 dark:text-slate-50 focus:ring-primary/60" />
                                    </label>

                                    <button type="submit"
                                        class="mt-2 flex w-full cursor-pointer items-center justify-center rounded-lg h-11 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                                        Log In
                                    </button>
                                </form>

                                <p class="mt-6 text-sm text-slate-500 dark:text-slate-400 text-center">
                                    Belum punya akun?
                                    <a href="signup.php" class="text-primary font-semibold hover:underline">Daftar sekarang</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>