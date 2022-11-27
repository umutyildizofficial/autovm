-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 25 Eyl 2020, 20:51:44
-- Sunucu sürümü: 5.7.31-0ubuntu0.18.04.1
-- PHP Sürümü: 7.2.24-0ubuntu0.18.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `vmpanel`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `api`
--

CREATE TABLE `api` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` char(16) COLLATE utf8_bin NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `api`
--

INSERT INTO `api` (`id`, `key`, `created_at`, `updated_at`) VALUES
(8, '2qhh39i5q3ynq65x', 1600990572, 1600990572);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `api_log`
--

CREATE TABLE `api_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `api_id` int(11) UNSIGNED NOT NULL,
  `action` tinyint(1) UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bandwidth`
--

CREATE TABLE `bandwidth` (
  `id` int(11) UNSIGNED NOT NULL,
  `vps_id` int(11) UNSIGNED NOT NULL,
  `used` int(11) UNSIGNED NOT NULL,
  `pure_used` int(11) UNSIGNED NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `datastore`
--

CREATE TABLE `datastore` (
  `id` int(11) UNSIGNED NOT NULL,
  `server_id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_bin NOT NULL,
  `space` int(11) UNSIGNED NOT NULL,
  `is_default` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `vsan` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `is_public` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ip`
--

CREATE TABLE `ip` (
  `id` int(11) UNSIGNED NOT NULL,
  `server_id` int(11) UNSIGNED NOT NULL,
  `ip` varchar(45) COLLATE utf8_bin NOT NULL,
  `gateway` varchar(255) COLLATE utf8_bin NOT NULL,
  `netmask` varchar(255) COLLATE utf8_bin NOT NULL,
  `mac_address` varchar(255) COLLATE utf8_bin NOT NULL,
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `network` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `is_dhcp` tinyint(1) UNSIGNED NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `iso`
--

CREATE TABLE `iso` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `log`
--

CREATE TABLE `log` (
  `id` int(11) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `lost_password`
--

CREATE TABLE `lost_password` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `key` char(16) COLLATE utf8_bin NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `expired_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_bin NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m200128_070835_drop_server_license_column', 1581383537),
('m200131_081908_drop_console_column', 1581383538);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `os`
--

CREATE TABLE `os` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `type` varchar(255) COLLATE utf8_bin NOT NULL,
  `operation_system` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `adapter` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `guest` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `plan`
--

CREATE TABLE `plan` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `ram` int(11) UNSIGNED NOT NULL,
  `cpu_mhz` int(11) UNSIGNED NOT NULL,
  `cpu_core` int(11) UNSIGNED NOT NULL,
  `hard` int(11) UNSIGNED NOT NULL,
  `band_width` bigint(20) NOT NULL,
  `is_public` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `network` int(11) NOT NULL,
  `os_lists` varchar(1255) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `plan`
--

INSERT INTO `plan` (`id`, `name`, `ram`, `cpu_mhz`, `cpu_core`, `hard`, `band_width`, `is_public`, `created_at`, `updated_at`, `network`, `os_lists`) VALUES
(31, 'Test', 2048, 2500, 2, 50, 100, 1, 1601064627, 1601064627, 1, '[\"1\"]');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rdns_ayar`
--

CREATE TABLE `rdns_ayar` (
  `id` int(11) NOT NULL,
  `key` varchar(355) COLLATE utf8_turkish_ci NOT NULL,
  `value` text COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `rdns_ayar`
--

INSERT INTO `rdns_ayar` (`id`, `key`, `value`) VALUES
(1, 'SatanHosting_one_data_types', ''),
(2, 'SatanHosting_one_data_delete', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rdns_data`
--

CREATE TABLE `rdns_data` (
  `id` int(11) NOT NULL,
  `rdns_id` int(11) NOT NULL,
  `rdns_server_id` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `name` varchar(855) COLLATE utf8_turkish_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `content` varchar(855) COLLATE utf8_turkish_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `ttl` int(11) NOT NULL,
  `ana_ip` varchar(455) COLLATE utf8_turkish_ci NOT NULL,
  `created_at` varchar(355) COLLATE utf8_turkish_ci NOT NULL,
  `updated_at` varchar(355) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rdns_settings`
--

CREATE TABLE `rdns_settings` (
  `id` int(11) NOT NULL,
  `rdns_url` varchar(1555) COLLATE utf8_turkish_ci NOT NULL,
  `rdns_username` varchar(855) COLLATE utf8_turkish_ci NOT NULL,
  `rdns_password` text COLLATE utf8_turkish_ci NOT NULL,
  `rdns_language` varchar(455) COLLATE utf8_turkish_ci NOT NULL,
  `created_at` varchar(355) COLLATE utf8_turkish_ci NOT NULL,
  `updated_at` varchar(355) COLLATE utf8_turkish_ci NOT NULL,
  `rdns_ids` varchar(1555) COLLATE utf8_turkish_ci NOT NULL,
  `rdns_types` text COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rdns_status`
--

CREATE TABLE `rdns_status` (
  `id` int(11) NOT NULL,
  `rdns_server_id` int(11) NOT NULL,
  `rdns_id` int(11) NOT NULL,
  `dataid` int(11) NOT NULL,
  `pending_type` int(11) NOT NULL,
  `ana_ip` varchar(355) COLLATE utf8_turkish_ci NOT NULL,
  `ip_son` int(11) NOT NULL,
  `type` varchar(355) COLLATE utf8_turkish_ci NOT NULL,
  `content` text COLLATE utf8_turkish_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `ttl` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` varchar(355) COLLATE utf8_turkish_ci NOT NULL,
  `updated_at` varchar(355) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `server`
--

CREATE TABLE `server` (
  `id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `ip` varchar(45) COLLATE utf8_bin NOT NULL,
  `port` smallint(11) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `vcenter_ip` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `vcenter_username` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `vcenter_password` varchar(255) COLLATE utf8_bin NOT NULL,
  `network` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `second_network` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `version` int(11) UNSIGNED DEFAULT NULL,
  `virtualization` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `dns1` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '4.2.2.4',
  `dns2` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '8.8.8.8',
  `server_address` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'https://server1.autovm.info/api/default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `setting`
--

CREATE TABLE `setting` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `setting`
--

INSERT INTO `setting` (`id`, `key`, `value`) VALUES
(1, 'title', 'Kontrol Paneli'),
(2, 'api_url', 'http://181.118.143.51/api/'),
(3, 'terminate', '1'),
(4, 'language', 'tr'),
(5, 'change_limit', '8');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `vps_id` int(11) NOT NULL,
  `ip_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `last_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `auth_key` varchar(255) COLLATE utf8_bin NOT NULL,
  `is_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `auth_key`, `is_admin`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Admin', 'Admin', 'omzsmmbutspdchtbw', 1, 1579163518, 1579163518, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_email`
--

CREATE TABLE `user_email` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `key` char(16) COLLATE utf8_bin NOT NULL,
  `is_primary` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `is_confirmed` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `user_email`
--

INSERT INTO `user_email` (`id`, `user_id`, `email`, `key`, `is_primary`, `is_confirmed`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@admin.com', 'jshdufjthr75869i', 1, 1, 1579163518, 1579163518);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_login`
--

CREATE TABLE `user_login` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `ip` varchar(45) COLLATE utf8_bin NOT NULL,
  `os_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `browser_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_password`
--

CREATE TABLE `user_password` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `hash` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `salt` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Tablo döküm verisi `user_password`
--

INSERT INTO `user_password` (`id`, `user_id`, `hash`, `salt`, `password`, `created_at`) VALUES
(1, 1, 2, 'hgjfht76utjgih98', '582a578f064c371a50b0b8eca503a0faf8ba7471', 1579163518);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `SatanHosting_mailer`
--

CREATE TABLE `SatanHosting_mailer` (
  `id` int(11) NOT NULL,
  `key` varchar(355) COLLATE utf8_turkish_ci NOT NULL,
  `value` text COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `SatanHosting_mailer`
--

INSERT INTO `SatanHosting_mailer` (`id`, `key`, `value`) VALUES
(1, 'hostname', 'mail.test.com'),
(2, 'username', 'noreply@test.com'),
(3, 'password', 'LqBd1oCre3jNxYTcxvcuCmQyOGZiYWRkZDJmM2QyNTI4Yjk5ODMwMmU1NGQ2YWQ4MmU3ZmFiYmE5ZWViOTA1MWU3NWE2YTBhOWMwZDc2OGWzAM1soc1TNe2feLrDOTozdjkMCcsmWq8Egl6fdkbNTg=='),
(4, 'security', 'ssl'),
(5, 'port', '465'),
(6, 'from', 'TEST');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vps`
--

CREATE TABLE `vps` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `server_id` int(11) UNSIGNED NOT NULL,
  `datastore_id` int(11) UNSIGNED NOT NULL,
  `os_id` int(11) UNSIGNED DEFAULT NULL,
  `plan_id` int(11) UNSIGNED DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `hostname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `updated_at` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `plan_type` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `vps_ram` int(11) UNSIGNED DEFAULT NULL,
  `vps_cpu_mhz` int(11) UNSIGNED DEFAULT NULL,
  `vps_cpu_core` int(11) UNSIGNED DEFAULT NULL,
  `vps_hard` int(11) UNSIGNED DEFAULT NULL,
  `vps_band_width` int(11) UNSIGNED DEFAULT NULL,
  `reset_at` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `power` tinyint(1) UNSIGNED NOT NULL DEFAULT '2',
  `disk` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `snapshot` tinyint(1) UNSIGNED NOT NULL DEFAULT '2',
  `extra_bw` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `change_limit` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `notify_at` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vps_action`
--

CREATE TABLE `vps_action` (
  `id` int(11) UNSIGNED NOT NULL,
  `vps_id` int(11) UNSIGNED NOT NULL,
  `action` tinyint(1) UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `vps_ip`
--

CREATE TABLE `vps_ip` (
  `id` int(11) UNSIGNED NOT NULL,
  `vps_id` int(11) UNSIGNED NOT NULL,
  `ip_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `api`
--
ALTER TABLE `api`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_key_unique_key` (`key`);

--
-- Tablo için indeksler `api_log`
--
ALTER TABLE `api_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `api_log_api_id` (`api_id`);

--
-- Tablo için indeksler `bandwidth`
--
ALTER TABLE `bandwidth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bandwidth_vps_id` (`vps_id`);

--
-- Tablo için indeksler `datastore`
--
ALTER TABLE `datastore`
  ADD PRIMARY KEY (`id`),
  ADD KEY `datastore_server_id_foreign_key` (`server_id`);

--
-- Tablo için indeksler `ip`
--
ALTER TABLE `ip`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_id_unique_key` (`server_id`,`ip`),
  ADD KEY `ip_id_primary_key` (`id`);

--
-- Tablo için indeksler `iso`
--
ALTER TABLE `iso`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `lost_password`
--
ALTER TABLE `lost_password`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lost_password_key_unique_key` (`key`),
  ADD KEY `lost_password_user_id_foreign_key` (`user_id`);

--
-- Tablo için indeksler `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Tablo için indeksler `os`
--
ALTER TABLE `os`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `rdns_ayar`
--
ALTER TABLE `rdns_ayar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `rdns_data`
--
ALTER TABLE `rdns_data`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `rdns_settings`
--
ALTER TABLE `rdns_settings`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `rdns_status`
--
ALTER TABLE `rdns_status`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `server`
--
ALTER TABLE `server`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key_unique_key` (`key`);

--
-- Tablo için indeksler `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_auth_key_unique_key` (`auth_key`);

--
-- Tablo için indeksler `user_email`
--
ALTER TABLE `user_email`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_email_email_unique_key` (`email`),
  ADD UNIQUE KEY `user_email_key_unique_key` (`key`),
  ADD KEY `user_email_user_id_foreign_key` (`user_id`);

--
-- Tablo için indeksler `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_login_user_id_foreign_key` (`user_id`);

--
-- Tablo için indeksler `user_password`
--
ALTER TABLE `user_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_password_user_id_foreign_key` (`user_id`);

--
-- Tablo için indeksler `SatanHosting_mailer`
--
ALTER TABLE `SatanHosting_mailer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key` (`key`(255));

--
-- Tablo için indeksler `vps`
--
ALTER TABLE `vps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `server_id` (`server_id`),
  ADD KEY `datastore_id` (`datastore_id`),
  ADD KEY `os_id` (`os_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Tablo için indeksler `vps_action`
--
ALTER TABLE `vps_action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vps_id` (`vps_id`);

--
-- Tablo için indeksler `vps_ip`
--
ALTER TABLE `vps_ip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vps_id` (`vps_id`),
  ADD KEY `ip_id` (`ip_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `api`
--
ALTER TABLE `api`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Tablo için AUTO_INCREMENT değeri `api_log`
--
ALTER TABLE `api_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `bandwidth`
--
ALTER TABLE `bandwidth`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Tablo için AUTO_INCREMENT değeri `datastore`
--
ALTER TABLE `datastore`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- Tablo için AUTO_INCREMENT değeri `ip`
--
ALTER TABLE `ip`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Tablo için AUTO_INCREMENT değeri `iso`
--
ALTER TABLE `iso`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Tablo için AUTO_INCREMENT değeri `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `lost_password`
--
ALTER TABLE `lost_password`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `os`
--
ALTER TABLE `os`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- Tablo için AUTO_INCREMENT değeri `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- Tablo için AUTO_INCREMENT değeri `rdns_ayar`
--
ALTER TABLE `rdns_ayar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Tablo için AUTO_INCREMENT değeri `rdns_data`
--
ALTER TABLE `rdns_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `rdns_settings`
--
ALTER TABLE `rdns_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Tablo için AUTO_INCREMENT değeri `rdns_status`
--
ALTER TABLE `rdns_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `server`
--
ALTER TABLE `server`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Tablo için AUTO_INCREMENT değeri `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Tablo için AUTO_INCREMENT değeri `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Tablo için AUTO_INCREMENT değeri `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;
--
-- Tablo için AUTO_INCREMENT değeri `user_email`
--
ALTER TABLE `user_email`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;
--
-- Tablo için AUTO_INCREMENT değeri `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Tablo için AUTO_INCREMENT değeri `user_password`
--
ALTER TABLE `user_password`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;
--
-- Tablo için AUTO_INCREMENT değeri `SatanHosting_mailer`
--
ALTER TABLE `SatanHosting_mailer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Tablo için AUTO_INCREMENT değeri `vps`
--
ALTER TABLE `vps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Tablo için AUTO_INCREMENT değeri `vps_action`
--
ALTER TABLE `vps_action`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- Tablo için AUTO_INCREMENT değeri `vps_ip`
--
ALTER TABLE `vps_ip`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `api_log`
--
ALTER TABLE `api_log`
  ADD CONSTRAINT `api_log_api_id` FOREIGN KEY (`api_id`) REFERENCES `api` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `bandwidth`
--
ALTER TABLE `bandwidth`
  ADD CONSTRAINT `bandwidth_vps_id` FOREIGN KEY (`vps_id`) REFERENCES `vps` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `datastore`
--
ALTER TABLE `datastore`
  ADD CONSTRAINT `datastore_server_id_foreign_key` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `ip`
--
ALTER TABLE `ip`
  ADD CONSTRAINT `ip_server_id_foreign_key` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `lost_password`
--
ALTER TABLE `lost_password`
  ADD CONSTRAINT `lost_password_user_id_foreign_key` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `user_email`
--
ALTER TABLE `user_email`
  ADD CONSTRAINT `user_email_user_id_foreign_key` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `user_login`
--
ALTER TABLE `user_login`
  ADD CONSTRAINT `user_login_user_id_foreign_key` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `user_password`
--
ALTER TABLE `user_password`
  ADD CONSTRAINT `user_password_user_id_foreign_key` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `vps_action`
--
ALTER TABLE `vps_action`
  ADD CONSTRAINT `vps_action_ibfk_1` FOREIGN KEY (`vps_id`) REFERENCES `vps` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
