-- -------------------------------------------------------------
-- TablePlus 4.6.8(424)
--
-- https://tableplus.com/
--
-- Database: App
-- Generation Time: 2024-02-04 21:16:09.8000
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `subscribers`;
CREATE TABLE `subscribers`
(
    `id`         int                                     NOT NULL AUTO_INCREMENT,
    `email`      varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
    `name`       varchar(50) COLLATE utf8mb4_general_ci  NOT NULL,
    `last_name`  varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `status`     smallint                               DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `u_email` (`email`),
    KEY          `idx_email` (`email`),
    KEY          `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `subscribers` (`id`,
                           `email`,
                           `name`,
                           `last_name`,
                           `status`,
                           `created_at`,
                           `updated_at`)
VALUES (1, 'john.doe@example.com', 'John', 'Doe', 1, '2024-01-30 07:26:43', '2024-01-30 18:11:37'),
       (2, 'jane.smith@example.com', 'Jane', 'Smith', 1, '2024-01-31 19:00:34', '2024-01-31 19:00:34'),
       (3, 'michael.johnson@example.com', 'Michael', 'Johnson', 0, '2024-01-31 19:59:24', '2024-01-31 19:59:24'),
       (4, 'lisa.brown@example.com', 'Lisa', 'Brown', 0, '2024-01-31 19:59:35', '2024-01-31 19:59:35'),
       (5, 'david.miller@example.com', 'David', 'Miller', 0, '2024-02-01 17:23:34', '2024-02-01 17:23:34'),
       (6, 'susan.wilson@example.com', 'Susan', 'Wilson', 1, '2024-02-01 19:28:12', '2024-02-01 19:28:12'),
       (7, 'robert.moore@example.com', 'Robert', 'Moore', 0, '2024-02-02 09:35:41', '2024-02-02 09:35:41'),
       (8, 'patricia.taylor@example.com', 'Patricia', 'Taylor', 0, '2024-02-02 09:59:46', '2024-02-02 09:59:46'),
       (9, 'charles.anderson@example.com', 'Charles', 'Anderson', 0, '2024-02-02 10:50:00', '2024-02-02 10:50:00'),
       (10, 'emily.thomas@example.com', 'Emily', 'Thomas', 1, '2024-02-02 10:58:36', '2024-02-02 10:58:36'),
       (11, 'daniel.jackson@example.com', 'Daniel', 'Jackson', 0, '2024-02-02 11:01:52', '2024-02-02 11:01:52'),
       (12, 'nancy.white@example.com', 'Nancy', 'White', 0, '2024-02-02 11:12:55', '2024-02-02 11:12:55'),
       (13, 'joseph.harris@example.com', 'Joseph', 'Harris', 0, '2024-02-02 16:39:08', '2024-02-02 16:39:08'),
       (14, 'sarah.martin@example.com', 'Sarah', 'Martin', 0, '2024-02-02 17:04:14', '2024-02-02 17:04:14'),
       (15, 'kevin.thompson@example.com', 'Kevin', 'Thompson', 0, '2024-02-04 09:18:18', '2024-02-04 09:18:18'),
       (16, 'karen.garcia@example.com', 'Karen', 'Garcia', 1, '2024-02-04 09:32:48', '2024-02-04 09:32:48'),
       (17, 'brian.martinez@example.com', 'Brian', 'Martinez', 1, '2024-02-04 15:05:18', '2024-02-04 15:05:18'),
       (18, 'dorothy.robinson@example.com', 'Dorothy', 'Robinson', 1, '2024-02-04 15:12:17', '2024-02-04 15:12:17'),
       (19, 'stephen.clark@example.com', 'Stephen', 'Clark', 1, '2024-02-04 15:19:29', '2024-02-04 15:19:29'),
       (20, 'rebecca.rodriguez@example.com', 'Rebecca', 'Rodriguez', 0, '2024-02-04 15:23:48', '2024-02-04 15:23:48'),
       (21, 'george.lewis@example.com', 'George', 'Lewis', 1, '2024-02-04 15:23:56', '2024-02-04 15:23:56'),
       (22, 'betty.walker@example.com', 'Betty', 'Walker', 1, '2024-02-04 15:30:58', '2024-02-04 15:30:58'),
       (23, 'paul.hall@example.com', 'Paul', 'Hall', 1, '2024-02-04 15:33:32', '2024-02-04 15:33:32'),
       (24, 'jennifer.allen@example.com', 'Jennifer', 'Allen', 0, '2024-02-04 15:34:13', '2024-02-04 15:34:13'),
       (25, 'frank.young@example.com', 'Frank', 'Young', 1, '2024-02-04 15:39:10', '2024-02-04 15:39:10'),
       (26, 'linda.scott@example.com', 'Linda', 'Scott', 1, '2024-02-04 15:40:13', '2024-02-04 15:40:13'),
       (27, 'scott.green@example.com', 'Scott', 'Green', 0, '2024-02-04 15:41:56', '2024-02-04 15:41:56'),
       (28, 'olivia.adams@example.com', 'Olivia', 'Adams', 0, '2024-02-04 15:43:32', '2024-02-04 15:43:32'),
       (29, 'benjamin.nelson@example.com', 'Benjamin', 'Nelson', 0, '2024-02-04 15:44:22', '2024-02-04 15:44:22'),
       (30, 'deborah.carter@example.com', 'Deborah', 'Carter', 0, '2024-02-04 15:45:39', '2024-02-04 15:45:39'),
       (31, 'mark.mitchell@example.com', 'Mark', 'Mitchell', 0, '2024-02-04 15:49:50', '2024-02-04 15:49:50'),
       (32, 'laura.perez@example.com', 'Laura', 'Perez', 0, '2024-02-04 15:51:57', '2024-02-04 15:51:57'),
       (33, 'larry.roberts@example.com', 'Larry', 'Roberts', 0, '2024-02-04 15:56:24', '2024-02-04 15:56:24'),
       (34, 'julie.turner@example.com', 'Julie', 'Turner', 1, '2024-02-04 16:57:09', '2024-02-04 16:57:09');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;