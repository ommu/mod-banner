/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.16-MariaDB : Database - ommu_db_banner
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ommu_db_banner` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `ommu_db_banner`;

/*Table structure for table `ommu_banner_category` */

DROP TABLE IF EXISTS `ommu_banner_category`;

CREATE TABLE `ommu_banner_category` (
  `cat_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `publish` tinyint(1) NOT NULL DEFAULT '0',
  `name` int(11) unsigned NOT NULL,
  `desc` int(11) unsigned NOT NULL,
  `cat_code` varchar(32) NOT NULL,
  `banner_size` text NOT NULL,
  `banner_limit` tinyint(1) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `creation_id` int(11) unsigned NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  `modified_id` int(11) unsigned NOT NULL,
  `slug` varchar(32) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_banner_click_detail` */

DROP TABLE IF EXISTS `ommu_banner_click_detail`;

CREATE TABLE `ommu_banner_click_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'trigger',
  `click_id` int(11) unsigned NOT NULL,
  `click_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `click_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `view_id` (`click_id`),
  CONSTRAINT `ommu_banner_click_detail_ibfk_1` FOREIGN KEY (`click_id`) REFERENCES `ommu_banner_clicks` (`click_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_banner_clicks` */

DROP TABLE IF EXISTS `ommu_banner_clicks`;

CREATE TABLE `ommu_banner_clicks` (
  `click_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT '1',
  `click_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `click_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`click_id`),
  KEY `FK_ommu_pose_likes` (`banner_id`),
  CONSTRAINT `ommu_banner_clicks_ibfk_1` FOREIGN KEY (`banner_id`) REFERENCES `ommu_banners` (`banner_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_banner_setting` */

DROP TABLE IF EXISTS `ommu_banner_setting`;

CREATE TABLE `ommu_banner_setting` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `license` varchar(32) NOT NULL,
  `permission` tinyint(1) NOT NULL,
  `meta_keyword` text NOT NULL,
  `meta_description` text NOT NULL,
  `banner_validation` tinyint(1) NOT NULL,
  `banner_resize` tinyint(1) NOT NULL,
  `banner_file_type` text NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  `modified_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_banner_view_detail` */

DROP TABLE IF EXISTS `ommu_banner_view_detail`;

CREATE TABLE `ommu_banner_view_detail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'trigger',
  `view_id` int(11) unsigned NOT NULL,
  `view_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `view_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `view_id` (`view_id`),
  CONSTRAINT `ommu_banner_view_detail_ibfk_1` FOREIGN KEY (`view_id`) REFERENCES `ommu_banner_views` (`view_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_banner_views` */

DROP TABLE IF EXISTS `ommu_banner_views`;

CREATE TABLE `ommu_banner_views` (
  `view_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `views` int(11) NOT NULL DEFAULT '1',
  `view_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `view_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`view_id`),
  KEY `FK_ommu_pose_likes` (`banner_id`),
  CONSTRAINT `ommu_banner_views_ibfk_1` FOREIGN KEY (`banner_id`) REFERENCES `ommu_banners` (`banner_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `ommu_banners` */

DROP TABLE IF EXISTS `ommu_banners`;

CREATE TABLE `ommu_banners` (
  `banner_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `publish` tinyint(1) NOT NULL DEFAULT '1',
  `cat_id` tinyint(3) unsigned NOT NULL,
  `title` varchar(64) NOT NULL,
  `url` text NOT NULL,
  `banner_filename` text NOT NULL,
  `banner_desc` text NOT NULL,
  `published_date` date NOT NULL,
  `expired_date` date NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'trigger',
  `creation_id` int(11) unsigned NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'trigger',
  `modified_id` int(11) unsigned NOT NULL,
  `slug` text NOT NULL,
  PRIMARY KEY (`banner_id`),
  KEY `FK_ommu_banners` (`cat_id`),
  CONSTRAINT `FK_ommu_banners` FOREIGN KEY (`cat_id`) REFERENCES `ommu_banner_category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* Trigger structure for table `ommu_banner_category` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerAfterDeleteCategory` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerAfterDeleteCategory` AFTER DELETE ON `ommu_banner_category` FOR EACH ROW BEGIN
	DELETE FROM `ommu_core_system_phrase` WHERE `phrase_id`=OLD.name;
	DELETE FROM `ommu_core_system_phrase` WHERE `phrase_id`=OLD.desc;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_banner_clicks` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerAfterInsertClicks` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerAfterInsertClicks` AFTER INSERT ON `ommu_banner_clicks` FOR EACH ROW BEGIN
	INSERT `ommu_banner_click_detail` (`click_id`, `click_date`,  `click_ip`)
	VALUE (NEW.click_id, NEW.click_date, NEW.click_ip);
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_banner_views` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerAfterInsertViews` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerAfterInsertViews` AFTER INSERT ON `ommu_banner_views` FOR EACH ROW BEGIN
	INSERT `ommu_banner_view_detail` (`view_id`, `view_date`, `view_ip`)
	VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_banner_clicks` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerAfterUpdateClicks` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerAfterUpdateClicks` AFTER UPDATE ON `ommu_banner_clicks` FOR EACH ROW BEGIN
	IF (NEW.clicks <> OLD.clicks) THEN
		INSERT `ommu_banner_click_detail` (`click_id`, `click_date`,  `click_ip`)
		VALUE (NEW.click_id, NEW.click_date, NEW.click_ip);
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_banner_views` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerAfterUpdateViews` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerAfterUpdateViews` AFTER UPDATE ON `ommu_banner_views` FOR EACH ROW BEGIN
	IF (NEW.views <> OLD.views) THEN
		INSERT `ommu_banner_view_detail` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_banners` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerBeforeUpdate` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerBeforeUpdate` BEFORE UPDATE ON `ommu_banners` FOR EACH ROW BEGIN
	DECLARE column_update_count INT;
	SET column_update_count = 0;	
	
	IF (NEW.publish <> OLD.publish) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.cat_id <> OLD.cat_id) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.title <> OLD.title) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.url <> OLD.url) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.banner_filename <> OLD.banner_filename AND ((NEW.modified_id <> OLD.modified_id) OR (NEW.modified_id = OLD.modified_id AND NEW.modified_id <> 0))) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.published_date <> OLD.published_date) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.expired_date <> OLD.expired_date) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.creation_date <> OLD.creation_date) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.creation_id <> OLD.creation_id) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	
	IF (column_update_count > 0) THEN
		SET NEW.modified_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_banner_category` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerBeforeUpdateCategory` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerBeforeUpdateCategory` BEFORE UPDATE ON `ommu_banner_category` FOR EACH ROW BEGIN
	DECLARE column_update_count INT;
	SET column_update_count = 0;	
	
	IF (NEW.publish <> OLD.publish) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.name <> OLD.name) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.desc <> OLD.desc) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.cat_code <> OLD.cat_code) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.banner_size <> OLD.banner_size) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.banner_limit <> OLD.banner_limit) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.creation_date <> OLD.creation_date) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	IF (NEW.creation_id <> OLD.creation_id) THEN
		SET column_update_count = column_update_count + 1;
	END IF;
	
	IF (column_update_count > 0) THEN
		SET NEW.modified_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_banner_clicks` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerBeforeUpdateClicks` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerBeforeUpdateClicks` BEFORE UPDATE ON `ommu_banner_clicks` FOR EACH ROW BEGIN
	IF ((NEW.clicks <> OLD.clicks) AND (NEW.clicks> OLD.clicks)) THEN
		SET NEW.click_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_banner_setting` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerBeforeUpdateSetting` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerBeforeUpdateSetting` BEFORE UPDATE ON `ommu_banner_setting` FOR EACH ROW BEGIN
	DECLARE column_update_count INT;
	SET column_update_count = 0;	
	
	IF (NEW.license <> OLD.license) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.permission <> OLD.permission) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.meta_keyword <> OLD.meta_keyword) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.meta_description <> OLD.meta_description) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.banner_validation <> OLD.banner_validation) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.banner_resize <> OLD.banner_resize) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	IF (NEW.banner_file_type <> OLD.banner_file_type) THEN
		SET column_update_count = column_update_count + 1;
	END IF;	
	
	IF (column_update_count > 0) THEN
		SET NEW.modified_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/* Trigger structure for table `ommu_banner_views` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `bannerBeforeUpdateViews` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `bannerBeforeUpdateViews` BEFORE UPDATE ON `ommu_banner_views` FOR EACH ROW BEGIN
	IF ((NEW.views <> OLD.views) AND (NEW.views> OLD.views)) THEN
		SET NEW.view_date = NOW();
	END IF;
    END */$$


DELIMITER ;

/*Table structure for table `_view_banner_category` */

DROP TABLE IF EXISTS `_view_banner_category`;

/*!50001 DROP VIEW IF EXISTS `_view_banner_category` */;
/*!50001 DROP TABLE IF EXISTS `_view_banner_category` */;

/*!50001 CREATE TABLE  `_view_banner_category`(
 `cat_id` tinyint(3) unsigned ,
 `banners` decimal(23,0) ,
 `banner_pending` decimal(23,0) ,
 `banner_expired` decimal(23,0) ,
 `banner_unpublish` decimal(23,0) ,
 `banner_all` bigint(21) 
)*/;

/*Table structure for table `_view_banners` */

DROP TABLE IF EXISTS `_view_banners`;

/*!50001 DROP VIEW IF EXISTS `_view_banners` */;
/*!50001 DROP TABLE IF EXISTS `_view_banners` */;

/*!50001 CREATE TABLE  `_view_banners`(
 `banner_id` int(11) unsigned ,
 `clicks` decimal(32,0) ,
 `views` decimal(32,0) 
)*/;

/*View structure for view _view_banner_category */

/*!50001 DROP TABLE IF EXISTS `_view_banner_category` */;
/*!50001 DROP VIEW IF EXISTS `_view_banner_category` */;

/*!50001 CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_banner_category` AS select `a`.`cat_id` AS `cat_id`,sum((case when ((`b`.`publish` = '1') and ((`b`.`expired_date` >= curdate()) or (`b`.`published_date` >= curdate()))) then 1 else 0 end)) AS `banners`,sum((case when ((`b`.`publish` = '1') and (`b`.`published_date` > curdate())) then 1 else 0 end)) AS `banner_pending`,sum((case when ((`b`.`publish` = '1') and (`b`.`expired_date` < curdate())) then 1 else 0 end)) AS `banner_expired`,sum((case when (`b`.`publish` = '0') then 1 else 0 end)) AS `banner_unpublish`,count(`b`.`cat_id`) AS `banner_all` from (`ommu_banner_category` `a` left join `ommu_banners` `b` on((`a`.`cat_id` = `b`.`cat_id`))) group by `a`.`cat_id` */;

/*View structure for view _view_banners */

/*!50001 DROP TABLE IF EXISTS `_view_banners` */;
/*!50001 DROP VIEW IF EXISTS `_view_banners` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `_view_banners` AS select `a`.`banner_id` AS `banner_id`,(select sum(`b`.`clicks`) from `ommu_banner_clicks` `b` where (`a`.`banner_id` = `b`.`banner_id`)) AS `clicks`,(select sum(`b`.`views`) from `ommu_banner_views` `b` where (`a`.`banner_id` = `b`.`banner_id`)) AS `views` from `ommu_banners` `a` group by `a`.`banner_id` */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
