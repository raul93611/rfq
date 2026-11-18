-- Retire the Tracking Sheet migration (features/retire-tracking-sheet.md)
-- Run once on each environment (local, staging, production).
--
-- Adds the two optional bid fields that replace sheet-only columns, and drops the four
-- SharePoint sync columns now that the sheet sync is gone. rfq.name is NOT dropped:
-- sheet_sync_migration.sql added it, but it is the quote Description.
-- Allowed values (YES/NO/TBD, PLATFORM/EMAIL/MAIL) are enforced in Rfq, not here;
-- anything else is stored as NULL.

ALTER TABLE rfq
  ADD COLUMN letter_of_intent  VARCHAR(10) NULL DEFAULT NULL,
  ADD COLUMN submission_method VARCHAR(10) NULL DEFAULT NULL,
  DROP COLUMN sync_to_sheet,
  DROP COLUMN sheet_row,
  DROP COLUMN sheet_sync_status,
  DROP COLUMN sheet_sync_at;
