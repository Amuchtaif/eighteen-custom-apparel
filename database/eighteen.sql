-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: eighteen
-- ------------------------------------------------------
-- Server version	8.0.46-0ubuntu0.24.04.3

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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `office_id` bigint unsigned DEFAULT NULL,
  `work_shift_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_in_latitude` decimal(10,8) DEFAULT NULL,
  `check_in_longitude` decimal(11,8) DEFAULT NULL,
  `check_in_distance` int DEFAULT NULL,
  `check_in_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_time` datetime DEFAULT NULL,
  `check_out_latitude` decimal(10,8) DEFAULT NULL,
  `check_out_longitude` decimal(11,8) DEFAULT NULL,
  `check_out_distance` int DEFAULT NULL,
  `check_out_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendance_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hadir',
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `device_info` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `attendances_employee_id_foreign` (`employee_id`),
  KEY `attendances_office_id_foreign` (`office_id`),
  KEY `attendances_work_shift_id_foreign` (`work_shift_id`),
  CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendances_work_shift_id_foreign` FOREIGN KEY (`work_shift_id`) REFERENCES `work_shifts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
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
-- Table structure for table `customer_histories`
--

DROP TABLE IF EXISTS `customer_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_histories_customer_id_foreign` (`customer_id`),
  KEY `customer_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `customer_histories_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_histories`
--

LOCK TABLES `customer_histories` WRITE;
/*!40000 ALTER TABLE `customer_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`),
  KEY `customers_company_name_index` (`company_name`),
  KEY `customers_phone_index` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'FC Brotherhood Jersey','John Doe','081234567896','customer@eighteen.com','Jl. Merdeka No. 18','Jakarta','Regular jerseys orderer',NULL,'2026-11-03 10:37:13','2026-11-03 10:37:13'),(2,'Harimau FC','Budi Pratama','081299887766',NULL,NULL,NULL,NULL,NULL,'2026-11-03 11:17:24','2026-11-03 11:17:24');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Sales','Sales Department',1,'2026-11-03 10:37:05','2026-11-03 10:37:08'),(2,'Produksi','Production Department',1,'2026-11-03 10:37:05','2026-11-03 10:37:08'),(3,'Marketing','Marketing Department',1,'2026-11-03 10:37:05','2026-11-03 10:37:08'),(4,'Keuangan','Finance Department',1,'2026-11-03 10:37:05','2026-11-03 10:37:08'),(5,'HRD','Human Resources Department',1,'2026-11-03 10:37:05','2026-11-03 10:37:08'),(6,'Production','Production Department (English)',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(7,'Human Resources','Human Resources Department (English)',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(8,'Finance','Finance Department (English)',1,'2026-11-03 10:37:08','2026-11-03 10:37:08');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `design_approvals`
--

DROP TABLE IF EXISTS `design_approvals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `design_approvals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `design_file_id` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_approvals_order_id_foreign` (`order_id`),
  KEY `design_approvals_design_file_id_foreign` (`design_file_id`),
  KEY `design_approvals_approved_by_foreign` (`approved_by`),
  CONSTRAINT `design_approvals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `design_approvals_design_file_id_foreign` FOREIGN KEY (`design_file_id`) REFERENCES `design_files` (`id`) ON DELETE CASCADE,
  CONSTRAINT `design_approvals_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `design_approvals`
--

LOCK TABLES `design_approvals` WRITE;
/*!40000 ALTER TABLE `design_approvals` DISABLE KEYS */;
/*!40000 ALTER TABLE `design_approvals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `design_files`
--

DROP TABLE IF EXISTS `design_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `design_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'v1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `uploaded_by` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `design_files_order_id_foreign` (`order_id`),
  KEY `design_files_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `design_files_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `design_files_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `design_files`
--

LOCK TABLES `design_files` WRITE;
/*!40000 ALTER TABLE `design_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `design_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  `position_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `office_id` bigint unsigned DEFAULT NULL,
  `work_shift_id` bigint unsigned DEFAULT NULL,
  `employee_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `join_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  KEY `employees_user_id_foreign` (`user_id`),
  KEY `employees_office_id_foreign` (`office_id`),
  KEY `employees_work_shift_id_foreign` (`work_shift_id`),
  KEY `employees_role_id_foreign` (`role_id`),
  KEY `employees_position_id_foreign` (`position_id`),
  KEY `employees_department_id_foreign` (`department_id`),
  CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employees_work_shift_id_foreign` FOREIGN KEY (`work_shift_id`) REFERENCES `work_shifts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,3,4,1,1,1,1,'EMP-00001','2026-05-03','active',NULL,'2026-11-03 10:37:10','2026-11-03 10:51:10'),(2,4,5,7,6,1,1,'EMP-00002','2026-05-03','active',NULL,'2026-11-03 10:37:11','2026-11-03 10:51:10'),(3,5,7,8,7,1,1,'EMP-00003','2025-11-03','active',NULL,'2026-11-03 10:37:12','2026-11-03 10:51:10'),(4,6,8,9,8,1,1,'EMP-00004','2026-03-03','active',NULL,'2026-11-03 10:37:13','2026-11-03 10:51:10');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
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
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_order_id_foreign` (`order_id`),
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,3,'INV-202611-0001','2026-11-03','2026-11-17',2400000.00,'partially_paid','2026-11-03 11:19:21','2026-11-03 11:19:24');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
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
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (1,'Dryfit','Bahan dryfit berpori, cocok untuk jersey olahraga.','2026-11-03 10:37:14','2026-11-03 10:37:14'),(2,'Serena','Bahan serena halus dan lentur, ideal untuk printing jersey.','2026-11-03 10:37:14','2026-11-03 10:37:14'),(3,'Cotton Combed','Bahan katun combed 30s berkualitas tinggi dan adem.','2026-11-03 10:37:14','2026-11-03 10:37:14');
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_roles_table',1),(2,'0001_01_01_000000_create_users_table',1),(3,'0001_01_01_000001_create_cache_table',1),(4,'0001_01_01_000002_create_jobs_table',1),(5,'2026_06_17_000002_create_employees_table',1),(6,'2026_06_17_000003_create_attendances_table',1),(7,'2026_06_17_000004_create_customers_table',1),(8,'2026_06_17_000005_create_customer_histories_table',1),(9,'2026_06_17_000006_create_product_categories_table',1),(10,'2026_06_17_000007_create_products_table',1),(11,'2026_06_17_000008_create_quotations_table',1),(12,'2026_06_17_000009_create_quotation_items_table',1),(13,'2026_06_17_000010_create_orders_table',1),(14,'2026_06_17_000011_create_order_items_table',1),(15,'2026_06_17_000012_create_order_players_table',1),(16,'2026_06_17_000013_create_design_files_table',1),(17,'2026_06_17_000014_create_design_approvals_table',1),(18,'2026_06_17_000015_create_production_stages_table',1),(19,'2026_06_17_000016_create_production_logs_table',1),(20,'2026_06_17_000017_create_invoices_table',1),(21,'2026_06_17_000018_create_payments_table',1),(22,'2026_06_17_000019_create_notifications_table',1),(23,'2026_06_17_000020_create_activity_logs_table',1),(24,'2026_06_21_123005_create_offices_table',1),(25,'2026_06_21_123005_create_work_shifts_table',1),(26,'2026_06_21_123006_add_office_and_shift_to_employees_table',1),(27,'2026_06_21_123006_refactor_attendances_table',1),(28,'2026_06_23_000001_create_materials_table',1),(29,'2026_06_23_000002_add_production_fields_to_orders_table',1),(30,'2026_06_23_000003_create_production_progress_table',1),(31,'2026_06_23_000004_create_production_progress_histories_table',1),(32,'2026_06_23_100000_create_packages_table',1),(33,'2026_06_23_200000_refactor_custom_orders_table',1),(34,'2026_06_24_055215_add_custom_order_fields_to_orders_table',1),(35,'2026_06_24_061051_create_settings_table',1),(36,'2026_06_24_070001_add_is_active_to_roles_table',1),(37,'2026_06_24_070002_create_positions_table',1),(38,'2026_06_24_070003_create_departments_table',1),(39,'2026_06_24_070004_refactor_employees_table',1),(40,'2026_06_25_000001_recreate_production_progress_and_order_fields',1);
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
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offices`
--

DROP TABLE IF EXISTS `offices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `radius_meter` int NOT NULL DEFAULT '100',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offices`
--

LOCK TABLES `offices` WRITE;
/*!40000 ALTER TABLE `offices` DISABLE KEYS */;
INSERT INTO `offices` VALUES (1,'Kantor Produksi','Jl. Produksi No. 1',-6.20000000,106.81666600,100,1,'2026-11-03 10:37:14','2026-11-03 10:37:14'),(2,'Kantor Studio','Jl. Studio No. 2',-6.21000000,106.82000000,100,1,'2026-11-03 10:37:14','2026-11-03 10:37:14');
/*!40000 ALTER TABLE `offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_attachments`
--

DROP TABLE IF EXISTS `order_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_attachments_order_id_foreign` (`order_id`),
  CONSTRAINT `order_attachments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_attachments`
--

LOCK TABLES `order_attachments` WRITE;
/*!40000 ALTER TABLE `order_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_players`
--

DROP TABLE IF EXISTS `order_players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_players` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `player_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `player_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_players_order_id_foreign` (`order_id`),
  CONSTRAINT `order_players_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_players`
--

LOCK TABLES `order_players` WRITE;
/*!40000 ALTER TABLE `order_players` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_order` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `nama_team` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bahan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_esti` int NOT NULL DEFAULT '0',
  `qty_tink` int NOT NULL DEFAULT '0',
  `package_id` bigint unsigned DEFAULT NULL,
  `total_tagihan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `dp` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sisa_tagihan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status_pembayaran` enum('Belum DP','DP','Lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Belum DP',
  `status_produksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Antrian',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Backoffice',
  `jenis_apparel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_penggunaan` date DEFAULT NULL,
  `budget_perkiraan` decimal(15,2) DEFAULT NULL,
  `tanggal_masuk` date NOT NULL,
  `deadline` date NOT NULL,
  `estimasi_selesai` date DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `lokasi_produksi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Kantor Produksi',
  `designer_id` bigint unsigned DEFAULT NULL,
  `operator_print_id` bigint unsigned DEFAULT NULL,
  `penjahit_id` bigint unsigned DEFAULT NULL,
  `qc_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`kode_order`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_package_id_foreign` (`package_id`),
  KEY `orders_designer_id_foreign` (`designer_id`),
  KEY `orders_operator_print_id_foreign` (`operator_print_id`),
  KEY `orders_penjahit_id_foreign` (`penjahit_id`),
  KEY `orders_qc_id_foreign` (`qc_id`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_designer_id_foreign` FOREIGN KEY (`designer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_operator_print_id_foreign` FOREIGN KEY (`operator_print_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_penjahit_id_foreign` FOREIGN KEY (`penjahit_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_qc_id_foreign` FOREIGN KEY (`qc_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-202611-0001',1,'Garuda FC','Dryfit Premium',24,0,2,2400000.00,1200000.00,1200000.00,'DP','Selesai','Backoffice',NULL,NULL,NULL,'2026-11-01','2026-11-13',NULL,NULL,'Kantor Produksi',4,NULL,NULL,NULL,NULL,'2026-11-03 10:51:14','2026-11-03 10:51:58'),(2,'ORD-202611-0002',1,'Elang United','Serena',15,0,2,1500000.00,1500000.00,0.00,'Lunas','Pola','Backoffice',NULL,NULL,NULL,'2026-11-02','2026-11-05',NULL,'<p>Kerah V-Neck Rib, Sublim 200C 30s, Jahit Rantai.</p>','Studio Desain',NULL,NULL,NULL,NULL,NULL,'2026-11-03 10:51:14','2026-11-03 11:17:09'),(3,'ORD-202611-0003',2,'Harimau FC','Milano Dryfit',20,0,1,2400000.00,1000000.00,1400000.00,'DP','Antrian','Landing Page',NULL,NULL,NULL,'2026-11-03','2026-11-20',NULL,'Sablon sponsor dada warna emas','Kantor Produksi',NULL,NULL,NULL,NULL,NULL,'2026-11-03 11:19:14','2026-11-03 11:19:24');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (1,'Reguler','Paket Standar Produksi Apparel',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(2,'Premium','Paket Prioritas Bahan Eksklusif & QC Khusus',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(3,'Express','Paket Kilat Pengerjaan Cepat',1,'2026-11-03 10:37:08','2026-11-03 10:37:08');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
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
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'2026-11-03',1000000.00,'transfer_bca','TRX-BCA-00123',NULL,'DP Produksi Jersey Harimau FC','2026-11-03 11:19:24','2026-11-03 11:19:24');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `positions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (1,'Sales Representative','Sales Representative',1,'2026-11-03 10:37:06','2026-11-03 10:37:06'),(2,'Sales Manager','Sales Manager',1,'2026-11-03 10:37:06','2026-11-03 10:37:06'),(3,'Operator Produksi','Production Operator',1,'2026-11-03 10:37:06','2026-11-03 10:37:06'),(4,'Supervisor Produksi','Production Supervisor',1,'2026-11-03 10:37:06','2026-11-03 10:37:06'),(5,'Admin Keuangan','Finance Administrator',1,'2026-11-03 10:37:06','2026-11-03 10:37:06'),(6,'Staff HRD','HRD Staff member',1,'2026-11-03 10:37:06','2026-11-03 10:37:06'),(7,'Production Designer','Production Designer',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(8,'HR Specialist','HR Specialist',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(9,'Accountant','Accountant',1,'2026-11-03 10:37:08','2026-11-03 10:37:08');
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_logs`
--

DROP TABLE IF EXISTS `production_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `production_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `stage_id` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `production_logs_order_id_foreign` (`order_id`),
  KEY `production_logs_stage_id_foreign` (`stage_id`),
  KEY `production_logs_updated_by_foreign` (`updated_by`),
  CONSTRAINT `production_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `production_logs_stage_id_foreign` FOREIGN KEY (`stage_id`) REFERENCES `production_stages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `production_logs_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_logs`
--

LOCK TABLES `production_logs` WRITE;
/*!40000 ALTER TABLE `production_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `production_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_progress`
--

DROP TABLE IF EXISTS `production_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `production_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `print_status` tinyint(1) NOT NULL DEFAULT '0',
  `print_at` timestamp NULL DEFAULT NULL,
  `press_status` tinyint(1) NOT NULL DEFAULT '0',
  `press_at` timestamp NULL DEFAULT NULL,
  `pola_status` tinyint(1) NOT NULL DEFAULT '0',
  `pola_at` timestamp NULL DEFAULT NULL,
  `jahit_status` tinyint(1) NOT NULL DEFAULT '0',
  `jahit_at` timestamp NULL DEFAULT NULL,
  `finish_status` tinyint(1) NOT NULL DEFAULT '0',
  `finish_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_progress_order_id_foreign` (`order_id`),
  CONSTRAINT `production_progress_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_progress`
--

LOCK TABLES `production_progress` WRITE;
/*!40000 ALTER TABLE `production_progress` DISABLE KEYS */;
INSERT INTO `production_progress` VALUES (1,1,1,'2026-11-03 10:51:54',1,'2026-11-03 10:51:58',1,'2026-11-03 10:51:58',1,'2026-11-03 10:51:58',1,'2026-11-03 10:51:58','2026-11-03 10:51:14','2026-11-03 10:51:58'),(2,2,1,'2026-11-03 04:17:03',1,NULL,1,NULL,0,NULL,0,NULL,'2026-11-03 10:51:14','2026-11-03 11:17:06'),(3,2,1,NULL,1,NULL,1,NULL,0,NULL,0,NULL,'2026-11-03 11:17:06','2026-11-03 11:17:06'),(4,3,0,NULL,0,NULL,0,NULL,0,NULL,0,NULL,'2026-11-03 11:19:14','2026-11-03 11:19:14');
/*!40000 ALTER TABLE `production_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_progress_histories`
--

DROP TABLE IF EXISTS `production_progress_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `production_progress_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_progress_histories_order_id_foreign` (`order_id`),
  KEY `production_progress_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `production_progress_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `production_progress_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_progress_histories`
--

LOCK TABLES `production_progress_histories` WRITE;
/*!40000 ALTER TABLE `production_progress_histories` DISABLE KEYS */;
INSERT INTO `production_progress_histories` VALUES (1,1,1,'Print',1,NULL,'2026-11-03 10:51:54','2026-11-03 10:51:54'),(2,1,1,'Press',1,NULL,'2026-11-03 10:51:58','2026-11-03 10:51:58'),(3,1,1,'Pola',1,NULL,'2026-11-03 10:51:58','2026-11-03 10:51:58'),(4,1,1,'Jahit',1,NULL,'2026-11-03 10:51:58','2026-11-03 10:51:58'),(5,1,1,'Finish',1,NULL,'2026-11-03 10:51:58','2026-11-03 10:51:58'),(6,2,1,'Print',1,'Tahapan Print diubah menjadi Selesai','2026-11-03 11:17:03','2026-11-03 11:17:03'),(7,2,1,'Pola',1,'Pesanan dipindahkan ke tahapan Pola via Kanban Board','2026-11-03 11:17:06','2026-11-03 11:17:06');
/*!40000 ALTER TABLE `production_progress_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `production_stages`
--

DROP TABLE IF EXISTS `production_stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `production_stages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `production_stages_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `production_stages`
--

LOCK TABLES `production_stages` WRITE;
/*!40000 ALTER TABLE `production_stages` DISABLE KEYS */;
INSERT INTO `production_stages` VALUES (1,'Antrian',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(2,'Print',2,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(3,'Press',3,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(4,'Pola',4,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(5,'Jahit',5,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(6,'Finish',6,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(7,'Siap Kirim',7,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(8,'Selesai',8,'2026-11-03 10:37:08','2026-11-03 10:37:08');
/*!40000 ALTER TABLE `production_stages` ENABLE KEYS */;
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
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','Super Administrator with full access',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(2,'Owner','Business Owner with access to reports and monitoring',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(3,'Admin','Administrator with partial access',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(4,'Sales','Sales staff managing customers, quotations, and orders',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(5,'Production','Production staff tracking apparel design and manufacturing stages',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(6,'Produksi','Staf Produksi melacak desain pakaian dan tahapan manufaktur',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(7,'HRD','Human Resources Department managing employees and attendance',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(8,'Finance','Finance staff managing invoices and payment approvals',1,'2026-11-03 10:37:08','2026-11-03 10:37:08'),(9,'Keuangan','Staf Keuangan mengelola faktur dan persetujuan pembayaran',1,'2026-11-03 10:37:08','2026-11-03 10:37:08');
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
INSERT INTO `sessions` VALUES ('4KPOf8LhuTVy5k3ZBoMUfaYsUlW9Yo5YzLG8J8fJ',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkhFV0FuUXdIaHVpZzY2WlBjbU92dVZESlV3WTQ2M2lGZkREMWJmYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9sb2NhbGhvc3QvZWlnaHRlZW4tY3VzdG9tLWFwcGFyZWwvcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1793703080),('gTc8nGC5eBsqyq5WVl713KS1OxkaOCxFPG4xFGC2',1,'::1','curl/8.5.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieThGQVF4eno5ZDJKWGhQRjM3eTZGUEhYRWhobUJjZmhMMDNxUlgxcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTQ6Imh0dHA6Ly9sb2NhbGhvc3QvZWlnaHRlZW4tY3VzdG9tLWFwcGFyZWwvcHVibGljL29yZGVycyI7czo1OiJyb3V0ZSI7czoxMjoib3JkZXJzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1793703092),('M2hAhbFXuRqAr3z5fsRpUIsJU3HZFKVPIzS4Hssa',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoieWg4VWU1WUhORXZFWXQzSEV4Z3RvS3dBWTIxajVxS0dmRUNWcDVybCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9sb2NhbGhvc3QvZWlnaHRlZW4tY3VzdG9tLWFwcGFyZWwvcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1793703056),('Q2JlNP1nRgJG6cLzWaxS19HJUPO6G6iwVMq5mnhj',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYm0xV01FcVBhZ2cwS0NUZTZqVzJpWHE2Q2FLTUF3RVJXZ2gxTnVwWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly9sb2NhbGhvc3QvZWlnaHRlZW4tY3VzdG9tLWFwcGFyZWwvcHVibGljIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1793703056),('R7tZcfM9tCwiB5jclWVAH7GEHtzAqHJmDg0EzcV6',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUnJ3RTExbnVtTHdiaXR2UEx6WHRDYnZVSjh4SEdEaTluaXZ4aXNiRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODA6Imh0dHA6Ly9sb2NhbGhvc3QvZWlnaHRlZW4tY3VzdG9tLWFwcGFyZWwvcHVibGljL3RyYWNrLW9yZGVyP2NvZGU9T1JELTIwMjYxMS0wMDAxIjtzOjU6InJvdXRlIjtzOjE5OiJvcmRlcnMudHJhY2stcHVibGljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1793703057),('ri3TIyZ4Wh3WoeEPgtYaY0wgDAw28t0GUd6V527E',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY2tzNnBHbWpCZ1V2c3B1bnFJSEJ0VG0xb1NSNHVraHNaSncxQm5mRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjA6Imh0dHA6Ly9sb2NhbGhvc3QvZWlnaHRlZW4tY3VzdG9tLWFwcGFyZWwvcHVibGljL2N1c3RvbS1vcmRlciI7czo1OiJyb3V0ZSI7czoxNzoiY3VzdG9tLW9yZGVyLmZvcm0iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1793703056),('wUX1kUKXoHADnagP1D5B1q03NbIWwjOxlJjjQN3P',NULL,'::1','curl/8.5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidkNFZlNsT2hDSWl2Uld2elVPdUtVWTlGTkZ2MHBmZmVOdlZEeFhUMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODA6Imh0dHA6Ly9sb2NhbGhvc3QvZWlnaHRlZW4tY3VzdG9tLWFwcGFyZWwvcHVibGljL3RyYWNrLW9yZGVyP2NvZGU9T1JELTIwMjYxMS0wMDAxIjtzOjU6InJvdXRlIjtzOjE5OiJvcmRlcnMudHJhY2stcHVibGljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1793703077);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'whatsapp_number','6281234567890','2026-11-03 10:37:05','2026-11-03 10:37:05');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_phone_index` (`phone`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Super Admin Eighteen','admin@eighteen.com','081234567890',NULL,'$2y$12$Jz./duTXG5HXLYlbHFaxDuT8szi8uJ0j.TE0KuBTcfz4K180UbbKa',1,'2026-11-03 10:51:23',NULL,NULL,'2026-11-03 10:37:08','2026-11-03 10:51:23'),(2,2,'Owner Eighteen','owner@eighteen.com','081234567891',NULL,'$2y$12$jwV3zglezxgwSp9xMyxPGOuoH56Y/3tWaqbtkWrFUNtyPDHC8874m',1,NULL,NULL,NULL,'2026-11-03 10:37:09','2026-11-03 10:37:09'),(3,4,'Sales Staff','sales@eighteen.com','081234567892',NULL,'$2y$12$Ds8ewuU7RBhrecQapX3pj.9m85kcmoxSD2rLEvGU1TBHFTyF7bGQy',1,NULL,NULL,NULL,'2026-11-03 10:37:10','2026-11-03 10:37:10'),(4,5,'Production Staff','production@eighteen.com','081234567893',NULL,'$2y$12$VtcGyzlNDUG9bSRwJEabMeFpPNcopgYMVhvH0YfiUS5Frnqg2m82G',1,NULL,NULL,NULL,'2026-11-03 10:37:11','2026-11-03 10:37:11'),(5,7,'HRD Staff','hrd@eighteen.com','081234567894',NULL,'$2y$12$.0ck/7/tLuxeFTig4xOc7ujbg0nG9jEni7L2R4CY1dMDulop8LaFG',1,NULL,NULL,NULL,'2026-11-03 10:37:12','2026-11-03 10:37:12'),(6,8,'Finance Staff','finance@eighteen.com','081234567895',NULL,'$2y$12$UVmRsF/nBaOUEFYKFYS3Xu/jKiZzwxQgSUlB3G3WkUrDjRVdyVuY2',1,NULL,NULL,NULL,'2026-11-03 10:37:13','2026-11-03 10:37:13');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_shifts`
--

DROP TABLE IF EXISTS `work_shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_shifts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `late_tolerance_minutes` int NOT NULL DEFAULT '15',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_shifts`
--

LOCK TABLES `work_shifts` WRITE;
/*!40000 ALTER TABLE `work_shifts` DISABLE KEYS */;
INSERT INTO `work_shifts` VALUES (1,'Shift Reguler','08:00:00','17:00:00',15,1,'2026-11-03 10:37:15','2026-11-03 10:37:15'),(2,'Shift Produksi','07:00:00','16:00:00',15,1,'2026-11-03 10:37:15','2026-11-03 10:37:15');
/*!40000 ALTER TABLE `work_shifts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-03 21:58:16
