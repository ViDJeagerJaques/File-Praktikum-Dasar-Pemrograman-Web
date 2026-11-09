-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2026 at 06:56 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `1categories`
--

CREATE TABLE `1categories` (
  `id` int(11) NOT NULL,
  `kode_kategori` varchar(20) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `kategori_induk` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `1categories`
--

INSERT INTO `1categories` (`id`, `kode_kategori`, `nama_kategori`, `kategori_induk`, `deskripsi`, `status`, `created_at`) VALUES
(1, 'KAT001', 'Laptop', 'Elektronik', 'Kategori laptop', 'Aktif', '2026-11-09 16:28:24');

-- --------------------------------------------------------

--
-- Table structure for table `2products`
--

CREATE TABLE `2products` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(30) NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `merek` varchar(100) DEFAULT NULL,
  `harga_jual` decimal(12,2) NOT NULL,
  `harga_modal` decimal(12,2) DEFAULT NULL,
  `stok_awal` int(11) NOT NULL,
  `batas_stok` int(11) DEFAULT 0,
  `berat` int(11) DEFAULT NULL,
  `foto_barang` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `2products`
--

INSERT INTO `2products` (`id`, `kode_barang`, `nama_barang`, `kategori`, `merek`, `harga_jual`, `harga_modal`, `stok_awal`, `batas_stok`, `berat`, `foto_barang`, `deskripsi`, `status`, `created_at`) VALUES
(1, 'BRG001', 'Mouse Wireless', 'Aksesori Komputer', 'Logitech', 150000.00, 100000.00, 3, 5, 150, '', 'Mouse wireless untuk komputer', 'Aktif', '2026-11-09 16:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `3brands`
--

CREATE TABLE `3brands` (
  `id` int(11) NOT NULL,
  `kode_merek` varchar(20) NOT NULL,
  `nama_merek` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `3brands`
--

INSERT INTO `3brands` (`id`, `kode_merek`, `nama_merek`, `keterangan`, `status`, `created_at`) VALUES
(1, 'MRK001', 'Logitech', 'Merek aksesori komputer', 'Aktif', '2026-11-09 16:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `4units`
--

CREATE TABLE `4units` (
  `id` int(11) NOT NULL,
  `kode_satuan` varchar(150) NOT NULL,
  `nama_satuan` varchar(150) NOT NULL,
  `simbol` varchar(150) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `4units`
--

INSERT INTO `4units` (`id`, `kode_satuan`, `nama_satuan`, `simbol`, `keterangan`, `status`, `created_at`) VALUES
(1, 'dad1qd1e', 'ad1dda', 'aadad1q', 'ad1d1d1', 'Aktif', '2026-11-09 17:52:32'),
(2, 'd1d1dad', '1dadq3da', 'dad1dad', '1dad11', 'Aktif', '2026-11-09 17:52:50');

-- --------------------------------------------------------

--
-- Table structure for table `5customers`
--

CREATE TABLE `5customers` (
  `id` int(11) NOT NULL,
  `kode_pelanggan` varchar(150) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `no_telepon` varchar(150) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `alamat_utama` text DEFAULT NULL,
  `status` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `5customers`
--

INSERT INTO `5customers` (`id`, `kode_pelanggan`, `nama_lengkap`, `email`, `no_telepon`, `password`, `alamat_utama`, `status`, `created_at`) VALUES
(1, 'PLG001', 'Andi Saputra', 'andi.test@example.com', '081234567890', '$2y$12$qWcOkfzp/HgQy4OARUmu8eeYwwFZArZWjTP5LwnImRCfnyWiQFyVu', 'Jl. Melati No. 10, Pekanbaru', 'Aktif', '2026-11-09 17:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `6shipping_addresses`
--

CREATE TABLE `6shipping_addresses` (
  `id` int(11) NOT NULL,
  `pelanggan` varchar(150) NOT NULL,
  `label_alamat` varchar(150) NOT NULL,
  `nama_penerima` varchar(150) NOT NULL,
  `no_telepon` varchar(150) NOT NULL,
  `provinsi` varchar(150) NOT NULL,
  `kota` varchar(150) NOT NULL,
  `kecamatan` varchar(150) DEFAULT NULL,
  `alamat_lengkap` text NOT NULL,
  `kode_pos` varchar(150) DEFAULT NULL,
  `alamat_utama` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `7payment_methods`
--

CREATE TABLE `7payment_methods` (
  `id` int(11) NOT NULL,
  `kode_metode` varchar(150) NOT NULL,
  `nama_metode` varchar(150) NOT NULL,
  `jenis` varchar(150) NOT NULL,
  `instruksi_pembayaran` text DEFAULT NULL,
  `biaya_layanan` decimal(12,2) DEFAULT NULL,
  `status` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `8couriers`
--

CREATE TABLE `8couriers` (
  `id` int(11) NOT NULL,
  `kode_kurir` varchar(150) NOT NULL,
  `nama_kurir` varchar(150) NOT NULL,
  `layanan` varchar(150) DEFAULT NULL,
  `kontak` varchar(150) DEFAULT NULL,
  `status` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `9vouchers`
--

CREATE TABLE `9vouchers` (
  `id` int(11) NOT NULL,
  `kode_promo` varchar(150) NOT NULL,
  `nama_promo` varchar(150) NOT NULL,
  `jenis_diskon` varchar(150) NOT NULL,
  `nilai_diskon` decimal(12,2) NOT NULL,
  `minimum_belanja` decimal(12,2) DEFAULT NULL,
  `maksimum_diskon` decimal(12,2) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date NOT NULL,
  `kuota_penggunaan` int(11) DEFAULT NULL,
  `status` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `10taxes`
--

CREATE TABLE `10taxes` (
  `id` int(11) NOT NULL,
  `kode_pajak` varchar(150) NOT NULL,
  `nama_pajak` varchar(150) NOT NULL,
  `persentase` decimal(12,2) NOT NULL,
  `berlaku_mulai` date DEFAULT NULL,
  `status` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `1categories`
--
ALTER TABLE `1categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kategori` (`kode_kategori`);

--
-- Indexes for table `2products`
--
ALTER TABLE `2products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `3brands`
--
ALTER TABLE `3brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_merek` (`kode_merek`);

--
-- Indexes for table `4units`
--
ALTER TABLE `4units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_satuan` (`kode_satuan`);

--
-- Indexes for table `5customers`
--
ALTER TABLE `5customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pelanggan` (`kode_pelanggan`);

--
-- Indexes for table `6shipping_addresses`
--
ALTER TABLE `6shipping_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `7payment_methods`
--
ALTER TABLE `7payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_metode` (`kode_metode`);

--
-- Indexes for table `8couriers`
--
ALTER TABLE `8couriers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kurir` (`kode_kurir`);

--
-- Indexes for table `9vouchers`
--
ALTER TABLE `9vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_promo` (`kode_promo`);

--
-- Indexes for table `10taxes`
--
ALTER TABLE `10taxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pajak` (`kode_pajak`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `1categories`
--
ALTER TABLE `1categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `2products`
--
ALTER TABLE `2products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `3brands`
--
ALTER TABLE `3brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `4units`
--
ALTER TABLE `4units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `5customers`
--
ALTER TABLE `5customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `6shipping_addresses`
--
ALTER TABLE `6shipping_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `7payment_methods`
--
ALTER TABLE `7payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `8couriers`
--
ALTER TABLE `8couriers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `9vouchers`
--
ALTER TABLE `9vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `10taxes`
--
ALTER TABLE `10taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
