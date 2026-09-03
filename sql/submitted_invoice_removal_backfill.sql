-- Submitted Invoice status removal: migrate existing "Submitted Invoice" quotes back to
-- Invoice. The submitted_invoice / submitted_invoice_date columns stay in the schema
-- (kept to avoid an unnecessary migration and to preserve historical audit_trails rows);
-- only their value is reset so these quotes render/list as Invoice everywhere.
-- Idempotent — safe to re-run. Run on production (same pattern as
-- sql/provider_name_unescape_backfill.sql).
UPDATE rfq SET submitted_invoice = 0, submitted_invoice_date = NULL WHERE submitted_invoice = 1;
