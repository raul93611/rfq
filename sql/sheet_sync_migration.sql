-- SharePoint Sheet Sync migration
-- Run once on each environment (local, staging, production)

ALTER TABLE rfq
  ADD COLUMN name TEXT NULL AFTER priority,
  ADD COLUMN sheet_sync_status VARCHAR(10) NULL AFTER name,
  ADD COLUMN sheet_sync_at DATETIME NULL AFTER sheet_sync_status,
  ADD COLUMN sheet_row INT NULL AFTER sheet_sync_at;
