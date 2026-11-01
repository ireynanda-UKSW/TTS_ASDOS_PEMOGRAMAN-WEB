-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20261031.6016908886
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 01, 2026 at 01:34 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.36

DROP DATABASE IF EXISTS toko_komputer;
CREATE DATABASE `toko_komputer` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `toko_komputer`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_komputer`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--
CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`) VALUES
(1, 'Laptop'),
(2, 'Komponen'),
(3, 'Periferal'),
(4, 'Monitor'),
(5, 'Penyimpanan'),
(6, 'Jaringan');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--
CREATE TABLE `pesanan` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(12,0) NOT NULL,
  `status` enum('baru','diproses','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baru',
  `dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_item`
--
CREATE TABLE `pesanan_item` (
  `id` int NOT NULL,
  `pesanan_id` int NOT NULL,
  `produk_id` int DEFAULT NULL,
  `nama_produk` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(12,0) NOT NULL,
  `jumlah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--
CREATE TABLE `produk` (
  `id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `nama` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merek` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spesifikasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga` decimal(12,0) NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kategori_id`, `nama`, `merek`, `spesifikasi`, `foto`, `harga`, `stok`, `dibuat`) VALUES
(1, 1, 'Ultrabook 14 inci', 'Lenovo', 'Ryzen 5, RAM 16 GB, SSD 512 GB, layar IPS', 'https://p4-ofp.static.pub//fes/cms/2024/07/17/2oqk2rqj588tdkcz8c8kdpzcrij3j7171606.png', 9499000, 8, '2026-11-01 12:20:37'),
(2, 1, 'Laptop Gaming 15 inci', 'Asus', 'Core i7, RAM 16 GB, SSD 1 TB, RTX 4050', 'https://dlcdnwebimgs.asus.com/gain/1d7ff1ce-d5ed-4184-bbfa-2cee7150b1fc/w800', 17999000, 4, '2026-11-01 12:20:37'),
(3, 1, 'Laptop Pelajar 14 inci', 'Acer', 'Core i3, RAM 8 GB, SSD 256 GB', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQyNVc27EbUMaZuv5C-uGcqR7_4o1MrtRki7J_2gvXNmA&s=10', 5799000, 12, '2026-11-01 12:20:37'),
(4, 2, 'Prosesor 6 core', 'AMD', '6 core 12 thread, AM5, boost hingga 5,0 GHz', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTZ3pdeBBE67O4h3utx4banoQxY-2AAyWer_Nw35pgIKg&s=10', 2899000, 10, '2026-11-01 12:20:37'),
(5, 2, 'RAM DDR5 16 GB', 'Corsair', 'DDR5 5600 MHz, 1 keping 16 GB', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQylxIGIxY814p5Low3R8PKd6PjXcXoFddEI65ez6ujXw&s=10', 849000, 25, '2026-11-01 12:20:37'),
(6, 2, 'Motherboard ATX', 'MSI', 'Socket AM5, DDR5, PCIe 4.0, WiFi', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHukvcl4KCo3og3IWXf0IfLNjmgpePymmgC_E5Q2Womw&s=10', 2599000, 6, '2026-11-01 12:20:37'),
(7, 3, 'Keyboard Mekanikal', 'Keychron', 'Layout 75%, hot-swap, Bluetooth dan kabel', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTq9VVAFJPkJknTYRd6-Rf5RNX7EzI6JFxiBUMgJ9NrWg&s=10', 1349000, 15, '2026-11-01 12:20:37'),
(8, 3, 'Mouse Nirkabel Ringan', 'Logitech', 'Sensor 25K DPI, bobot 60 g, baterai 70 jam', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT6Gpvf8DQd_OT1lNdmzAMLxcScK5uGzBM7TZLcCpe_zg&s', 1099000, 20, '2026-11-01 12:20:37'),
(9, 4, 'Monitor 24 inci IPS', 'LG', 'Full HD, 100 Hz, HDMI, bezel tipis', 'https://www.lg.com/content/dam/channel/wcms/sa_en/images/monitors/24mr400-b/large01.jpg', 1699000, 9, '2026-11-01 12:20:37'),
(10, 4, 'Monitor Lengkung 27 inci', 'Samsung', 'QHD, 165 Hz, panel VA', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTN3jSDMGKxxDQOdCmVISfwznLBMAFkkelQ7okGaLuFGCkZg093LntVMPw&s=10', 3299000, 5, '2026-11-01 12:20:37'),
(11, 5, 'SSD NVMe 1 TB', 'Samsung', 'PCIe 4.0, baca hingga 7.000 MB/s', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQohcGLjxyznDAn9AcDo9XX-ewipKg9FJAVSyd-X_6vSQ&s=10', 1399000, 18, '2026-11-01 12:20:37'),
(12, 6, 'Router WiFi 6', 'TP-Link', 'AX3000, dual band, 4 antena', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSP4aiBF7HyzxlQxwl2IFygj-uWX3D9BAyeILf7l09peA&s=10', 899000, 14, '2026-11-01 12:20:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan_item`
--
ALTER TABLE `pesanan_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan_item`
--
ALTER TABLE `pesanan_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pesanan_item`
--
ALTER TABLE `pesanan_item`
  ADD CONSTRAINT `pesanan_item_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_item_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
