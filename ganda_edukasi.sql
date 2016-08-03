-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 03 Agu 2016 pada 10.40
-- Versi Server: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ganda_edukasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_area`
--

CREATE TABLE `tb_area` (
  `id` int(11) NOT NULL,
  `propinsi_id` int(11) NOT NULL,
  `kabupaten_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_area`
--

INSERT INTO `tb_area` (`id`, `propinsi_id`, `kabupaten_id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Renon', '2016-08-03 05:44:57', NULL),
(2, 1, 2, 'Dalung', '2016-08-03 05:46:21', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_artikel`
--

CREATE TABLE `tb_artikel` (
  `id` int(11) NOT NULL,
  `cover` varchar(50) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `author` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_artikel`
--

INSERT INTO `tb_artikel` (`id`, `cover`, `judul`, `content`, `author`, `created_at`, `updated_at`) VALUES
(1, 'image1.png', 'Article 1 Test', 'test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel ', 2, '2016-07-30 16:38:44', NULL),
(2, 'image2.png', 'Article Test 2', 'test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel test isi artikel ', 2, '2016-07-30 16:39:13', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_cabang`
--

CREATE TABLE `tb_cabang` (
  `id` int(11) NOT NULL,
  `area_id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_cabang`
--

INSERT INTO `tb_cabang` (`id`, `area_id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cabang Renon', '2016-08-03 05:46:46', NULL),
(2, 2, 'Cabang Dalung', '2016-08-03 05:46:54', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_history`
--

CREATE TABLE `tb_history` (
  `id` int(11) NOT NULL,
  `jadwal_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `history_keterangan` mediumtext NOT NULL,
  `tambahan_jam` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_jadwal`
--

CREATE TABLE `tb_jadwal` (
  `id` int(11) NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `jadwal_tempat` varchar(100) NOT NULL,
  `logitude` varchar(100) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` varchar(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_jadwal_pengajar`
--

CREATE TABLE `tb_jadwal_pengajar` (
  `id` int(11) NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `hari` int(11) NOT NULL,
  `jam` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kabupaten`
--

CREATE TABLE `tb_kabupaten` (
  `id` int(11) NOT NULL,
  `id_propinsi` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_kabupaten`
--

INSERT INTO `tb_kabupaten` (`id`, `id_propinsi`, `nama`, `created_at`, `updated_at`) VALUES
(1, 1, 'Denpasar', '2016-08-03 05:44:28', NULL),
(2, 1, 'Badung', '2016-08-03 05:45:36', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_mapel`
--

CREATE TABLE `tb_mapel` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tingkat_pendidikan` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_mapel`
--

INSERT INTO `tb_mapel` (`id`, `nama`, `tingkat_pendidikan`, `created_at`, `updated_at`) VALUES
(1, 'English', 1, '2016-07-29 17:57:14', NULL),
(2, 'Matematika', 1, '2016-07-29 17:57:14', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_mapel_pengajar`
--

CREATE TABLE `tb_mapel_pengajar` (
  `id` int(11) NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pembayaran`
--

CREATE TABLE `tb_pembayaran` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `pengajar_id` int(11) DEFAULT NULL,
  `history_id` int(11) DEFAULT NULL,
  `jenis_tagihan` enum('PROGRAM','JADWAL') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `pembayaran_metode` int(1) NOT NULL COMMENT '1=>Cash, 2=>Transfer Bank',
  `pembayaran_status` int(1) NOT NULL COMMENT '1=>Proses, 2=>Lunas',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pengajar`
--

CREATE TABLE `tb_pengajar` (
  `pengajar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `zona_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `pengajar_alamat` text NOT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `pengajar_cp` char(12) NOT NULL,
  `pengajar_pendidikan` varchar(100) NOT NULL,
  `pengajar_rating` int(11) DEFAULT NULL,
  `status_mengajar` enum('200','400') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_pengajar`
--

INSERT INTO `tb_pengajar` (`pengajar_id`, `user_id`, `zona_id`, `fullname`, `photo`, `pengajar_alamat`, `latitude`, `longitude`, `pengajar_cp`, `pengajar_pendidikan`, `pengajar_rating`, `status_mengajar`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Pengajar Sample', NULL, 'Jalan asdasdasd', NULL, NULL, '085737343647', 'S.Kom', NULL, '200', '2016-07-29 18:13:47', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_program`
--

CREATE TABLE `tb_program` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `biaya` varchar(100) NOT NULL,
  `desk` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_propinsi`
--

CREATE TABLE `tb_propinsi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_propinsi`
--

INSERT INTO `tb_propinsi` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Bali', '2016-08-03 05:44:17', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `siswa_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `zona_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `longitude` varchar(100) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `siswa_cp` varchar(12) NOT NULL,
  `siswa_wali` varchar(100) NOT NULL,
  `siswa_pendidikan` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_siswa`
--

INSERT INTO `tb_siswa` (`siswa_id`, `user_id`, `zona_id`, `fullname`, `photo`, `alamat`, `tempat_lahir`, `tgl_lahir`, `longitude`, `latitude`, `siswa_cp`, `siswa_wali`, `siswa_pendidikan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Hendra Wijaya', '', 'Jalan nangka utara', 'Tabanan', '1994-08-06', '', '', '085737353569', 'Wayan aasd', 6, '2016-07-29 17:57:53', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_tarif`
--

CREATE TABLE `tb_tarif` (
  `id` int(11) NOT NULL,
  `tipe` enum('P1','P2','P3') NOT NULL,
  `harga` int(11) NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `currency` enum('IDR','USD') NOT NULL DEFAULT 'IDR',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_tingkat_pendidikan`
--

CREATE TABLE `tb_tingkat_pendidikan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_tingkat_pendidikan`
--

INSERT INTO `tb_tingkat_pendidikan` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'SMA', '2016-08-03 05:23:07', NULL),
(2, 'SMP', '2016-08-03 05:23:11', NULL),
(3, 'SD', '2016-08-03 05:23:14', NULL),
(4, 'UMUM', '2016-08-03 05:23:19', NULL),
(5, 'SMK', '2016-08-03 05:23:39', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_tingkat_pendidikan_pengajar`
--

CREATE TABLE `tb_tingkat_pendidikan_pengajar` (
  `id` int(11) NOT NULL,
  `pengajar_id` int(11) DEFAULT NULL,
  `tingkat_pendidikan_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_users`
--

CREATE TABLE `tb_users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` int(1) NOT NULL COMMENT '1=>Aktif,0=>Pending',
  `type` enum('AD','PG','SW') NOT NULL COMMENT 'AD=>admin, PG=>Pengajar, SW=>Siswa',
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_users`
--

INSERT INTO `tb_users` (`id`, `email`, `password`, `status`, `type`, `token`, `created_at`, `updated_at`) VALUES
(1, 'siswa@gmail.com', 'd76b4fe16e602bba95a7b43a838c37f1', 1, 'SW', NULL, '2016-07-29 17:08:37', NULL),
(2, 'pengajar@gmail.com', '39242600731811472e4c4e0b6055db8a', 1, 'PG', NULL, '2016-07-29 17:53:36', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_area`
--
ALTER TABLE `tb_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_artikel`
--
ALTER TABLE `tb_artikel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_cabang`
--
ALTER TABLE `tb_cabang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_history`
--
ALTER TABLE `tb_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_jadwal_pengajar`
--
ALTER TABLE `tb_jadwal_pengajar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_kabupaten`
--
ALTER TABLE `tb_kabupaten`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_mapel`
--
ALTER TABLE `tb_mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_mapel_pengajar`
--
ALTER TABLE `tb_mapel_pengajar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pengajar`
--
ALTER TABLE `tb_pengajar`
  ADD PRIMARY KEY (`pengajar_id`);

--
-- Indexes for table `tb_program`
--
ALTER TABLE `tb_program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_propinsi`
--
ALTER TABLE `tb_propinsi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`siswa_id`);

--
-- Indexes for table `tb_tarif`
--
ALTER TABLE `tb_tarif`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_tingkat_pendidikan`
--
ALTER TABLE `tb_tingkat_pendidikan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_tingkat_pendidikan_pengajar`
--
ALTER TABLE `tb_tingkat_pendidikan_pengajar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_area`
--
ALTER TABLE `tb_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_artikel`
--
ALTER TABLE `tb_artikel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_cabang`
--
ALTER TABLE `tb_cabang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_history`
--
ALTER TABLE `tb_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_jadwal`
--
ALTER TABLE `tb_jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_jadwal_pengajar`
--
ALTER TABLE `tb_jadwal_pengajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_kabupaten`
--
ALTER TABLE `tb_kabupaten`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_mapel`
--
ALTER TABLE `tb_mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_mapel_pengajar`
--
ALTER TABLE `tb_mapel_pengajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_pengajar`
--
ALTER TABLE `tb_pengajar`
  MODIFY `pengajar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_program`
--
ALTER TABLE `tb_program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_propinsi`
--
ALTER TABLE `tb_propinsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_tarif`
--
ALTER TABLE `tb_tarif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_tingkat_pendidikan`
--
ALTER TABLE `tb_tingkat_pendidikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tb_tingkat_pendidikan_pengajar`
--
ALTER TABLE `tb_tingkat_pendidikan_pengajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
