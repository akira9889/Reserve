-- 予約(reserve)テーブル作成
CREATE TABLE `reserve`.`reserve` ( `id` INT NOT NULL AUTO_INCREMENT , `reserve_date` DATE NOT NULL , `reserve_time` TIME NOT NULL , `reserve_num` INT NOT NULL , `name` VARCHAR(100) NOT NULL , `email` VARCHAR(254) NOT NULL , `tel` VARCHAR(20) NOT NULL , `comment` MEDIUMTEXT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

-- 店舗(shop)テーブル作成
CREATE TABLE `reserve`.`shop` ( `id` INT NOT NULL AUTO_INCREMENT , `login_id` VARCHAR(20) NOT NULL , `login_password` INT(60) NOT NULL , `resevable_date` INT NOT NULL , `start_time` TIME NOT NULL , `end_time` TIME NOT NULL , `max_reserve_num` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
