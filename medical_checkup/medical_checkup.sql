-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Jan 2026 pada 07.35
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medical_checkup`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `artikel`
--

CREATE TABLE `artikel` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `artikel`
--

INSERT INTO `artikel` (`id`, `title`, `content`, `image_url`, `link`, `created_at`) VALUES
(1, 'Manfaat Minum Air Mineral Saat Berpuasa', 'Air mineral sangat baik untuk dikonsumsi selama berpuasa. Selain mampu memberikan tubuh kita asupan cairan, air yang bersumber dari mata air pegunungan ini juga mengandung berbagai manfaat lain untuk menjaga kesehatan di bulan Ramadan.', 'img/manfaat-minum-air-mineral.jpg', 'https://www.alodokter.com/manfaat-minum-air-mineral-saat-berpuasa', '2026-01-07 01:51:38'),
(2, '9 Manfaat Jogging Pagi bagi Kesehatan Tubuh', 'Manfaat jogging pagi sudah tidak perlu diragukan lagi. Melakukan olahraga di pagi tidak hanya mampu menguatkan fisik, tetapi juga bisa mengurangi stres. Berbagai manfaat tersebut akan terasa jika jogging dilakukan secara rutin sebelum Anda beraktivitas.', 'img/manfaat-jogging.jpg', 'https://www.halodoc.com/artikel/berolahraga-di-pagi-hari-ini-manfaatnya?srsltid=AfmBOor7VUWtTCAeHBXd0xJBLsoYIso9rLMd0jZ3Id3t8HtiB4vCU3Ye', '2026-01-07 01:51:38'),
(4, '9 Manfaat Makan Buah dan Aturan Sehat Mengonsumsinya', 'Padatnya aktivitas menyebabkan sebagian orang melewatkan makan buah karena tidak punya waktu untuk mencuci dan mengupas buah. Padahal, manfaat makan buah sangat banyak, antara lain meningkatkan daya tahan tubuh, membuat kulit lebih glowing, dan mencegah sembelit.', 'img/manfaat-makan-buah.jpg', 'https://www.alodokter.com/9-manfaat-makan-buah-dan-aturan-sehat-mengonsumsinya', '2026-01-07 01:51:38'),
(5, 'Tips Mengelola Stres dengan Baik', 'Mengelola stres dengan baik dapat membantu menjaga kesehatan mental dan meningkatkan kualitas hidup.', NULL, 'https://www.halodoc.com/artikel/cara-mengelola-stres', '2026-01-07 01:51:38'),
(6, 'Pentingnya Menjaga Kesehatan Mental', 'Kesehatan mental sama pentingnya dengan kesehatan fisik untuk menunjang aktivitas sehari-hari.', NULL, 'https://www.sehatq.com/artikel/pentingnya-kesehatan-mental', '2026-01-07 01:51:38'),
(7, 'Manfaat Sarapan Pagi untuk Tubuh', 'Sarapan pagi membantu meningkatkan metabolisme tubuh dan memberikan energi untuk memulai aktivitas.', NULL, 'https://www.alodokter.com/manfaat-sarapan', '2026-01-07 01:51:38'),
(8, 'Bahaya Kurang Aktivitas Fisik', 'Kurangnya aktivitas fisik dapat meningkatkan risiko penyakit kronis seperti obesitas dan penyakit jantung.', NULL, 'https://www.halodoc.com/artikel/bahaya-kurang-bergerak', '2026-01-07 01:51:38'),
(9, 'Tips Menjaga Imunitas Tubuh', 'Menjaga imunitas tubuh dapat dilakukan dengan pola makan sehat, olahraga teratur, dan istirahat cukup.', NULL, 'https://www.sehatq.com/artikel/cara-meningkatkan-imunitas', '2026-01-07 01:51:38'),
(10, 'Pola Hidup Sehat untuk Masa Depan', 'Pola hidup sehat sejak dini membantu mencegah berbagai penyakit dan meningkatkan kualitas hidup.', NULL, 'https://www.alodokter.com/pola-hidup-sehat', '2026-01-07 01:51:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type` enum('doctor','mcu') NOT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `experience` int(11) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `reviews_count` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT 250000,
  `image_url` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `about` text DEFAULT NULL,
  `expertise` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `experience_detail` text DEFAULT NULL,
  `schedule_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`schedule_json`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialization`, `experience`, `location`, `rating`, `reviews_count`, `price`, `image_url`, `is_available`, `about`, `expertise`, `education`, `experience_detail`, `schedule_json`) VALUES
(1, 'Dr. Andi Pratama, Sp.PD', 'Penyakit Dalam', 12, 'Klinik & Laboratorium Parahita Deltasari', 4.9, 156, 250000, 'img/gambar_dokter_cowo.png', 1, 'Dr. Andi Pratama adalah spesialis penyakit dalam senior di Parahita Deltasari. Beliau dikenal sangat teliti dalam mendiagnosa penyakit kronis dan memberikan edukasi pola hidup sehat kepada pasien.', '• Manajemen Diabetes & Hipertensi\n• Gangguan Pencernaan & Lambung\n• Konsultasi Penyakit Ginjal', '• S1 Kedokteran, Universitas Airlangga\n• Spesialis Penyakit Dalam, Universitas Indonesia', NULL, '{\"Senin\":\"08.00 - 14.00\", \"Rabu\":\"08.00 - 14.00\", \"Jumat\":\"08.00 - 14.00\"}'),
(2, 'Dr. Siti Aminah, Sp.A', 'Anak', 8, 'Klinik & Laboratorium Parahita Deltasari', 5.0, 92, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Dr. Siti Aminah memiliki pendekatan yang sangat lembut terhadap anak-anak. Beliau ahli dalam menangani masalah gizi, stunting, serta imunisasi rutin untuk bayi dan balita.', '• Imunisasi & Vaksinasi\n• Tumbuh Kembang Anak\n• Penanganan Infeksi & Demam', '• S1 Kedokteran, Universitas Gadjah Mada\n• Spesialis Anak, Universitas Airlangga', NULL, '{\"Selasa\":\"10.00 - 15.00\", \"Kamis\":\"10.00 - 15.00\", \"Sabtu\":\"09.00 - 12.00\"}'),
(3, 'Dr. Budi Santoso, Sp.JP', 'Jantung', 15, 'Klinik & Laboratorium Parahita Darmo Permai', 4.8, 210, 250000, 'img/gambar_dokter_cowo.png', 1, 'Dr. Budi Santoso spesialis jantung yang fokus pada pencegahan serangan jantung dan rehabilitasi jantung pasca operasi bagi warga di area Darmo Permai.', '• Ekokardiografi\n• Penanganan Jantung Koroner\n• Gagal Jantung Kongestif', '• S1 Kedokteran, Universitas Diponegoro\n• Spesialis Jantung, Universitas Indonesia', NULL, '{\"Senin\":\"13.00 - 18.00\", \"Rabu\":\"13.00 - 18.00\", \"Sabtu\":\"13.00 - 16.00\"}'),
(4, 'Dr. Rina Wati, Sp.M', 'Mata', 6, 'Klinik & Laboratorium Parahita Diponegoro', 4.7, 85, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Dr. Rina Wati melayani pemeriksaan mata menyeluruh, mulai dari gangguan refraksi (minus/silinder) hingga deteksi dini katarak dan glaukoma.', '• Pemeriksaan Tajam Penglihatan\n• Operasi Katarak Phacoemulsification\n• Penanganan Mata Kering & Infeksi', '• S1 Kedokteran, Universitas Padjadjaran\n• Spesialis Mata, Universitas Airlangga', NULL, '{\"Selasa\":\"08.00 - 13.00\", \"Kamis\":\"08.00 - 13.00\", \"Jumat\":\"13.00 - 17.00\"}'),
(5, 'Dr. Bambang Sudjatmiko, Sp.S', 'Saraf', 20, 'Klinik & Laboratorium Parahita Mulyosari', 4.9, 120, 250000, 'img/gambar_dokter_cowo.png', 0, 'Dr. Bambang adalah spesialis saraf yang ahli dalam menangani kasus stroke, saraf terjepit (HNP), dan migrain kronis dengan pendekatan komprehensif.', '• Manajemen Stroke\n• Penanganan Nyeri Saraf terjepit\n• Vertigo & Sakit Kepala Kronis', '• S1 Kedokteran, Universitas Indonesia\n• Spesialis Saraf, Universitas Indonesia', NULL, '{\"Senin\":\"09.00 - 15.00\", \"Rabu\":\"09.00 - 15.00\"}'),
(6, 'Dr. Indah Permata, Drg.', 'Gigi', 5, 'Klinik & Laboratorium Parahita Diponegoro', 4.8, 67, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Drg. Indah Permata fokus pada kesehatan gigi keluarga dan estetika. Beliau sangat memperhatikan kenyamanan pasien saat prosedur pembersihan atau pencabutan.', '• Scaling & Pembersihan Karang Gigi\n• Penambalan Gigi Estetik\n• Gigi Tiruan & Pencabutan', '• Kedokteran Gigi, Universitas Airlangga', NULL, '{\"Selasa\":\"14.00 - 20.00\", \"Kamis\":\"14.00 - 20.00\", \"Sabtu\":\"08.00 - 12.00\"}'),
(7, 'Dr. Hendra Wijaya, Sp.OT', 'Bedah Tulang', 14, 'Klinik & Laboratorium Parahita Diponegoro', 4.9, 143, 250000, 'img/gambar_dokter_cowo.png', 1, 'Dr. Hendra Wijaya spesialis bedah tulang yang sering menangani cedera olahraga, patah tulang, serta nyeri sendi pada lansia.', '• Bedah Ortopedi Umum\n• Penanganan Cedera Tulang & Sendi\n• Osteoporosis & Pengapuran', '• S1 Kedokteran, Universitas Hasanuddin\n• Spesialis Ortopedi, Universitas Indonesia', NULL, '{\"Senin\":\"08.00 - 12.00\", \"Selasa\":\"08.00 - 12.00\", \"Rabu\":\"08.00 - 12.00\"}'),
(8, 'Dr. Larasati, Sp.KK', 'Kulit & Kelamin', 9, 'Klinik & Laboratorium Parahita Deltasari', 4.6, 110, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Dr. Larasati ahli dalam permasalahan kulit wajah seperti jerawat kronis dan flek hitam, serta menangani berbagai alergi kulit pada dewasa maupun anak.', '• Perawatan Jerawat & Bekas Luka\n• Eksim & Dermatitis Alergi\n• Prosedur Estetika Dasar', '• S1 Kedokteran, Universitas Brawijaya\n• Spesialis Kulit & Kelamin, UI', NULL, '{\"Rabu\":\"15.00 - 19.00\", \"Jumat\":\"15.00 - 19.00\", \"Minggu\":\"10.00 - 13.00\"}'),
(9, 'Dr. Faisal Akmal, Sp.THT', 'THT', 7, 'Klinik & Laboratorium Parahita Deltasari', 4.7, 54, 250000, 'img/gambar_dokter_cowo.png', 1, 'Dr. Faisal Akmal menangani gangguan pada telinga, hidung, dan tenggorokan, termasuk masalah amandel dan gangguan pendengaran.', '• Endoskopi THT\n• Pembersihan Telinga Medis\n• Penanganan Sinusitis & Alergi Hidung', '• S1 Kedokteran, Universitas Sebelas Maret\n• Spesialis THT, Universitas Airlangga', NULL, '{\"Senin\":\"16.00 - 21.00\", \"Kamis\":\"16.00 - 21.00\"}'),
(10, 'Dr. Maya Putri, Sp.OG', 'Kandungan', 11, 'Klinik & Laboratorium Parahita Mulyosari', 5.0, 188, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Dr. Maya Putri mendampingi para calon ibu mulai dari program hamil hingga proses persalinan dengan layanan konsultasi yang ramah dan detail.', '• Kontrol Kehamilan (USG 4D)\n• Program Kehamilan & Fertilitas\n• Kesehatan Reproduksi Wanita', '• S1 Kedokteran, Universitas Airlangga\n• Spesialis Obgyn, Universitas Indonesia', NULL, '{\"Selasa\":\"09.00 - 14.00\", \"Jumat\":\"09.00 - 14.00\", \"Sabtu\":\"09.00 - 14.00\"}'),
(11, 'Dr. Gunawan Saputra, Sp.U', 'Urologi', 18, 'Klinik & Laboratorium Parahita Dharmawangsa', 4.8, 95, 250000, 'img/gambar_dokter_cowo.png', 0, 'Dr. Gunawan Saputra ahli dalam menangani masalah saluran kemih, batu ginjal, serta kesehatan prostat pada pria.', '• Penanganan Batu Saluran Kemih\n• Infeksi Saluran Kemih\n• Kesehatan Prostat', '• S1 Kedokteran, Universitas Indonesia\n• Spesialis Urologi, Universitas Indonesia', NULL, '{\"Rabu\":\"10.00 - 16.00\", \"Kamis\":\"10.00 - 16.00\"}'),
(12, 'Dr. Dian Sastro, Sp.Kj', 'Psikiater', 10, 'Klinik & Laboratorium Parahita Dharmawangsa', 4.9, 76, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Dr. Dian Sastro melayani konsultasi kesehatan mental untuk menangani gangguan kecemasan, depresi, serta manajemen stres di lingkungan kerja.', '• Psikoterapi & Konseling\n• Gangguan Kecemasan & Mood\n• Manajemen Stres & Insomnia', '• S1 Kedokteran, Universitas Gadjah Mada\n• Spesialis Kedokteran Jiwa, UI', NULL, '{\"Senin\":\"11.00 - 17.00\", \"Selasa\":\"11.00 - 17.00\", \"Jumat\":\"11.00 - 17.00\"}'),
(13, 'Dr. Erik Kurniawan, Sp.An', 'Anestesi', 13, 'Klinik & Laboratorium Parahita Rungkut', 4.7, 42, 250000, 'img/gambar_dokter_cowo.png', 1, 'Dr. Erik Kurniawan ahli dalam persiapan pembiusan sebelum operasi dan manajemen nyeri kronis pasca tindakan medis.', '• Manajemen Nyeri\n• Sedasi & Pembiusan Lokal\n• Perawatan Intensif', '• S1 Kedokteran, Universitas Airlangga\n• Spesialis Anestesi, Universitas Airlangga', NULL, '{\"Kamis\":\"08.00 - 12.00\", \"Sabtu\":\"08.00 - 12.00\"}'),
(14, 'Dr. Fitri Handayani, Sp.P', 'Paru', 8, 'Klinik & Laboratorium Parahita Darmo Permai', 4.8, 88, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Dr. Fitri Handayani spesialis paru yang fokus pada pengobatan asma, bronkitis, serta pemulihan paru pasca infeksi virus.', '• Manajemen Asma & PPOK\n• Skrining TBC & Infeksi Paru\n• Tes Fungsi Paru (Spirometri)', '• S1 Kedokteran, Universitas Diponegoro\n• Spesialis Paru, Universitas Indonesia', NULL, '{\"Senin\":\"07.00 - 11.00\", \"Rabu\":\"07.00 - 11.00\", \"Jumat\":\"07.00 - 11.00\"}'),
(15, 'Dr. Gading Marten, Sp.B', 'Bedah Umum', 16, 'Klinik & Laboratorium Parahita Deltasari', 4.9, 134, 250000, 'img/gambar_dokter_cowo.png', 1, 'Dr. Gading Marten menangani tindakan bedah umum seperti usus buntu, hernia, dan benjolan pada permukaan tubuh (lipoma/kista).', '• Bedah Minor & Mayor\n• Penanganan Luka Kronis\n• Operasi Usus Buntu & Hernia', '• S1 Kedokteran, Universitas Brawijaya\n• Spesialis Bedah Umum, UI', NULL, '{\"Selasa\":\"15.00 - 20.00\", \"Kamis\":\"15.00 - 20.00\", \"Sabtu\":\"15.00 - 18.00\"}'),
(16, 'Dr. Hesti Purwadinata, Sp.Rad', 'Radiologi', 9, 'Klinik & Laboratorium Parahita Dharmawangsa', 4.7, 31, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Dr. Hesti Purwadinata ahli dalam membaca hasil foto rontgen, CT Scan, dan USG untuk memastikan diagnosis yang akurat bagi pasien.', '• Interpretasi Rontgen & CT Scan\n• Ultrasonografi (USG) Organ Dalam\n• Radiologi Diagnostik', '• S1 Kedokteran, Universitas Indonesia\n• Spesialis Radiologi, Universitas Airlangga', NULL, '{\"Senin\":\"08.00 - 15.00\", \"Selasa\":\"08.00 - 15.00\"}'),
(17, 'Dr. Irfan Hakim, Sp.N', 'Neurologi', 12, 'Klinik & Laboratorium Parahita Dharmawangsa', 4.8, 112, 250000, 'img/gambar_dokter_cowo.png', 1, 'Dr. Irfan Hakim fokus pada saraf tepi, menangani kesemutan kronis, gangguan saraf wajah, serta tremor atau Parkinson.', '• Neuropati (Kesemutan/Nyeri Saraf)\n• Gangguan Gerak & Parkinson\n• Epilepsi & Kejang', '• S1 Kedokteran, Universitas Gadjah Mada\n• Spesialis Neurologi, UI', NULL, '{\"Rabu\":\"13.00 - 17.00\", \"Kamis\":\"13.00 - 17.00\", \"Jumat\":\"13.00 - 17.00\"}'),
(18, 'Dr. Jessica Mila, Sp.GK', 'Gizi Klinik', 5, 'Klinik & Laboratorium Parahita Deltasari', 5.0, 45, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Dr. Jessica Mila membantu pasien menyusun pola makan khusus untuk program diet, penurunan berat badan, atau nutrisi penderita kanker/diabetes.', '• Manajemen Obesitas & Berat Badan\n• Diet Khusus Penyakit Kronis\n• Nutrisi Ibu Hamil', '• S1 Kedokteran, Universitas Airlangga\n• Spesialis Gizi Klinik, Universitas Indonesia', NULL, '{\"Selasa\":\"10.00 - 14.00\", \"Sabtu\":\"10.00 - 14.00\", \"Minggu\":\"10.00 - 14.00\"}'),
(19, 'Dr. Kevin Julio, Sp.BTKV', 'Bedah Jantung', 19, 'Klinik & Laboratorium Parahita Rungkut', 4.9, 167, 250000, 'img/gambar_dokter_cowo.png', 0, 'Dr. Kevin Julio menangani kasus bedah yang melibatkan jantung dan pembuluh darah, termasuk varises parah dan penyumbatan pembuluh darah.', '• Bedah Pembuluh Darah (Vaskular)\n• Penanganan Varises\n• Trauma Dada & Jantung', '• S1 Kedokteran, Universitas Indonesia\n• Spesialis Bedah Jantung, UI', NULL, '{\"Senin\":\"14.00 - 19.00\", \"Rabu\":\"14.00 - 19.00\"}'),
(20, 'Dr. Luna Maya, Sp.JP', 'Jantung', 10, 'Klinik & Laboratorium Parahita Dharmawangsa', 4.9, 142, 250000, 'img/gambar_dokter_perempuan.png', 1, 'Dr. Luna Maya fokus pada kesehatan jantung komunitas dan deteksi dini risiko stroke akibat kelainan irama jantung.', '• Treadmill Test & EKG\n• Gangguan Irama Jantung (Aritmia)\n• Hipertensi & Kolesterol', '• S1 Kedokteran, Universitas Padjadjaran\n• Spesialis Jantung, Universitas Airlangga', NULL, '{\"Kamis\":\"09.00 - 16.00\", \"Jumat\":\"09.00 - 16.00\", \"Sabtu\":\"09.00 - 13.00\"}');

-- --------------------------------------------------------

--
-- Struktur dari tabel `family_members`
--

CREATE TABLE `family_members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `lab_tests` text DEFAULT NULL,
  `consultation` text DEFAULT NULL,
  `heart_radiology` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `packages`
--

INSERT INTO `packages` (`id`, `name`, `description`, `lab_tests`, `consultation`, `heart_radiology`, `notes`, `price`, `created_at`, `category`) VALUES
(1, 'Panel Blue', 'Skrining kesehatan dasar: Hematologi Rutin, Urine Lengkap, Glukosa Puasa, Kolesterol Total, dan Asam Urat.', NULL, NULL, NULL, NULL, 450000.00, '2026-01-07 03:52:24', 'General MCU'),
(2, 'Panel Silver', 'Pemeriksaan menengah: Fungsi Hati (SGOT/SGPT), Fungsi Ginjal (Ureum/Kreatinin), dan Lemak Lengkap (HDL/LDL).', NULL, NULL, NULL, NULL, 750000.00, '2026-01-07 03:52:24', 'General MCU'),
(3, 'Panel Gold', 'Skrining menyeluruh: HBsAg (Hepatitis B), EKG Jantung, dan Rontgen Thorax.', NULL, NULL, NULL, NULL, 1250000.00, '2026-01-07 03:52:24', 'General MCU'),
(4, 'Panel Platinum', 'Layanan premium: USG Abdomen, HbA1c, dan Konsultasi Dokter Spesialis.', NULL, NULL, NULL, NULL, 2500000.00, '2026-01-07 03:52:24', 'General MCU'),
(5, 'Panel Pranikah', 'Persiapan kesehatan calon pengantin: Hematologi, HBsAg, VDRL (Sifilis), dan Urinalisis.', NULL, NULL, NULL, NULL, 850000.00, '2026-01-07 03:52:24', 'Pranikah'),
(6, 'Panel Jantung', 'Fokus deteksi dini penyakit jantung: EKG dan Treadmill Test.', NULL, NULL, NULL, NULL, 1600000.00, '2026-01-07 03:52:24', 'Lifestyle'),
(7, 'Panel Diabetes', 'Pemantauan gula darah: Glukosa Puasa, Glukosa 2 Jam PP, dan HbA1c.', NULL, NULL, NULL, NULL, 500000.00, '2026-01-07 03:52:24', 'Lifestyle'),
(8, 'Panel Fungsi Hati', 'Evaluasi liver: SGOT, SGPT, Gamma GT, Bilirubin, dan HBsAg.', NULL, NULL, NULL, NULL, 600000.00, '2026-01-07 03:52:24', 'General MCU'),
(9, 'Panel Fungsi Ginjal', 'Evaluasi ginjal: Ureum, Kreatinin, Asam Urat, dan Elektrolit.', NULL, NULL, NULL, NULL, 550000.00, '2026-01-07 03:52:24', 'General MCU'),
(10, 'Panel Stroke', 'Skrining risiko stroke: Profil Lemak, Gula Darah, Asam Urat, dan Agregasi Trombosit.', NULL, NULL, NULL, NULL, 1100000.00, '2026-01-07 03:52:24', 'Lifestyle');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_status` enum('pending','success','failed') DEFAULT 'pending',
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `promo`
--

CREATE TABLE `promo` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `membership_status` varchar(50) DEFAULT 'Pasien Reguler'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password_hash`, `phone`, `created_at`, `membership_status`) VALUES
(1, 'Nurhafizah pratiwi', 'nurhafizahpratiwi03@gmail.com', '$2y$10$qJM11MT61I7GvxrVbV0xauZNvInz4uX7IOL.9hXccBpxUAVx1ADR6', '082197400619', '2026-01-06 21:09:35', 'Pasien Reguler'),
(2, 'tiwi', 'tiwi03@gmail.com', '$2y$10$xOwcOJLP7iuPNSACntbF0uNzKn4H2jj1M2ya1kmgtvti8Ob3gy3vS', '089567891234', '2026-01-07 00:42:17', 'Pasien Reguler');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `family_members`
--
ALTER TABLE `family_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indeks untuk tabel `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT untuk tabel `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `family_members`
--
ALTER TABLE `family_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `promo`
--
ALTER TABLE `promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `family_members`
--
ALTER TABLE `family_members`
  ADD CONSTRAINT `family_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
