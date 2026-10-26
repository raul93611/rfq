-- Automatic Sales Commission: the commission is calculated on the server when a quote is
-- invoiced and frozen on the quote. Run once per environment.
--
-- sales_commission_amount IS NULL marks a legacy quote (manual choice + the old CASE
-- expression); a non-NULL value, including 0, marks an auto-calculated one. No backfill:
-- quotes invoiced before go-live keep their commission exactly as before.
--
--   sales_commission_amount           the frozen commission, in dollars
--   sales_commission_basis_profit     the profit the 3% was applied to (the smaller of the
--                                     re-quote and fulfillment profit, before the $0 floor)
--   sales_commission_fulfillment_pct  the 2-decimal fulfillment profit % used for the 6% test
--   sales_commission_reason           not_eligible_type | no_price | under_threshold |
--                                     requote_smaller | fulfillment_smaller | no_profit
ALTER TABLE rfq
  ADD COLUMN sales_commission_amount DECIMAL(12,2) NULL,
  ADD COLUMN sales_commission_basis_profit DECIMAL(12,2) NULL,
  ADD COLUMN sales_commission_fulfillment_pct DECIMAL(6,2) NULL,
  ADD COLUMN sales_commission_reason VARCHAR(30) NULL;
