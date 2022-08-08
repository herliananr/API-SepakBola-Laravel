/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.4.24-MariaDB : Database - api-sepakbola
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`api-sepakbola` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

/*Table structure for table `hasil_pertandingan` */

DROP TABLE IF EXISTS `hasil_pertandingan`;

CREATE TABLE `hasil_pertandingan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_pertandingan_id` int(11) DEFAULT NULL,
  `total_skor_akhir_tim_tuan` int(11) DEFAULT NULL,
  `total_skor_akhir_tim_tamu` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `hasil_pertandingan` */

insert  into `hasil_pertandingan`(`id`,`jadwal_pertandingan_id`,`total_skor_akhir_tim_tuan`,`total_skor_akhir_tim_tamu`,`created_at`,`updated_at`,`deleted_at`) values 
(1,1,3,2,'2022-08-08 09:01:16','2022-08-08 09:36:35',NULL),
(2,2,1,2,'2022-08-08 09:19:38','2022-08-08 09:20:45',NULL);

/*Table structure for table `jadwal_pertandingan` */

DROP TABLE IF EXISTS `jadwal_pertandingan`;

CREATE TABLE `jadwal_pertandingan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl_pertandingan` date DEFAULT NULL,
  `waktu_pertandingan` time DEFAULT NULL,
  `tim_tuan_id` int(11) DEFAULT NULL,
  `tim_tamu_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `jadwal_pertandingan` */

insert  into `jadwal_pertandingan`(`id`,`tgl_pertandingan`,`waktu_pertandingan`,`tim_tuan_id`,`tim_tamu_id`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'2022-08-16','08:15:00',1,3,'2022-08-08 03:56:52','2022-08-08 03:56:52',NULL),
(2,'2022-08-16','14:00:00',4,5,'2022-08-08 04:04:09','2022-08-08 04:04:09',NULL),
(3,'2022-08-17','10:15:00',1,5,'2022-08-08 04:04:31','2022-08-08 04:15:15','2022-08-08 04:15:15'),
(4,'2022-08-17','15:10:00',4,3,'2022-08-08 04:05:05','2022-08-08 04:12:53',NULL);

/*Table structure for table `kota_markas` */

DROP TABLE IF EXISTS `kota_markas`;

CREATE TABLE `kota_markas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kota` varchar(60) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `kota_markas` */

insert  into `kota_markas`(`id`,`nama_kota`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Bekasi Barat','2022-08-08 01:46:15','2022-08-08 02:02:55',NULL),
(2,'Bandung','2022-08-08 01:49:58','2022-08-08 01:49:58',NULL),
(3,'Cikarang','2022-08-08 01:50:06','2022-08-08 01:50:06',NULL),
(4,'Depok','2022-08-08 01:52:47','2022-08-08 01:52:47',NULL),
(5,'Surabaya','2022-08-08 01:52:53','2022-08-08 01:52:53',NULL),
(6,'Jakarta','2022-08-08 01:53:01','2022-08-08 01:53:01',NULL),
(7,'Makassar','2022-08-08 02:06:35','2022-08-08 02:06:35',NULL),
(8,'Denpasar','2022-08-08 02:06:42','2022-08-08 02:08:24','2022-08-08 02:08:24');

/*Table structure for table `log_pemain_tim` */

DROP TABLE IF EXISTS `log_pemain_tim`;

CREATE TABLE `log_pemain_tim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pemain_id` int(11) DEFAULT NULL,
  `tim_id` int(11) DEFAULT NULL,
  `posisi` varchar(30) DEFAULT NULL,
  `nomor_punggung` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

/*Data for the table `log_pemain_tim` */

insert  into `log_pemain_tim`(`id`,`pemain_id`,`tim_id`,`posisi`,`nomor_punggung`,`created_at`,`updated_at`,`deleted_at`) values 
(1,1,1,'striker',13,'2022-08-08 03:10:09','2022-08-08 03:10:09',NULL),
(2,2,1,'gelandang',25,'2022-08-08 03:18:18','2022-08-08 03:18:18',NULL),
(3,3,3,'gelandang',12,'2022-08-08 03:19:39','2022-08-08 03:19:39',NULL),
(4,4,3,'striker',15,'2022-08-08 03:23:11','2022-08-08 03:23:11',NULL),
(5,5,3,'striker',14,'2022-08-08 03:23:42','2022-08-08 03:23:42',NULL),
(6,6,4,'striker',4,'2022-08-08 03:24:06','2022-08-08 03:24:06',NULL),
(7,7,3,'gelandang',3,'2022-08-08 03:24:33','2022-08-08 03:24:33',NULL),
(8,8,5,'gelandang',5,'2022-08-08 03:25:26','2022-08-08 03:25:26',NULL),
(9,9,5,'penyerang',6,'2022-08-08 03:25:50','2022-08-08 03:25:50',NULL),
(10,10,5,'kiper',10,'2022-08-08 03:26:21','2022-08-08 03:26:21',NULL),
(11,11,5,'striker',11,'2022-08-08 03:27:24','2022-08-08 03:42:00','2022-08-08 03:42:00');

/*Table structure for table `pemain` */

DROP TABLE IF EXISTS `pemain`;

CREATE TABLE `pemain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(60) DEFAULT NULL,
  `tinggi_badan` float DEFAULT NULL,
  `berat_badan` float DEFAULT NULL,
  `tim_id` int(11) DEFAULT NULL,
  `posisi` varchar(30) DEFAULT NULL,
  `nomor_punggung` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

/*Data for the table `pemain` */

insert  into `pemain`(`id`,`nama`,`tinggi_badan`,`berat_badan`,`tim_id`,`posisi`,`nomor_punggung`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Candra',187,78.9,1,'striker',13,'2022-08-08 03:10:09','2022-08-08 03:10:09',NULL),
(2,'Bagas',176.2,70,1,'gelandang',25,'2022-08-08 03:18:18','2022-08-08 03:18:18',NULL),
(3,'Andri',169,69,3,'gelandang',12,'2022-08-08 03:19:39','2022-08-08 03:19:39',NULL),
(4,'Caki',169,69,3,'striker',15,'2022-08-08 03:23:11','2022-08-08 03:23:11',NULL),
(5,'Doni',177,71.7,3,'striker',14,'2022-08-08 03:23:42','2022-08-08 03:23:42',NULL),
(6,'Rangga',177,71.7,4,'striker',4,'2022-08-08 03:24:06','2022-08-08 03:24:06',NULL),
(7,'Ringgo',171.9,70,4,'gelandang',3,'2022-08-08 03:24:33','2022-08-08 03:24:33',NULL),
(8,'Lingga',170,70,5,'gelandang',5,'2022-08-08 03:25:26','2022-08-08 03:25:26',NULL),
(9,'Haris',180,70.1,5,'penyerang',6,'2022-08-08 03:25:50','2022-08-08 03:25:50',NULL),
(10,'Zoni',181,77,5,'kiper',10,'2022-08-08 03:26:21','2022-08-08 03:26:21',NULL),
(11,'Zaki Kurniawan',166,60.4,5,'striker',11,'2022-08-08 03:27:24','2022-08-08 03:42:00','2022-08-08 03:42:00');

/*Table structure for table `pencetak_gol_pertandingan` */

DROP TABLE IF EXISTS `pencetak_gol_pertandingan`;

CREATE TABLE `pencetak_gol_pertandingan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_pertandingan_id` int(11) DEFAULT NULL,
  `tim_id` int(11) DEFAULT NULL,
  `pemain_pencetakgol_id` int(11) DEFAULT NULL,
  `waktu_gol` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

/*Data for the table `pencetak_gol_pertandingan` */

insert  into `pencetak_gol_pertandingan`(`id`,`jadwal_pertandingan_id`,`tim_id`,`pemain_pencetakgol_id`,`waktu_gol`,`created_at`,`updated_at`,`deleted_at`) values 
(1,1,1,1,'08:31:45','2022-08-08 09:01:16','2022-08-08 09:09:40',NULL),
(2,1,1,2,'08:36:12','2022-08-08 09:02:45','2022-08-08 09:02:45',NULL),
(3,1,1,1,'09:00:19','2022-08-08 09:03:07','2022-08-08 09:03:07',NULL),
(4,1,3,4,'08:47:00','2022-08-08 09:04:32','2022-08-08 09:04:32',NULL),
(5,1,3,3,'08:50:08','2022-08-08 09:04:53','2022-08-08 09:04:53',NULL),
(6,1,3,3,'08:51:08','2022-08-08 09:10:21','2022-08-08 09:13:16','2022-08-08 09:13:16'),
(7,2,4,6,'14:29:12','2022-08-08 09:19:38','2022-08-08 09:19:38',NULL),
(8,2,5,9,'14:39:00','2022-08-08 09:20:31','2022-08-08 09:20:31',NULL),
(9,2,5,9,'14:54:40','2022-08-08 09:20:45','2022-08-08 09:20:45',NULL),
(10,1,3,3,'08:52:00','2022-08-08 09:29:54','2022-08-08 09:36:35','2022-08-08 09:36:35');

/*Table structure for table `report_hasil_pertandingan` */

DROP TABLE IF EXISTS `report_hasil_pertandingan`;

CREATE TABLE `report_hasil_pertandingan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_pertandingan_id` int(11) DEFAULT NULL,
  `tim_tuan_id` int(11) DEFAULT NULL,
  `nama_tim_tuan` varchar(50) DEFAULT NULL,
  `tim_tamu_id` int(11) DEFAULT NULL,
  `nama_tim_tamu` varchar(50) DEFAULT NULL,
  `total_skor_akhir_tim_tuan` int(11) DEFAULT NULL,
  `total_skor_akhir_tim_tamu` int(11) DEFAULT NULL,
  `status_akhir_pertandingan` enum('Tim Tuan Menang','Tim Tamu Menang','Draw') DEFAULT NULL,
  `id_pemain_pencetakgol_terbanyak` int(11) DEFAULT NULL,
  `nama_pemain_pencetakgol_terbanyak` varchar(60) DEFAULT NULL,
  `akumulasi_total_kemenangan_tim_tuan` int(11) DEFAULT NULL,
  `akumulasi_total_kemenangan_tim_tamu` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `report_hasil_pertandingan` */

insert  into `report_hasil_pertandingan`(`id`,`jadwal_pertandingan_id`,`tim_tuan_id`,`nama_tim_tuan`,`tim_tamu_id`,`nama_tim_tamu`,`total_skor_akhir_tim_tuan`,`total_skor_akhir_tim_tamu`,`status_akhir_pertandingan`,`id_pemain_pencetakgol_terbanyak`,`nama_pemain_pencetakgol_terbanyak`,`akumulasi_total_kemenangan_tim_tuan`,`akumulasi_total_kemenangan_tim_tamu`,`created_at`,`updated_at`,`deleted_at`) values 
(1,1,1,'Anyar',3,'Banar',3,2,'Tim Tuan Menang',3,'Andri',3,4,'2022-08-08 09:01:16','2022-08-08 09:36:35',NULL),
(2,2,4,'Dirganta',5,'Mandani',1,2,'Tim Tamu Menang',9,'Haris',1,2,'2022-08-08 09:19:38','2022-08-08 09:20:45',NULL);

/*Table structure for table `tim` */

DROP TABLE IF EXISTS `tim`;

CREATE TABLE `tim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `tahun_berdiri` year(4) DEFAULT NULL,
  `alamat_markas` varchar(70) DEFAULT NULL,
  `kota_markas_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tim` */

insert  into `tim`(`id`,`nama`,`logo`,`tahun_berdiri`,`alamat_markas`,`kota_markas_id`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Anyar','logo-2022-08-08-02-53-47.jpg',1990,'Jln Selasih no 104',NULL,'2022-08-08 02:36:35','2022-08-08 02:53:47',NULL),
(3,'Banar','logo-2022-08-08-02-45-40.jpg',2005,'Jln Mawa no 5',1,'2022-08-08 02:45:40','2022-08-08 02:45:40',NULL),
(4,'Dirganta','logo-2022-08-08-02-46-21.jpg',1999,'Jln Pattimura Selatan',3,'2022-08-08 02:46:21','2022-08-08 02:46:21',NULL),
(5,'Mandani','logo-2022-08-08-02-46-45.jpg',1991,'Jln Melati nomor 4',4,'2022-08-08 02:46:45','2022-08-08 02:46:45',NULL),
(6,'Nambo','logo-2022-08-08-02-47-13.jpg',1978,'Jln Markisa nomor 23',7,'2022-08-08 02:47:13','2022-08-08 02:57:52','2022-08-08 02:57:52');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
