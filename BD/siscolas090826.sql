-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para siscolas
CREATE DATABASE IF NOT EXISTS `siscolas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `siscolas`;

-- Volcando estructura para tabla siscolas.operador_servicios
CREATE TABLE IF NOT EXISTS `operador_servicios` (
  `id_opeserv` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_servicio` int DEFAULT NULL,
  `estado_os` tinyint DEFAULT '1',
  `creado_os` datetime DEFAULT CURRENT_TIMESTAMP,
  `actualizado_os` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_opeserv`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_servicio` (`id_servicio`),
  CONSTRAINT `ope_serv_serv` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicios`),
  CONSTRAINT `ope_serv_user` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=435 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla siscolas.operador_servicios: ~10 rows (aproximadamente)
INSERT INTO `operador_servicios` (`id_opeserv`, `id_usuario`, `id_servicio`, `estado_os`, `creado_os`, `actualizado_os`) VALUES
	(422, 3, 4, 1, '2026-08-09 15:37:45', '2026-08-09 15:37:45'),
	(423, 3, 18, 1, '2026-08-09 15:37:45', '2026-08-09 15:37:45'),
	(424, 3, 1, 1, '2026-08-09 15:37:45', '2026-08-09 15:37:45'),
	(425, 3, 3, 1, '2026-08-09 15:37:45', '2026-08-09 15:37:45'),
	(426, 3, 5, 1, '2026-08-09 15:37:45', '2026-08-09 15:37:45'),
	(427, 3, 8, 1, '2026-08-09 15:37:45', '2026-08-09 15:37:45'),
	(428, 3, 2, 1, '2026-08-09 15:37:45', '2026-08-09 15:37:45'),
	(429, 39, 4, 1, '2026-08-09 15:49:27', '2026-08-09 15:49:27'),
	(430, 39, 1, 1, '2026-08-09 15:49:27', '2026-08-09 15:49:27'),
	(431, 39, 3, 1, '2026-08-09 15:49:27', '2026-08-09 15:49:27'),
	(432, 39, 5, 1, '2026-08-09 15:49:27', '2026-08-09 15:49:27'),
	(433, 39, 8, 1, '2026-08-09 15:49:27', '2026-08-09 15:49:27'),
	(434, 39, 2, 1, '2026-08-09 15:49:27', '2026-08-09 15:49:27');

-- Volcando estructura para tabla siscolas.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_rol` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_rol` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `creado_rol` timestamp NULL DEFAULT NULL,
  `actualizado_rol` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla siscolas.roles: ~4 rows (aproximadamente)
INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion_rol`, `estado_rol`, `creado_rol`, `actualizado_rol`) VALUES
	(1, 'Super Admin', 'Administrador del sistema', '1', '2026-02-23 14:34:24', NULL),
	(2, 'Admin', 'Administrador de turnos', '1', '2026-02-23 14:34:38', NULL),
	(3, 'Operador', 'Ejecutivos de Atención', '1', '2026-02-23 14:34:59', NULL),
	(4, 'Monitor', 'Vista de pantallas', '1', '2026-02-23 14:35:32', NULL);

-- Volcando estructura para tabla siscolas.servicios
CREATE TABLE IF NOT EXISTS `servicios` (
  `id_servicios` int NOT NULL AUTO_INCREMENT,
  `nombre_serv` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_serv` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_serv` tinyint DEFAULT '1',
  `prioridad_serv` enum('NORMAL','ALTA','EMERGENCIA') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'NORMAL',
  `creado_serv` datetime DEFAULT CURRENT_TIMESTAMP,
  `actualizado_serv` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_servicios`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla siscolas.servicios: ~15 rows (aproximadamente)
INSERT INTO `servicios` (`id_servicios`, `nombre_serv`, `codigo_serv`, `estado_serv`, `prioridad_serv`, `creado_serv`, `actualizado_serv`) VALUES
	(1, 'CONTRATOS', 'CONT', 1, 'NORMAL', '2026-02-20 09:07:40', '2026-08-09 10:46:15'),
	(2, 'RECLAMOS COMERCIALES', 'RECO', 1, 'NORMAL', '2026-02-20 09:07:54', '2026-05-23 16:01:58'),
	(3, 'DUPLICADOS', 'DUPL', 1, 'NORMAL', '2026-02-20 09:08:10', '2026-05-23 16:06:24'),
	(4, 'CONCILIACIONES', 'CONC', 1, 'NORMAL', '2026-02-20 09:12:30', '2026-08-09 14:41:33'),
	(5, 'OTROS', 'OTRO', 1, 'NORMAL', '2026-02-20 09:14:03', '2026-06-06 22:40:37'),
	(6, 'CONEXIONES NUEVAS', 'CONU', 0, 'NORMAL', '2026-02-20 10:30:16', '2026-08-09 10:46:38'),
	(7, 'FACTIBILIDAD', 'FACT', 0, 'NORMAL', '2026-05-14 21:19:37', '2026-05-23 16:01:37'),
	(8, 'PREFERENCIAL', 'PREF', 1, 'ALTA', '2026-05-14 21:19:36', '2026-06-20 21:58:42'),
	(9, 'ROBO DE MEDIDOR', 'ROME', 0, 'NORMAL', '2026-05-14 21:19:39', '2026-05-23 16:02:05'),
	(10, 'SERVICIOS CORTADOS', 'SECO', 0, 'NORMAL', '2026-05-14 21:19:40', '2026-05-23 16:02:12'),
	(11, 'RECLAMO OPERACIONALES', 'REOP', 0, 'NORMAL', '2026-05-14 21:19:41', '2026-06-20 21:56:35'),
	(18, 'CONEXIONES ANTIGUAS', 'COAN', 0, 'NORMAL', '2026-05-23 15:34:36', '2026-08-09 22:10:49'),
	(19, 'PRESTAMOS', 'PRE', 0, 'NORMAL', '2026-06-20 21:29:37', '2026-06-20 21:35:29'),
	(20, 'DEUDAS', 'DEUD', 0, 'NORMAL', '2026-06-20 21:42:34', '2026-08-09 10:46:43'),
	(21, 'PAGO', 'PAGO', 0, 'NORMAL', '2026-06-20 21:50:12', '2026-08-09 10:46:50'),
	(22, 'RETIROS', 'RETI', 0, 'NORMAL', '2026-06-20 21:56:12', '2026-08-09 14:40:17');

-- Volcando estructura para tabla siscolas.tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id_tickets` int NOT NULL AUTO_INCREMENT,
  `numero_tk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_servicios` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `fecha_tk` date DEFAULT NULL,
  `hora_cita` timestamp NULL DEFAULT NULL,
  `hora_atencion` timestamp NULL DEFAULT NULL,
  `hora_finalizado` timestamp NULL DEFAULT NULL,
  `estado_tk` enum('PENDIENTE','LLAMADO','EN_ATENCION','FINALIZADO','CANCELADO','NO_PRESENTADO') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDIENTE',
  `prioridad_tk` enum('NORMAL','ALTA','EMERGENCIA') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_tk` timestamp NULL DEFAULT NULL,
  `actualizado_tk` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tickets`),
  KEY `id_servicios` (`id_servicios`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `FK_tickets_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `ticket_servicio` FOREIGN KEY (`id_servicios`) REFERENCES `servicios` (`id_servicios`)
) ENGINE=InnoDB AUTO_INCREMENT=894 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla siscolas.tickets: ~17 rows (aproximadamente)
INSERT INTO `tickets` (`id_tickets`, `numero_tk`, `id_servicios`, `id_usuario`, `fecha_tk`, `hora_cita`, `hora_atencion`, `hora_finalizado`, `estado_tk`, `prioridad_tk`, `creado_tk`, `actualizado_tk`) VALUES
	(839, 'DEUD-001', 20, NULL, '2026-06-20', NULL, NULL, NULL, 'PENDIENTE', 'NORMAL', '2026-06-21 02:51:24', NULL),
	(840, 'DUPL-001', 3, NULL, '2026-06-20', NULL, NULL, NULL, 'PENDIENTE', 'NORMAL', '2026-06-21 02:51:28', NULL),
	(841, 'PREF-001', 8, 3, '2026-06-20', '2026-06-21 02:58:49', '2026-06-21 02:58:55', '2026-06-21 02:58:59', 'FINALIZADO', 'ALTA', '2026-06-21 02:51:37', NULL),
	(842, 'PAGO-001', 21, NULL, '2026-06-20', NULL, NULL, NULL, 'PENDIENTE', 'ALTA', '2026-06-21 02:52:22', NULL),
	(843, 'RETI-001', 22, NULL, '2026-06-20', NULL, NULL, NULL, 'PENDIENTE', 'NORMAL', '2026-06-21 02:56:49', NULL),
	(844, 'CONU-001', 6, NULL, '2026-08-07', NULL, NULL, NULL, 'PENDIENTE', 'NORMAL', '2026-08-07 05:13:04', NULL),
	(845, 'COAN-001', 18, 3, '2026-08-09', '2026-08-09 20:38:02', '2026-08-09 20:38:11', '2026-08-09 20:38:44', 'FINALIZADO', 'NORMAL', '2026-08-09 05:44:37', NULL),
	(846, 'CONU-001', 6, 3, '2026-08-09', '2026-08-09 05:44:54', '2026-08-09 05:45:00', '2026-08-09 05:52:19', 'FINALIZADO', 'NORMAL', '2026-08-09 05:44:37', NULL),
	(847, 'DEUD-001', 20, 3, '2026-08-09', '2026-08-09 16:41:57', '2026-08-09 16:47:04', '2026-08-09 16:47:09', 'FINALIZADO', 'NORMAL', '2026-08-09 05:44:38', NULL),
	(848, 'RECO-001', 2, 3, '2026-08-09', '2026-08-09 19:12:03', '2026-08-09 19:12:07', '2026-08-09 19:12:11', 'CANCELADO', 'NORMAL', '2026-08-09 05:44:39', NULL),
	(849, 'CONC-001', 4, 3, '2026-08-09', '2026-08-09 20:08:50', '2026-08-09 20:08:53', '2026-08-09 20:08:55', 'FINALIZADO', 'NORMAL', '2026-08-09 05:44:39', NULL),
	(850, 'DUPL-001', 3, 3, '2026-08-09', '2026-08-09 19:12:15', NULL, '2026-08-09 19:12:17', 'CANCELADO', 'NORMAL', '2026-08-09 05:57:07', NULL),
	(851, 'DUPL-002', 3, 3, '2026-08-09', '2026-08-09 19:12:21', NULL, '2026-08-09 19:12:24', 'CANCELADO', 'NORMAL', '2026-08-09 05:57:20', NULL),
	(852, 'DUPL-003', 3, 3, '2026-08-09', '2026-08-09 19:12:28', '2026-08-09 19:12:31', '2026-08-09 19:12:39', 'FINALIZADO', 'NORMAL', '2026-08-09 05:57:20', NULL),
	(853, 'DUPL-004', 3, 3, '2026-08-09', '2026-08-09 19:31:15', '2026-08-09 19:31:20', '2026-08-09 19:31:24', 'FINALIZADO', 'NORMAL', '2026-08-09 05:57:20', NULL),
	(854, 'DUPL-005', 3, 3, '2026-08-09', '2026-08-09 19:31:27', NULL, '2026-08-09 19:31:31', 'CANCELADO', 'NORMAL', '2026-08-09 05:57:21', NULL),
	(855, 'RECO-002', 2, 3, '2026-08-09', '2026-08-09 19:31:34', '2026-08-09 19:33:07', '2026-08-09 19:33:09', 'FINALIZADO', 'NORMAL', '2026-08-09 05:57:33', NULL),
	(856, 'PREF-001', 8, 3, '2026-08-09', '2026-08-09 16:47:21', '2026-08-09 16:47:27', '2026-08-09 16:47:33', 'FINALIZADO', 'ALTA', '2026-08-09 16:42:31', NULL),
	(857, 'RECO-003', 2, 3, '2026-08-09', '2026-08-09 19:33:22', NULL, '2026-08-09 19:33:24', 'CANCELADO', 'NORMAL', '2026-08-09 16:42:32', NULL),
	(858, 'PREF-002', 8, 3, '2026-08-09', '2026-08-09 19:44:35', '2026-08-09 19:44:39', '2026-08-09 19:44:43', 'FINALIZADO', 'ALTA', '2026-08-09 19:36:25', NULL),
	(859, 'RETI-001', 22, 3, '2026-08-09', '2026-08-09 20:09:00', NULL, '2026-08-09 20:09:02', 'CANCELADO', 'NORMAL', '2026-08-09 19:36:29', NULL),
	(860, 'RECO-004', 2, 3, '2026-08-09', '2026-08-09 20:09:10', NULL, '2026-08-09 20:09:13', 'CANCELADO', 'NORMAL', '2026-08-09 19:36:33', NULL),
	(861, 'CONT-001', 1, 3, '2026-08-09', '2026-08-09 20:09:18', '2026-08-09 20:09:20', '2026-08-09 20:09:22', 'FINALIZADO', 'NORMAL', '2026-08-09 19:36:59', NULL),
	(862, 'PREF-003', 8, 3, '2026-08-09', '2026-08-09 19:46:02', NULL, '2026-08-09 20:08:42', 'CANCELADO', 'ALTA', '2026-08-09 19:41:04', NULL),
	(863, 'CONC-002', 4, 3, '2026-08-09', '2026-08-09 20:31:00', '2026-08-09 20:34:51', '2026-08-09 20:35:58', 'CANCELADO', 'NORMAL', '2026-08-09 19:41:24', NULL),
	(864, 'DUPL-006', 3, 3, '2026-08-09', '2026-08-09 20:36:01', NULL, '2026-08-09 20:36:04', 'CANCELADO', 'NORMAL', '2026-08-09 19:44:02', NULL),
	(865, 'DUPL-007', 3, 3, '2026-08-09', '2026-08-09 20:36:06', '2026-08-09 20:36:09', '2026-08-09 20:36:12', 'FINALIZADO', 'NORMAL', '2026-08-09 19:44:14', NULL),
	(866, 'OTRO-001', 5, 39, '2026-08-09', '2026-08-09 21:00:58', '2026-08-09 21:01:11', '2026-08-09 21:01:16', 'FINALIZADO', 'NORMAL', '2026-08-09 20:09:28', NULL),
	(867, 'PREF-004', 8, 3, '2026-08-09', '2026-08-09 20:09:52', '2026-08-09 20:30:43', '2026-08-09 20:30:56', 'FINALIZADO', 'ALTA', '2026-08-09 20:09:45', NULL),
	(868, 'CONT-002', 1, 39, '2026-08-09', '2026-08-09 21:01:20', NULL, '2026-08-09 21:01:24', 'CANCELADO', 'NORMAL', '2026-08-09 20:53:57', NULL),
	(869, 'DUPL-008', 3, 39, '2026-08-09', '2026-08-09 21:01:26', '2026-08-09 21:03:19', '2026-08-09 21:11:13', 'FINALIZADO', 'NORMAL', '2026-08-09 20:54:00', NULL),
	(870, 'PREF-005', 8, 39, '2026-08-09', '2026-08-09 20:59:59', '2026-08-09 21:00:09', '2026-08-09 21:00:47', 'FINALIZADO', 'ALTA', '2026-08-09 20:54:16', NULL),
	(871, 'RECO-005', 2, 39, '2026-08-09', '2026-08-09 21:11:16', NULL, '2026-08-09 21:11:21', 'CANCELADO', 'NORMAL', '2026-08-09 20:54:20', NULL),
	(872, 'OTRO-002', 5, 3, '2026-08-09', '2026-08-09 21:11:34', '2026-08-09 21:12:28', '2026-08-09 21:12:40', 'FINALIZADO', 'NORMAL', '2026-08-09 20:54:25', NULL),
	(873, 'CONT-003', 1, 39, '2026-08-09', '2026-08-09 21:11:37', '2026-08-09 21:12:24', '2026-08-09 21:12:36', 'FINALIZADO', 'NORMAL', '2026-08-09 20:54:34', NULL),
	(874, 'DUPL-009', 3, 39, '2026-08-09', '2026-08-09 21:12:45', NULL, '2026-08-09 21:12:48', 'CANCELADO', 'NORMAL', '2026-08-09 20:54:37', NULL),
	(875, 'RECO-006', 2, 39, '2026-08-09', '2026-08-09 21:12:51', NULL, '2026-08-09 21:12:54', 'CANCELADO', 'NORMAL', '2026-08-09 21:08:20', NULL),
	(876, 'RECO-007', 2, 39, '2026-08-09', '2026-08-09 21:12:56', NULL, '2026-08-09 21:12:59', 'CANCELADO', 'NORMAL', '2026-08-09 21:08:22', NULL),
	(877, 'RECO-008', 2, 39, '2026-08-09', '2026-08-09 21:13:03', NULL, '2026-08-09 21:13:06', 'CANCELADO', 'NORMAL', '2026-08-09 21:11:46', NULL),
	(878, 'RECO-009', 2, 39, '2026-08-09', '2026-08-09 21:13:09', '2026-08-09 21:13:12', '2026-08-09 21:13:14', 'FINALIZADO', 'NORMAL', '2026-08-09 21:11:49', NULL),
	(879, 'RECO-010', 2, 39, '2026-08-09', '2026-08-09 21:13:17', '2026-08-09 21:13:20', '2026-08-09 21:13:23', 'FINALIZADO', 'NORMAL', '2026-08-09 21:11:52', NULL),
	(880, 'CONC-003', 4, 39, '2026-08-09', '2026-08-09 21:20:37', '2026-08-09 21:21:29', '2026-08-09 21:21:33', 'FINALIZADO', 'NORMAL', '2026-08-09 21:17:04', NULL),
	(881, 'CONT-004', 1, 3, '2026-08-09', '2026-08-09 21:20:46', '2026-08-09 21:21:46', '2026-08-09 21:36:40', 'FINALIZADO', 'NORMAL', '2026-08-09 21:17:10', NULL),
	(882, 'DUPL-010', 3, 39, '2026-08-09', '2026-08-09 21:21:37', '2026-08-09 21:22:26', '2026-08-09 21:22:30', 'FINALIZADO', 'NORMAL', '2026-08-09 21:17:12', NULL),
	(883, 'OTRO-003', 5, 39, '2026-08-09', '2026-08-09 21:36:24', '2026-08-09 21:36:27', '2026-08-09 21:36:32', 'FINALIZADO', 'NORMAL', '2026-08-09 21:17:14', NULL),
	(884, 'PREF-006', 8, 39, '2026-08-09', '2026-08-09 21:19:13', '2026-08-09 21:19:19', '2026-08-09 21:19:25', 'FINALIZADO', 'ALTA', '2026-08-09 21:17:16', NULL),
	(885, 'RECO-011', 2, 39, '2026-08-09', '2026-08-09 21:36:35', '2026-08-09 21:38:42', '2026-08-09 21:38:45', 'FINALIZADO', 'NORMAL', '2026-08-09 21:17:22', NULL),
	(886, 'RECO-012', 2, 3, '2026-08-09', '2026-08-09 21:36:44', '2026-08-10 00:21:56', '2026-08-10 00:21:59', 'FINALIZADO', 'NORMAL', '2026-08-09 21:17:24', NULL),
	(887, 'RECO-013', 2, 39, '2026-08-09', '2026-08-09 21:38:49', NULL, '2026-08-09 21:38:53', 'CANCELADO', 'NORMAL', '2026-08-09 21:17:26', NULL),
	(888, 'RECO-014', 2, 39, '2026-08-09', '2026-08-09 21:38:56', '2026-08-09 21:38:59', '2026-08-09 21:39:02', 'FINALIZADO', 'NORMAL', '2026-08-09 21:38:19', NULL),
	(889, 'CONC-004', 4, 39, '2026-08-09', '2026-08-09 21:39:10', NULL, '2026-08-09 21:39:16', 'CANCELADO', 'NORMAL', '2026-08-09 21:38:25', NULL),
	(890, 'CONT-005', 1, 3, '2026-08-09', '2026-08-10 00:32:25', '2026-08-10 00:32:31', '2026-08-10 00:32:38', 'FINALIZADO', 'NORMAL', '2026-08-09 21:38:37', NULL),
	(891, 'RECO-015', 2, 3, '2026-08-09', '2026-08-10 00:33:43', NULL, '2026-08-10 00:33:46', 'CANCELADO', 'NORMAL', '2026-08-10 00:19:52', NULL),
	(892, 'DUPL-011', 3, 3, '2026-08-09', '2026-08-10 00:33:49', NULL, NULL, 'LLAMADO', 'NORMAL', '2026-08-10 00:32:45', NULL),
	(893, 'DUPL-012', 3, NULL, '2026-08-09', NULL, NULL, NULL, 'PENDIENTE', 'NORMAL', '2026-08-10 00:33:22', NULL);

-- Volcando estructura para tabla siscolas.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `usuario_user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dni_user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `puesto_user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oficina_user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero_user` enum('M','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones_user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_rol_user` int DEFAULT NULL,
  `num_ventanilla` int DEFAULT NULL,
  `estado_user` tinyint DEFAULT '1',
  `creado_user` datetime DEFAULT NULL,
  `actualizado_user` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario` (`usuario_user`) USING BTREE,
  UNIQUE KEY `dni_user` (`dni_user`),
  KEY `id_rol` (`id_rol_user`) USING BTREE,
  CONSTRAINT `usuario_rol` FOREIGN KEY (`id_rol_user`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla siscolas.usuarios: ~6 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `usuario_user`, `password_user`, `nombre_user`, `dni_user`, `puesto_user`, `oficina_user`, `genero_user`, `observaciones_user`, `id_rol_user`, `num_ventanilla`, `estado_user`, `creado_user`, `actualizado_user`) VALUES
	(3, 'roman', '$2y$10$SU9opMcHrxHfJtFQ7fmypOTD.BMOAhq9ytFYI7tViRZsAD253zPg.', 'Roman', 'YYYYYY', 'Analista', 'OTI', 'M', 'Sin observaciones.', 1, 3, 1, NULL, NULL),
	(14, 'admin', '$2y$10$FA2tckBGqjmyigCgTBcNs.0p7Z6AF8pPh9QGmj8p48keXP.PRpT42', 'Admin', NULL, NULL, NULL, NULL, NULL, 1, NULL, 1, NULL, NULL),
	(17, 'turnos', '$2y$10$4pniV3Sof4GUxBd1BZwPJek9tRH.0NGyG8jhNXNmnmD5TdbaHUoRG', 'Turnoss', NULL, NULL, NULL, NULL, NULL, 4, NULL, 1, NULL, NULL),
	(30, 'seleccion', '$2y$10$.65kNc7gFkdHlv/MvF5dvejxE7GsFnbRIxWjDuYHDkSsF6/sPv2Tq', 'Seleccion', NULL, NULL, NULL, NULL, NULL, 4, NULL, 1, NULL, NULL),
	(39, 'soporte', '$2y$10$JJI0YjrpxQtTIL5bgXouLeB8QeIuHlHLzbd23MlXoGizKSEomkupy', 'Soporte', '123', 'Soporte', 'Soporte', 'M', 'Soporte', 3, 5, 1, NULL, NULL),
	(40, 'aceroolivaroman@gmail.com', '$2y$10$RyZhvtv8Gcclz7jE81VTou29xHEr1RvCM.jczWjifX5y63PIi7.O.', 'Roman Acero Oliva', NULL, NULL, NULL, NULL, NULL, 2, NULL, 1, NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
