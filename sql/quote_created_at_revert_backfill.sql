-- PRODUCTION ONLY — undo the issue_date backfill of rfq.created_at.
--
-- We do NOT want created_at to carry a value copied from the hand-typed issue_date in
-- production. This NULLs the backfilled values so created_at means "true creation time"
-- — known only for rows inserted after the column was added. The column keeps its
-- DEFAULT CURRENT_TIMESTAMP, so NEW quotes still auto-populate correctly.
--
-- Targets only the backfilled rows (created_at == parsed issue_date, set to midnight by
-- the backfill); genuine post-migration rows have a real time component and are left alone.
--
-- INTENDED EFFECT: the Bid Pipeline Metrics page filters on created_at, so after this runs
-- the report tracks forward from the migration — legacy rows (created_at NULL) are excluded
-- and only quotes created from now on appear. This is the chosen behavior for production.
-- Do NOT run this on local (local keeps the backfill on purpose, so the dev page still
-- shows historical data).

UPDATE rfq
SET created_at = NULL
WHERE created_at IS NOT NULL
  AND created_at = STR_TO_DATE(issue_date, '%m/%d/%Y');
