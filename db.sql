/*
SQLyog Ultimate v11.42 (32 bit)
MySQL - 10.1.9-MariaDB : Database - sf
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `sf_access_ref` */

DROP TABLE IF EXISTS `sf_access_ref`;

CREATE TABLE `sf_access_ref` (
  `accessId` int(11) NOT NULL AUTO_INCREMENT,
  `accessName` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`accessId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `sf_access_ref` */

insert  into `sf_access_ref`(`accessId`,`accessName`) values (1,'add'),(2,'edit'),(3,'delete'),(4,'print');

/*Table structure for table `sf_group` */

DROP TABLE IF EXISTS `sf_group`;

CREATE TABLE `sf_group` (
  `groupId` int(4) NOT NULL AUTO_INCREMENT,
  `groupName` varchar(15) NOT NULL,
  PRIMARY KEY (`groupId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `sf_group` */

insert  into `sf_group`(`groupId`,`groupName`) values (1,'Admin'),(2,'No Group');

/*Table structure for table `sf_group_menu` */

DROP TABLE IF EXISTS `sf_group_menu`;

CREATE TABLE `sf_group_menu` (
  `groupMenuId` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) DEFAULT NULL,
  `menuId` int(11) DEFAULT NULL,
  `menuInc` int(11) DEFAULT NULL,
  PRIMARY KEY (`groupMenuId`),
  KEY `groupId` (`groupId`),
  KEY `menuId` (`menuId`),
  CONSTRAINT `sf_group_menu_ibfk_1` FOREIGN KEY (`groupId`) REFERENCES `sf_group` (`groupId`),
  CONSTRAINT `sf_group_menu_ibfk_2` FOREIGN KEY (`menuId`) REFERENCES `sf_menu` (`menuId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sf_group_menu` */

/*Table structure for table `sf_group_module` */

DROP TABLE IF EXISTS `sf_group_module`;

CREATE TABLE `sf_group_module` (
  `groupModId` int(4) NOT NULL AUTO_INCREMENT,
  `groupId` int(4) NOT NULL DEFAULT '0',
  `moduleId` int(4) NOT NULL DEFAULT '0',
  `accessId` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`groupModId`),
  KEY `groupId` (`groupId`),
  KEY `modulId` (`moduleId`),
  CONSTRAINT `sf_group_module_ibfk_1` FOREIGN KEY (`groupId`) REFERENCES `sf_group` (`groupId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sf_group_module_ibfk_2` FOREIGN KEY (`moduleId`) REFERENCES `sf_module` (`moduleId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

/*Data for the table `sf_group_module` */

insert  into `sf_group_module`(`groupModId`,`groupId`,`moduleId`,`accessId`) values (73,2,1,NULL),(74,2,2,NULL),(77,2,3,NULL),(78,1,2,NULL),(79,1,3,NULL);

/*Table structure for table `sf_label` */

DROP TABLE IF EXISTS `sf_label`;

CREATE TABLE `sf_label` (
  `labelId` int(11) NOT NULL AUTO_INCREMENT,
  `languageId` int(11) DEFAULT NULL,
  `modulId` int(11) DEFAULT NULL,
  `labelName` varchar(20) DEFAULT NULL,
  `labelValue` text,
  `userId` int(11) DEFAULT NULL,
  `dateInsert` datetime DEFAULT NULL,
  `dateChange` datetime DEFAULT NULL,
  `publish` enum('Yes','No') DEFAULT 'Yes',
  `status` enum('Yes','No') DEFAULT 'Yes',
  PRIMARY KEY (`labelId`),
  KEY `FK_label_language` (`languageId`),
  KEY `FK_label_user` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sf_label` */

/*Table structure for table `sf_language` */

DROP TABLE IF EXISTS `sf_language`;

CREATE TABLE `sf_language` (
  `languageId` int(11) NOT NULL AUTO_INCREMENT,
  `languageName` varchar(20) DEFAULT NULL,
  `dateChange` datetime DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  PRIMARY KEY (`languageId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `sf_language` */

insert  into `sf_language`(`languageId`,`languageName`,`dateChange`,`userId`) values (1,'Indonesian','2016-01-24 21:12:40',1),(2,'English','2016-01-24 21:12:36',1);

/*Table structure for table `sf_menu` */

DROP TABLE IF EXISTS `sf_menu`;

CREATE TABLE `sf_menu` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT,
  `menuName` varchar(25) DEFAULT NULL,
  `moduleId` int(11) DEFAULT NULL,
  `parentLink` int(11) DEFAULT NULL,
  `menuIcon` varchar(50) DEFAULT NULL,
  `order` tinyint(4) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `dateChange` datetime DEFAULT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `sf_menu` */

insert  into `sf_menu`(`menuId`,`menuName`,`moduleId`,`parentLink`,`menuIcon`,`order`,`userId`,`dateChange`) values (1,'Home',NULL,NULL,NULL,1,1,'2016-01-24 21:14:36'),(2,'Story',NULL,NULL,NULL,2,1,'2016-01-24 21:15:16'),(3,'Character',NULL,NULL,NULL,3,1,'2016-01-24 21:15:13'),(4,'Comics',NULL,NULL,NULL,4,1,'2016-01-24 21:16:22'),(5,'News & Event',NULL,NULL,NULL,5,1,'2016-01-24 21:16:33'),(6,'Shop',NULL,NULL,NULL,6,1,'2016-01-24 21:16:42'),(7,'Shop',NULL,6,NULL,1,1,'2016-01-24 21:17:13'),(8,'Download Center',NULL,6,NULL,2,1,'2016-01-24 21:17:26'),(9,'Gallery',NULL,NULL,NULL,7,1,'2016-01-24 21:17:39'),(10,'Chart',NULL,NULL,NULL,8,1,'2016-01-24 21:17:51');

/*Table structure for table `sf_menu_language` */

DROP TABLE IF EXISTS `sf_menu_language`;

CREATE TABLE `sf_menu_language` (
  `menuLangId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `languageId` int(11) DEFAULT NULL,
  `menuId` int(11) DEFAULT NULL,
  `displayMenu` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`menuLangId`),
  KEY `FK_menu_language_menu` (`menuId`),
  KEY `FK_menu_language_lang` (`languageId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `sf_menu_language` */

insert  into `sf_menu_language`(`menuLangId`,`languageId`,`menuId`,`displayMenu`) values (1,2,1,'Home'),(2,2,2,'Story'),(3,2,3,'Character'),(4,2,4,'Comics'),(5,2,5,'News & Event'),(6,2,6,'Shop'),(7,2,7,'Shop'),(8,2,8,'Download Center'),(9,2,9,'Gallery'),(10,2,10,'Chart');

/*Table structure for table `sf_module` */

DROP TABLE IF EXISTS `sf_module`;

CREATE TABLE `sf_module` (
  `moduleId` int(4) NOT NULL AUTO_INCREMENT,
  `moduleCode` varchar(100) NOT NULL,
  `moduleName` varchar(50) NOT NULL,
  `fileName` varchar(50) NOT NULL DEFAULT '',
  `typeFile` varchar(15) NOT NULL DEFAULT '',
  `action` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`moduleId`),
  UNIQUE KEY `modulCode` (`moduleCode`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='InnoDB free: 3072 kB; InnoDB free: 3072 kB; InnoDB free: 307';

/*Data for the table `sf_module` */

insert  into `sf_module`(`moduleId`,`moduleCode`,`moduleName`,`fileName`,`typeFile`,`action`) values (1,'homeViewHomeJson','home','Home','json','View'),(2,'loginDoLoginJson','login','Login','json','Do'),(3,'loginDoLogoutJson','login','Logout','json','Do');

/*Table structure for table `sf_user` */

DROP TABLE IF EXISTS `sf_user`;

CREATE TABLE `sf_user` (
  `userId` bigint(20) NOT NULL AUTO_INCREMENT,
  `userName` varchar(15) NOT NULL,
  `realName` varchar(50) DEFAULT NULL,
  `pwdUser` varchar(100) NOT NULL,
  `groupId` int(4) DEFAULT NULL,
  `lastLogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('active','non') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userName` (`userName`),
  KEY `groupId` (`groupId`),
  CONSTRAINT `sf_user_ibfk_1` FOREIGN KEY (`groupId`) REFERENCES `sf_group` (`groupId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `sf_user` */

insert  into `sf_user`(`userId`,`userName`,`realName`,`pwdUser`,`groupId`,`lastLogin`,`status`) values (1,'admin','Scope','21232f297a57a5a743894a0e4a801fc3',1,'2006-10-11 05:50:40','active'),(2,'nobody','No Body','',2,'0000-00-00 00:00:00','active');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
