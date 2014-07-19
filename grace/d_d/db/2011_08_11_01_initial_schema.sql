CREATE TABLE `feeds` (
	`feedurl` VARCHAR(255) NOT NULL DEFAULT '',
	`nextup` INT(11),
	`lastmod` VARCHAR(40),
	`etag` VARCHAR(250),
	`content` LONGTEXT,
	PRIMARY KEY (`feedurl`)
);
