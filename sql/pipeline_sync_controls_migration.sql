-- Bid Pipeline Sync Controls migration
-- Adds an explicit per-quote "sync to pipeline" flag. Existing rows default to 1,
-- which closely matches the previous "eligible bid types sync" behavior; any quote
-- that should not sync can be turned off with the Break Sync button.
-- Run once on each environment (local, staging, production).

ALTER TABLE rfq
  ADD COLUMN sync_to_sheet TINYINT(1) NOT NULL DEFAULT 1;
