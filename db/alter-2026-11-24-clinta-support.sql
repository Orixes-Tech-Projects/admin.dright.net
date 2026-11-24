-- ClinTa Support knowledge base (admin.dright.net)
-- Run against the admin MySQL database (DB_NAME).

CREATE TABLE IF NOT EXISTS `extended_software_support` (
  `UID` int NOT NULL AUTO_INCREMENT,
  `SystemDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SystemUpdatedDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `URL` varchar(500) NOT NULL,
  `AccessLevel` varchar(100) NOT NULL DEFAULT 'ALL',
  `Question` text NOT NULL,
  `Answer` text,
  `VideoLink` varchar(1000) DEFAULT NULL,
  `VideoUpload` varchar(500) DEFAULT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'active',
  `Archive` smallint NOT NULL DEFAULT 0,
  PRIMARY KEY (`UID`),
  KEY `idx_ess_url` (`URL`),
  KEY `idx_ess_access` (`AccessLevel`),
  KEY `idx_ess_status` (`Status`),
  KEY `idx_ess_archive` (`Archive`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin_access` (`Module`, `AccessKey`, `Description`)
SELECT 'Extended', 'extended_clinta_support_list', 'ClinTa Support List'
WHERE NOT EXISTS (
  SELECT 1 FROM `admin_access` WHERE `AccessKey` = 'extended_clinta_support_list'
);

INSERT INTO `admin_access` (`Module`, `AccessKey`, `Description`)
SELECT 'Extended', 'extended_clinta_support_add', 'ClinTa Support Add'
WHERE NOT EXISTS (
  SELECT 1 FROM `admin_access` WHERE `AccessKey` = 'extended_clinta_support_add'
);

INSERT INTO `admin_access` (`Module`, `AccessKey`, `Description`)
SELECT 'Extended', 'extended_clinta_support_update', 'ClinTa Support Update'
WHERE NOT EXISTS (
  SELECT 1 FROM `admin_access` WHERE `AccessKey` = 'extended_clinta_support_update'
);

INSERT INTO `admin_access` (`Module`, `AccessKey`, `Description`)
SELECT 'Extended', 'extended_clinta_support_delete', 'ClinTa Support Delete'
WHERE NOT EXISTS (
  SELECT 1 FROM `admin_access` WHERE `AccessKey` = 'extended_clinta_support_delete'
);
