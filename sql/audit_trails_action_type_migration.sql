-- Add action_type and id_user to all three audit trail tables (IF NOT EXISTS — safe to re-run)
ALTER TABLE audit_trails
  ADD COLUMN IF NOT EXISTS action_type VARCHAR(50) NULL,
  ADD COLUMN IF NOT EXISTS id_user     INT         NULL;

ALTER TABLE re_quote_audit_trails
  ADD COLUMN IF NOT EXISTS action_type VARCHAR(50) NULL,
  ADD COLUMN IF NOT EXISTS id_user     INT         NULL;

ALTER TABLE fulfillment_audit_trails
  ADD COLUMN IF NOT EXISTS action_type VARCHAR(50) NULL,
  ADD COLUMN IF NOT EXISTS id_user     INT         NULL;
