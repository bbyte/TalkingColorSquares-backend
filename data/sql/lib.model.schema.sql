
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- devices
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `devices`;

CREATE TABLE `devices`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `device_id` VARCHAR(255) NOT NULL,
    `device_token` VARCHAR(255),
    `device_type` VARCHAR(255),
    `device_os` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- activity
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `activity`;

CREATE TABLE `activity`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `device_id` INTEGER NOT NULL,
    `event` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `activity_FI_1` (`device_id`),
    CONSTRAINT `activity_FK_1`
        FOREIGN KEY (`device_id`)
        REFERENCES `devices` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
