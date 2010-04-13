SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `traq_attachments`;
CREATE TABLE `traq_attachments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `contents` longtext COLLATE utf8_bin NOT NULL,
  `type` varchar(255) COLLATE utf8_bin NOT NULL,
  `size` bigint(20) NOT NULL,
  `uploaded` int(11) NOT NULL,
  `owner_id` bigint(20) NOT NULL,
  `owner_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `ticket_id` bigint(20) NOT NULL,
  `project_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_components`;
CREATE TABLE `traq_components` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `default` smallint(6) NOT NULL DEFAULT '0',
  `project_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_milestones`;
CREATE TABLE `traq_milestones` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `milestone` varchar(255) COLLATE utf8_bin NOT NULL,
  `slug` varchar(255) COLLATE utf8_bin NOT NULL,
  `codename` varchar(255) COLLATE utf8_bin NOT NULL,
  `info` longtext COLLATE utf8_bin NOT NULL,
  `changelog` longtext COLLATE utf8_bin NOT NULL,
  `due` bigint(20) NOT NULL,
  `completed` bigint(20) NOT NULL DEFAULT '0',
  `cancelled` bigint(20) NOT NULL DEFAULT '0',
  `locked` smallint(6) NOT NULL DEFAULT '0',
  `project_id` bigint(20) NOT NULL,
  `displayorder` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_plugins`;
CREATE TABLE `traq_plugins` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `author` varchar(255) COLLATE utf8_bin NOT NULL,
  `website` varchar(255) COLLATE utf8_bin NOT NULL,
  `version` varchar(20) COLLATE utf8_bin NOT NULL,
  `enabled` bigint(20) NOT NULL,
  `install_sql` longtext COLLATE utf8_bin NOT NULL,
  `uninstall_sql` longtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_plugin_code`;
CREATE TABLE `traq_plugin_code` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `plugin_id` bigint(20) NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `hook` mediumtext COLLATE utf8_bin NOT NULL,
  `code` longtext COLLATE utf8_bin NOT NULL,
  `execorder` bigint(20) NOT NULL,
  `enabled` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_priorities`;
CREATE TABLE `traq_priorities` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_projects`;
CREATE TABLE `traq_projects` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `slug` varchar(255) COLLATE utf8_bin NOT NULL,
  `codename` varchar(255) COLLATE utf8_bin NOT NULL,
  `info` longtext COLLATE utf8_bin NOT NULL,
  `managers` mediumtext COLLATE utf8_bin NOT NULL,
  `private` smallint(6) NOT NULL,
  `next_tid` bigint(20) NOT NULL,
  `displayorder` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_repositories`;
CREATE TABLE `traq_repositories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `slug` varchar(255) COLLATE utf8_bin NOT NULL,
  `location` varchar(255) COLLATE utf8_bin NOT NULL,
  `info` longtext COLLATE utf8_bin NOT NULL,
  `main` smallint(6) NOT NULL,
  `project_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_settings`;
CREATE TABLE `traq_settings` (
  `setting` varchar(255) COLLATE utf8_bin NOT NULL,
  `value` longtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`setting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_severities`;
CREATE TABLE `traq_severities` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_tickets`;
CREATE TABLE `traq_tickets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint(20) NOT NULL,
  `summary` varchar(255) COLLATE utf8_bin NOT NULL,
  `body` longtext COLLATE utf8_bin NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `project_id` bigint(20) NOT NULL,
  `milestone_id` bigint(20) NOT NULL,
  `version_id` bigint(20) NOT NULL,
  `component_id` bigint(20) NOT NULL,
  `type` bigint(20) NOT NULL,
  `status` bigint(20) NOT NULL DEFAULT '1',
  `priority` bigint(20) NOT NULL,
  `severity` bigint(20) NOT NULL,
  `assigned_to` bigint(20) NOT NULL,
  `closed` bigint(20) NOT NULL DEFAULT '0',
  `created` bigint(20) NOT NULL,
  `updated` bigint(20) NOT NULL DEFAULT '0',
  `private` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_ticket_history`;
CREATE TABLE `traq_ticket_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `ticket_id` bigint(20) NOT NULL,
  `project_id` bigint(20) NOT NULL,
  `changes` longtext COLLATE utf8_bin NOT NULL,
  `comment` longtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_ticket_status`;
CREATE TABLE `traq_ticket_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `status` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_ticket_types`;
CREATE TABLE `traq_ticket_types` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `bullet` varchar(10) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_timeline`;
CREATE TABLE `traq_timeline` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) NOT NULL,
  `owner_id` bigint(20) NOT NULL,
  `action` varchar(255) COLLATE utf8_bin NOT NULL,
  `data` longtext COLLATE utf8_bin NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_usergroups`;
CREATE TABLE `traq_usergroups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `is_admin` smallint(6) NOT NULL,
  `create_tickets` smallint(6) NOT NULL,
  `update_tickets` smallint(6) NOT NULL,
  `delete_tickets` smallint(6) NOT NULL,
  `add_attachments` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_users`;
CREATE TABLE `traq_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `group_id` bigint(20) NOT NULL DEFAULT '2',
  `sesshash` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `traq_versions`;
CREATE TABLE `traq_versions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8_bin NOT NULL,
  `project_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `traq_settings` VALUES ('title', 'Traq'),
('theme', 'Traq2'),
('locale', 'enus'),
('seo_urls','0'),
('recaptcha_enabled','0'),
('recaptcha_pubkey', ''),
('recaptcha_privkey', ''),
('allow_registration', '1'),
('date_time_format', 'g:iA d/m/Y'),
('db_revision', '18');