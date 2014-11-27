SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `covoiturage` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `covoiturage` ;

-- -----------------------------------------------------
-- Table `covoiturage`.`holidays`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage`.`holidays` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `begin` DATE NULL,
  `end` DATE NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covoiturage`.`towns`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage`.`towns` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `npa` INT NULL,
  PRIMARY KEY (`id`)
  )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covoiturage`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cpnvId` VARCHAR(45) NULL,
  `email` VARCHAR(60) NULL,
  `hideEmail` INT NULL,
  `telephone` VARCHAR(45) NULL,
  `hideTelephone` INT NULL,
  `notifInscription` INT NULL,
  `notifComment` INT NULL,
  `notifUnsuscribe` INT NULL,
  `notifDeleteRide` INT NULL,
  `notifModification` INT NULL,
  `blacklisted` INT NULL,
  `admin` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covoiturage`.`rides`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage`.`rides` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `driver_fk` INT NOT NULL,
  `departuretown_fk` INT NOT NULL,
  `arrivaltown_fk` INT NOT NULL,
  `bindedride` INT NULL,
  `description` TEXT NULL,
  `departure` TIME NULL,
  `arrival` TIME NULL,
  `seats` INT NULL,
  `startDate` DATETIME NULL,
  `endDate` DATETIME NULL,
  `day` INT NULL,
  `visibility` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_rides_towns1_idx` (`departuretown_fk` ASC),
  INDEX `fk_rides_towns2_idx` (`arrivaltown_fk` ASC),
  INDEX `fk_rides_rides1_idx` (`bindedride` ASC),
  INDEX `fk_rides_users1_idx` (`driver_fk` ASC),
  CONSTRAINT `fk_rides_towns1`
    FOREIGN KEY (`departuretown_fk`)
    REFERENCES `covoiturage`.`towns` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rides_towns2`
    FOREIGN KEY (`arrivaltown_fk`)
    REFERENCES `covoiturage`.`towns` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rides_rides1`
    FOREIGN KEY (`bindedride`)
    REFERENCES `covoiturage`.`rides` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rides_users1`
    FOREIGN KEY (`driver_fk`)
    REFERENCES `covoiturage`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covoiturage`.`badges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage`.`badges` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(90) NULL,
  `picture` VARCHAR(90) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covoiturage`.`comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage`.`comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `author_fk` INT NULL,
  `ride_fk` INT NOT NULL,
  `date` DATETIME NULL,
  `comment` TEXT NULL,
  INDEX `fk_comments_users1_idx` (`author_fk` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_comments_rides1_idx` (`ride_fk` ASC),
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`author_fk`)
    REFERENCES `covoiturage`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_rides1`
    FOREIGN KEY (`ride_fk`)
    REFERENCES `covoiturage`.`rides` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covoiturage`.`ridebadges`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage`.`ridebadges` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ride_fk` INT NOT NULL,
  `badge_fk` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_rides_has_badges_badges1_idx` (`badge_fk` ASC),
  INDEX `fk_rides_has_badges_rides1_idx` (`ride_fk` ASC),
  CONSTRAINT `fk_rides_has_badges_rides1`
    FOREIGN KEY (`ride_fk`)
    REFERENCES `covoiturage`.`rides` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rides_has_badges_badges1`
    FOREIGN KEY (`badge_fk`)
    REFERENCES `covoiturage`.`badges` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covoiturage`.`registrations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage`.`registrations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_fk` INT NOT NULL,
  `ride_fk` INT NOT NULL,
  `startDate` DATETIME NULL,
  `endDate` DATETIME NULL,
  `accepted` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_users_has_rides_rides1_idx` (`ride_fk` ASC),
  INDEX `fk_users_has_rides_users1_idx` (`user_fk` ASC),
  CONSTRAINT `fk_users_has_rides_users1`
    FOREIGN KEY (`user_fk`)
    REFERENCES `covoiturage`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_rides_rides1`
    FOREIGN KEY (`ride_fk`)
    REFERENCES `covoiturage`.`rides` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `covoiturage`.`votes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `covoiturage`.`votes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `passenger_fk` INT NOT NULL,
  `targetuser_fk` INT NOT NULL,
  `date` DATETIME NULL,
  `vote` INT NULL,
  INDEX `fk_votes_registrations1_idx` (`passenger_fk` ASC),
  INDEX `fk_votes_users1_idx` (`targetuser_fk` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_votes_registrations1`
    FOREIGN KEY (`passenger_fk`)
    REFERENCES `covoiturage`.`registrations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_votes_users1`
    FOREIGN KEY (`targetuser_fk`)
    REFERENCES `covoiturage`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

ALTER TABLE towns ADD UNIQUE INDEX(`name`,`npa`);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
