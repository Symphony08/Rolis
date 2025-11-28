-- --------------------------------------------------------
-- Host:                         192.168.1.13
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

-- Dumping data for table rolis2.master: ~0 rows (approximately)
INSERT INTO `master` (`id`, `nama`, `username`, `password`) VALUES
	(1, 'admin', 'admin', '1234');

-- Dumping structure for table rolis2.merek
CREATE TABLE IF NOT EXISTS `merek` (
  `id_merek` int NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id_merek`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.merek: ~13 rows (approximately)
INSERT INTO `merek` (`id_merek`, `value`) VALUES
	(1, 'Davigo'),
	(2, 'Honda'),
	(3, 'Toyota'),
	(4, 'Tesla'),
	(5, '11'),
	(6, 'ss'),
	(7, 'kisahmy'),
	(8, 'BMW'),
	(10, 'ssr'),
	(11, 'Yamaha'),
	(12, 'Herta'),
	(18, 'Grandmaster');

-- Dumping structure for table rolis2.model
CREATE TABLE IF NOT EXISTS `model` (
  `id_model` int NOT NULL AUTO_INCREMENT,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_model`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.model: ~6 rows (approximately)
INSERT INTO `model` (`id_model`, `value`) VALUES
	(1, 'kisahmy 150cm'),
	(2, 'Brabus'),
	(4, 'Matic'),
	(5, 'Listrik'),
	(6, 'awaw'),
	(7, 'Herta 190cm'),
	(8, 'Shunguang 190cm');

-- Dumping structure for table rolis2.pelanggan
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` bigint NOT NULL AUTO_INCREMENT,
  `model_id` int DEFAULT NULL,
  `merek_id` int DEFAULT NULL,
  `warna_id` int DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_hp` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_ktp` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `email` varchar(100) DEFAULT NULL,
  `tgl_beli` date DEFAULT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_pelanggan`),
  KEY `model_id` (`model_id`),
  KEY `merek_id` (`merek_id`),
  KEY `warna_id` (`warna_id`),
  CONSTRAINT `FK_pelanggan_merek` FOREIGN KEY (`merek_id`) REFERENCES `merek` (`id_merek`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pelanggan_model` FOREIGN KEY (`model_id`) REFERENCES `model` (`id_model`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pelanggan_warna` FOREIGN KEY (`warna_id`) REFERENCES `warna` (`id_warna`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.pelanggan: ~2 rows (approximately)
INSERT INTO `pelanggan` (`id_pelanggan`, `model_id`, `merek_id`, `warna_id`, `nama`, `alamat`, `no_hp`, `no_ktp`, `email`, `tgl_beli`, `keterangan`) VALUES
	(37, NULL, NULL, NULL, 'wawa', 'wawa', '085386101930', '0', 'wawa@gmail.com', '2025-11-27', 'kisah my'),
	(38, 1, 8, 2, 'wdawd', 'wdawda', '6285386101930', '0', 'wawa22@gmail.com', '2025-11-27', 'asdfasf');

-- Dumping structure for table rolis2.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` bigint NOT NULL AUTO_INCREMENT,
  `merek_id` int NOT NULL,
  `model_id` int NOT NULL DEFAULT '0',
  `warna_id` int NOT NULL DEFAULT (0),
  `jenis` enum('MOTOR','SEPEDA') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'MOTOR',
  `deskripsi` text NOT NULL,
  `harga` int NOT NULL,
  `foto` varchar(255) NOT NULL,
  PRIMARY KEY (`id_produk`),
  KEY `produk_merek_id_index` (`merek_id`),
  KEY `id_model` (`model_id`),
  KEY `warna_id` (`warna_id`),
  CONSTRAINT `FK_produk_merek` FOREIGN KEY (`merek_id`) REFERENCES `merek` (`id_merek`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_produk_model` FOREIGN KEY (`model_id`) REFERENCES `model` (`id_model`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_produk_warna` FOREIGN KEY (`warna_id`) REFERENCES `warna` (`id_warna`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.produk: ~3 rows (approximately)
INSERT INTO `produk` (`id_produk`, `merek_id`, `model_id`, `warna_id`, `jenis`, `deskripsi`, `harga`, `foto`) VALUES
	(66, 1, 5, 4, 'MOTOR', 'Baru', 1000000, '../../uploads/b6e59d3bedcc5715e6252c6bc8b39bde_20251127054239.jpg'),
	(67, 12, 6, 2, 'MOTOR', 'sadasd', 1, '../../uploads/unnamed (3)_20251127070445.png'),
	(68, 2, 6, 1, 'SEPEDA', 'wawa', 10000, '../../uploads/video-capture-t0000.00seg-2655_20251127070534.png'),
	(69, 12, 7, 6, 'SEPEDA', 'genjot', 100000, '../../uploads/G5Y3Ia3aMAAgAox_20251127070637.jpg'),
	(70, 18, 8, 6, 'SEPEDA', 'shunguang', 1000000, '../../uploads/𝘠𝘦 𝘚𝘩𝘶𝘯𝘨𝘶𝘢𝘯𝘨 ★_20251128023429.jpeg');

-- Dumping structure for table rolis2.servis
CREATE TABLE IF NOT EXISTS `servis` (
  `id_servis` bigint NOT NULL AUTO_INCREMENT,
  `pelanggan_id` bigint NOT NULL,
  `produk_id` bigint DEFAULT NULL,
  `transaksi_id` bigint DEFAULT NULL,
  `keluhan` text NOT NULL,
  `jenis_produk` enum('Motor','Sepeda') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `merek_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `model_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
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

-- Dumping data for table rolis2.servis: ~0 rows (approximately)

-- Dumping structure for table rolis2.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` bigint NOT NULL AUTO_INCREMENT,
  `pelanggan_id` bigint NOT NULL,
  `produk_id` bigint NOT NULL,
  `nomor_mesin` varchar(255) NOT NULL,
  `nomor_body` varchar(255) NOT NULL,
  `tanggal_garansi` date NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  PRIMARY KEY (`id_transaksi`),
  UNIQUE KEY `nomor_mesin` (`nomor_mesin`),
  UNIQUE KEY `nomor_body` (`nomor_body`),
  KEY `transaksi_pelanggan_id_index` (`pelanggan_id`),
  KEY `transaksi_produk_id_index` (`produk_id`),
  CONSTRAINT `FK_transaksi_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_transaksi_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.transaksi: ~2 rows (approximately)
INSERT INTO `transaksi` (`id_transaksi`, `pelanggan_id`, `produk_id`, `nomor_mesin`, `nomor_body`, `tanggal_garansi`, `tanggal_transaksi`) VALUES
	(34, 38, 66, '1234', '12451', '2025-11-30', '2025-11-27'),
	(35, 38, 69, '1111', 'wadasd', '2025-12-25', '2025-11-27');

-- Dumping structure for table rolis2.warna
CREATE TABLE IF NOT EXISTS `warna` (
  `id_warna` int NOT NULL AUTO_INCREMENT,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id_warna`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table rolis2.warna: ~4 rows (approximately)
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