-- Add action_type and id_user to all three audit trail tables
-- Uses information_schema check — safe to re-run on any MySQL version

-- audit_trails
SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='audit_trails' AND COLUMN_NAME='action_type'), 'ALTER TABLE audit_trails ADD COLUMN action_type VARCHAR(50) NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='audit_trails' AND COLUMN_NAME='id_user'), 'ALTER TABLE audit_trails ADD COLUMN id_user INT NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- re_quote_audit_trails
SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='re_quote_audit_trails' AND COLUMN_NAME='action_type'), 'ALTER TABLE re_quote_audit_trails ADD COLUMN action_type VARCHAR(50) NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='re_quote_audit_trails' AND COLUMN_NAME='id_user'), 'ALTER TABLE re_quote_audit_trails ADD COLUMN id_user INT NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- fulfillment_audit_trails
SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='fulfillment_audit_trails' AND COLUMN_NAME='action_type'), 'ALTER TABLE fulfillment_audit_trails ADD COLUMN action_type VARCHAR(50) NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='fulfillment_audit_trails' AND COLUMN_NAME='id_user'), 'ALTER TABLE fulfillment_audit_trails ADD COLUMN id_user INT NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;
