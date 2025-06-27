CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id` varchar(128) NOT null,
    `ip_address` varchar(45) NOT null,
    `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP NOT null,
    `data` blob NOT null,
    KEY `ci_sessions_timestamp` (`timestamp`)
    );



    ALTER TABLE `extended_profiles` ADD `PharmaName` VARCHAR(250) NULL AFTER `SubDomainUrl`; 

    DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `SystemDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `CustomerID` int(11) DEFAULT NULL,
  `InvoiceID` varchar(250) NOT NULL,
  `Name` varchar(250) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL,
  `Address` text DEFAULT NULL,
  `Email` text DEFAULT NULL,
  `Archive` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `invoices` (`UID`, `SystemDate`, `CustomerID`, `InvoiceID`, `Name`, `PhoneNumber`, `Address`, `Email`, `Archive`) VALUES
(1,	'2025-01-20 11:32:09',	NULL,	'INV-000001',	'Riffa Sohaib Testing',	'03185301217',	'House A/675 Street no 3 arjan nagar city saddar road, rawalpindi\r\n',	'riffasohaib2222@gmail.com',	1),
(2,	'2025-01-30 07:12:28',	NULL,	'INV-000002',	'Gage Rollins',	'+1 (588) 373-6252',	'Laboris enim officia',	'xitoc@mailinator.com',	1),
(3,	'2025-01-30 10:58:40',	1,	'INV-000003',	'riffu sohaib',	'03185301217',	'House A/675 Street no 3 arjan nagar city saddar road, rawalpindi\r\nHouse A/675 Street no 3 arjan nagar city saddar road, rawalpindi',	'riffasohaib2222@gmail.com',	0);

DROP TABLE IF EXISTS `invoice_customers`;
CREATE TABLE `invoice_customers` (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `SystemDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Name` varchar(50) NOT NULL,
  `PhoneNumber` varchar(50) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Archive` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

DROP TABLE IF EXISTS `product_item_invoice`;
CREATE TABLE `product_item_invoice` (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `SystemDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `InvoiceID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `ItemName` text NOT NULL,
  `ItemPrice` text NOT NULL,
  `Type` text NOT NULL,
  `Archive` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



DROP TABLE IF EXISTS "Files";
DROP SEQUENCE IF EXISTS "Files_UID_seq";
CREATE SEQUENCE "Files_UID_seq"  ;

CREATE TABLE "public"."Files" (
    "UID" bigint DEFAULT nextval('"Files_UID_seq"') NOT NULL,
    "SystemDate" timestamp,
    "Content" text,
    "Ext" character varying,
    "Size" bigint NOT NULL,
    "DBRef" text NOT NULL,
    CONSTRAINT "Files_pkey" PRIMARY KEY ("UID")
) WITH (oids = false);


DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `UID` int NOT NULL AUTO_INCREMENT,
  `SystemDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Content` text NOT NULL,
  `Ext` varchar(250) DEFAULT NULL,
  `Size` int DEFAULT NULL,
  `DBRef` text NOT NULL,
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `pharmacy_profiles` ADD `Status` VARCHAR(55) NOT NULL DEFAULT 'block' AFTER `ExpireDate`;

CREATE TABLE IF NOT EXISTS `invoices_payments` (
   `UID` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
   `SystemDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `InvoiceUID` int NOT NULL,
   `PaymentMode` varchar(55) DEFAULT NULL,
    `Date` date DEFAULT NULL,
    `Amount` varchar(55) NOT NULL DEFAULT '0',
    PRIMARY KEY (`UID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `profiles_auto_domains_status` (
      `UID` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
      `SystemDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `ProfileID` int NOT NULL DEFAULT '0',
      `ProductType` varchar(55) DEFAULT NULL,
    `Product` varchar(55) DEFAULT NULL,
    `Domain` varchar(55) DEFAULT NULL,
    `Status` varchar(55) DEFAULT NULL,
    `Message` text,
    PRIMARY KEY (`UID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4