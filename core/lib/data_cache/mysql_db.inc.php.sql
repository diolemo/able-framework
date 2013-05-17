CREATE TABLE `able_session` (
  `name` VARCHAR(128) NOT NULL PRIMARY KEY,
  `value` MEDIUMTEXT NOT NULL,
  `last_modified` BIGINT NOT NULL,
  `expires` INT NOT NULL
)
