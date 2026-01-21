<?php
require_once 'config.php';

// Kalau sudah login, langsung lempar ke home
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Pesan error sederhana via ?err=1 dll (opsional)
$errorMsg = '';
if (isset($_GET['err']) && $_GET['err'] === 'email') {
    $errorMsg = 'Email sudah terdaftar. Gunakan email lain atau login.';
}
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Sign Up - Health Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script id="tailwind-config">
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
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-[#0d141b] dark:text-slate-50">
    <div class="relative flex min-h-screen w-full flex-col">
        <div class="flex h-full grow flex-col">
            <div class="flex flex-1">
                <div class="flex w-full flex-col lg:flex-row">
                    <!-- Left Column (Branding) -->
                    <div
                        class="relative hidden w-full flex-1 items-center justify-center bg-slate-100 dark:bg-background-dark lg:flex">
                        <div class="absolute inset-0 bg-cover bg-center"
                            data-alt="A modern and clean clinic interior with a smiling doctor in the background"
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBDfBwPl2PBhaZHC_zHsBmUNSXV7fF6g4K9TnNNnxLOD_Zt0OiuPVi1rGXSgwSo0igrZNM53G0Q2M7rEAJdzZH7mAWz2KhrCFyN359HQ-a8Oj41AmVSXXhj72LhCHx6KODK5k_kOSmPUzAJ359L7latJzEphrZkHPJe531dtlcw-twVzw3ClyAUJGBW1zjWlMSkAbvtxvuiwm8Wmg0BzarO3JWZ7CL2Up3jBHl7lIl9Jz8JIHRRsMzNe5Cxw5Vfmmwj5iRsILZC8uE');">
                            <div class="absolute inset-0 bg-primary/20 dark:bg-background-dark/30"></div>
                        </div>
                        <div class="relative z-10 mx-auto max-w-md p-8 text-center text-slate-50">
                            <h1 class="mb-4 text-4xl font-black leading-tight tracking-tighter">
                                Your Health, Simplified.
                            </h1>
                            <p class="text-lg font-normal text-slate-200">
                                Join us to manage your medical records, book appointments, and take control of your well-being.
                            </p>
                        </div>
                    </div>

                    <!-- Right Column (Form) -->
                    <div
                        class="flex w-full flex-1 flex-col items-center justify-center bg-background-light dark:bg-background-dark py-12 lg:py-16">
                        <div class="w-full max-w-md px-6 sm:px-8">

                            <div class="mb-8 text-center lg:text-left">
                                <h1
                                    class="text-3xl font-black leading-tight tracking-tight text-[#0d141b] dark:text-slate-50 sm:text-4xl">
                                    Create Your Account
                                </h1>
                                <h2 class="mt-2 text-base font-normal leading-normal text-slate-600 dark:text-slate-400">
                                    Get access to your personal health dashboard.
                                </h2>
                            </div>

                            <?php if ($errorMsg): ?>
                                <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm px-3 py-2 border border-red-200">
                                    <?= htmlspecialchars($errorMsg) ?>
                                </div>
                            <?php endif; ?>

                            <form class="flex flex-col gap-5" action="signup_process.php" method="post">
                                <label class="flex flex-col w-full">
                                    <p class="text-base font-medium leading-normal pb-2 text-[#0d141b] dark:text-slate-200">
                                        Full Name
                                    </p>
                                    <input
                                        name="full_name"
                                        required
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg
                                           text-[#0d141b] dark:text-slate-50 focus:outline-0 focus:ring-2 focus:ring-primary/70" />
                                </label>

                                <label class="flex flex-col w-full">
                                    <p class="text-base font-medium leading-normal pb-2 text-[#0d141b] dark:text-slate-200">
                                        Email
                                    </p>
                                    <input
                                        type="email"
                                        name="email"
                                        required
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg
                                           text-[#0d141b] dark:text-slate-50 focus:outline-0 focus:ring-2 focus:ring-primary/70" />
                                </label>

                                <label class="flex flex-col w-full">
                                    <p class="text-base font-medium leading-normal pb-2 text-[#0d141b] dark:text-slate-200">
                                        Phone Number
                                    </p>
                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg
                                           text-[#0d141b] dark:text-slate-50 focus:outline-0 focus:ring-2 focus:ring-primary/70" />
                                </label>

                                <label class="flex flex-col w-full">
                                    <p class="text-base font-medium leading-normal pb-2 text-[#0d141b] dark:text-slate-200">
                                        Password
                                    </p>
                                    <input
                                        type="password"
                                        name="password"
                                        required
                                        class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg
                                           text-[#0d141b] dark:text-slate-50 focus:outline-0 focus:ring-2 focus:ring-primary/70" />
                                </label>

                                <button type="submit"
                                    class="mt-4 flex w-full cursor-pointer items-center justify-center rounded-lg h-11 px-5
                                           bg-primary text-white text-base font-bold leading-normal tracking-[0.015em]
                                           hover:bg-primary/90">
                                    Create Account
                                </button>
                            </form>

                            <p class="mt-6 text-sm text-slate-600 dark:text-slate-400 text-center">
                                Sudah punya akun?
                                <a href="login.php" class="text-primary font-semibold hover:underline">Login di sini</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>