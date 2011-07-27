
CREATE TABLE `phpdoc_categories` (
  `cid` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(12) unsigned DEFAULT '0',
  `weight` int(4) unsigned DEFAULT '1',
  `itemid` int(12) unsigned DEFAULT '0',
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) DEFAULT '0',
  `updated` int(12) DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_classes` (
  `classid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `cids` varchar(1000) DEFAULT NULL,
  `projectids` varchar(1000) DEFAULT NULL,
  `versionids` varchar(1000) DEFAULT NULL,
  `fileids` varchar(1000) DEFAULT NULL,
  `weight` int(4) unsigned DEFAULT '1',
  `name` varchar(128) DEFAULT NULL,
  `extends` varchar(128) DEFAULT NULL,
  `extendsclassid` int(16) unsigned DEFAULT '0',
  `functions` int(8) unsigned DEFAULT '0',
  `variables` int(8) unsigned DEFAULT '0',
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`classid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_files` (
  `fileid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(12) unsigned DEFAULT '0',
  `projectid` int(12) unsigned DEFAULT '0',
  `versionid` int(12) unsigned DEFAULT '0',
  `basepathid` int(12) unsigned DEFAULT '0',
  `secondpathid` int(12) unsigned DEFAULT '0',
  `thirdpathid` int(12) unsigned DEFAULT '0',
  `forthpathid` int(12) unsigned DEFAULT '0',
  `itemid` int(16) unsigned DEFAULT '0',
  `weight` int(4) unsigned DEFAULT '1',
  `path` varchar(500) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `classes` int(8) unsigned DEFAULT '0',
  `functions` int(8) unsigned DEFAULT '0',
  `filemd5` varchar(32) DEFAULT NULL,
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_functions` (
  `functionid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `cids` varchar(1000) DEFAULT NULL,
  `projectids` varchar(1000) DEFAULT NULL,
  `classids` varchar(1000) DEFAULT NULL,
  `fileids` varchar(1000) DEFAULT NULL,
  `itemid` int(16) unsigned DEFAULT '0',
  `weight` int(4) unsigned DEFAULT '1',
  `name` varchar(128) DEFAULT NULL,
  `mode` enum('_MI_PHPDOC_MODE_PUBLIC','_MI_PHPDOC_MODE_PRIVATE','_MI_PHPDOC_MODE_PROTECTED') DEFAULT '_MI_PHPDOC_MODE_PUBLIC',
  `return` enum('_MI_PHPDOC_TYPE_MIXED','_MI_PHPDOC_TYPE_INTEGER','_MI_PHPDOC_TYPE_LONG','_MI_PHPDOC_TYPE_DOUBLE','_MI_PHPDOC_TYPE_FLOAT','_MI_PHPDOC_TYPE_STRING','_MI_PHPDOC_TYPE_ARRAY','_MI_PHPDOC_TYPE_OBJECT','_MI_PHPDOC_TYPE_BOOLEAN') DEFAULT '_MI_PHPDOC_TYPE_MIXED',
  `call` varchar(1500) DEFAULT NULL,
  `variables` int(8) unsigned DEFAULT '0',
  `md5ofcode` varchar(32) DEFAULT NULL,
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  `actioned` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`functionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_index` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(12) unsigned DEFAULT '0',
  `projectid` int(12) unsigned DEFAULT '0',
  `versionid` int(12) unsigned DEFAULT '0',
  `fileid` int(16) unsigned DEFAULT '0',
  `classid` int(16) unsigned DEFAULT '0',
  `functionid` int(16) unsigned DEFAULT '0',
  `itemid` int(12) unsigned DEFAULT '0',
  `weight` int(4) unsigned DEFAULT '1',
  `status` enum('_MI_PHPDOC_STATUS_ALPHA','_MI_PHPDOC_STATUS_BETA','_MI_PHPDOC_STATUS_RC','_MI_PHPDOC_STATUS_STABLE','_MI_PHPDOC_STATUS_MATURE','_MI_PHPDOC_STATUS_EXPERIMENTAL') DEFAULT '_MI_PHPDOC_STATUS_STABLE',
  `mode` enum('_MI_PHPDOC_MODE_PUBLIC','_MI_PHPDOC_MODE_PRIVATE','_MI_PHPDOC_MODE_PROTECTED') DEFAULT '_MI_PHPDOC_MODE_PUBLIC',
  `tags` varchar(255) DEFAULT NULL,
  `domains` varchar(1000) DEFAULT NULL,
  `visible` tinyint(2) unsigned DEFAULT '1',
  `comments` tinyint(2) unsigned DEFAULT '1',
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  `actioned` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_items` (
  `itemid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('_MI_PHPDOC_TYPE_CATEGORY','_MI_PHPDOC_TYPE_VERSION','_MI_PHPDOC_TYPE_PROJECT','_MI_PHPDOC_TYPE_PATH','_MI_PHPDOC_TYPE_FILE','_MI_PHPDOC_TYPE_FUNCTION','_MI_PHPDOC_TYPE_CLASS','_MI_PHPDOC_TYPE_VARIABLE','_MI_PHPDOC_TYPE_OTHER') DEFAULT '_MI_PHPDOC_TYPE_OTHER',
  `languages` varchar(1000) DEFAULT NULL,
  `default` varchar(128) DEFAULT 'english',
  `created` int(12) unsigned DEFAULT NULL,
  `updated` int(12) unsigned DEFAULT NULL,
  `actioned` int(12) unsigned DEFAULT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_items_digest` (
  `itemdigestid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `itemid` int(16) unsigned DEFAULT '0',
  `cid` int(12) unsigned DEFAULT '0',
  `projectid` int(12) unsigned DEFAULT '0',
  `versionid` int(12) unsigned DEFAULT '0',
  `fileid` int(12) unsigned DEFAULT '0',
  `classid` int(12) unsigned DEFAULT '0',
  `functionid` int(12) unsigned DEFAULT '0',
  `variableid` int(12) unsigned DEFAULT '0',
  `pathid` int(12) unsigned DEFAULT '0',
  `language` varchar(128) DEFAULT 'english',
  `menu` varchar(128) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` mediumtext,
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`itemdigestid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_paths` (
  `pathid` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `folder` varchar(128) DEFAULT NULL,
  `path` varchar(500) DEFAULT NULL,
  `itemid` int(12) unsigned DEFAULT NULL,
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`pathid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_projects` (
  `projectid` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(12) unsigned DEFAULT '0',
  `itemid` int(16) unsigned DEFAULT '0',
  `weight` int(4) unsigned DEFAULT '1',
  `status` enum('_MI_PHPDOC_STATUS_ALPHA','_MI_PHPDOC_STATUS_BETA','_MI_PHPDOC_STATUS_RC','_MI_PHPDOC_STATUS_STABLE','_MI_PHPDOC_STATUS_MATURE','_MI_PHPDOC_STATUS_EXPERIMENTAL') DEFAULT '_MI_PHPDOC_STATUS_STABLE',
  `url` varchar(255) DEFAULT NULL,
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  `actioned` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`projectid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_variables` (
  `variableid` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `cids` varchar(1000) DEFAULT NULL,
  `projectids` varchar(1000) DEFAULT NULL,
  `versionids` varchar(1000) DEFAULT NULL,
  `classids` varchar(1000) DEFAULT NULL,
  `functionids` varchar(1000) DEFAULT NULL,
  `fileids` varchar(1000) DEFAULT NULL,
  `itemid` int(16) unsigned DEFAULT '0',
  `weight` int(4) unsigned DEFAULT '1',
  `name` varchar(128) DEFAULT NULL,
  `default` varchar(128) DEFAULT NULL,
  `type` enum('_MI_PHPDOC_TYPE_MIXED','_MI_PHPDOC_TYPE_INTEGER','_MI_PHPDOC_TYPE_LONG','_MI_PHPDOC_TYPE_DOUBLE','_MI_PHPDOC_TYPE_FLOAT','_MI_PHPDOC_TYPE_STRING','_MI_PHPDOC_TYPE_ARRAY','_MI_PHPDOC_TYPE_OBJECT','_MI_PHPDOC_TYPE_BOOLEAN') DEFAULT '_MI_PHPDOC_TYPE_MIXED',
  `md5` varchar(32) DEFAULT NULL,
  `created` int(12) unsigned DEFAULT '0',
  `updated` int(12) unsigned DEFAULT '0',
  `actioned` int(12) unsigned DEFAULT '0',
  PRIMARY KEY (`variableid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phpdoc_versions` (
  `versionid` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cid` INT(12) UNSIGNED DEFAULT '0',
  `projectid` INT(12) UNSIGNED DEFAULT '0',
  `weight` INT(4) UNSIGNED DEFAULT '1',
  `status` ENUM('_MI_PHPDOC_STATUS_ALPHA','_MI_PHPDOC_STATUS_BETA','_MI_PHPDOC_STATUS_RC','_MI_PHPDOC_STATUS_STABLE','_MI_PHPDOC_STATUS_MATURE','_MI_PHPDOC_STATUS_EXPERIMENTAL') DEFAULT '_MI_PHPDOC_STATUS_STABLE',
  `itemid` INT(16) UNSIGNED DEFAULT '0',
  `supporturl` VARCHAR(255) DEFAULT NULL,
  `downloadurl` VARCHAR(255) DEFAULT NULL,
  `repourl` VARCHAR(255) DEFAULT NULL,
  `major` TINYINT(4) UNSIGNED DEFAULT '0',
  `minor` TINYINT(4) UNSIGNED DEFAULT '0',
  `revision` TINYINT(4) UNSIGNED DEFAULT '0',
  `md5` VARCHAR(32) DEFAULT NULL,
  `created` INT(12) UNSIGNED DEFAULT NULL,
  `updated` INT(12) UNSIGNED DEFAULT NULL,
  `actioned` INT(12) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`versionid`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;
