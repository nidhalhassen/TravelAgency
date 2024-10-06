-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table project.agence
CREATE TABLE IF NOT EXISTS `agence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) DEFAULT NULL,
  `nom_agence` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `agence_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table project.agence: ~0 rows (approximately)
INSERT INTO `agence` (`id`, `utilisateur_id`, `nom_agence`) VALUES
	(1, 3, '000');

-- Dumping structure for table project.billet
CREATE TABLE IF NOT EXISTS `billet` (
  `id_billet` int(11) NOT NULL AUTO_INCREMENT,
  `numero_de_voyage` varchar(255) NOT NULL,
  `depart` varchar(255) NOT NULL,
  `arrivee` varchar(255) NOT NULL,
  `type` enum('business class','economy') NOT NULL,
  PRIMARY KEY (`id_billet`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table project.billet: ~2 rows (approximately)
INSERT INTO `billet` (`id_billet`, `numero_de_voyage`, `depart`, `arrivee`, `type`) VALUES
	(1, '001', 'TUNIS', 'LYON', 'business class'),
	(2, '2', 'sousse', '11', 'business class');

-- Dumping structure for table project.client
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) DEFAULT NULL,
  `fonction` varchar(50) DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `tel_personnel` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `client_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table project.client: ~8 rows (approximately)
INSERT INTO `client` (`id`, `utilisateur_id`, `fonction`, `nationalite`, `tel_personnel`) VALUES
	(1, 1, '00', '00', '00'),
	(2, 2, 'hellos000001', 'tn501521', '549887551'),
	(3, 4, '00', '00', '00'),
	(4, 5, '00', '00', '00'),
	(5, 6, '00', '00', '00'),
	(6, 7, '00', '00', '00'),
	(7, 8, '11', '11', '22'),
	(8, 9, 'KKLLKN', 'NK', '455445');

-- Dumping structure for table project.compte
CREATE TABLE IF NOT EXISTS `compte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status` enum('activated','not activated') DEFAULT NULL,
  `type` enum('client','agence') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `compte_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table project.compte: ~4 rows (approximately)
INSERT INTO `compte` (`id`, `utilisateur_id`, `username`, `password`, `status`, `type`) VALUES
	(2, 2, 'client', '000', 'activated', 'client'),
	(3, 3, 'agency', '000', 'activated', 'agence'),
	(4, 8, 'root', 'root', 'not activated', 'client'),
	(5, 9, 'hassen', '000', 'activated', 'client');

-- Dumping structure for table project.restaurant
CREATE TABLE IF NOT EXISTS `restaurant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table project.restaurant: ~0 rows (approximately)
INSERT INTO `restaurant` (`id`, `nom`, `adresse`) VALUES
	(1, 'AAi', 'SOUSSE');

-- Dumping structure for table project.utilisateur
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(25) DEFAULT NULL,
  `prenom` varchar(25) DEFAULT NULL,
  `email` varchar(25) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `adresse` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table project.utilisateur: ~9 rows (approximately)
INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `email`, `tel`, `adresse`) VALUES
	(1, 'elkebir', 'nidhal', 'nidhalo.kabir12@gmail.com', '29857045', 'sousse'),
	(2, 'nidhal51', 'hassen51', 'nidhalo.kabir12@gmail.com', '0101', 'ghost00501'),
	(3, 'elkebir', 'nidhal', 'nidhalo.kabir12@gmail.com', '29857045', 'sousse'),
	(4, 'elkebir', 'nidhal', 'nidhalo.kabir12@gmail.com', '00', 'sousse'),
	(5, 'elkebir', 'nidhal', 'nidhalo.kabir12@gmail.com', '000', 'sousse'),
	(6, 'elkebir', 'nidhal', 'nidhalo.kabir12@gmail.com', '000', 'sousse'),
	(7, '00', '00', 'nidhalo.kabir12@gmail.com', '00', '00'),
	(8, 'elkebir', 'nidhal', 'nidhalo.kabir12@gmail.com', 'sdsds', 'sousse'),
	(9, 'elkebir', 'nidhal', 'nidhalo.kabir12@gmail.com', '545445', 'sousse');

-- Dumping structure for table project.voyage
CREATE TABLE IF NOT EXISTS `voyage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_vg` date NOT NULL,
  `destination` varchar(255) NOT NULL,
  `programme` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Dumping data for table project.voyage: ~0 rows (approximately)
INSERT INTO `voyage` (`id`, `date_vg`, `destination`, `programme`) VALUES
	(1, '1999-09-07', 'LYON', 'SDQDQ');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
