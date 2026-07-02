-- Commercial Moving bid type + 50/50 payment term
--
-- Adds the "Commercial Moving" bid type so it appears in the quote bid-type
-- dropdown and the pipeline metrics category breakdown. Idempotent — safe to
-- re-run on local + prod.
--
-- The "50% Upfront / 50% on Completion" payment term needs NO schema change:
-- it is stored as the existing string value in rfq.payment_terms /
-- rfq.services_payment_term, and the split amounts are computed on the fly.
INSERT INTO type_of_bids (type_of_bid)
SELECT 'Commercial Moving'
WHERE 'Commercial Moving' NOT IN (SELECT type_of_bid FROM type_of_bids);
