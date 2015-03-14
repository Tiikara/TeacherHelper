-- MySQL Script generated by MySQL Workbench
-- 03/14/15 23:11:47
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema teacher
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema teacher
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `teacher` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `teacher` ;

-- -----------------------------------------------------
-- Table `teacher`.`Teachers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Teachers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(20) NOT NULL,
  `md5hash` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`AcademicYear`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`AcademicYear` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `year` INT NOT NULL,
  `semester` INT NOT NULL,
  `id_teacher` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `for_teacher_idx` (`id_teacher` ASC),
  UNIQUE INDEX `uniq_year_semeser` (`semester` ASC, `year` ASC, `id_teacher` ASC),
  CONSTRAINT `for_acadyear_to_teacher`
    FOREIGN KEY (`id_teacher`)
    REFERENCES `teacher`.`Teachers` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`Discipline`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Discipline` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_academicyear` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `for_semester_idx` (`id_academicyear` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  CONSTRAINT `for_disc_to_academicyear`
    FOREIGN KEY (`id_academicyear`)
    REFERENCES `teacher`.`AcademicYear` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`Groups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Groups` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_academicyear` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_academ_UNIQUE` (`name` ASC, `id_academicyear` ASC),
  CONSTRAINT `for_groups_to_academicyear`
    FOREIGN KEY (`id_academicyear`)
    REFERENCES `teacher`.`AcademicYear` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`Discipline_Groups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Discipline_Groups` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_group` INT NOT NULL,
  `id_discipline` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `for_disci_idx` (`id_discipline` ASC),
  UNIQUE INDEX `uniq_group_disc` (`id_group` ASC, `id_discipline` ASC),
  CONSTRAINT `for_dg_to_disci`
    FOREIGN KEY (`id_discipline`)
    REFERENCES `teacher`.`Discipline` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `for_dg_to_group`
    FOREIGN KEY (`id_group`)
    REFERENCES `teacher`.`Groups` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`Students`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Students` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_group` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `date_entered` DATE NOT NULL,
  `date_expel` DATE NULL,
  PRIMARY KEY (`id`),
  INDEX `for_group_idx` (`id_group` ASC),
  CONSTRAINT `for_student_to_group`
    FOREIGN KEY (`id_group`)
    REFERENCES `teacher`.`Groups` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`Days`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Days` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_disc_groups` INT NOT NULL,
  `date` DATE NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `for_disc_groups_idx` (`id_disc_groups` ASC),
  UNIQUE INDEX `uniq_discgroups_date` (`id_disc_groups` ASC, `date` ASC),
  CONSTRAINT `for_day_to_disc_groups`
    FOREIGN KEY (`id_disc_groups`)
    REFERENCES `teacher`.`Discipline_Groups` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`Tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Tasks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_discipline` INT NOT NULL,
  `description` MEDIUMTEXT NOT NULL,
  `date_to` DATE NOT NULL,
  `difficulty` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `for_disc_idx` (`id_discipline` ASC),
  CONSTRAINT `for_task_to_disc`
    FOREIGN KEY (`id_discipline`)
    REFERENCES `teacher`.`Discipline` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`Day_Events`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Day_Events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_day` INT NOT NULL,
  `id_student` INT NOT NULL,
  `id_discipline` INT NOT NULL,
  `rating` INT NOT NULL,
  `id_tasks` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `for_day_idx` (`id_day` ASC),
  INDEX `for_student_idx` (`id_student` ASC),
  UNIQUE INDEX `uniq_day_student` (`id_student` ASC, `id_day` ASC, `id_discipline` ASC),
  INDEX `for_tasks_idx` (`id_tasks` ASC),
  UNIQUE INDEX `uniq_tasks_student` (`id_student` ASC, `id_tasks` ASC),
  INDEX `for_disc_idx` (`id_discipline` ASC),
  CONSTRAINT `for_events_to_day`
    FOREIGN KEY (`id_day`)
    REFERENCES `teacher`.`Days` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `for_events_to_student`
    FOREIGN KEY (`id_student`)
    REFERENCES `teacher`.`Students` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `for_events_to_tasks`
    FOREIGN KEY (`id_tasks`)
    REFERENCES `teacher`.`Tasks` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `for_events_to_disc`
    FOREIGN KEY (`id_discipline`)
    REFERENCES `teacher`.`Discipline` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`Type_lecture`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Type_lecture` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `teacher`.`Schedule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `teacher`.`Schedule` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_academicyear` INT NOT NULL,
  `day_week` INT NOT NULL,
  `num_lecture` INT NOT NULL,
  `type_alternation` INT NOT NULL,
  `id_disc_group` INT NOT NULL,
  `id_type_lecture` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `for_disc_group_idx` (`id_disc_group` ASC),
  INDEX `for_type_lecture_idx` (`id_type_lecture` ASC),
  UNIQUE INDEX `uniq_dayweek_numlecture` (`day_week` ASC, `num_lecture` ASC, `type_alternation` ASC, `id_academicyear` ASC),
  INDEX `for_academicyear_idx` (`id_academicyear` ASC),
  CONSTRAINT `for_schedule_to_disc_group`
    FOREIGN KEY (`id_disc_group`)
    REFERENCES `teacher`.`Discipline_Groups` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `for_schedule_to_type_lecture`
    FOREIGN KEY (`id_type_lecture`)
    REFERENCES `teacher`.`Type_lecture` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `for_schedule_to_academicyear`
    FOREIGN KEY (`id_academicyear`)
    REFERENCES `teacher`.`AcademicYear` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
