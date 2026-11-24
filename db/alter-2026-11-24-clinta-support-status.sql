-- Add Active/Lock status for ClinTa Support guides
-- Run against the admin MySQL database (DB_NAME).

ALTER TABLE `extended_software_support`
  ADD COLUMN `Status` varchar(20) NOT NULL DEFAULT 'active' AFTER `VideoUpload`,
  ADD KEY `idx_ess_status` (`Status`);

UPDATE `extended_software_support`
SET `Status` = 'active'
WHERE `Status` = '' OR `Status` IS NULL;
