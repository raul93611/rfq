-- Data fix: correct services.total_price for the Net 30/CC penny tie-rounding bug
-- (see app/Service/ServiceRepository.inc.php calc_items_with_CC).
--
-- ROUND(unit_price * :payment_term, 2), with :payment_term bound as a plain PDO
-- parameter, made MySQL evaluate the multiplication in DOUBLE precision. For unit
-- prices whose *1.03 result lands exactly on a half-cent (e.g. 10687.50 * 1.03 =
-- 11008.125), DOUBLE rounds the tie down where PHP/JS — used everywhere else this
-- number is computed and displayed (app table, PDF unit-price column) — round it
-- up. The stored total_price ended up a cent short of what the rest of the app
-- already shows. Fixed going forward by casting the bound param to DECIMAL(10,4).
--
-- Scoped to rows off by <= 2 cents (the signature of this specific tie bug) so it
-- does not touch the unrelated, larger-magnitude services.total_price rows that
-- were simply never recalculated after their quote's payment term was set to
-- Net 30/CC — those need a separate investigation, not a blanket recompute.
--
-- Idempotent: safe to run more than once, and safe on both local and production.

UPDATE services s
JOIN rfq r ON r.id = s.id_rfq
SET s.total_price = ROUND(s.quantity * ROUND(s.unit_price * CAST(1.03 AS DECIMAL(10,4)), 2), 2)
WHERE r.services_payment_term = 'Net 30/CC'
  AND ABS(s.total_price - ROUND(s.quantity * ROUND(s.unit_price * CAST(1.03 AS DECIMAL(10,4)), 2), 2)) BETWEEN 0.001 AND 0.02;
