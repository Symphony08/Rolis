-- --------------------------------------------------------
-- Host:                         192.168.1.14
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

-- Dumping structure for table rolis2.master
CREATE TABLE IF NOT EXISTS `master` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table rolis2.merek
CREATE TABLE IF NOT EXISTS `merek` (
  `id_merek` int NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id_merek`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table rolis2.pelanggan
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` bigint NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table rolis2.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` bigint NOT NULL AUTO_INCREMENT,
  `merek_id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis` enum('MOTOR','SEPEDA') NOT NULL,
  `deskripsi` text NOT NULL,
  `warna` varchar(255) NOT NULL,
  `harga` int NOT NULL,
  `foto` varchar(255) NOT NULL,
  PRIMARY KEY (`id_produk`),
  KEY `produk_merek_id_index` (`merek_id`),
  CONSTRAINT `FK_produk_merek` FOREIGN KEY (`merek_id`) REFERENCES `merek` (`id_merek`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table rolis2.servis
CREATE TABLE IF NOT EXISTS `servis` (
  `id_servis` bigint NOT NULL AUTO_INCREMENT,
  `pelanggan_id` bigint NOT NULL,
  `produk_id` bigint NOT NULL,
  `transaksi_id` bigint DEFAULT NULL,
  `keluhan` text NOT NULL,
  PRIMARY KEY (`id_servis`),
  KEY `FK_servis_pelanggan` (`pelanggan_id`),
  KEY `FK_servis_produk` (`produk_id`),
  KEY `FK_servis_transaksi` (`transaksi_id`),
  CONSTRAINT `FK_servis_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_servis_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_servis_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

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
  KEY `transaksi_pelanggan_id_index` (`pelanggan_id`),
  KEY `transaksi_produk_id_index` (`produk_id`),
  CONSTRAINT `FK_transaksi_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_transaksi_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
