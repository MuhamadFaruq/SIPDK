-- MySQL dump 10.13  Distrib 9.4.0, for macos15.4 (arm64)
--
-- Host: localhost    Database: sipdk_dukuh
-- ------------------------------------------------------
-- Server version	9.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,2,'Siti Aminah, A.Md','Admin','Mencatat Surat Masuk','Surat Masuk','Surat No. 005/124/KEC.SKM/2026 berhasil diagendakan.','127.0.0.1','2026-08-27 07:21:03'),(2,3,'H. Bambang Sutarjo, S.STP, M.Si','Pimpinan','Memberikan Disposisi','Disposisi','Disposisi Surat AGD-2026/08/001 ke Kasi Pemerintahan.','127.0.0.1','2026-08-27 07:21:03');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'SEKRETARIAT','Sekretariat Kelurahan','Sekretaris Kelurahan','2026-08-27 07:21:02','2026-08-27 07:21:02'),(2,'PEMERINTAHAN','Seksi Pemerintahan','Kasi Pemerintahan','2026-08-27 07:21:02','2026-08-27 07:21:02'),(3,'PMD','Seksi Pemberdayaan Masyarakat & Desa','Kasi PMD','2026-08-27 07:21:02','2026-08-27 07:21:02'),(4,'TRANTIB','Seksi Ketenteraman & Ketertiban','Kasi Trantib','2026-08-27 07:21:02','2026-08-27 07:21:02'),(5,'KESRA','Seksi Kesejahteraan Rakyat','Kasi Kesra','2026-08-27 07:21:02','2026-08-27 07:21:02'),(6,'UMUM_KEU','Subbagian Umum & Keuangan','Kaur Umum & Keuangan','2026-08-27 07:21:02','2026-08-27 07:21:02');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disposition_histories`
--

DROP TABLE IF EXISTS `disposition_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disposition_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `disposition_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disposition_histories_disposition_id_foreign` (`disposition_id`),
  KEY `disposition_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `disposition_histories_disposition_id_foreign` FOREIGN KEY (`disposition_id`) REFERENCES `dispositions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `disposition_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disposition_histories`
--

LOCK TABLES `disposition_histories` WRITE;
/*!40000 ALTER TABLE `disposition_histories` DISABLE KEYS */;
INSERT INTO `disposition_histories` VALUES (1,1,3,'Disposisi Dikirim','Disposisi utama disahkan oleh Lurah','2026-08-27 07:21:03','2026-08-27 07:21:03');
/*!40000 ALTER TABLE `disposition_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dispositions`
--

DROP TABLE IF EXISTS `dispositions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispositions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `letter_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `sender_user_id` bigint unsigned NOT NULL,
  `recipient_user_id` bigint unsigned NOT NULL,
  `recipient_department_id` bigint unsigned DEFAULT NULL,
  `instruction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `urgency` enum('Biasa','Penting','Rahasia','Sangat Segera') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Biasa',
  `due_date` date DEFAULT NULL,
  `status` enum('Menunggu','Diproses','Selesai','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `follow_up_notes` text COLLATE utf8mb4_unicode_ci,
  `followed_up_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispositions_letter_id_foreign` (`letter_id`),
  KEY `dispositions_sender_user_id_foreign` (`sender_user_id`),
  KEY `dispositions_recipient_user_id_foreign` (`recipient_user_id`),
  KEY `dispositions_recipient_department_id_foreign` (`recipient_department_id`),
  KEY `dispositions_parent_id_foreign` (`parent_id`),
  CONSTRAINT `dispositions_letter_id_foreign` FOREIGN KEY (`letter_id`) REFERENCES `letters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dispositions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `dispositions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dispositions_recipient_department_id_foreign` FOREIGN KEY (`recipient_department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `dispositions_recipient_user_id_foreign` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dispositions_sender_user_id_foreign` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispositions`
--

LOCK TABLES `dispositions` WRITE;
/*!40000 ALTER TABLE `dispositions` DISABLE KEYS */;
INSERT INTO `dispositions` VALUES (1,1,NULL,3,5,2,'Harap hadir mendampingi dan siapkan peta batas wilayah RT 01-08 terbaru.','Penting','2026-08-28','Diproses',NULL,NULL,NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03'),(2,2,NULL,3,6,3,'Koordinasikan dengan Kader Posyandu RW 01-06 dan buatkan rekap jadwal pelaksanaannya.','Sangat Segera','2026-08-29','Diproses',NULL,NULL,NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03');
/*!40000 ALTER TABLE `dispositions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `letter_categories`
--

DROP TABLE IF EXISTS `letter_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `letter_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `letter_categories_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `letter_categories`
--

LOCK TABLES `letter_categories` WRITE;
/*!40000 ALTER TABLE `letter_categories` DISABLE KEYS */;
INSERT INTO `letter_categories` VALUES (1,'UND','Surat Undangan','Undangan rapat, dinas, atau kegiatan kemasyarakatan.','2026-08-27 07:21:02','2026-08-27 07:21:02'),(2,'EDR','Surat Edaran','Instruksi dan edaran dari Pemkot/Kecamatan.','2026-08-27 07:21:02','2026-08-27 07:21:02'),(3,'PMH','Surat Permohonan','Permohonan bantuan, fasilitasi, atau izin.','2026-08-27 07:21:02','2026-08-27 07:21:02'),(4,'PBT','Surat Pemberitahuan','Pemberitahuan resmi instansi luar.','2026-08-27 07:21:02','2026-08-27 07:21:02'),(5,'DNS','Surat Dinas Umum','Surat masuk kedinasan umum.','2026-08-27 07:21:02','2026-08-27 07:21:02');
/*!40000 ALTER TABLE `letter_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `letters`
--

DROP TABLE IF EXISTS `letters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `letters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agenda_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `letter_date` date NOT NULL,
  `received_date` date NOT NULL,
  `sender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pdf',
  `file_size` int NOT NULL DEFAULT '0',
  `status` enum('Baru','Dibaca','Didisposisi','Diproses','Selesai','Arsip','Pending','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baru',
  `degree` enum('Biasa','Penting','Rahasia','Sangat Segera') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Biasa',
  `created_by` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `letters_agenda_number_unique` (`agenda_number`),
  KEY `letters_category_id_foreign` (`category_id`),
  KEY `letters_created_by_foreign` (`created_by`),
  CONSTRAINT `letters_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `letter_categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `letters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `letters`
--

LOCK TABLES `letters` WRITE;
/*!40000 ALTER TABLE `letters` DISABLE KEYS */;
INSERT INTO `letters` VALUES (1,'AGD-2026/08/001','005/124/KEC.SKH/2026','2026-08-25','2026-08-25','Kecamatan Sukoharjo - Bagian Tata Pemerintahan','Undangan Rapat Koordinasi Penataan Batas Wilayah RT/RW Tahun 2026','Mengharap kehadiran Lurah dan Kasi Pemerintahan dalam rapat koordinasi penataan wilayah administrasi RT/RW.',1,'letters/sample_surat_01.pdf','undangan_rapat_koordinasi.pdf','pdf',102450,'Didisposisi','Penting',2,NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03'),(2,'AGD-2026/08/002','440/088/DISKES/2026','2026-08-26','2026-08-26','Dinas Kesehatan Kabupaten Sukoharjo','Pelaksanaan Program Posyandu Remaja dan Penanganan Stunting','Sosialisasi dan jadwal supervisi pelayanan Posyandu Remaja serta verifikasi data balita terindikasi stunting.',2,'letters/sample_surat_01.pdf','edaran_posyandu_stunting.pdf','pdf',204800,'Diproses','Sangat Segera',2,NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03'),(3,'AGD-2026/08/003','012/RW.04/DKH/VIII/2026','2026-08-27','2026-08-27','Pengurus RW 04 Kelurahan Dukuh','Permohonan Bantuan Perbaikan Saluran Air / Drainase Lingkungan','Pengajuan usulan kerja bakti dan bantuan material perbaikan selokan tersumbat menjelang musim hujan.',3,'letters/sample_surat_01.pdf','permohonan_drainase_rw04.pdf','pdf',158000,'Baru','Biasa',2,NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03');
/*!40000 ALTER TABLE `letters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_05_000001_create_roles_and_departments_tables',1),(5,'2026_08_05_000002_update_users_table_for_sipdk',1),(6,'2026_08_05_000003_create_letter_categories_table',1),(7,'2026_08_05_000004_create_letters_table',1),(8,'2026_08_05_000005_create_dispositions_table',1),(9,'2026_08_05_000006_create_audit_logs_and_notifications_tables',1),(10,'2026_08_14_041057_simplify_roles_to_three',1),(11,'2026_08_26_000001_add_parent_id_to_dispositions_table',1),(12,'2026_08_26_000002_create_outgoing_letters_table',1),(13,'2026_08_26_000003_make_file_path_nullable_in_letters_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,5,'Disposisi Baru dari Lurah','Anda menerima disposisi untuk surat: Undangan Rapat Koordinasi Penataan Batas Wilayah.','/dispositions',0,'2026-08-27 07:21:03','2026-08-27 07:21:03');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outgoing_letters`
--

DROP TABLE IF EXISTS `outgoing_letters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `outgoing_letters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agenda_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `letter_date` date NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pdf',
  `file_size` int NOT NULL DEFAULT '0',
  `status` enum('Konsep','Disetujui','Terkirim','Arsip') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Terkirim',
  `degree` enum('Biasa','Penting','Rahasia','Sangat Segera') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Biasa',
  `created_by` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `outgoing_letters_agenda_number_unique` (`agenda_number`),
  KEY `outgoing_letters_category_id_foreign` (`category_id`),
  KEY `outgoing_letters_created_by_foreign` (`created_by`),
  CONSTRAINT `outgoing_letters_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `letter_categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `outgoing_letters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outgoing_letters`
--

LOCK TABLES `outgoing_letters` WRITE;
/*!40000 ALTER TABLE `outgoing_letters` DISABLE KEYS */;
/*!40000 ALTER TABLE `outgoing_letters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Admin','Admin Sistem & Tata Usaha','2026-08-27 07:20:56','2026-08-27 07:20:56'),(2,'pimpinan','Pimpinan','Lurah & Sekretaris (Monitoring & Disposisi)','2026-08-27 07:20:56','2026-08-27 07:20:56'),(3,'pelaksana','Pelaksana','Petugas Pelaksana (Menerima Disposisi)','2026-08-27 07:20:56','2026-08-27 07:20:56');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_department_id_foreign` (`department_id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator SIPDK','admin@kelurahan.go.id',NULL,'$2y$12$SJRt2QMJWqImPumBNShEguEvNd2u4b1DnqKLq2wzlx3hi4r8B99H6',NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03',1,NULL,'19850101 201001 1 001','System Administrator','081234567890',NULL,1),(2,'Siti Aminah, A.Md','tu@kelurahan.go.id',NULL,'$2y$12$SJRt2QMJWqImPumBNShEguEvNd2u4b1DnqKLq2wzlx3hi4r8B99H6',NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03',1,1,'19900315 201402 2 003','Petugas Tata Usaha & Agendaris','081298765432',NULL,1),(3,'H. Bambang Sutarjo, S.STP, M.Si','lurah@kelurahan.go.id',NULL,'$2y$12$SJRt2QMJWqImPumBNShEguEvNd2u4b1DnqKLq2wzlx3hi4r8B99H6',NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03',2,NULL,'19780512 199803 1 002','Lurah Dukuh','081122334455',NULL,1),(4,'Drs. Rahmat Hidayat','sekretaris@kelurahan.go.id',NULL,'$2y$12$SJRt2QMJWqImPumBNShEguEvNd2u4b1DnqKLq2wzlx3hi4r8B99H6',NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03',2,1,'19800720 200501 1 005','Sekretaris Kelurahan','081344556677',NULL,1),(5,'Ahmad Fauzi, S.IP','kasi_pem@kelurahan.go.id',NULL,'$2y$12$SJRt2QMJWqImPumBNShEguEvNd2u4b1DnqKLq2wzlx3hi4r8B99H6',NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03',3,2,'19841110 200903 1 008','Kasi Pemerintahan','081566778899',NULL,1),(6,'Dewi Sartika, S.E.','kasi_pmd@kelurahan.go.id',NULL,'$2y$12$SJRt2QMJWqImPumBNShEguEvNd2u4b1DnqKLq2wzlx3hi4r8B99H6',NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03',3,3,'19870425 201101 2 006','Kasi Pemberdayaan Masyarakat','081677889900',NULL,1),(7,'Budi Santoso','staff@kelurahan.go.id',NULL,'$2y$12$SJRt2QMJWqImPumBNShEguEvNd2u4b1DnqKLq2wzlx3hi4r8B99H6',NULL,'2026-08-27 07:21:03','2026-08-27 07:21:03',3,2,'19950812 202001 1 012','Staff Pelaksana Pemerintahan','081788990011',NULL,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-27 21:21:14
