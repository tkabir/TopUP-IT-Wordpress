SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS `%prefix%projects` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `settings` TEXT NOT NULL,
  PRIMARY KEY  (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `%prefix%networks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `class` VARCHAR(255) NOT NULL,
  `brand_primary` VARCHAR(7) NOT NULL DEFAULT '#000000',
  `brand_secondary` VARCHAR(7) NOT NULL DEFAULT '#ffffff',
  `total_shares` INT(11) UNSIGNED NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `%prefix%project_networks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `network_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `position` INT(11) UNSIGNED NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  INDEX `FK__%prefix%projects` (`project_id`),
  INDEX `FK__%prefix%networks` (`network_id`),
  CONSTRAINT `FK__%prefix%networks` FOREIGN KEY (`network_id`) REFERENCES `%prefix%networks` (`id`),
  CONSTRAINT `FK__%prefix%projects` FOREIGN KEY (`project_id`) REFERENCES `%prefix%projects` (`id`) ON DELETE CASCADE 
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `%prefix%shares` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `network_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `project_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `post_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `FK_%prefix%shares_%prefix%networks` (`network_id`),
  INDEX `FK_%prefix%shares_%prefix%projects` (`project_id`),
  CONSTRAINT `FK_%prefix%shares_%prefix%networks` FOREIGN KEY (`network_id`) REFERENCES `%prefix%networks` (`id`) ON DELETE CASCADE ,
  CONSTRAINT `FK_%prefix%shares_%prefix%projects` FOREIGN KEY (`project_id`) REFERENCES `%prefix%projects` (`id`) ON DELETE CASCADE 
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

CREATE TABLE IF NOT EXISTS `%prefix%views` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `post_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `FK_%prefix%views_%prefix%projects` (`project_id`),
  CONSTRAINT `FK_%prefix%views_%prefix%projects` FOREIGN KEY (`project_id`) REFERENCES `%prefix%projects` (`id`) ON DELETE CASCADE 
)
  COLLATE='utf8_general_ci'
  ENGINE=InnoDB
;

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (1, 'Facebook', 'http://www.facebook.com/sharer.php?u={url}', 'facebook', '#3b5998', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (2, 'Twitter', 'https://twitter.com/share?url={url}&text={title}', 'twitter', '#55acee', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (3, 'Google+', 'https://plus.google.com/share?url={url}', 'googleplus', '#dd4b39', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (4, 'VKontakte', 'http://vk.com/share.php?url={url}', 'vk', '#45668e', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (5, 'Like', '#', 'like', '#9b59b6', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (6, 'Reddit', 'http://reddit.com/submit?url={url}&title={title}', 'reddit', '#cee3f8', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (7, 'Pinterest', 'http://pinterest.com/pin/create/link/?url={url}', 'pinterest', '#cc2127', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (8, 'Digg', 'http://digg.com/submit?url={url}&title={title}', 'digg', '#000000', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (9, 'StumbleUpon', 'http://www.stumbleupon.com/submit?url={url}&title={title}', 'stumbleupon', '#eb4924', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (10, 'Delicious', 'https://delicious.com/save?v=5&jump=close&url={url}&title={title}', 'delicious', '#3399ff', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (11, 'Livejournal', 'http://www.livejournal.com/update.bml?subject={title}&event={url}', 'livejournal', '#3399ff', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (12, 'Odnoklassniki', 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments={title}&st._surl={url}', 'odnoklassniki', '#3399ff', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (13, 'Linkedin', 'https://www.linkedin.com/shareArticle?mini=true&title={title}&url={url}', 'linkedin', '#3399ff', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (14, 'Print', '#', 'print', '#3399ff', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (15, 'Add Bookmark', '#', 'bookmark', '#3399ff', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (16, 'Mail', '#', 'mail', '#3399ff', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);

INSERT INTO `%prefix%networks` (`id`, `name`, `url`, `class`, `brand_primary`, `brand_secondary`, `total_shares`) VALUES (17, 'Evernote', 'https://www.evernote.com/clip.action?url={url}&title={title}', 'evernote', '#6ba92f', '#ffffff', 0)
ON DUPLICATE KEY UPDATE
    name = VALUES(name), url = VALUES(url), class = VALUES(class), brand_primary = VALUES(brand_primary), brand_secondary = VALUES(brand_secondary), total_shares = VALUES(total_shares);


SET FOREIGN_KEY_CHECKS=1;