/*
SQLyog Community v8.63 
MySQL - 5.1.53-community : Database - 251
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`251` /*!40100 DEFAULT CHARACTER SET utf8 */;

/*Table structure for table `phpdoc_filestypes` */

CREATE TABLE `phpdoc_filestypes` (
  `filetypeid` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `filetype` enum('_MI_PHPDOC_FILETYPE_PHP','_MI_PHPDOC_FILETYPE_CSS','_MI_PHPDOC_FILETYPE_HTML','_MI_PHPDOC_FILETYPE_JAVA','_MI_PHPDOC_FILETYPE_TXT','_MI_PHPDOC_FILETYPE_IMAGE','_MI_PHPDOC_FILETYPE_ASSET','_MI_PHPDOC_FILETYPE_OTHER') DEFAULT '_MI_PHPDOC_FILETYPE_OTHER',
  `extension` varchar(20) DEFAULT 'php',
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  `actioned` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`filetypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

/*Data for the table `phpdoc_filestypes` */

insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (1,'_MI_PHPDOC_FILETYPE_PHP','php',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (2,'_MI_PHPDOC_FILETYPE_PHP','php4',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (3,'_MI_PHPDOC_FILETYPE_PHP','php3',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (4,'_MI_PHPDOC_FILETYPE_TXT','sql',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (5,'_MI_PHPDOC_FILETYPE_TXT','txt',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (6,'_MI_PHPDOC_FILETYPE_TXT','htaccess',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (7,'_MI_PHPDOC_FILETYPE_TXT','dist',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (8,'_MI_PHPDOC_FILETYPE_JAVA','java',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (9,'_MI_PHPDOC_FILETYPE_JAVA','js',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (10,'_MI_PHPDOC_FILETYPE_CSS','css',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (11,'_MI_PHPDOC_FILETYPE_HTML','html',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (12,'_MI_PHPDOC_FILETYPE_HTML','htm',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (13,'_MI_PHPDOC_FILETYPE_HTML','xhtml',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (14,'_MI_PHPDOC_FILETYPE_IMAGE','jpg',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (15,'_MI_PHPDOC_FILETYPE_IMAGE','jpeg',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (16,'_MI_PHPDOC_FILETYPE_IMAGE','gif',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (17,'_MI_PHPDOC_FILETYPE_IMAGE','tif',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (18,'_MI_PHPDOC_FILETYPE_IMAGE','png',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (19,'_MI_PHPDOC_FILETYPE_IMAGE','ico',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (20,'_MI_PHPDOC_FILETYPE_ASSET','pdf',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (21,'_MI_PHPDOC_FILETYPE_ASSET','doc',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (22,'_MI_PHPDOC_FILETYPE_ASSET','docx',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (23,'_MI_PHPDOC_FILETYPE_ASSET','xml',NULL,0,0,0);
insert  into `phpdoc_filestypes`(`filetypeid`,`filetype`,`extension`,`md5`,`created`,`updated`,`actioned`) values (24,'_MI_PHPDOC_FILETYPE_TXT','csv',NULL,0,0,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
