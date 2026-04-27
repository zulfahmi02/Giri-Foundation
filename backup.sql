/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.16-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: laravel
-- ------------------------------------------------------
-- Server version	10.11.16-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `description` longtext NOT NULL,
  `activity_date` date DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `featured_image_url` text DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `activities_slug_unique` (`slug`),
  KEY `activities_program_id_foreign` (`program_id`),
  KEY `activities_created_by_foreign` (`created_by`),
  CONSTRAINT `activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `activities_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `record_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('laravel-cache-boost:mcp:database-schema:mysql::1:0:0:0','a:2:{s:6:\"engine\";s:5:\"mysql\";s:6:\"tables\";a:37:{s:10:\"activities\";a:14:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:10:\"program_id\";s:19:\"bigint(20) unsigned\";s:5:\"title\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:7:\"summary\";s:4:\"text\";s:11:\"description\";s:8:\"longtext\";s:13:\"activity_date\";s:4:\"date\";s:13:\"location_name\";s:12:\"varchar(255)\";s:18:\"featured_image_url\";s:4:\"text\";s:6:\"status\";s:36:\"enum(\'draft\',\'published\',\'archived\')\";s:12:\"published_at\";s:9:\"timestamp\";s:10:\"created_by\";s:19:\"bigint(20) unsigned\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:13:\"activity_logs\";a:9:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:7:\"user_id\";s:19:\"bigint(20) unsigned\";s:6:\"action\";s:12:\"varchar(255)\";s:6:\"module\";s:12:\"varchar(255)\";s:9:\"record_id\";s:19:\"bigint(20) unsigned\";s:11:\"description\";s:4:\"text\";s:10:\"ip_address\";s:12:\"varchar(255)\";s:10:\"user_agent\";s:4:\"text\";s:10:\"created_at\";s:9:\"timestamp\";}s:5:\"cache\";a:3:{s:3:\"key\";s:12:\"varchar(255)\";s:5:\"value\";s:10:\"mediumtext\";s:10:\"expiration\";s:10:\"bigint(20)\";}s:11:\"cache_locks\";a:3:{s:3:\"key\";s:12:\"varchar(255)\";s:5:\"owner\";s:12:\"varchar(255)\";s:10:\"expiration\";s:10:\"bigint(20)\";}s:13:\"consultations\";a:11:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:5:\"email\";s:12:\"varchar(255)\";s:5:\"phone\";s:12:\"varchar(255)\";s:7:\"subject\";s:12:\"varchar(255)\";s:7:\"message\";s:4:\"text\";s:25:\"preferred_contact_channel\";s:32:\"enum(\'email\',\'phone\',\'whatsapp\')\";s:6:\"status\";s:55:\"enum(\'new\',\'in_review\',\'scheduled\',\'resolved\',\'closed\')\";s:11:\"assigned_to\";s:19:\"bigint(20) unsigned\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:16:\"contact_messages\";a:9:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:5:\"email\";s:12:\"varchar(255)\";s:5:\"phone\";s:12:\"varchar(255)\";s:7:\"subject\";s:12:\"varchar(255)\";s:7:\"message\";s:4:\"text\";s:6:\"status\";s:43:\"enum(\'new\',\'in_review\',\'resolved\',\'closed\')\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:8:\"contents\";a:17:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:5:\"title\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:4:\"type\";s:39:\"enum(\'story\',\'article\',\'news\',\'report\')\";s:11:\"category_id\";s:19:\"bigint(20) unsigned\";s:7:\"excerpt\";s:4:\"text\";s:4:\"body\";s:8:\"longtext\";s:18:\"featured_image_url\";s:4:\"text\";s:9:\"author_id\";s:19:\"bigint(20) unsigned\";s:6:\"status\";s:36:\"enum(\'draft\',\'published\',\'archived\')\";s:11:\"is_featured\";s:10:\"tinyint(1)\";s:12:\"published_at\";s:9:\"timestamp\";s:9:\"seo_title\";s:12:\"varchar(255)\";s:15:\"seo_description\";s:4:\"text\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";s:10:\"deleted_at\";s:9:\"timestamp\";}s:18:\"content_categories\";a:7:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:4:\"type\";s:39:\"enum(\'story\',\'article\',\'news\',\'report\')\";s:11:\"description\";s:4:\"text\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:13:\"content_files\";a:7:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:10:\"content_id\";s:19:\"bigint(20) unsigned\";s:9:\"file_name\";s:12:\"varchar(255)\";s:8:\"file_url\";s:4:\"text\";s:9:\"file_type\";s:12:\"varchar(255)\";s:9:\"file_size\";s:19:\"bigint(20) unsigned\";s:10:\"created_at\";s:9:\"timestamp\";}s:12:\"content_tags\";a:3:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:10:\"content_id\";s:19:\"bigint(20) unsigned\";s:6:\"tag_id\";s:19:\"bigint(20) unsigned\";}s:9:\"documents\";a:15:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:5:\"title\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:8:\"category\";s:12:\"varchar(255)\";s:11:\"description\";s:4:\"text\";s:8:\"file_url\";s:4:\"text\";s:13:\"thumbnail_url\";s:4:\"text\";s:9:\"file_type\";s:12:\"varchar(255)\";s:9:\"file_size\";s:19:\"bigint(20) unsigned\";s:14:\"download_count\";s:16:\"int(10) unsigned\";s:9:\"is_public\";s:10:\"tinyint(1)\";s:12:\"published_at\";s:9:\"timestamp\";s:10:\"created_by\";s:19:\"bigint(20) unsigned\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:9:\"donations\";a:14:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:11:\"campaign_id\";s:19:\"bigint(20) unsigned\";s:8:\"donor_id\";s:19:\"bigint(20) unsigned\";s:14:\"invoice_number\";s:12:\"varchar(255)\";s:6:\"amount\";s:13:\"decimal(14,2)\";s:14:\"payment_method\";s:12:\"varchar(255)\";s:15:\"payment_channel\";s:12:\"varchar(255)\";s:14:\"payment_status\";s:42:\"enum(\'pending\',\'paid\',\'failed\',\'refunded\')\";s:7:\"paid_at\";s:9:\"timestamp\";s:7:\"message\";s:4:\"text\";s:9:\"proof_url\";s:4:\"text\";s:23:\"external_transaction_id\";s:12:\"varchar(255)\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:18:\"donation_campaigns\";a:15:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:5:\"title\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:17:\"short_description\";s:4:\"text\";s:11:\"description\";s:8:\"longtext\";s:13:\"target_amount\";s:13:\"decimal(14,2)\";s:16:\"collected_amount\";s:13:\"decimal(14,2)\";s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:16:\"banner_image_url\";s:4:\"text\";s:6:\"status\";s:45:\"enum(\'draft\',\'active\',\'completed\',\'archived\')\";s:11:\"is_featured\";s:10:\"tinyint(1)\";s:12:\"published_by\";s:19:\"bigint(20) unsigned\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:16:\"donation_updates\";a:8:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:11:\"campaign_id\";s:19:\"bigint(20) unsigned\";s:5:\"title\";s:12:\"varchar(255)\";s:7:\"content\";s:4:\"text\";s:9:\"image_url\";s:4:\"text\";s:12:\"published_at\";s:9:\"timestamp\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:6:\"donors\";a:7:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:9:\"full_name\";s:12:\"varchar(255)\";s:5:\"email\";s:12:\"varchar(255)\";s:5:\"phone\";s:12:\"varchar(255)\";s:12:\"is_anonymous\";s:10:\"tinyint(1)\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:11:\"failed_jobs\";a:7:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"uuid\";s:12:\"varchar(255)\";s:10:\"connection\";s:4:\"text\";s:5:\"queue\";s:4:\"text\";s:7:\"payload\";s:8:\"longtext\";s:9:\"exception\";s:8:\"longtext\";s:9:\"failed_at\";s:9:\"timestamp\";}s:4:\"jobs\";a:7:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:5:\"queue\";s:12:\"varchar(255)\";s:7:\"payload\";s:8:\"longtext\";s:8:\"attempts\";s:19:\"tinyint(3) unsigned\";s:11:\"reserved_at\";s:16:\"int(10) unsigned\";s:12:\"available_at\";s:16:\"int(10) unsigned\";s:10:\"created_at\";s:16:\"int(10) unsigned\";}s:11:\"job_batches\";a:10:{s:2:\"id\";s:12:\"varchar(255)\";s:4:\"name\";s:12:\"varchar(255)\";s:10:\"total_jobs\";s:7:\"int(11)\";s:12:\"pending_jobs\";s:7:\"int(11)\";s:11:\"failed_jobs\";s:7:\"int(11)\";s:14:\"failed_job_ids\";s:8:\"longtext\";s:7:\"options\";s:10:\"mediumtext\";s:12:\"cancelled_at\";s:7:\"int(11)\";s:10:\"created_at\";s:7:\"int(11)\";s:11:\"finished_at\";s:7:\"int(11)\";}s:13:\"media_library\";a:10:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:9:\"file_name\";s:12:\"varchar(255)\";s:9:\"file_path\";s:12:\"varchar(255)\";s:8:\"file_url\";s:4:\"text\";s:9:\"mime_type\";s:12:\"varchar(255)\";s:9:\"file_size\";s:19:\"bigint(20) unsigned\";s:11:\"uploaded_by\";s:19:\"bigint(20) unsigned\";s:8:\"alt_text\";s:12:\"varchar(255)\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:10:\"migrations\";a:3:{s:2:\"id\";s:16:\"int(10) unsigned\";s:9:\"migration\";s:12:\"varchar(255)\";s:5:\"batch\";s:7:\"int(11)\";}s:21:\"organization_profiles\";a:18:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:17:\"short_description\";s:4:\"text\";s:16:\"full_description\";s:8:\"longtext\";s:6:\"vision\";s:4:\"text\";s:7:\"mission\";s:4:\"text\";s:6:\"values\";s:4:\"text\";s:12:\"founded_date\";s:4:\"date\";s:5:\"email\";s:12:\"varchar(255)\";s:5:\"phone\";s:12:\"varchar(255)\";s:15:\"whatsapp_number\";s:12:\"varchar(255)\";s:7:\"address\";s:4:\"text\";s:17:\"google_maps_embed\";s:4:\"text\";s:8:\"logo_url\";s:4:\"text\";s:11:\"favicon_url\";s:4:\"text\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:18:\"organization_stats\";a:9:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:5:\"title\";s:12:\"varchar(255)\";s:5:\"value\";s:19:\"bigint(20) unsigned\";s:6:\"suffix\";s:12:\"varchar(255)\";s:4:\"icon\";s:12:\"varchar(255)\";s:10:\"sort_order\";s:16:\"int(10) unsigned\";s:9:\"is_active\";s:10:\"tinyint(1)\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:5:\"pages\";a:14:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:5:\"title\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:7:\"content\";s:8:\"longtext\";s:9:\"hero_data\";s:8:\"longtext\";s:12:\"section_data\";s:8:\"longtext\";s:8:\"template\";s:12:\"varchar(255)\";s:6:\"status\";s:36:\"enum(\'draft\',\'published\',\'archived\')\";s:9:\"seo_title\";s:12:\"varchar(255)\";s:15:\"seo_description\";s:4:\"text\";s:12:\"published_at\";s:9:\"timestamp\";s:10:\"created_by\";s:19:\"bigint(20) unsigned\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:8:\"partners\";a:10:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:8:\"logo_url\";s:4:\"text\";s:11:\"website_url\";s:4:\"text\";s:4:\"type\";s:69:\"enum(\'foundation\',\'ngo\',\'corporate\',\'community\',\'government\',\'media\')\";s:11:\"description\";s:4:\"text\";s:9:\"is_active\";s:10:\"tinyint(1)\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:21:\"partnership_inquiries\";a:10:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:17:\"organization_name\";s:12:\"varchar(255)\";s:14:\"contact_person\";s:12:\"varchar(255)\";s:5:\"email\";s:12:\"varchar(255)\";s:5:\"phone\";s:12:\"varchar(255)\";s:12:\"inquiry_type\";s:12:\"varchar(255)\";s:7:\"message\";s:4:\"text\";s:6:\"status\";s:43:\"enum(\'new\',\'in_review\',\'resolved\',\'closed\')\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:21:\"password_reset_tokens\";a:3:{s:5:\"email\";s:12:\"varchar(255)\";s:5:\"token\";s:12:\"varchar(255)\";s:10:\"created_at\";s:9:\"timestamp\";}s:8:\"programs\";a:22:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:5:\"title\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:7:\"excerpt\";s:4:\"text\";s:11:\"description\";s:8:\"longtext\";s:11:\"category_id\";s:19:\"bigint(20) unsigned\";s:6:\"status\";s:48:\"enum(\'draft\',\'published\',\'completed\',\'archived\')\";s:10:\"start_date\";s:4:\"date\";s:8:\"end_date\";s:4:\"date\";s:13:\"location_name\";s:12:\"varchar(255)\";s:8:\"province\";s:12:\"varchar(255)\";s:4:\"city\";s:12:\"varchar(255)\";s:20:\"target_beneficiaries\";s:12:\"varchar(255)\";s:19:\"beneficiaries_count\";s:16:\"int(10) unsigned\";s:13:\"budget_amount\";s:13:\"decimal(14,2)\";s:18:\"featured_image_url\";s:4:\"text\";s:11:\"is_featured\";s:10:\"tinyint(1)\";s:12:\"published_at\";s:9:\"timestamp\";s:10:\"created_by\";s:19:\"bigint(20) unsigned\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";s:10:\"deleted_at\";s:9:\"timestamp\";}s:18:\"program_categories\";a:6:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:11:\"description\";s:4:\"text\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:17:\"program_galleries\";a:6:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:10:\"program_id\";s:19:\"bigint(20) unsigned\";s:8:\"file_url\";s:4:\"text\";s:7:\"caption\";s:12:\"varchar(255)\";s:10:\"sort_order\";s:16:\"int(10) unsigned\";s:10:\"created_at\";s:9:\"timestamp\";}s:16:\"program_partners\";a:5:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:10:\"program_id\";s:19:\"bigint(20) unsigned\";s:10:\"partner_id\";s:19:\"bigint(20) unsigned\";s:4:\"role\";s:12:\"varchar(255)\";s:10:\"created_at\";s:9:\"timestamp\";}s:5:\"roles\";a:5:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:11:\"description\";s:4:\"text\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:8:\"sessions\";a:6:{s:2:\"id\";s:12:\"varchar(255)\";s:7:\"user_id\";s:19:\"bigint(20) unsigned\";s:10:\"ip_address\";s:11:\"varchar(45)\";s:10:\"user_agent\";s:4:\"text\";s:7:\"payload\";s:8:\"longtext\";s:13:\"last_activity\";s:7:\"int(11)\";}s:8:\"settings\";a:7:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:8:\"key_name\";s:12:\"varchar(255)\";s:10:\"value_text\";s:4:\"text\";s:10:\"value_type\";s:38:\"enum(\'text\',\'number\',\'boolean\',\'json\')\";s:11:\"description\";s:4:\"text\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:4:\"tags\";a:5:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:12:\"team_members\";a:13:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:4:\"slug\";s:12:\"varchar(255)\";s:8:\"position\";s:12:\"varchar(255)\";s:8:\"division\";s:12:\"varchar(255)\";s:3:\"bio\";s:4:\"text\";s:9:\"photo_url\";s:4:\"text\";s:5:\"email\";s:12:\"varchar(255)\";s:12:\"linkedin_url\";s:4:\"text\";s:10:\"sort_order\";s:16:\"int(10) unsigned\";s:9:\"is_active\";s:10:\"tinyint(1)\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}s:5:\"users\";a:13:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:5:\"email\";s:12:\"varchar(255)\";s:5:\"phone\";s:12:\"varchar(255)\";s:10:\"avatar_url\";s:4:\"text\";s:6:\"status\";s:37:\"enum(\'active\',\'inactive\',\'suspended\')\";s:13:\"last_login_at\";s:9:\"timestamp\";s:17:\"email_verified_at\";s:9:\"timestamp\";s:13:\"password_hash\";s:12:\"varchar(255)\";s:14:\"remember_token\";s:12:\"varchar(100)\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";s:10:\"deleted_at\";s:9:\"timestamp\";}s:10:\"user_roles\";a:4:{s:2:\"id\";s:19:\"bigint(20) unsigned\";s:7:\"user_id\";s:19:\"bigint(20) unsigned\";s:7:\"role_id\";s:19:\"bigint(20) unsigned\";s:10:\"created_at\";s:9:\"timestamp\";}}}',1775754316),
('laravel-cache-boost:mcp:database-schema:mysql:team_members:0:0:0:1','a:2:{s:6:\"engine\";s:5:\"mysql\";s:6:\"tables\";a:1:{s:12:\"team_members\";a:5:{s:7:\"columns\";a:14:{s:2:\"id\";a:4:{s:4:\"type\";s:19:\"bigint(20) unsigned\";s:8:\"nullable\";b:0;s:7:\"default\";N;s:14:\"auto_increment\";b:1;}s:4:\"name\";a:4:{s:4:\"type\";s:12:\"varchar(255)\";s:8:\"nullable\";b:0;s:7:\"default\";N;s:14:\"auto_increment\";b:0;}s:4:\"slug\";a:4:{s:4:\"type\";s:12:\"varchar(255)\";s:8:\"nullable\";b:0;s:7:\"default\";N;s:14:\"auto_increment\";b:0;}s:8:\"position\";a:4:{s:4:\"type\";s:12:\"varchar(255)\";s:8:\"nullable\";b:0;s:7:\"default\";N;s:14:\"auto_increment\";b:0;}s:8:\"division\";a:4:{s:4:\"type\";s:12:\"varchar(255)\";s:8:\"nullable\";b:1;s:7:\"default\";s:4:\"NULL\";s:14:\"auto_increment\";b:0;}s:11:\"division_id\";a:4:{s:4:\"type\";s:19:\"bigint(20) unsigned\";s:8:\"nullable\";b:1;s:7:\"default\";s:4:\"NULL\";s:14:\"auto_increment\";b:0;}s:3:\"bio\";a:4:{s:4:\"type\";s:4:\"text\";s:8:\"nullable\";b:1;s:7:\"default\";s:4:\"NULL\";s:14:\"auto_increment\";b:0;}s:9:\"photo_url\";a:4:{s:4:\"type\";s:4:\"text\";s:8:\"nullable\";b:1;s:7:\"default\";s:4:\"NULL\";s:14:\"auto_increment\";b:0;}s:5:\"email\";a:4:{s:4:\"type\";s:12:\"varchar(255)\";s:8:\"nullable\";b:1;s:7:\"default\";s:4:\"NULL\";s:14:\"auto_increment\";b:0;}s:12:\"linkedin_url\";a:4:{s:4:\"type\";s:4:\"text\";s:8:\"nullable\";b:1;s:7:\"default\";s:4:\"NULL\";s:14:\"auto_increment\";b:0;}s:10:\"sort_order\";a:4:{s:4:\"type\";s:16:\"int(10) unsigned\";s:8:\"nullable\";b:0;s:7:\"default\";s:1:\"1\";s:14:\"auto_increment\";b:0;}s:9:\"is_active\";a:4:{s:4:\"type\";s:10:\"tinyint(1)\";s:8:\"nullable\";b:0;s:7:\"default\";s:1:\"1\";s:14:\"auto_increment\";b:0;}s:10:\"created_at\";a:4:{s:4:\"type\";s:9:\"timestamp\";s:8:\"nullable\";b:1;s:7:\"default\";s:4:\"NULL\";s:14:\"auto_increment\";b:0;}s:10:\"updated_at\";a:4:{s:4:\"type\";s:9:\"timestamp\";s:8:\"nullable\";b:1;s:7:\"default\";s:4:\"NULL\";s:14:\"auto_increment\";b:0;}}s:7:\"indexes\";a:3:{s:7:\"primary\";a:4:{s:7:\"columns\";a:1:{i:0;s:2:\"id\";}s:4:\"type\";s:5:\"btree\";s:9:\"is_unique\";b:1;s:10:\"is_primary\";b:1;}s:32:\"team_members_division_id_foreign\";a:4:{s:7:\"columns\";a:1:{i:0;s:11:\"division_id\";}s:4:\"type\";s:5:\"btree\";s:9:\"is_unique\";b:0;s:10:\"is_primary\";b:0;}s:24:\"team_members_slug_unique\";a:4:{s:7:\"columns\";a:1:{i:0;s:4:\"slug\";}s:4:\"type\";s:5:\"btree\";s:9:\"is_unique\";b:1;s:10:\"is_primary\";b:0;}}s:12:\"foreign_keys\";a:1:{i:0;a:7:{s:4:\"name\";s:32:\"team_members_division_id_foreign\";s:7:\"columns\";a:1:{i:0;s:11:\"division_id\";}s:14:\"foreign_schema\";s:7:\"laravel\";s:13:\"foreign_table\";s:9:\"divisions\";s:15:\"foreign_columns\";a:1:{i:0;s:2:\"id\";}s:9:\"on_update\";s:8:\"restrict\";s:9:\"on_delete\";s:8:\"set null\";}}s:8:\"triggers\";a:0:{}s:17:\"check_constraints\";a:0:{}}}}',1775800756),
('laravel-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6','i:2;',1775811919),
('laravel-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer','i:1775811919;',1775811919);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
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
-- Table structure for table `consultations`
--

DROP TABLE IF EXISTS `consultations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `preferred_contact_channel` enum('email','phone','whatsapp') NOT NULL DEFAULT 'email',
  `status` enum('new','in_review','scheduled','resolved','closed') NOT NULL DEFAULT 'new',
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consultations_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `consultations_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultations`
--

LOCK TABLES `consultations` WRITE;
/*!40000 ALTER TABLE `consultations` DISABLE KEYS */;
/*!40000 ALTER TABLE `consultations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','in_review','resolved','closed') NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_categories`
--

DROP TABLE IF EXISTS `content_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('story','article','news','report','journal','opinion') NOT NULL DEFAULT 'story',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_categories`
--

LOCK TABLES `content_categories` WRITE;
/*!40000 ALTER TABLE `content_categories` DISABLE KEYS */;
INSERT INTO `content_categories` VALUES
(1,'Budaya','culture','story','Narasi lapangan tentang warisan hidup.','2026-04-08 10:37:20','2026-04-08 20:17:42'),
(2,'Pendidikan','education-stories','story','Peliputan yang berfokus pada pembelajaran dan generasi muda.','2026-04-08 10:37:20','2026-04-08 20:17:42'),
(3,'Strategi Konservasi','conservation-strategy','story','Wawasan tentang ekologi, pendampingan, dan restorasi.','2026-04-08 10:37:20','2026-04-08 20:17:42');
/*!40000 ALTER TABLE `content_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_files`
--

DROP TABLE IF EXISTS `content_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_url` text NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `content_files_content_id_foreign` (`content_id`),
  CONSTRAINT `content_files_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_files`
--

LOCK TABLES `content_files` WRITE;
/*!40000 ALTER TABLE `content_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `content_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_tags`
--

DROP TABLE IF EXISTS `content_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `content_tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_tags_content_id_tag_id_unique` (`content_id`,`tag_id`),
  KEY `content_tags_tag_id_foreign` (`tag_id`),
  CONSTRAINT `content_tags_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_tags`
--

LOCK TABLES `content_tags` WRITE;
/*!40000 ALTER TABLE `content_tags` DISABLE KEYS */;
INSERT INTO `content_tags` VALUES
(1,1,1),
(2,1,2),
(3,2,3),
(5,3,2),
(4,3,4),
(6,4,1),
(7,5,1);
/*!40000 ALTER TABLE `content_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contents`
--

DROP TABLE IF EXISTS `contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('story','article','news','report','journal','opinion') NOT NULL DEFAULT 'story',
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `body` longtext NOT NULL,
  `featured_image_url` text DEFAULT NULL,
  `author_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contents_slug_unique` (`slug`),
  KEY `contents_category_id_foreign` (`category_id`),
  KEY `contents_author_id_foreign` (`author_id`),
  CONSTRAINT `contents_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `contents_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `content_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contents`
--

LOCK TABLES `contents` WRITE;
/*!40000 ALTER TABLE `contents` DISABLE KEYS */;
INSERT INTO `contents` VALUES
(1,'Resonansi Sunyi Tradisi.','silent-resonance-of-tradition','story',1,'Praktik menenun kuno di kaki Himalaya sedang membangun masa depan berkelanjutan bagi generasi perajin berikutnya.','Tenun kuno di kaki Himalaya bukanlah artefak yang diam. Ia adalah ekonomi hidup, arsitektur sosial, dan bahasa kepedulian.\n\nCerita ini menelusuri bagaimana generasi muda perajin Giri membangun kembali kepercayaan diri, akses pasar, dan kebanggaan lokal melalui pengetahuan material yang diwariskan antarkeluarga.','https://lh3.googleusercontent.com/aida-public/AB6AXuDf9RYizB9soXzDqE3jEHfVuRyRibRCM6JAa82S3mc3OdphWi-75aPQd386QfKGmK135jVqk6j2EJVnheai6RExI7dSD54hzA3H4F6kypw08jH6XMvSH7Cxg8sSB8YIsVbXpbDT4G_X-EmYmzQMXftBRczpqYhsGMqgWnuNXTkpUkBd_31lBPBWISEw9NMLmg94W5VfS0SmJKj42lrzzgp8gg0H5FHF5V2UYDY15BHhbOGA2DTYoUQ0e4U17KnyaQ3W1mxhXwWhrME',1,'published',1,'2026-03-08 20:17:42','Resonansi Sunyi Tradisi','Cerita dari lapangan tentang tenun, warisan budaya, dan penghidupan yang tangguh.','2026-04-08 10:37:20','2026-04-08 20:27:57','2026-04-08 20:27:57'),
(2,'Arsitek Sunyi: Reforestasi di Lembah Tinggi','silent-architect-high-valleys','story',3,'Mengapa reforestasi yang mendahulukan biodiversitas lebih efektif daripada monokultur di ekosistem dataran tinggi yang rapuh.','Reforestasi menjadi arsitektur ketika dirancang untuk bertahan lebih lama daripada para penggagasnya. Di lembah tinggi, keragaman spesies dan tata kelola komunitas lebih penting daripada sekadar kepadatan tanam.\n\nLaporan lapangan ini menjelaskan mengapa memulihkan kompleksitas, bukan hanya jumlah pohon, adalah inti dari kesehatan ekologis jangka panjang.','https://lh3.googleusercontent.com/aida-public/AB6AXuAX2kFJ55NOwQWsSbAqASXL8vmzaTgSvvedZM3yGmyvKCOxMjTYU5xFxG4kAbdLcmC3SRHhDUsaXPJISz2BWYV2mQ55V8K6E8uENVo6ISAopzIiMfeYI8jWkItXMHQhLzFfk9oOFjy6l59XkivfLXO7HH9jO_1gpV3vCvCb5wbXKap6N5jghoJJdV8Xvt7x7mQVCqVksGMlX7VJJrQJrP0OZUzvZ8Ruya1_5BMG16SjTP8OZlHDyV1m62LCJK44yR9C7dQrgMDckLM',1,'published',0,'2026-03-18 20:17:42','Arsitek Sunyi','Strategi reforestasi dan pendampingan komunitas di lembah dataran tinggi.','2026-04-08 10:37:20','2026-04-08 20:27:57','2026-04-08 20:27:57'),
(3,'Melampaui Sumur: Air sebagai Pendorong Pendidikan.','beyond-the-well','story',2,'Infrastruktur air bersih mengembalikan jam belajar bagi anak perempuan di komunitas pedesaan.','Ketika akses air membaik, ruang kelas ikut berubah. Kehadiran menjadi lebih stabil, beban domestik berkurang, dan siswa kembali pada rutinitas yang membuat cita-cita terasa nyata.\n\nLaporan ini menghubungkan infrastruktur dasar dengan kesinambungan pendidikan jangka panjang.','https://lh3.googleusercontent.com/aida-public/AB6AXuDfL3wv0462YmuW5bbdedsGwD8QEmS9epG-NMs79YW_8kf_ycu4_IrHrK7cIhbmQxKthZa-kIbgYoORSoFSPPX8z14xMLsjYZW3Rg4PvzsAC32OyXXFQiwK7PGhPYZNTLsMNXo_QXd0ocGFKnPfdjBp8EFwH22FWBgziqs208AptFtOL0EHu8sZ5sBdosJpARFtHmb4DCftqnwjD54dpYHN3ivshd49bMe61tzWJmqYP2XlND2FSfWstWWDCTn2FkbyWarEa4kCCH8',1,'published',0,'2026-02-08 20:17:42','Melampaui Sumur','Akses air dan kesetaraan pendidikan di wilayah pedesaan.','2026-04-08 10:37:20','2026-04-08 20:27:57','2026-04-08 20:27:57'),
(4,'Akar Ketangguhan: Proyek Kebun Kota.','roots-of-resilience','story',3,'Bagaimana pemanfaatan lahan kosong mengubah kawasan minim pangan menjadi ekosistem lokal yang sehat dan produktif.','Pertanian kota menjadi tangguh ketika komunitas memiliki ritme tanam, panen, dan distribusinya sendiri. Dalam proyek ini, akses pangan dan martabat lokal bertumbuh bersama.','https://lh3.googleusercontent.com/aida-public/AB6AXuAcPLsnE5mSp0LPWsQWiJ8Jb6llwts_98XZ_7kj-yw7m5dSWq8NcBXEP9eZ-acHs0aCVAckFw5my5xVRwbU0dKcFCKy9G6zhWtesFPQTr2cZ4OLz-oYBMnHIgIFMg0e3X9qVJHC7ZvRKIEFXVxDr-GArDTg3kLhaB0_bqNXFusuJuQdkxoJcj6S81g5UfW19jN29QwzQCME9UKN1rM4sChuiYUR4YGy9iLOP22ks_Q4CYoaXQac-Ehr25Y9ZSGays6oLecRWNJOH2E',1,'published',0,'2026-03-04 20:17:42','Akar Ketangguhan','Pertanian yang dipimpin komunitas dan sistem pangan lokal.','2026-04-08 10:37:20','2026-04-08 20:27:57','2026-04-08 20:27:57'),
(5,'Merancang untuk Martabat: Inisiatif Hunian Baru Kami.','designing-for-dignity','story',1,'Arsitektur sebagai alat empati dan pemulihan sosial di kawasan perkotaan.','Ruang terbangun menyampaikan nilai. Entri arsip ini mengeksplorasi bagaimana desain hunian dapat memulihkan partisipasi, daya kendali, dan rasa aman secara psikologis.','https://lh3.googleusercontent.com/aida-public/AB6AXuCYYtE-YUwV86BODabku-4CrS8lVuSxgCwhX9sZagOhpigsbnSucy_UAmc6WSMu2eSQ9DApFLoWT5ZN_tD76bWsu4h_UcfTgYDiW7gBfsUPwvfAD82MgnipNh10GSRFpzeLp19Wg0I9CqxiTttD6hHWLtiOO3TbvAqOGrClVttnHg67MDi69sb96T0rP2cNc027mz_3-WSoylgwJgPHE2Xn5P60_lBa9QWixwP9zh1018O93aqjJXPurSomuorUfcpvT4hYU5uN4DE',1,'published',0,'2026-04-01 20:17:42','Merancang untuk Martabat','Arsitektur sebagai sarana pemulihan sosial.','2026-04-08 10:37:20','2026-04-08 20:27:57','2026-04-08 20:27:57');
/*!40000 ALTER TABLE `contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `divisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `divisions`
--

LOCK TABLES `divisions` WRITE;
/*!40000 ALTER TABLE `divisions` DISABLE KEYS */;
INSERT INTO `divisions` VALUES
(1,'Editorial','editorial',NULL,1,1,'2026-04-09 21:28:53','2026-04-09 21:28:53'),
(2,'Kepemimpinan','kepemimpinan',NULL,2,1,'2026-04-09 21:28:53','2026-04-09 21:28:53'),
(3,'Program','program',NULL,3,1,'2026-04-09 21:28:53','2026-04-09 21:28:53');
/*!40000 ALTER TABLE `divisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_url` text NOT NULL,
  `thumbnail_url` text DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL,
  `download_count` int(10) unsigned NOT NULL DEFAULT 0,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_slug_unique` (`slug`),
  KEY `documents_created_by_foreign` (`created_by`),
  CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donation_campaigns`
--

DROP TABLE IF EXISTS `donation_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `donation_campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext NOT NULL,
  `target_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `collected_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `banner_image_url` text DEFAULT NULL,
  `status` enum('draft','active','completed','archived') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `published_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `donation_campaigns_slug_unique` (`slug`),
  KEY `donation_campaigns_published_by_foreign` (`published_by`),
  CONSTRAINT `donation_campaigns_published_by_foreign` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donation_campaigns`
--

LOCK TABLES `donation_campaigns` WRITE;
/*!40000 ALTER TABLE `donation_campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `donation_campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donation_updates`
--

DROP TABLE IF EXISTS `donation_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `donation_updates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_url` text DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donation_updates_campaign_id_foreign` (`campaign_id`),
  CONSTRAINT `donation_updates_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `donation_campaigns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donation_updates`
--

LOCK TABLES `donation_updates` WRITE;
/*!40000 ALTER TABLE `donation_updates` DISABLE KEYS */;
/*!40000 ALTER TABLE `donation_updates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donations`
--

DROP TABLE IF EXISTS `donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `donations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` bigint(20) unsigned DEFAULT NULL,
  `donor_id` bigint(20) unsigned DEFAULT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_channel` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `message` text DEFAULT NULL,
  `proof_url` text DEFAULT NULL,
  `external_transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `donations_invoice_number_unique` (`invoice_number`),
  KEY `donations_campaign_id_foreign` (`campaign_id`),
  KEY `donations_donor_id_foreign` (`donor_id`),
  CONSTRAINT `donations_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `donation_campaigns` (`id`) ON DELETE SET NULL,
  CONSTRAINT `donations_donor_id_foreign` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donations`
--

LOCK TABLES `donations` WRITE;
/*!40000 ALTER TABLE `donations` DISABLE KEYS */;
/*!40000 ALTER TABLE `donations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donors`
--

DROP TABLE IF EXISTS `donors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `donors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donors_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donors`
--

LOCK TABLES `donors` WRITE;
/*!40000 ALTER TABLE `donors` DISABLE KEYS */;
/*!40000 ALTER TABLE `donors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
-- Table structure for table `media_library`
--

DROP TABLE IF EXISTS `media_library`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_library` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_url` text NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `file_size` bigint(20) unsigned DEFAULT NULL,
  `uploaded_by` bigint(20) unsigned DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_library_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `media_library_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_library`
--

LOCK TABLES `media_library` WRITE;
/*!40000 ALTER TABLE `media_library` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_library` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_04_08_165819_alter_users_table_for_giri_foundation',1),
(5,'2026_04_08_165819_create_organization_tables',1),
(6,'2026_04_08_165821_create_access_control_tables',1),
(7,'2026_04_08_165822_create_partner_and_program_tables',1),
(8,'2026_04_08_165824_create_content_tables',1),
(9,'2026_04_08_165826_create_document_and_page_tables',1),
(10,'2026_04_08_165827_create_donation_tables',1),
(11,'2026_04_08_165828_create_interaction_tables',1),
(12,'2026_04_08_165829_create_activity_log_table',1),
(13,'2026_04_09_025300_add_structured_content_to_pages_table',2),
(14,'2026_04_09_183424_create_divisions_and_videos_tables',3),
(15,'2026_04_09_183425_add_phase_and_division_id_to_programs_and_team_members_tables',3),
(16,'2026_04_09_183426_update_content_types_for_publications',3),
(17,'2026_04_10_055925_add_hierarchy_fields_to_team_members_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization_profiles`
--

DROP TABLE IF EXISTS `organization_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `organization_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `full_description` longtext NOT NULL,
  `vision` text NOT NULL,
  `mission` text NOT NULL,
  `values` text NOT NULL,
  `founded_date` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `whatsapp_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `google_maps_embed` text DEFAULT NULL,
  `logo_url` text DEFAULT NULL,
  `favicon_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `organization_profiles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization_profiles`
--

LOCK TABLES `organization_profiles` WRITE;
/*!40000 ALTER TABLE `organization_profiles` DISABLE KEYS */;
INSERT INTO `organization_profiles` VALUES
(1,'GIRI FOUNDATION','giri-foundation','Membangun komunitas yang tangguh melalui tindakan autentik, cerita editorial, dan kemitraan jangka panjang.','GIRI Foundation adalah organisasi dampak sosial dan budaya yang berfokus pada perubahan yang dipimpin komunitas. Kami bekerja di bidang pendidikan, restorasi ekologi, kesehatan, dan pelestarian kerajinan untuk membantu kepemimpinan lokal tumbuh secara berkelanjutan.\n\nPendekatan kami menggabungkan kejernihan editorial dengan kerja lapangan yang membumi. Kami mendokumentasikan dampak sebaik kami menjalankan program, sehingga terbentuk arsip hidup tentang program, kemitraan, dan cerita dari lapangan.','Dunia tempat komunitas membentuk masa depannya sendiri dengan martabat, kesinambungan, dan keseimbangan ekologis.','Bermitra langsung dengan pemimpin lokal, berinvestasi pada infrastruktur sosial yang tahan lama, dan menerbitkan narasi dampak yang transparan.','Integritas Radikal\nEkologi Mendalam\nPertumbuhan Bertahap','2014-04-16','hello@giri.foundation','+62 361 123 456','+62 812 0000 0000','Jl. Raya Serangan 27, Denpasar Selatan, Bali, Indonesia','https://maps.google.com/?q=Serangan+Bali',NULL,NULL,'2026-04-08 10:37:20','2026-04-08 20:17:42');
/*!40000 ALTER TABLE `organization_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization_stats`
--

DROP TABLE IF EXISTS `organization_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `organization_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `value` bigint(20) unsigned NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization_stats`
--

LOCK TABLES `organization_stats` WRITE;
/*!40000 ALTER TABLE `organization_stats` DISABLE KEYS */;
INSERT INTO `organization_stats` VALUES
(1,'Lives Impacted',10000,'+','favorite',1,1,'2026-04-08 10:37:20','2026-04-08 10:37:20'),
(2,'Rural Centers',42,NULL,'apartment',2,1,'2026-04-08 10:37:20','2026-04-08 10:37:20'),
(3,'Direct Allocation',85,'%','verified',3,1,'2026-04-08 10:37:20','2026-04-08 10:37:20'),
(4,'Penerima Manfaat',10000,'+','favorite',1,1,'2026-04-08 20:17:42','2026-04-08 20:17:42'),
(5,'Pusat Layanan',42,NULL,'apartment',2,1,'2026-04-08 20:17:42','2026-04-08 20:17:42'),
(6,'Alokasi Langsung',85,'%','verified',3,1,'2026-04-08 20:17:42','2026-04-08 20:17:42');
/*!40000 ALTER TABLE `organization_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `hero_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`hero_data`)),
  `section_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`section_data`)),
  `template` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_created_by_foreign` (`created_by`),
  CONSTRAINT `pages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES
(1,'Tentang','about','Tentang','{\"kicker\":\"Tentang Yayasan\",\"title_prefix\":\"Arsip hidup tentang kepedulian,\",\"highlight\":\"keberlanjutan\",\"title_suffix\":\", dan daya lokal.\",\"body\":\"Membangun komunitas yang tangguh melalui tindakan autentik, cerita editorial, dan kemitraan jangka panjang.\"}','{\"values\":{\"kicker\":\"Nilai Kami\",\"title\":\"Prinsip di balik setiap keputusan.\",\"description_template\":\":value membentuk cara GIRI merancang program, mendokumentasikan dampak, dan menjaga akuntabilitas kepada komunitas.\"},\"journey\":{\"kicker\":\"Perjalanan Kami\",\"title\":\"Penjagaan yang tumbuh seiring waktu.\",\"body\":\"Didirikan pada :founded_date, yayasan ini tumbuh dari inisiatif lokal menjadi platform lapangan multidisipliner.\"},\"team\":{\"kicker\":\"Tim Penggerak\",\"title\":\"Orang-orang yang menjaga arah gerak.\"}}','about','published','Tentang GIRI Foundation','Visi, misi, dan pengelolaan di balik GIRI Foundation.','2026-03-08 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:17:42'),
(2,'Kontak','contact','Kontak','{\"kicker\":\"Kontak\",\"title_prefix\":\"Mari membentuk\",\"highlight\":\"masa depan\",\"title_suffix\":\"bersama.\",\"body\":\"Hubungi kami untuk kolaborasi, permintaan editorial, kunjungan lapangan, atau pertanyaan umum.\"}',NULL,'contact','published','Kontak GIRI Foundation','Hubungi tim GIRI Foundation untuk kemitraan, media, dan kebutuhan lapangan.','2026-03-08 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:17:42'),
(3,'Donasi','donate','Donasi','{\"kicker\":\"Donasi\",\"title_prefix\":\"Danai\",\"highlight\":\"infrastruktur\",\"title_suffix\":\"yang berkelanjutan, bukan gestur sesaat.\"}','{\"documents\":{\"kicker\":\"Dokumen\",\"title\":\"Dokumen pendukung\",\"link_label\":\"Buka arsip\"}}','donate','published','Dukung GIRI Foundation','Berkontribusi pada kampanye aktif dan infrastruktur komunitas jangka panjang.','2026-03-08 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:17:42'),
(4,'Dokumen & Wawasan','resources','Dokumen & Wawasan','{\"kicker\":\"Arsip Dokumen\",\"title_prefix\":\"Dokumen &\",\"highlight\":\"Wawasan\",\"title_suffix\":\"\",\"body\":\"Telusuri laporan, kerangka kebijakan, arsip riset, dan rencana strategis yang mendukung kerja publik yayasan.\"}','{\"filters\":{\"search_label\":\"Cari\",\"search_placeholder\":\"Cari berdasarkan judul atau deskripsi...\",\"category_label\":\"Kategori\",\"submit_label\":\"Saring Arsip\"}}','resources','published','Dokumen & Wawasan GIRI Foundation','Telusuri laporan, kebijakan, riset, dan dokumen strategis.','2026-03-08 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:17:42'),
(5,'Beranda','home','Beranda','{\"kicker\":\"Arsip Hidup\",\"title_prefix\":\"Memberdayakan komunitas melalui\",\"highlight\":\"tindakan\",\"title_suffix\":\"yang autentik.\",\"body\":\"Membangun komunitas yang tangguh melalui tindakan autentik, cerita editorial, dan kemitraan jangka panjang.\",\"primary_cta_label\":\"Donasi Sekarang\",\"primary_cta_url\":\"\\/donate\",\"secondary_cta_label\":\"Jelajahi Program\",\"secondary_cta_url\":\"\\/programs\"}','{\"philosophy\":{\"kicker\":\"Filosofi Kami\",\"title\":\"Warisan kebijaksanaan, masa depan yang mandiri.\",\"body\":null,\"link_label\":\"Baca Cerita Lengkap Kami\"},\"quote\":{\"body\":\"Perubahan terjadi ketika kita berhenti melihat komunitas sebagai masalah yang harus diselesaikan, dan mulai melihatnya sebagai mitra untuk bertumbuh bersama.\",\"author_name\":\"Amara Giri\",\"author_role\":\"Pendiri & Direktur\"},\"initiatives\":{\"kicker\":\"Inisiatif Aktif\",\"title\":\"Di mana dukungan Anda bekerja.\",\"featured_cta_label\":\"Lihat Detail Program\"},\"campaign\":{\"kicker\":\"Kampanye Saat Ini\",\"cta_label\":\"Dukung Proyek Ini\"},\"stories\":{\"title\":\"Cerita Terbaru\",\"link_label\":\"Lihat Semua Cerita\"}}','home','published','GIRI Foundation Indonesia','Membangun komunitas yang tangguh melalui tindakan autentik, cerita editorial, dan kemitraan jangka panjang.','2026-03-08 20:17:42',1,'2026-04-08 20:17:42','2026-04-08 20:17:42'),
(6,'Program','programs','Program','{\"kicker\":\"Program\",\"title_prefix\":\"Mengarsipkan\",\"highlight\":\"potensi\",\"title_suffix\":\".\",\"body\":\"Jelajahi inisiatif tempat GIRI berinvestasi pada sistem yang tangguh, kapasitas lokal, dan pendampingan jangka panjang.\"}','{\"filters\":{\"search_label\":\"Cari\",\"search_placeholder\":\"Cari program...\",\"category_label\":\"Kategori\",\"submit_label\":\"Terapkan Filter\"},\"featured\":{\"badge\":\"Inisiatif Unggulan\",\"status_label\":\"Aktif\",\"location_label\":\"Lokasi\",\"duration_label\":\"Durasi\",\"cta_label\":\"Lihat Detail Program\"},\"listing\":{\"kicker\":\"Program Berjalan\",\"title\":\"Daftar program\",\"count_label\":\"Menampilkan :count program\"}}','programs','published','Program GIRI Foundation','Jelajahi inisiatif tempat GIRI berinvestasi pada sistem yang tangguh, kapasitas lokal, dan pendampingan jangka panjang.','2026-03-08 20:17:42',1,'2026-04-08 20:17:42','2026-04-08 20:17:42'),
(7,'Cerita Dari Lapangan','stories','Cerita Dari Lapangan','{\"kicker\":\"Cerita Pilihan\",\"primary_cta_label\":\"Baca Arsip Lengkap\"}','{\"newsletter\":{\"title\":\"Tetap terhubung dengan cerita yang penting.\",\"body\":\"Ikuti ringkasan editorial bulanan kami untuk pembahasan mendalam tentang dampak, budaya, dan daya hidup manusia.\"},\"archive\":{\"kicker\":\"Lebih Banyak Dari Arsip\"}}','stories','published','Cerita Dari Lapangan','Ikuti arsip cerita, narasi dampak, dan laporan lapangan dari inisiatif GIRI Foundation.','2026-03-08 20:17:42',1,'2026-04-08 20:17:42','2026-04-08 20:17:42'),
(8,'Kemitraan','partners','Kemitraan','{\"kicker\":\"Kemitraan\",\"title_prefix\":\"Merancang\",\"highlight\":\"perubahan\",\"title_suffix\":\"bersama.\",\"body\":\"Kami membangun relasi jangka panjang dengan institusi, komunitas, dan pendukung yang peduli pada sistem yang bertahan lama, bukan perhatian sesaat.\"}','{\"highlight\":{\"label\":\"Mitra Aktif\",\"body\":\"Kolaborasi mencakup filantropi, jaringan komunitas, implementasi strategis, dan akuntabilitas naratif.\"},\"collaborators\":{\"kicker\":\"Kolaborator Utama\",\"title\":\"Arsip kolaborasi dengan institusi yang sejalan.\"},\"programs\":{\"kicker\":\"Program Bersama Mitra\",\"title\":\"Tempat kolaborasi menjadi aksi.\"},\"inquiry\":{\"kicker\":\"Kembangkan Arsip Bersama Kami\",\"title\":\"Mulai percakapan kemitraan.\",\"submit_label\":\"Kirim Permintaan\"}}','partners','published','Kemitraan GIRI Foundation','Kemitraan jangka panjang dengan institusi, komunitas, dan pendukung yang sejalan dengan misi GIRI Foundation.','2026-03-08 20:17:42',1,'2026-04-08 20:17:42','2026-04-08 20:17:42');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo_url` text DEFAULT NULL,
  `website_url` text DEFAULT NULL,
  `type` enum('foundation','ngo','corporate','community','government','media') NOT NULL DEFAULT 'ngo',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partners_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partnership_inquiries`
--

DROP TABLE IF EXISTS `partnership_inquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `partnership_inquiries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `inquiry_type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','in_review','resolved','closed') NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partnership_inquiries`
--

LOCK TABLES `partnership_inquiries` WRITE;
/*!40000 ALTER TABLE `partnership_inquiries` DISABLE KEYS */;
/*!40000 ALTER TABLE `partnership_inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `program_categories`
--

DROP TABLE IF EXISTS `program_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `program_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_categories`
--

LOCK TABLES `program_categories` WRITE;
/*!40000 ALTER TABLE `program_categories` DISABLE KEYS */;
INSERT INTO `program_categories` VALUES
(1,'Keberlanjutan','sustainability','Restorasi ekologi dan penghidupan regeneratif.','2026-04-08 10:37:20','2026-04-08 20:17:42'),
(2,'Pendidikan','education','Pembelajaran, literasi, dan akses digital.','2026-04-08 10:37:20','2026-04-08 20:17:42'),
(3,'Kesehatan','health','Kesejahteraan komunitas dan jangkauan layanan medis.','2026-04-08 10:37:20','2026-04-08 20:17:42'),
(4,'Pelestarian Kerajinan','craft-preservation','Warisan hidup dan ketangguhan perajin.','2026-04-08 10:37:20','2026-04-08 20:17:42');
/*!40000 ALTER TABLE `program_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `program_galleries`
--

DROP TABLE IF EXISTS `program_galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_galleries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `file_url` text NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `program_galleries_program_id_foreign` (`program_id`),
  CONSTRAINT `program_galleries_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_galleries`
--

LOCK TABLES `program_galleries` WRITE;
/*!40000 ALTER TABLE `program_galleries` DISABLE KEYS */;
INSERT INTO `program_galleries` VALUES
(5,1,'https://lh3.googleusercontent.com/aida-public/AB6AXuBec1pJ4_kRV7Iy2EnlGg8KwASf8OAzbs6g2_DSaDLO3ZcgijXmufFKzQh-M4q9nOBiH5-vT0Y90bt3TQlLxzaes0Rf2vz74fCGs9DdS_MxxXnVZVb59_tFuboaUkdTMhmGZeLAxolkzh2AzZeddX7Kh5gApN2vffCt7M1hq3lqnQxX0IaDbHnXSskz-ntZ9yXPDZdNTAq7gXbndUC6X0v9iUEp9REuw_zecLJga3CqYZKhcUWY1IsBoxllOLUe2DKn2oW2oD8IeUQ','Momen belajar yang terfokus dari kelas lapangan.',1,'2026-04-09 03:17:42'),
(6,1,'https://lh3.googleusercontent.com/aida-public/AB6AXuBi-4QmdDguTAQekNX6JRa7Ugzn_HzIazQEuBRrYDqcJ4t7E02NTBsuEVun9G2w1SZX9OZTVaIS0OjwwumEDXZx3lqX9TZK7H92FrWqJ4TpsMCG981ofBQKjCp7S5lgcuf1zdhvztsKnH4-Jr65fp9mCOBK-xf0TKO9I58zdK57Xslo7CjWjb7V9UL_roz2IwmL146GaCfzSrlASXmq0xb_wVx8rtvtFi7FlkxHb8oPEM1J_qIl0Lx7NTmIllfdr3RWsxlsyKr1fSU','Ruang belajar yang dirancang untuk iklim dan komunitas.',2,'2026-04-09 03:17:42'),
(7,1,'https://lh3.googleusercontent.com/aida-public/AB6AXuDGuEHJT4ZS_nKHhVU_ZG0_pkqQZRRujkpjnSG4rBlxBsFEi2FkB4tirwJA6aFgSdBTsiLKUEE-QbfZUM3RCqiMqP_3SREhJeIOomWC0HL0nThJKTQ08sJoYI1R-Wc0IpnMfKAOS7qjKLt7lBmUrcpZEZljfRV4jcsfZJU1wj-xs7p5MB05vM1gFny_H_q_kYZ_ry2ueKdo3xJeY0eKA5E8uXX5h6EXwfIZBBKFOeNNR2ihVoShsr-ylyyjL5UiGijV1pELSyGCpeY','Pengelola komunitas dan pendidik menyusun langkah berikutnya.',3,'2026-04-09 03:17:42'),
(8,1,'https://lh3.googleusercontent.com/aida-public/AB6AXuDrqYwRUkhL2lZ1xERQzt0HnE-2ybW_78uCB9RghgMNJPFfnVLkRTvBW3-WVn9lP_FWsqet0xhwgf3rrvkyHt1Xxr2H38rRAYpkbpzE3rLMkL_wL3I_6rf95r8sNoD9FnpDHlGGbdLsG4tAtxB7EoReWObpNHkCIVN0bSEWLPSPVhfg6UxaSXig64mMl5EuCznF451NSdnzRwNZIAwuoG-QlkhVJDXnW3GjJdpdvxwBZxKxQocUAQmPn1F95lae_06havWt-0L_bwk','Penanaman mangrove yang terintegrasi dalam program siswa.',4,'2026-04-09 03:17:42');
/*!40000 ALTER TABLE `program_galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `program_partners`
--

DROP TABLE IF EXISTS `program_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_partners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `program_id` bigint(20) unsigned NOT NULL,
  `partner_id` bigint(20) unsigned NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `program_partners_program_id_partner_id_unique` (`program_id`,`partner_id`),
  KEY `program_partners_partner_id_foreign` (`partner_id`),
  CONSTRAINT `program_partners_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `program_partners_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_partners`
--

LOCK TABLES `program_partners` WRITE;
/*!40000 ALTER TABLE `program_partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `program_partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `programs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `description` longtext NOT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('draft','published','completed','archived') NOT NULL DEFAULT 'draft',
  `phase` enum('active','upcoming','archived') NOT NULL DEFAULT 'active',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `target_beneficiaries` varchar(255) DEFAULT NULL,
  `beneficiaries_count` int(10) unsigned NOT NULL DEFAULT 0,
  `budget_amount` decimal(14,2) DEFAULT NULL,
  `featured_image_url` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `programs_slug_unique` (`slug`),
  KEY `programs_category_id_foreign` (`category_id`),
  KEY `programs_created_by_foreign` (`created_by`),
  CONSTRAINT `programs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `program_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `programs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
INSERT INTO `programs` VALUES
(1,'Proyek Restorasi Hutan Sakral','sacred-grove-restoration-project','Memulihkan kembali keanekaragaman hayati asli di kawasan hutan terdegradasi bersama pengelola lokal dan perencanaan jangka panjang.','Proyek Restorasi Hutan Sakral membangun kembali ingatan ekologis di 400 hektare hutan yang rusak. Pekerjaan ini dirancang sebagai kemitraan selama satu dekade dengan komunitas lokal, memadukan pola tanam leluhur, bank benih asli, dan pemulihan tanah.\n\nLebih dari sekadar menanam, program ini melatih penjaga muda, mengoordinasikan pemantauan komunitas, dan mendokumentasikan perubahan ekologis agar setiap musim kerja menjadi bagian dari arsip bersama.',1,'published','active','2023-02-01','2033-02-01','Western Ghats, India','Karnataka','Kodagu','Komunitas tepian hutan',1200,780000.00,'https://lh3.googleusercontent.com/aida-public/AB6AXuBFYhN-0c03inkP_Cj4j0eXzi40fhIUrVWMjwQLmjwJc2IRCyelpZ4fkIsPskNXwH1gDtcQ_8FL0RoXOpPvwpDkf7BOp6euGW-nVvskrOkMgh3OTdZjejNdFapYm-ALh31j1skBMT1_Cxw5PfN2GCYYJrxf3K6P2a6vWM-NARlnmiuGbYvq0QRnZHKML3p5p6iE7N9saXHxyiQtFDqSqik9ykkNV5yaRNDduQYFjUFwh9GEf4tuE3gr8J4U7sECm2i63aTIJgAbZh8',1,'2025-12-08 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:26:09','2026-04-08 20:26:09'),
(2,'Pertanian Regeneratif','regenerative-agriculture','Membekali petani dengan teknik tangguh iklim dan pengetahuan leluhur untuk memulihkan kesehatan tanah.','Program lapangan yang berfokus pada perbaikan tanah, kedaulatan benih, dan eksperimen yang dipimpin petani di wilayah pertanian berteras.',1,'published','active','2024-01-01','2028-12-31','Deccan Plateau','Maharashtra','Pune','Petani kecil',860,350000.00,'https://lh3.googleusercontent.com/aida-public/AB6AXuCY-8DQxaKqT4iHnRjetnRkhxgQH0vqCwfWOhUxUtKjILWuxofPEFtZfRu2EWVV7ZIEZZfwW59b62QRfjcyYRmkyqYsGOggiTRPc08ToZLHC90relVl4CVwU-1aMGqa6tlrubx8w9z_tO9SNTnmhoILqtbVObSDmv5X6mVe3p_a4gkCu70ai_EFhaoPepbmsyUZ7c8OUz-0kkgk1wq-dlVv_x_uxO2fgH2CyO1vPvJbb9K6EabvPbo0MLL8hCoHtnRUCrwap1l2c-Q',0,'2026-02-08 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:26:09','2026-04-08 20:26:09'),
(3,'Proyek Literasi','literacy-project','Membangun perpustakaan komunitas dan kelas digital bergerak.','Dirancang untuk menutup kesenjangan literasi di komunitas terpencil melalui perpustakaan, fasilitator, dan perangkat belajar digital.',2,'published','active','2023-08-01',NULL,'Tribal Odisha','Odisha','Koraput','Anak-anak sekolah dasar',1100,240000.00,'https://lh3.googleusercontent.com/aida-public/AB6AXuDulTC4UKy__dFz8ljhhzPeZgOyBvqQ4UES-BfkH0gKK9LCW4HS5isAz7JeuecPzaFfdhRNOieKf5nn2I8Tnghd0L7Fbzqh-F2314b6yxKfi_1A7NQ4nLzhCnIWrQX2QVrXkMrItxOJ5di85G1CswseKxf_QDJT7I2zQzb9wlGP9nokeSb4B9x6ShDMwjyC4mL8IELJS_FKFDQGNEFDoqkDiItAZOX44peD9xo2rthzHoJCO6BCx9sJXtw1U9CeuwDafuMXjccr5S0',0,'2026-01-08 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:26:09','2026-04-08 20:26:09'),
(4,'Pusat Kesehatan Holistik','holistic-health-hubs','Mengintegrasikan pengobatan modern dengan praktik kesehatan tradisional.','Model layanan hibrida yang menghadirkan pemeriksaan rutin, kesehatan ibu, dan perawatan pencegahan ke pusat komunitas terpencil.',3,'published','active','2024-02-01',NULL,'North Lombok','West Nusa Tenggara','Tanjung','Keluarga pesisir',540,315000.00,'https://lh3.googleusercontent.com/aida-public/AB6AXuDCtdWj5Ioa6lNDF4yOK7es9i47ayuvDTpMqE7Z2EtXt40oOCYjWLrZaiX2ELu8Gzr-jL-WdHc8TdJtGZcoIxDRt_yU2oAbZ72ej9f7S_hG6ZglG622W-XXkRwwtohs15qlICLYcxehoofBhNJ6tH2cd8oQ3-ioBX6721Rm7-SmSvTz_y8Kbtr-ec7vgFQLsqfoXbgBS6l27CMpO9NKgZ-EopOrKZS5lDbt1I__39fbKNt3rIkua9sXV33TXBOj9wXOWh9I7qWudAE',0,'2026-03-08 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:26:09','2026-04-08 20:26:09'),
(5,'Kolektif Penenun Indigo','indigo-weavers-collective','Menjaga upah layak dan melestarikan praktik menenun lintas generasi.','Inisiatif pelestarian budaya yang mempertemukan pewarna ahli dengan perajin muda serta akses pasar yang etis.',4,'published','active','2022-06-01',NULL,'Kachchh, Gujarat','Gujarat','Bhuj','Keluarga perajin',230,175000.00,'https://lh3.googleusercontent.com/aida-public/AB6AXuCfmPSkkCP_g1f1jZmEfumcs2FJLhJT0S4V19rnmSvoaLMnz0kcIC7JhocfLwAG3ZTXBB4qpOjovG9u0ajlMljt2p89tNIC6euiCPn5DXDocptAYWVlvFxSaWRzTfSTTJQCYsl-rk8gDx22LoaQA-zj9ZrsMSrpTKX8yFuzj1BqtNJ7VWiKcRY3utD5U5WUdU1M8Mn-LSB0YvZF3ZOjcYtmoTq2_RV5P45QkEPvpDVXXA__a-dEjL7C0N3iUziGi-i6VHCiircAYWE',0,'2025-11-08 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:26:09','2026-04-08 20:26:09'),
(6,'Layanan Kesehatan Bergerak','mobile-health-sanctuaries','Membawa layanan medis yang konsisten ke wilayah yang sulit dijangkau.','Sistem layanan bergerak untuk pengobatan preventif, kunjungan berkala, dan koordinasi kesehatan ibu.',3,'published','active','2024-05-15',NULL,'Rural Bali','Bali','Karangasem','Desa terpencil',120,98000.00,'https://lh3.googleusercontent.com/aida-public/AB6AXuAodo9C9RXzfN4yKOiDcwJc0wv2uQVLZPMwHX9ydjDGjTDb_J7Niq8CwQcOh1SZqcLL8Np9WUdCwmi7mD_oSjCADjrsx_bLWqQSuIKhGQ7oVMbvzP5Lt4tgDS9qITh2NVKJjMMcGkaxFu-u7Mxka5fDtpbrDUriuXrFul5pBnkEpFKPWa8y98mgYMXQZEfwITd1vbbi9GhBFG19S5tgfMhgxhQf11irvHlZvp4UX0028w3o91OPCYPhkA_0NS3yhBJgaRekkD8a__o',0,'2026-03-25 20:17:42',1,'2026-04-08 10:37:20','2026-04-08 20:26:09','2026-04-08 20:26:09');
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'Editor','Menerbitkan dan mengurasi konten yang tampil untuk publik.','2026-04-08 10:37:20','2026-04-08 20:17:42');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
INSERT INTO `sessions` VALUES
('1UOgAC1qNX7HjJFfzLl5BDw5HZwl3FmAAJahPkTS',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI3R3RBTDMxQ2RKR1hmcHV0a01zYVI2emlXRTdLYlJmbk4wQmJ6SVFDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wcm9ncmFtcyIsInJvdXRlIjoicHJvZ3JhbXMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775802538),
('2BI2VPmwdJ5adEf1BO37mLaLGiux8C7S9eJm4FFm',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJ6a1lSUnRpVVhMemlsVUJrRGMzYUtvQ2RkTzBsSFhUVDNwZHlBaFVoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zdG9yaWVzIiwicm91dGUiOiJzdG9yaWVzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiY2YzMWJkNjNmNGNkYWU0NDRjMzUzM2ZkYjg2Zjc3YTA0ZGM0ZWUzMmIwYjFmODYzYmJkOTQxN2Y5YjNkNzc0NyIsInRhYmxlcyI6eyI4ZmIxYjExY2E4ZDg0NDk5NWZkYzAwOTcxNzc1ZDZhZl9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InRpdGxlIiwibGFiZWwiOiJUaXRsZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzbHVnIiwibGFiZWwiOiJTbHVnIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNhdGVnb3J5IiwibGFiZWwiOiJDYXRlZ29yeSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJmaWxlX3R5cGUiLCJsYWJlbCI6IkZpbGUgdHlwZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJmaWxlX3NpemUiLCJsYWJlbCI6IkZpbGUgc2l6ZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJkb3dubG9hZF9jb3VudCIsImxhYmVsIjoiRG93bmxvYWQgY291bnQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfcHVibGljIiwibGFiZWwiOiJJcyBwdWJsaWMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicHVibGlzaGVkX2F0IiwibGFiZWwiOiJQdWJsaXNoZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY3JlYXRvci5uYW1lIiwibGFiZWwiOiJDcmVhdG9yIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNyZWF0ZWRfYXQiLCJsYWJlbCI6IkNyZWF0ZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6ZmFsc2UsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0Ijp0cnVlfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidXBkYXRlZF9hdCIsImxhYmVsIjoiVXBkYXRlZCBhdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjpmYWxzZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOnRydWV9XX19',1775753845),
('8KmjoTT1omUVM4G3FR9VkUdwx5g0yXrudPNu2qvF',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJ2WVFONXh0eFk1cENlS2hoWnZlbnR5MHY3NXRVMFBBaWFadlA2UXZRIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb250YWN0Iiwicm91dGUiOiJjb250YWN0LnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1775821599),
('b2nFP68RrH40uEeBEUtbs7XkPSAWNkj1X8efLYFo',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJqUXh4dENrSGpyZnltUmlpR3Z5Y2J4dk1JdjBpcVNtNGdGcmREUnpSIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbiIsInJvdXRlIjoiZmlsYW1lbnQuYWRtaW4ucGFnZXMuZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiY2YzMWJkNjNmNGNkYWU0NDRjMzUzM2ZkYjg2Zjc3YTA0ZGM0ZWUzMmIwYjFmODYzYmJkOTQxN2Y5YjNkNzc0NyIsInRhYmxlcyI6eyJmYzhjNmMzZDYwODkyMTBkZThiNThjZTI0YzgyNDQwY19jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InVzZXIubmFtZSIsImxhYmVsIjoiVXNlciIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJhY3Rpb24iLCJsYWJlbCI6IkFjdGlvbiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJtb2R1bGUiLCJsYWJlbCI6Ik1vZHVsZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJyZWNvcmRfaWQiLCJsYWJlbCI6IlJlY29yZCBpZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpcF9hZGRyZXNzIiwibGFiZWwiOiJJcCBhZGRyZXNzIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNyZWF0ZWRfYXQiLCJsYWJlbCI6IkNyZWF0ZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6ZmFsc2UsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0Ijp0cnVlfV19fQ==',1775811937);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key_name` varchar(255) NOT NULL,
  `value_text` text DEFAULT NULL,
  `value_type` enum('text','number','boolean','json') NOT NULL DEFAULT 'text',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_name_unique` (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES
(1,'Arsip','archive','2026-04-08 10:37:20','2026-04-08 20:17:42'),
(2,'Komunitas','community','2026-04-08 10:37:20','2026-04-08 20:17:42'),
(3,'Konservasi','conservation','2026-04-08 10:37:20','2026-04-08 20:17:42'),
(4,'Pendidikan','education','2026-04-08 10:37:20','2026-04-08 20:17:42');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `division` varchar(255) DEFAULT NULL,
  `division_id` bigint(20) unsigned DEFAULT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `photo_url` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `linkedin_url` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 1,
  `is_structural` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_members_slug_unique` (`slug`),
  KEY `team_members_division_id_foreign` (`division_id`),
  KEY `team_members_parent_id_foreign` (`parent_id`),
  CONSTRAINT `team_members_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `team_members_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `team_members` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_members`
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` VALUES
(1,'Amara Giri','amara-giri','Pendiri & Direktur','Kepemimpinan',2,NULL,'Mengarahkan strategi jangka panjang, arah editorial, dan kemitraan lintas sektor.','https://lh3.googleusercontent.com/aida-public/AB6AXuDd4z7nTQSjqfuAqRvGhbXN6gwkqG9sMxWvi1s8d4w2jvRtNS6OrDAJzNLGusyazVuZsAgIO19QzbEeBrUcG1xEnVD9queWEfW8OxzETEJqADLd9OWejXJxn-xSdyxKgF889qvd_eMZWP6jkUrKRJznz2u48VasacRHqBQYVFqMsbZIZ4yw_A-Rqei5CXHGemWQ8S-8miFwwoq3cDk8rctPMdYwGL-ev7SZnHcyIDUwIgEUkV9Gf0VMMY0jbp8bjwUPT_-qVkxHGVw','amara@giri.foundation','https://linkedin.com/in/amara-giri',1,1,1,'2026-04-08 10:37:20','2026-04-08 20:17:42'),
(2,'Elena Vance','elena-vance','Editor Lapangan','Editorial',1,NULL,'Memimpin peliputan mendalam dari inisiatif pendidikan dan pelestarian budaya.','https://lh3.googleusercontent.com/aida-public/AB6AXuDf9RYizB9soXzDqE3jEHfVuRyRibRCM6JAa82S3mc3OdphWi-75aPQd386QfKGmK135jVqk6j2EJVnheai6RExI7dSD54hzA3H4F6kypw08jH6XMvSH7Cxg8sSB8YIsVbXpbDT4G_X-EmYmzQMXftBRczpqYhsGMqgWnuNXTkpUkBd_31lBPBWISEw9NMLmg94W5VfS0SmJKj42lrzzgp8gg0H5FHF5V2UYDY15BHhbOGA2DTYoUQ0e4U17KnyaQ3W1mxhXwWhrME','elena@giri.foundation','https://linkedin.com/in/elena-vance',2,1,1,'2026-04-08 10:37:20','2026-04-08 20:17:42'),
(3,'Raka Nirwan','raka-nirwan','Arsitek Program','Program',3,NULL,'Mengoordinasikan inisiatif multi-tahun di bidang pertanian regeneratif dan pendidikan pesisir.','https://lh3.googleusercontent.com/aida-public/AB6AXuCYYtE-YUwV86BODabku-4CrS8lVuSxgCwhX9sZagOhpigsbnSucy_UAmc6WSMu2eSQ9DApFLoWT5ZN_tD76bWsu4h_UcfTgYDiW7gBfsUPwvfAD82MgnipNh10GSRFpzeLp19Wg0I9CqxiTttD6hHWLtiOO3TbvAqOGrClVttnHg67MDi69sb96T0rP2cNc027mz_3-WSoylgwJgPHE2Xn5P60_lBa9QWixwP9zh1018O93aqjJXPurSomuorUfcpvT4hYU5uN4DE','raka@giri.foundation','https://linkedin.com/in/raka-nirwan',3,1,1,'2026-04-08 10:37:20','2026-04-08 20:17:42');
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  KEY `user_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES
(1,1,1,'2026-04-08 17:37:20');
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar_url` text DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Meja Editorial GIRI','admin@giri.foundation','+62 812-0000-0000','https://lh3.googleusercontent.com/aida-public/AB6AXuDd4z7nTQSjqfuAqRvGhbXN6gwkqG9sMxWvi1s8d4w2jvRtNS6OrDAJzNLGusyazVuZsAgIO19QzbEeBrUcG1xEnVD9queWEfW8OxzETEJqADLd9OWejXJxn-xSdyxKgF889qvd_eMZWP6jkUrKRJznz2u48VasacRHqBQYVFqMsbZIZ4yw_A-Rqei5CXHGemWQ8S-8miFwwoq3cDk8rctPMdYwGL-ev7SZnHcyIDUwIgEUkV9Gf0VMMY0jbp8bjwUPT_-qVkxHGVw','active',NULL,NULL,'$2y$12$sszvbgujlUiNzoFdQGk0r.DdhzITWGTTS.8IVSWCJn5x2s0UTQlOG',NULL,'2026-04-08 10:37:20','2026-04-08 20:17:42',NULL),
(2,'admin','admin@gmail.com',NULL,NULL,'active',NULL,NULL,'$2y$12$Mj/C6cbxXuBPvNFMZ1jS8.Ow060mrMqjF.WMmP/1RQ2AEWP43/jsC',NULL,'2026-04-08 20:24:27','2026-04-08 20:24:27',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `videos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `youtube_url` text NOT NULL,
  `thumbnail_url` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 1,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `videos_slug_unique` (`slug`),
  KEY `videos_created_by_foreign` (`created_by`),
  CONSTRAINT `videos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-10 23:09:02
