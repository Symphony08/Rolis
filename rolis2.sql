-- --------------------------------------------------------
-- Host:                         192.168.1.8
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for rolis2
CREATE DATABASE IF NOT EXISTS `rolis2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `rolis2`;

-- Dumping structure for table rolis2.master
CREATE TABLE IF NOT EXISTS `master` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.master: ~1 rows (approximately)
INSERT INTO `master` (`id`, `nama`, `username`, `password`) VALUES
	(1, 'admin', 'admin', '1234');

-- Dumping structure for table rolis2.merek
CREATE TABLE IF NOT EXISTS `merek` (
  `id_merek` int NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id_merek`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.merek: ~9 rows (approximately)
INSERT INTO `merek` (`id_merek`, `value`) VALUES
	(1, 'Davigo'),
	(2, 'Honda'),
	(3, 'Toyota'),
	(4, 'Tesla'),
	(5, '11'),
	(6, 'ss'),
	(7, 'kisahmy'),
	(8, 'BMW'),
	(10, 'ssr');

-- Dumping structure for table rolis2.model
CREATE TABLE IF NOT EXISTS `model` (
  `id_model` int NOT NULL AUTO_INCREMENT,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_model`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.model: ~3 rows (approximately)
INSERT INTO `model` (`id_model`, `value`) VALUES
	(1, 'kisahmy 150cm'),
	(2, 'Brabus'),
	(4, 'Matic');

-- Dumping structure for table rolis2.pelanggan
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` bigint NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_hp` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_ktp` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tgl_beli` date DEFAULT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.pelanggan: ~11 rows (approximately)
INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `alamat`, `no_hp`, `no_ktp`, `email`, `tgl_beli`, `keterangan`) VALUES
	(12, 'Mikasa Ackerman', 'Jl. Eren No. 1 Samarinda', '085386101950', '33550336', NULL, NULL, NULL),
	(17, 'wawa11', 'jalan3', '121211', '12121223', NULL, NULL, NULL),
	(18, 'Eren', 'wawa', '6285386101930', '1111', NULL, NULL, NULL),
	(19, 'DJ Reze', 'Jl. Reze No. 1 ', '6285283369511', '123', NULL, NULL, NULL),
	(24, 'heta', 'waw', '621123124', '123', NULL, NULL, NULL),
	(25, 'orang baru', 'Jl wawa', '6211111', '1111', NULL, NULL, NULL),
	(26, 'ksaih my', 'jl kisah my', '69696969', NULL, 'kisahmy@gmail.com', '2025-11-19', 'kisha'),
	(27, 'HanyaOrangBiasa', 'Jalan Semangka No. 67', '0098765432', NULL, 'orangbiasa@gmail.com', '2025-11-01', 'Ferrari kapan podium'),
	(28, 'Sucipto', 'Jalan Kemakmuran No. 54', '006354789192', NULL, 'sucipto123@gmail.com', '2024-01-04', 'TES DARI ADMIN'),
	(29, 'Scarlet', 'Jalan Pilgrin No. 30', '009765897815', NULL, 'pilgrin456@gmail.com', '2024-04-12', 'Ban pecah'),
	(30, 'Asep', 'Jalan Hidup No. 78', '007065431203', NULL, 'asep789@gmail.com', '2021-01-16', '-');

-- Dumping structure for table rolis2.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` bigint NOT NULL AUTO_INCREMENT,
  `merek_id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis` enum('MOTOR','SEPEDA') NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` int NOT NULL,
  `foto` varchar(255) NOT NULL,
  PRIMARY KEY (`id_produk`),
  KEY `produk_merek_id_index` (`merek_id`),
  CONSTRAINT `FK_produk_merek` FOREIGN KEY (`merek_id`) REFERENCES `merek` (`id_merek`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.produk: ~4 rows (approximately)
INSERT INTO `produk` (`id_produk`, `merek_id`, `nama`, `jenis`, `deskripsi`, `harga`, `foto`) VALUES
	(58, 1, 'Hertamotor', 'MOTOR', 'hati2 kyut btul', 10000000, '../../uploads/G5SKR0bXcAAuCws_20251117045531.jpeg'),
	(59, 2, 'Sea', 'MOTOR', 'seahonda', 15000000, '../../uploads/sea-1547609_1280_20251118034943.jpg'),
	(60, 2, 'cengeng', 'MOTOR', 'pemboi', 1000, '../../uploads/unnamed (9)_20251118035920.jpg'),
	(61, 2, 'Rugia', 'SEPEDA', 'Rugia', 100000000, '../../uploads/Rukia Kuchiki (2)_20251119033718.jpeg');

-- Dumping structure for table rolis2.servis
CREATE TABLE IF NOT EXISTS `servis` (
  `id_servis` bigint NOT NULL AUTO_INCREMENT,
  `pelanggan_id` bigint NOT NULL,
  `produk_id` bigint DEFAULT NULL,
  `transaksi_id` bigint DEFAULT NULL,
  `keluhan` text NOT NULL,
  `nama_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jenis_produk` enum('MOTOR','SEPEDA') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `merek_produk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `warna_produk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `biaya` int DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `status` enum('PROGRESS','DONE') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_servis`),
  KEY `FK_servis_pelanggan` (`pelanggan_id`),
  KEY `FK_servis_produk` (`produk_id`),
  KEY `FK_servis_transaksi` (`transaksi_id`),
  CONSTRAINT `FK_servis_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_servis_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_servis_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.servis: ~4 rows (approximately)
INSERT INTO `servis` (`id_servis`, `pelanggan_id`, `produk_id`, `transaksi_id`, `keluhan`, `nama_produk`, `jenis_produk`, `merek_produk`, `warna_produk`, `biaya`, `keterangan`, `status`) VALUES
	(45, 17, NULL, NULL, 'wawa', 'wawa', 'SEPEDA', 'wawa', 'wawa', 100000, '0', 'PROGRESS'),
	(56, 25, NULL, NULL, 'ban bocor', 'motor baru', 'SEPEDA', 'Yamaha', 'Biru', 100000, 'fix ban', 'DONE');

-- Dumping structure for table rolis2.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` bigint NOT NULL AUTO_INCREMENT,
  `pelanggan_id` bigint NOT NULL,
  `produk_id` bigint NOT NULL,
  `nomor_mesin` varchar(255) NOT NULL,
  `nomor_body` varchar(255) NOT NULL,
  `warna` varchar(255) NOT NULL,
  `tanggal_garansi` date NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  PRIMARY KEY (`id_transaksi`),
  UNIQUE KEY `nomor_mesin` (`nomor_mesin`),
  UNIQUE KEY `nomor_body` (`nomor_body`),
  KEY `transaksi_pelanggan_id_index` (`pelanggan_id`),
  KEY `transaksi_produk_id_index` (`produk_id`),
  CONSTRAINT `FK_transaksi_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_transaksi_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.transaksi: ~1 rows (approximately)
INSERT INTO `transaksi` (`id_transaksi`, `pelanggan_id`, `produk_id`, `nomor_mesin`, `nomor_body`, `warna`, `tanggal_garansi`, `tanggal_transaksi`) VALUES
	(33, 24, 59, 'AX5000', 'A2304', 'Biru', '2025-11-30', '2025-11-18');

-- Dumping structure for table rolis2.warna
CREATE TABLE IF NOT EXISTS `warna` (
  `id_warna` int NOT NULL AUTO_INCREMENT,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id_warna`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.warna: ~3 rows (approximately)
INSERT INTO `warna` (`id_warna`, `value`) VALUES
	(1, 'Kisah'),
	(2, 'Hitam'),
	(4, 'Hijau'),
	(6, 'Putih');

-- Dumping structure for table rolis2.wa_api
CREATE TABLE IF NOT EXISTS `wa_api` (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `send_wa` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.wa_api: ~1 rows (approximately)
INSERT INTO `wa_api` (`id`, `token`, `send_wa`) VALUES
	(1, 'R2vxHXGYTWhHBXP7P8kMnJwS3PxpLHJUjXMMCnc3VGKLYxHuHK', 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
