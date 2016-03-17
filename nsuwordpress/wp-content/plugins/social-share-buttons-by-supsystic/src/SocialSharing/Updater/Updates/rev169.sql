ALTER TABLE `%prefix%project_networks`
	ADD COLUMN `title` VARCHAR(255) NULL AFTER `position`,
	ADD COLUMN `text` VARCHAR(255) NULL AFTER `title`,
	ADD COLUMN `tooltip` VARCHAR(255) NULL AFTER `text`