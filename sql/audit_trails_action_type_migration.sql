-- Add action_type and id_user to all three audit trail tables
ALTER TABLE audit_trails
  ADD COLUMN action_type VARCHAR(50) NULL,
  ADD COLUMN id_user     INT         NULL;

ALTER TABLE re_quote_audit_trails
  ADD COLUMN action_type VARCHAR(50) NULL,
  ADD COLUMN id_user     INT         NULL;

ALTER TABLE fulfillment_audit_trails
  ADD COLUMN action_type VARCHAR(50) NULL,
  ADD COLUMN id_user     INT         NULL;
