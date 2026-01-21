<?php
require_once "config.php";
require_once "functions.php";

// Ambil nama user dari session
$namaUser = $_SESSION['full_name'] ?? 'Pengguna';

// Ambil data dari database dengan PDO
$promo   = $pdo->query("SELECT * FROM promo ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$layanan = $pdo->query("SELECT * FROM layanan ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$doctor  = $pdo->query("SELECT * FROM doctors ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
$artikel = $pdo->query("SELECT * FROM artikel ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// --- LOGIKA WAKTU ---
date_default_timezone_set('Asia/Jakarta');
$hari_ini = date('l');

$hari_indo = [
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu',
    'Sunday'    => 'Minggu'
];

$sekarang = $hari_indo[$hari_ini];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Parahita Style Section - Enhanced Full</title>

    <base href="http://localhost:88/medical_checkup/index.php">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* =======================
           VARIABLES & BASE
           ======================= */
        :root {
            --primary: #0088b2;
            --primary-dark: #005f80;
            --accent: #6FCF97;
            --orange: #ff7b00;
            --orange-dark: #e06d00;
            --light-bg: #f6fcf8;
            --muted: #6c757d;
            --text-dark: #1f2937;
        }

        * {
            box-sizing: border-box
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #f8f9fa 0%, #f1fbff 100%);
            color: #222;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            scroll-behavior: smooth;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        /* =======================
           NAVBAR
           ======================= */
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(7, 32, 63, 0.06);
            padding: 12px 0;
            z-index: 100;
        }

        .navbar-brand img {
            height: 50px;
        }

        .nav-link {
            color: var(--primary) !important;
            font-weight: 600;
            margin: 0 12px;
            position: relative;
            padding-bottom: 8px !important;
            transition: color .25s ease;
        }

        .nav-link::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--orange), var(--primary));
            transition: width .28s ease;
            border-radius: 2px;
        }

        .nav-link:hover {
            color: var(--primary-dark) !important;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-light {
            background-color: #ffffff !important;
            color: var(--primary) !important;
            border: 2px solid var(--primary);
            font-weight: 600;
            border-radius: 50px;
            padding: 8px 22px;
            transition: all .28s ease;
        }

        .btn-light:hover {
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            color: #fff !important;
            box-shadow: 0 8px 25px rgba(0, 136, 178, 0.18);
            transform: translateY(-2px);
        }

        /* =======================
           HERO
           ======================= */
        .hero {
            position: relative;
            display: flex;
            align-items: center;
            min-height: 90vh;
            padding: 80px 0;
            overflow: hidden;
            background: linear-gradient(90deg, rgba(111, 207, 151, 0.06) 0%, rgba(11, 137, 179, 0.03) 50%, rgba(255, 255, 255, 0.6) 100%);
            clip-path: ellipse(100% 95% at 50% 0%);
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.0) 0%, rgba(0, 0, 0, 0.02) 100%);
            z-index: 0;
        }

        .hero-bg {
            position: absolute;
            right: -10%;
            top: -10%;
            width: 70%;
            height: 120%;
            background: url('img/mcu.jpg') center/cover no-repeat;
            filter: saturate(.95) contrast(.98);
            transform-origin: center;
            animation: bgZoom 18s ease-in-out infinite alternate;
            opacity: 0.55;
            z-index: 0;
            border-radius: 30px;
        }

        @keyframes bgZoom {
            from {
                transform: scale(1)
            }

            to {
                transform: scale(1.06)
            }
        }

        .hero .container {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3.4rem;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.08;
            margin-bottom: 18px;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero p {
            color: #555;
            font-size: 1.12rem;
            max-width: 600px;
            margin-bottom: 24px;
        }

        .btn-orange {
            background: linear-gradient(90deg, var(--orange), var(--orange-dark));
            color: #fff;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(255, 123, 0, 0.18);
            border: none;
            transition: all .28s ease;
        }

        .btn-orange:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 16px 40px rgba(224, 109, 0, 0.18)
        }

        @media (max-width:992px) {
            .hero {
                padding: 60px 18px;
                text-align: center;
                clip-path: none;
                min-height: auto;
            }

            .hero-bg {
                display: none;
            }

            .hero h1 {
                font-size: 2.4rem;
            }
        }

        /* =======================
           SECTION TITLES
           ======================= */
        .section-title {
            color: var(--text-dark);
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 6px;
            text-align: center;
        }

        .section-subtitle-promo {
            color: var(--muted);
            font-size: 1.05rem;
            margin-bottom: 36px;
            text-align: center;
        }

        /* =======================
           PROMO / PAKET
           ======================= */
        #promo-paket {
            padding: 80px 0;
            background: #fff;
        }

        .card-paket {
            border: none;
            background: linear-gradient(180deg, rgba(111, 207, 151, 0.04), rgba(255, 255, 255, 0.9));
            box-shadow: 0 8px 30px rgba(3, 36, 63, 0.04);
            border-radius: 12px;
            padding: 22px;
            transition: transform .32s ease, box-shadow .32s ease;
            position: relative;
            min-height: 230px;
        }

        .card-paket img {
            transform: none !important;
            transition: none !important;
        }

        .card-paket:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 45px rgba(3, 36, 63, 0.12)
        }

        .ribbon {
            position: absolute;
            top: 14px;
            right: 14px;
            background: linear-gradient(90deg, var(--orange), var(--orange-dark));
            color: #fff;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: .85rem;
            box-shadow: 0 6px 18px rgba(224, 109, 0, 0.18);
        }

        /* carousel tweaks */
        #promoCarousel {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(3, 36, 63, 0.06)
        }

        .carousel-indicators [data-bs-target] {
            background-color: var(--primary);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 6px;
            opacity: .55
        }

        .carousel-indicators .active {
            background-color: var(--orange);
            opacity: 1;
            transform: scale(1.05)
        }

        /* =======================
           LAYANAN (SERVICE)
           ======================= */
        #layanan {
            padding: 80px 0;
            background: linear-gradient(180deg, #f8f9fa 0%, #f7feff 100%);
        }

        .card-service {
            border: 1px solid rgba(7, 32, 63, 0.04);
            transition: all .28s ease;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            min-height: 220px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.9), rgba(241, 251, 255, 0.8));
        }

        .card-service:hover {
            border-color: var(--primary);
            box-shadow: 0 12px 30px rgba(0, 136, 178, 0.08);
            transform: translateY(-8px);
        }

        .card-service img {
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.06));
            width: 60px;
            height: auto;
            margin: 0 auto 10px;
        }

        /* floating micro-animation for icons (rule 2) */
        @keyframes float {
            0% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-8px)
            }

            100% {
                transform: translateY(0)
            }
        }


        /* =======================
           DOKTER
           ======================= */
        #doctor {
            padding: 80px 0;
            background: #fff;
        }

        .card-doctor {
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            transition: box-shadow .28s ease, transform .28s ease;
            min-height: 220px;
        }

        .card-doctor:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(3, 36, 63, 0.08);
        }

        .card-doctor img {
            width: 90px;
            height: auto;
            margin-bottom: 10px;
        }

        /* =======================
           LOKASI
           ======================= */
        .card-location {
            border-left: 5px solid var(--primary);
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(3, 36, 63, 0.04);
            padding: 18px;
            background: #8ac042;
        }

        .map-responsive {
            overflow: hidden;
            padding-bottom: 56.25%;
            position: relative;
            height: 0;
            border-radius: 8px;
        }

        .map-responsive iframe {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            border: 0;
            border-radius: 8px;
        }

        /* =======================
           ARTIKEL
           ======================= */
        #artikel .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: transform .28s ease, box-shadow .28s ease;
        }

        #artikel .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(3, 36, 63, 0.06);
        }

        #artikel .card-img-top {
            height: 180px;
            object-fit: cover;
        }

        /* =======================
           KONTAK / FORM
           ======================= */
        #kontak .card {
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(3, 36, 63, 0.04);
        }

        /* =======================
           FOOTER
           ======================= */
        footer {
            background: linear-gradient(90deg, var(--primary-dark), var(--primary));
            color: #fff;
            padding: 24px 0;
        }

        footer a {
            color: #fff;
            transition: color .2s
        }

        footer a:hover {
            color: var(--orange)
        }

        .modal-header {
            border-bottom: 3px solid var(--orange)
        }

        /* =======================
           ANIMATIONS & STAGGER (rule 1)
           ======================= */
        [data-animate] {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .6s ease, transform .6s ease;
        }

        [data-animate].visible {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger>* {
            opacity: 0;
            transform: translateY(18px);
            transition: all .5s cubic-bezier(.2, .9, .3, 1);
        }

        .stagger.visible>* {
            opacity: 1;
            transform: translateY(0);
        }

        /* specific stagger delays for rows with known columns (services, doctors, pakets, artikel) */
        /* promo-paket row: 3 columns */
        #promo-paket .stagger>.col-md-4:nth-child(1) {
            transition-delay: .06s;
        }

        #promo-paket .stagger>.col-md-4:nth-child(2) {
            transition-delay: .14s;
        }

        #promo-paket .stagger>.col-md-4:nth-child(3) {
            transition-delay: .22s;
        }

        /* layanan row: 4 columns */
        #layanan .stagger>.col-md-3:nth-child(1) {
            transition-delay: .06s;
        }

        #layanan .stagger>.col-md-3:nth-child(2) {
            transition-delay: .14s;
        }

        #layanan .stagger>.col-md-3:nth-child(3) {
            transition-delay: .22s;
        }

        #layanan .stagger>.col-md-3:nth-child(4) {
            transition-delay: .30s;
        }

        /* dokter row: 4 columns */
        #doctor .stagger>.col-md-3:nth-child(1) {
            transition-delay: .06s;
        }

        #doctor .stagger>.col-md-3:nth-child(2) {
            transition-delay: .14s;
        }

        #doctor .stagger>.col-md-3:nth-child(3) {
            transition-delay: .22s;
        }

        #doctor .stagger>.col-md-3:nth-child(4) {
            transition-delay: .30s;
        }

        /* artikel row: 3 columns */
        #artikel .stagger>.col-md-4:nth-child(1) {
            transition-delay: .06s;
        }

        #artikel .stagger>.col-md-4:nth-child(2) {
            transition-delay: .14s;
        }

        #artikel .stagger>.col-md-4:nth-child(3) {
            transition-delay: .22s;
        }

        /* kontak & lokasi small delays */
        #lokasi .stagger>.col-md-4:nth-child(1) {
            transition-delay: .06s;
        }

        #lokasi .stagger>.col-md-4:nth-child(2) {
            transition-delay: .14s;
        }

        #lokasi .stagger>.col-md-4:nth-child(3) {
            transition-delay: .22s;
        }

        /* visible class applied by JS will cause transitions above to run */
        .stagger.visible>* {
            opacity: 1;
            transform: translateY(0);
        }

        /* small responsive */
        @media (max-width:768px) {
            .section-title {
                font-size: 1.8rem;
            }

            .hero h1 {
                font-size: 2.4rem;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img/logo.png" alt="Logo Parahita">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link" href="login.php">Booking MCU</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Konsultasi Dokter</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Interpretasi Hasil</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Riwayat MCU</a></li>
                    <li class="nav-item ms-3">
                        <a href="login.php" class="btn btn-light">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-bg" aria-hidden="true"></div>
        <div class="container" data-animate>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Kenali Tubuhmu,<br>Jaga Sehatmu<br><span>Bersama Parahita</span></h1>
                    <p>Dengan pengalaman lebih dari 18 tahun, Klinik Parahita hadir sebagai <strong>Teman Sehat</strong> Anda. Layanannya terpercaya, cepat, dan akurat.</p>
                    <div class="d-flex gap-3">
                        <button class="btn btn-orange">Download Katalog</button>
                        <a href="#promo-paket" class="btn btn-light">Lihat Promo</a>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 d-none d-lg-block">
                    <!-- Optional hero right column (kept empty so you can place an illustration or card) -->
                </div>
            </div>
        </div>
    </section>

    <main class="flex-grow-1">

        <!-- PROMO PAKET -->
        <section id="promo-paket" class="py-5" data-animate>
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title">Saatnya Deteksi Dini, Kantong Tetap Happy!</h2>
                    <p class="section-subtitle-promo">Nikmati berbagai promo pemeriksaan kesehatan dengan harga spesial</p>
                </div>

                <div class="row g-4 stagger">
                    <div class="col-md-4">
                        <div class="card card-paket h-100">
                            <div class="ribbon">NEW!</div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon"><img src="img/jantung.png" alt="jantung" width="44"></div>
                                <div>
                                    <h5 class="mb-0">Skrining Jantung Dasar</h5>
                                    <small class="text-muted">Deteksi risiko penyakit kardiovaskular</small>
                                </div>
                            </div>
                            <p class="mb-3">Pemeriksaan profil lipid lengkap, EKG (rekam jantung), dan konsultasi dengan dokter umum.</p>
                            <div class="mt-auto d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">Detail</a>
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookingModal">Booking Online</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-paket h-100">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon"><img src="img/checkup.png" alt="checkup" width="44"></div>
                                <div>
                                    <h5 class="mb-0">General Checkup</h5>
                                    <small class="text-muted">Paket pemeriksaan rutin</small>
                                </div>
                            </div>
                            <p class="mb-3">Cocok untuk skrining tahunan keluarga — cepat dan terjangkau.</p>
                            <div class="mt-auto d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">Detail</a>
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookingModal">Booking Online</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-paket h-100">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon"><img src="img/nikah.png" alt="pranikah" width="44"></div>
                                <div>
                                    <h5 class="mb-0">Paket Pranikah</h5>
                                    <small class="text-muted">Pemeriksaan lengkap untuk calon pengantin</small>
                                </div>
                            </div>
                            <p class="mb-3">Termasuk pemeriksaan infeksi menular, hemoglobin, golongan darah, dan lainnya.</p>
                            <div class="mt-auto d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">Detail</a>
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookingModal">Booking Online</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- LAYANAN -->
        <section id="layanan" class="py-5" data-animate>
            <div class="container">
                <h2 class="section-title text-center">Layanan Parahita</h2>
                <p class="text-center text-muted mb-4">Layanan kami mudah diakses, baik di klinik maupun layanan home service.</p>
                <div class="row g-4 stagger">

                    <div class="col-md-3">
                        <div class="card card-service p-4 h-100 text-center shadow-sm">
                            <div class="mb-3 d-flex justify-content-center align-items-center" style="height: 70px;">
                                <img src="img/booking_mcu.png" width="60" alt="Icon Booking Online" class="img-fluid">
                            </div>
                            <h6 class="fw-bold">Booking MCU</h6>
                            <p class="text-muted small">Pesan Medical Check Up Anda dengan cepat dan pilih jadwal yang tersedia.</p>
                            <a href="login.php" class="btn btn-sm btn-outline-primary mt-auto">Pesan Sekarang</a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-service p-4 h-100 text-center shadow-sm">
                            <div class="mb-3 d-flex justify-content-center align-items-center" style="height: 70px;">
                                <img src="img/konsultasi_dokter.png" width="60" alt="Icon Konsultasi Dokter" class="img-fluid">
                            </div>
                            <h6 class="fw-bold">Konsultasi Dokter</h6>
                            <p class="text-muted small">Konsultasi hasil atau keluhan kesehatan langsung dengan dokter ahli.</p>
                            <a href="login.php" class="btn btn-sm btn-outline-primary mt-auto">Mulai Konsultasi</a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-service p-4 h-100 text-center shadow-sm">
                            <div class="mb-3 d-flex justify-content-center align-items-center" style="height: 70px;">
                                <img src="img/interpretasi_hasil.png" width="60" alt="Icon Interpretasi Hasil" class="img-fluid">
                            </div>
                            <h6 class="fw-bold">Interpretasi Hasil</h6>
                            <p class="text-muted small">Lihat dan pahami hasil pemeriksaan lab Anda secara digital.</p>
                            <a href="login.php" class="btn btn-sm btn-outline-primary mt-auto">Akses Hasil</a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-service p-4 h-100 text-center shadow-sm">
                            <div class="mb-3 d-flex justify-content-center align-items-center" style="height: 70px;">
                                <img src="img/Riwayat.png" width="60" alt="Icon Riwayat MCU" class="img-fluid">
                            </div>
                            <h6 class="fw-bold">Riwayat MCU</h6>
                            <p class="text-muted small">Catatan riwayat kesehatan Anda tersimpan aman dan terintegrasi.</p>
                            <a href="login.php" class="btn btn-sm btn-outline-primary mt-auto">Cek Riwayat</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- DOKTER -->
        <section id="doctor" class="py-5">
            <div class="container">
                <h2 class="section-title text-center">Jadwal Dokter & Tenaga Medis</h2>
                <p class="text-center text-muted mb-4">Hari ini: <strong><?= $sekarang; ?></strong></p>

                <div class="row g-4 mt-3">
                    <?php
                    // 1. Filter dokter yang praktik HARI INI saja
                    $doctor_on_duty = [];
                    if (isset($doctor) && !empty($doctor)) {
                        foreach ($doctor as $d) {
                            $jadwal = json_decode($d['schedule_json'], true);
                            // Jika hari ini ada di dalam jadwal, masukkan ke daftar dokter yang bertugas
                            if ($jadwal && isset($jadwal[$sekarang])) {
                                $doctor_on_duty[] = $d;
                            }
                        }
                    }

                    // 2. Batasi hanya maksimal 4 dokter saja yang diambil dari daftar tadi
                    $limit_doctor = array_slice($doctor_on_duty, 0, 4);

                    // 3. Loop dokter yang sudah difilter dan dibatasi
                    if (!empty($limit_doctor)):
                        foreach ($limit_doctor as $row):
                            $jadwal_data = json_decode($row['schedule_json'], true);
                            $jam_hari_ini = $jadwal_data[$sekarang];
                    ?>
                            <div class="col-md-3">
                                <div class="card card-doctor p-3 text-center h-100 border-primary shadow">
                                    <div class="mb-2">
                                        <span class="badge bg-success">Tersedia</span>
                                    </div>

                                    <div class="img-wrapper mb-2 mx-auto" style="width: 100px; height: 100px; overflow: hidden; border-radius: 50%;">
                                        <img src="<?= htmlspecialchars($row['image_url']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>

                                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($row['name']) ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($row['specialization']) ?></small>

                                    <div class="mt-2 p-2 rounded small bg-primary text-white">
                                        <strong>Jam Praktik: <?= $jam_hari_ini ?></strong>
                                    </div>

                                    <div class="mt-auto pt-3">
                                        <a href="https://wa.me/6281133326888?text=Halo, saya ingin booking <?= urlencode($row['name']) ?>"
                                            class="btn btn-sm btn-primary w-100">
                                            Booking Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <div class="col-12 text-center">
                            <p class="alert alert-info">Tidak ada jadwal dokter yang tersedia untuk hari <?= $sekarang; ?>.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- LOKASI -->
        <section id="lokasi" class="py-5" data-animate>
            <div class="container">
                <h2 class="section-title text-center">Cabang & Lokasi</h2>
                <div class="row g-4 mt-4">
                    <div class="col-md-4">
                        <div class="card card-location p-3 h-100">
                            <h6 class="fw-bold text-primary">Parahita Surabaya Citraland</h6>
                            <p class="mb-1">Jl. Puri Widya Kencana K-7/16, Surabaya</p>
                            <p class="mb-1"><strong>Telp:</strong> (031) 3592-3010</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Lihat Rute</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-location p-3 h-100">
                            <h6 class="fw-bold text-primary">Parahita Sidoarjo Deltasari</h6>
                            <p class="mb-1">Ruko Deltasari Indah Blok AQ No.10, Sidoarjo</p>
                            <p class="mb-1"><strong>Telp:</strong> (031) 8550-810</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Lihat Rute</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-location p-3 h-100">
                            <h6 class="fw-bold text-primary">Parahita Tangerang Bintaro</h6>
                            <p class="mb-1">Ruko Emerald Avenue 1, Jl. Boulevard Bintaro</p>
                            <p class="mb-1"><strong>Telp:</strong> (021) 3889-7024</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Lihat Rute</a>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="map-responsive">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!..." allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ARTIKEL -->
        <section id="artikel" class="py-5 bg-white" data-animate>
            <div class="container">
                <h2 class="section-title text-center">Info Kesehatan & Artikel</h2>

                <div class="row g-4 mt-3 stagger">
                    <?php
                    if (!empty($artikel)):
                        // Mengambil maksimal 3 artikel terbaru
                        $artikel_terbaru = array_slice($artikel, 0, 3);

                        foreach ($artikel_terbaru as $row):
                            // Cek apakah data image_url ada, jika tidak gunakan gambar default
                            // Karena di database sudah ada teks 'img/', kita langsung panggil $row['image_url']
                            $gambar = (!empty($row['image_url'])) ? $row['image_url'] : 'img/default-article.jpg';
                    ?>
                            <div class="col-md-4">
                                <article class="card h-100 shadow-sm border-0">
                                    <div style="height: 200px; overflow: hidden;">
                                        <img
                                            src="<?= htmlspecialchars($gambar) ?>"
                                            class="card-img-top"
                                            alt="<?= htmlspecialchars($row['title']) ?>"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">
                                            <?= htmlspecialchars($row['title']) ?>
                                        </h5>
                                        <p class="card-text small text-muted">
                                            <?= htmlspecialchars(substr(strip_tags($row['content']), 0, 80)) ?>...
                                        </p>
                                        <a href="<?= htmlspecialchars($row['link']) ?>"
                                            class="btn btn-sm btn-outline-primary w-100"
                                            target="_blank">
                                            Baca Selengkapnya
                                        </a>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-center text-muted">Belum ada artikel.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- TENTANG -->
        <section id="tentang" class="py-5" data-animate style="display: none;">
            <div class="container">
                <h2 class="section-title text-center">Tentang Lab Parahita</h2>
                <div class="row align-items-center mt-4">
                    <div class="col-md-6">
                        <p>Lab Parahita menyediakan layanan diagnostik dan pemeriksaan kesehatan lengkap dengan standar laboratorium modern, tenaga medis berpengalaman, dan jaringan cabang luas di Indonesia. Fokus kami: hasil akurat, layanan cepat, dan kepuasan pasien.</p>
                        <ul>
                            <li>Peralatan diagnostik modern</li>
                            <li>Tenaga kesehatan tersertifikasi</li>
                            <li>Pelayanan home service</li>
                            <li>Paket kesehatan untuk individu & perusahaan</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <img src="https://images.unsplash.com/photo-1580281657521-1d5c0c8d1fbd?auto=format&fit=crop&w=900&q=80" class="img-fluid rounded" alt="tentang lab parahita">
                    </div>
                </div>
            </div>
        </section>


        <!-- KONTAK -->
        <section id="kontak" class="py-5 bg-white" data-animate>
            <div class="container">
                <h2 class="section-title text-center">Hubungi & Booking</h2>
                <div class="row g-4 mt-3">
                    <div class="col-md-6">
                        <div class="card p-4">
                            <h5 class="fw-bold">Konsultasi & Booking</h5>
                            <p class="text-muted">Hubungi kami lewat WhatsApp untuk konsultasi cepat atau gunakan form booking untuk memilih cabang & paket.</p>

                            <p class="mb-1"><strong>WhatsApp:</strong> <a href="https://wa.me/6281133326888" target="_blank">0811 333 26 888</a></p>
                            <p class="mb-1"><strong>Email:</strong> <a href="mailto:medical@parahita.co.id">medical@parahita.co.id</a></p>
                            <p class="mb-1"><strong>Telepon:</strong> (031) 567-8910</p>

                            <div class="mt-3">
                                <a href="https://wa.me/6281133326888" class="btn btn-success">Konsultasi via WhatsApp</a>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal">Booking Online</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <form id="contactForm" class="card p-4" style="display: none;">
                            <h5 class="fw-bold">Kirim Pesan</h5>
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" id="cfName" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="cfEmail" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pesan</label>
                                <textarea class="form-control" id="cfMessage" rows="4" required></textarea>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-outline-primary" type="submit">Kirim Pesan</button>
                            </div>
                            <div id="cfAlert" class="mt-3" style="display:none;"></div>
                        </form>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <img src="img/logo.png" alt="logo" width="46" height="46" class="rounded">
                        <div>
                            <div class="fw-bold">Lab Parahita</div>
                            <small>Laboratorium Diagnostik & Layanan Kesehatan</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <small>© 2025 Lab Parahita • Semua hak cipta dilindungi</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- BOOKING MODAL -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="bookingForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Pemeriksaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telepon / WA</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Cabang</label>
                        <select name="branch" class="form-select" required>
                            <option value="">-- Pilih Cabang --</option>
                            <option>Surabaya Citraland</option>
                            <option>Sidoarjo Deltasari</option>
                            <option>Tangerang Bintaro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Paket</label>
                        <select name="package" class="form-select" required>
                            <option value="">-- Pilih Paket --</option>
                            <option>General Checkup</option>
                            <option>Healthy Lifestyle</option>
                            <option>Paket Pranikah</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Preferensi</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="note" rows="3" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Booking</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // -------------------------
        // Contact form (no backend)
        // -------------------------
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const name = document.getElementById('cfName').value.trim();
            const email = document.getElementById('cfEmail').value.trim();
            const msg = document.getElementById('cfMessage').value.trim();

            const alertEl = document.getElementById('cfAlert');
            alertEl.style.display = 'block';
            alertEl.className = 'alert alert-success';
            alertEl.innerText = 'Terima kasih, pesan Anda telah dikirim. Kami akan membalas melalui email/WA.';

            this.reset();
        });

        // -------------------------
        // Booking form -> WA
        // -------------------------
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = new FormData(this);
            const name = form.get('name') || '-';
            const phone = form.get('phone') || '-';
            const branch = form.get('branch') || '-';
            const pack = form.get('package') || '-';
            const date = form.get('date') || '-';
            const note = form.get('note') || '-';

            const text = `Booking%20Lab%20Parahita%0A%0ANama:%20${encodeURIComponent(name)}%0ATelepon:%20${encodeURIComponent(phone)}%0ACabang:%20${encodeURIComponent(branch)}%0APaket:%20${encodeURIComponent(pack)}%0ATanggal:%20${encodeURIComponent(date)}%0ANote:%20${encodeURIComponent(note)}%0A%0AMohon%20konfirmasi.`;
            const waNumber = '6281133326888';
            const waLink = `https://wa.me/${waNumber}?text=${text}`;

            window.open(waLink, '_blank');

            // hide modal
            try {
                bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
            } catch (err) {
                /* ignore */
            }
        });

        // -------------------------
        // IntersectionObserver for data-animate & staggered rows
        // -------------------------
        (function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        // if it's a stagger, add visible to trigger child delays
                        if (entry.target.classList.contains('stagger')) {
                            entry.target.classList.add('visible');
                            // also add visible class to each direct child so CSS nth-child delays apply
                            [...entry.target.children].forEach(child => child.classList.add('visible'));
                        }
                        // optional: unobserve after visible to avoid re-triggering
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12
            });

            // observe all data-animate sections and stagger rows
            document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
            document.querySelectorAll('.stagger').forEach(el => observer.observe(el));
        })();

        // -------------------------
        // mobile navbar toggler visual
        // -------------------------
        document.querySelectorAll('.navbar-toggler').forEach(btn => {
            btn.addEventListener('click', () => btn.classList.toggle('open'));
        });
    </script>
</body>

</html>