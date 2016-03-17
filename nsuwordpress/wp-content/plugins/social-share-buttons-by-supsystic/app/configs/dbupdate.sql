CREATE TABLE `%prefix%views` (
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